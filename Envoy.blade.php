@servers(['web' => 'akop'])

@setup
    $branch = $branch ?? 'main';
    $appDir = '/var/www/alekop.com/public_html';
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

    echo "==> Colocando em manutenção"
    php artisan down --refresh=15 --retry=10

    echo "==> Pulling {{ $branch }}"
    git fetch origin {{ $branch }}
    git reset --hard origin/{{ $branch }}
@endtask

@task('composer', ['on' => 'web'])
    cd {{ $appDir }}

    echo "==> Instalando dependências PHP"
    composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
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
    php artisan migrate --force

    echo "==> Limpando e reconstruindo caches"
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan event:cache
    php artisan icons:cache

    {{-- queue:restart reinicia o worker da newsletter (queue:work). O Horizon não
         obedece queue:restart: sem horizon:terminate ele segue com o código antigo
         em memória (e-mails de contato, de pedido aprovado...). O Supervisor o
         sobe de novo já com o código novo. --}}
    echo "==> Reiniciando queue workers"
    php artisan queue:restart
    php artisan horizon:terminate

    echo "==> Voltando ao ar"
    php artisan up

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
    php artisan db:seed --class={{ $class }} --force
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

    echo "==> Colocando em manutenção"
    php artisan down --refresh=15 --retry=10

    echo "==> Revertendo último commit"
    git reset --hard HEAD~1

    echo "==> Recompilando assets"
    composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
    npm ci --prefer-offline && npm run build

    echo "==> Banco intocado (ver a nota acima)"

    echo "==> Reconstruindo caches"
    php artisan optimize
    php artisan icons:cache

    echo "==> Reiniciando queue workers"
    php artisan queue:restart
    php artisan horizon:terminate

    echo "==> Voltando ao ar"
    php artisan up
@endtask

@task('cache:clear', ['on' => 'web'])
    cd {{ $appDir }}

    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    php artisan event:clear
    php artisan cache:clear
    php artisan icons:cache

    echo "==> Caches limpos"
@endtask

@task('down', ['on' => 'web'])
    cd {{ $appDir }}
    php artisan down --refresh=15 --retry=10
@endtask

@task('up', ['on' => 'web'])
    cd {{ $appDir }}
    php artisan up
@endtask
