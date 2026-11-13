@servers(['web' => 'akop'])

@setup
    $branch = $branch ?? 'main';
    $appDir = '/var/www/alekop.com/public_html';

    // A sessão SSH é root (`Host akop` no ~/.ssh/config), e o php-fpm e os
    // workers de fila (deploy/supervisor/alekop.conf) são www-data. Tudo o que o
    // artisan escreve — caches de config/rotas/eventos/views/ícones, o arquivo de
    // manutenção, o laravel.log — precisa ser de quem depois o *reescreve*, senão
    // a próxima view compilada ou a próxima linha de log falha com um erro de
    // permissão que parece tudo menos um problema de deploy. Por isso todo artisan
    // roda como www-data; sendo root, o sudo não pede senha.
    $artisan = 'sudo -u www-data php artisan';

    // `composer install` continua como root (www-data só lê vendor/), mas o
    // post-autoload-dump roda `package:discover`, que grava
    // bootstrap/cache/{packages,services}.php como root. Devolvidos logo depois.
    // Também roda no início do `pull`: deploys antigos (artisan como root)
    // deixaram caches do root, que o www-data não consegue sobrescrever.
    $ownWritable = 'chown -R www-data:www-data storage bootstrap/cache';
@endsetup

@story('deploy')
    pull
    composer
    npm
    artisan
@endstory

@task('pull', ['on' => 'web'])
    echo "==> Acessando {{ $appDir }}"
    cd {{ $appDir }}
    {{ $ownWritable }}

    echo "==> Colocando em manutenção"
    {{ $artisan }} down --refresh=15 --retry=10

    echo "==> Pulling {{ $branch }}"
    git fetch origin {{ $branch }}
    git reset --hard origin/{{ $branch }}

    {{-- HEAD@{1} é de onde o reset veio: o código que estava no ar. --}}
    if ! git diff --quiet HEAD@{1} HEAD -- deploy/supervisor/ 2>/dev/null; then
        echo "==> ATENÇÃO: deploy/supervisor/ mudou. Publique com: envoy run supervisor"
    fi
@endtask

@task('composer', ['on' => 'web'])
    cd {{ $appDir }}

    echo "==> Instalando dependências PHP"
    composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
    {{ $ownWritable }}
@endtask

@task('npm', ['on' => 'web'])
    cd {{ $appDir }}

    echo "==> Instalando dependências Node"
    npm ci --prefer-offline

    echo "==> Compilando assets para produção"
    npm run build
@endtask

@task('artisan', ['on' => 'web'])
    cd {{ $appDir }}

    echo "==> Executando migrations"
    {{ $artisan }} migrate --force

    echo "==> Limpando e reconstruindo caches"
    {{ $artisan }} config:cache
    {{ $artisan }} route:cache
    {{ $artisan }} view:cache
    {{ $artisan }} event:cache
    {{ $artisan }} icons:cache

    {{-- queue:restart reinicia o worker da newsletter (queue:work). O Horizon não
         obedece queue:restart: sem horizon:terminate ele segue com o código antigo
         em memória (e-mails de contato, de pedido aprovado...). O Supervisor o
         sobe de novo já com o código novo. O terminate manda SIGTERM ao processo,
         então só funciona com o Horizon também como www-data (envoy run supervisor). --}}
    echo "==> Reiniciando queue workers"
    {{ $artisan }} queue:restart
    {{ $artisan }} horizon:terminate

    echo "==> Voltando ao ar"
    {{ $artisan }} up

    echo "==> Deploy concluído!"
@endtask

{{-- Fora do deploy. Uso: envoy run seed --class=WhatsappOfferCourseSeeder --}}
@task('seed', ['on' => 'web'])
    @if (empty($class))
        echo "==> Informe o seeder: envoy run seed --class=NomeDoSeeder"
        exit 1
    @endif

    cd {{ $appDir }}

    echo "==> Rodando {{ $class }}"
    {{ $artisan }} db:seed --class={{ $class }} --force
@endtask

{{--
    Reverte o **código**, e de propósito não o banco.

    Antes isto terminava com `migrate:rollback`, que aqui não funciona depois do
    `git reset`: os arquivos das migrations do último deploy somem com o reset,
    o Migrator responde "Migration not found" e não reverte nada. Pior: se o
    último deploy não trouxe migration, o rollback desfaz o lote *anterior*, cujos
    arquivos ainda existem, e apaga colunas e dados que nada tinham a ver com o
    problema.

    Deixar o banco como está também é o certo por si só. Migration aditiva (tabela
    ou coluna nova) é inofensiva para o código anterior, que simplesmente não a
    usa. Migration destrutiva (coluna removida, tipo reduzido) não se desfaz com
    rollback: o dado já foi. Esse caso pede uma migration nova, escrita de
    propósito, não um comando de deploy.
--}}
@task('rollback', ['on' => 'web'])
    cd {{ $appDir }}
    {{ $ownWritable }}

    echo "==> Colocando em manutenção"
    {{ $artisan }} down --refresh=15 --retry=10

    echo "==> Revertendo último commit"
    git reset --hard HEAD~1

    echo "==> Recompilando assets"
    composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
    {{ $ownWritable }}
    npm ci --prefer-offline && npm run build

    echo "==> Banco intocado (ver a nota acima)"

    echo "==> Reconstruindo caches"
    {{ $artisan }} optimize
    {{ $artisan }} icons:cache

    echo "==> Reiniciando queue workers"
    {{ $artisan }} queue:restart
    {{ $artisan }} horizon:terminate

    echo "==> Voltando ao ar"
    {{ $artisan }} up
@endtask

@task('cache:clear', ['on' => 'web'])
    cd {{ $appDir }}

    {{ $artisan }} config:clear
    {{ $artisan }} route:clear
    {{ $artisan }} view:clear
    {{ $artisan }} event:clear
    {{ $artisan }} cache:clear
    {{ $artisan }} icons:cache

    echo "==> Caches limpos"
@endtask

@task('down', ['on' => 'web'])
    cd {{ $appDir }}
    {{ $artisan }} down --refresh=15 --retry=10
@endtask

@task('up', ['on' => 'web'])
    cd {{ $appDir }}
    {{ $artisan }} up
@endtask

{{--
    Fora do deploy: rode (envoy run supervisor) quando deploy/supervisor/ mudar.
    Publica no mesmo arquivo do setup manual antigo (laravel-horizon.conf), para
    substituí-lo em vez de subir um segundo Horizon ao lado. O `update` recria os
    programas que mudaram (aqui, de root para www-data); o restart do grupo
    garante que os dois sobem com o código atual.
--}}
@task('supervisor', ['on' => 'web'])
    cd {{ $appDir }}
    {{ $ownWritable }}

    echo "==> Publicando a configuração do Supervisor"
    cp deploy/supervisor/alekop.conf /etc/supervisor/conf.d/laravel-horizon.conf

    echo "==> Recarregando o Supervisor"
    supervisorctl reread
    supervisorctl update
    supervisorctl restart alekop:

    echo "==> Supervisor atualizado"
    supervisorctl status alekop:*
@endtask
