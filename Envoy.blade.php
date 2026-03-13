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

    echo "==> Reiniciando queue workers"
    php artisan queue:restart

    echo "==> Voltando ao ar"
    php artisan up

    echo "==> Deploy concluído!"
@endtask

@task('rollback', ['on' => 'web'])
    cd {{ $appDir }}

    echo "==> Revertendo último commit"
    git reset --hard HEAD~1

    echo "==> Recompilando assets"
    composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
    npm ci --prefer-offline && npm run build

    echo "==> Revertendo migrations"
    php artisan migrate:rollback --force

    echo "==> Reconstruindo caches"
    php artisan optimize

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
