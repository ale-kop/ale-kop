# Instruções e dicas gerais

## Script de deploy
```
set -x
function execute_run_php () {

cd /var/www/

sudo chown -R www-data:www-data s360

cd /var/www/s360/

sudo chmod -R 777 bootstrap/cache
sudo chmod -R 777 storage

sudo chmod -R 777 storage/logs
sudo chown -R www-data:www-data storage/logs

composer install --optimize-autoloader
composer install --prefer-dist --no-scripts
npm install

php artisan storage:link

sudo chown -R www-data:www-data /var/www/s360
sudo chmod -R 775 /var/www/s360
sudo chmod -R 777 bootstrap/cache
sudo chmod -R 777 storage

sudo -u www-data npm run build

php artisan migrate --force
#### php artisan db:seed --force
php artisan optimize:clear
php artisan optimize
php artisan route:cache
php artisan view:cache
php artisan cache:clear
php artisan route:clear
php artisan config:clear
php artisan config:cache
php artisan queue:restart
php artisan event:cache

sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl restart all

sudo chown -R www-data:www-data storage/logs

}

sudo bash -exc "$(declare -f  execute_run_php) ;  execute_run_php"
```
## Configuração do `supervisorctl`

### 1. Arquivo de configuração
```
sudo nano /etc/supervisor/conf.d/laravel-horizon.conf
```

### 2. Conteúdo da configuração 
```
[program:laravel-horizon]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/s360/artisan horizon
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=root
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/s360/storage/logs/horizon.log
```

Em seguida rodar `sudo supervisorctl reread
sudo supervisorctl update`

## Uso de Blade Heroicons

O projeto já inclui o pacote [blade-heroicons](https://github.com/driesvints/blade-heroicons).  
Para exibir um ícone utilize o componente correspondente:

```blade
<x-heroicon-o-home class="w-6 h-6 text-gray-500" />
<x-heroicon-s-user class="w-6 h-6 text-gray-500" />
```

Consulte o repositório para ver a lista completa de ícones disponíveis.

## Componentes Blade

Os componentes estão localizados em `resources/views/components/forms` e padronizam os elementos de formulário da aplicação. Exemplos de uso:

### `x-forms.input`

Campo de texto com suporte a ícones à esquerda ou à direita:

```blade
<x-forms.input name="username" :icon-left="'heroicon-o-user'" />
<x-forms.input name="search" :icon-right="'heroicon-o-magnifying-glass'" />
```

### `x-forms.input-with-button`

```blade
<x-forms.input-with-button name="email">
    <x-slot name="button">
        <x-forms.button>Enviar</x-forms.button>
    </x-slot>
</x-forms.input-with-button>
```

### `x-forms.button`

Botão estilizado que oferece o atributo `state` para futuras ações assíncronas:

```blade
<x-forms.button state="idle">Salvar</x-forms.button>
```

### `x-forms.label`

```blade
<x-forms.label for="name">Nome</x-forms.label>
```

### `x-forms.textarea`

```blade
<x-forms.textarea name="about" rows="3" />
```

### `x-forms.checkbox`

```blade
<x-forms.checkbox id="agree" name="agree" />
<x-forms.label for="agree">Aceito os termos</x-forms.label>
```

### `x-forms.radio-group` e `x-forms.radio`

```blade
<x-forms.radio-group>
    <x-forms.radio name="status" value="yes">Sim</x-forms.radio>
    <x-forms.radio name="status" value="no">Não</x-forms.radio>
</x-forms.radio-group>
```

### `x-forms.file`

```blade
<x-forms.file id="avatar" name="avatar" />
```

### Índice de posts (unificado)

Foi consolidado em um único componente o índice lateral de posts, tanto para posts que pertencem a um curso (seções e posts) quanto para posts avulsos (lista de últimos posts):

```blade
<x-post-index :post="$post" :recentPosts="$recentPosts ?? collect()" />
```

- Quando `post->course` existir, o componente renderiza o índice do curso (seções + posts) com badges “Lido/Não lido”.
- Quando não houver curso, renderiza o índice de “Últimos posts” (avulso).
- No mobile, inclui botão flutuante “Abrir índice”, overlay com blur e drawer animado (side‑panel genérico).

Componentes obsoletos removidos:
- `x-post-course-index`
- `x-post-recent-index`

Use sempre `x-post-index` daqui para frente.
