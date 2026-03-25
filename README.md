# Instruções e dicas gerais

> **Stack:** Laravel 13 · PHP 8.5+ · Blade · Tailwind CSS · Vanilla JS

## Deploy com Laravel Envoy

O arquivo `Envoy.blade.php` na raiz do projeto automatiza o deploy via SSH.

**Pré-requisito:** `composer require laravel/envoy --dev`
**SSH:** requer host `akop` configurado em `~/.ssh/config` apontando para o servidor.

### Deploy completo

```bash
php envoy run deploy
```

Executa em sequência: `pull` → `composer` → `npm` → `artisan` (migrations + caches + queues).

### Tasks avulsas

```bash
php envoy run cache:clear   # limpa todos os caches sem derrubar o site
php envoy run rollback      # reverte o último commit e refaz tudo
php envoy run down          # coloca em manutenção
php envoy run up            # volta ao ar
```

### Deploy em outra branch

```bash
php envoy run deploy --branch=staging
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
command=php /var/www/project-folder/artisan horizon
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=root
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/project-folder/storage/logs/horizon.log
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
        Enviar
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

Upload de imagem com preview automático. Ao selecionar um arquivo, exibe preview; ao clicar em "×", remove a imagem e seta o campo hidden `{name}_remove=1` para o backend limpar a media.

```blade
{{-- Novo upload --}}
<x-forms.file id="featured-image" name="featured_image" />

{{-- Com imagem existente (edit) --}}
<x-forms.file id="featured-image" name="featured_image"
              :initial-url="$post->image('medium')" />
```

### `x-forms.select`

```blade
<x-forms.select name="tag_id" id="tag_id">
    <option value="">Selecione...</option>
    @foreach($tags as $tag)
        <option value="{{ $tag->id }}" @selected(old('tag_id') == $tag->id)>{{ $tag->name }}</option>
    @endforeach
</x-forms.select>
```

### `x-forms.rich-text-editor`

Editor WYSIWYG baseado em ProseMirror. O conteúdo é sincronizado em um `<input type="hidden">` com o `name` informado.

```blade
{{-- Novo post --}}
<x-forms.rich-text-editor name="content" />

{{-- Edição (preenche o editor com o valor existente) --}}
<x-forms.rich-text-editor name="content" :value="$post->content" />
```

### `x-forms.glow-button`

Botão com borda animada (conic-gradient) e bloom de fundo. Suporta renderização como `<a>` ou `<button>`, estados de carregamento, tamanhos e tema claro/escuro.

| Prop | Tipo | Default | Descrição |
|------|------|---------|-----------|
| `href` | string\|null | `null` | Se informado, renderiza como `<a>` |
| `state` | `idle\|loading` | `idle` | Controla o spinner integrado |
| `size` | `sm\|md\|lg` | `md` | Tamanho do botão |
| `dark` | bool | `true` | `true` = fundo escuro; `false` = fundo branco |

```blade
{{-- Link --}}
<x-forms.glow-button href="{{ route('posts.create') }}" size="lg">
    Criar post
</x-forms.glow-button>

{{-- Botão de submit com fundo claro --}}
<x-forms.glow-button :dark="false" state="idle">
    Salvar
</x-forms.glow-button>
```

### `x-forms.button` — estado de carregamento

O atributo `state` controla o spinner interno. O módulo `ajax-post.js` alterna automaticamente entre `idle` e `loading` via `data-spinner` / `data-label`. Para uso manual:

```blade
<x-forms.button state="idle">Salvar</x-forms.button>
<x-forms.button state="loading">Salvar</x-forms.button>  {{-- exibe spinner --}}
```

---

## Layouts

### `x-layout`

Layout principal com header, footer, dark mode e Vite. Aceita `title` opcional.

```blade
<x-layout title="Título da página">
    <x-container>
        ...
    </x-container>
</x-layout>
```

### `x-layouts.form`

Layout minimalista para páginas de autenticação e formulários simples (sem header/footer).

```blade
<x-layouts.form title="Login">
    ...
</x-layouts.form>
```

### `x-container`

Wrapper centralizado com `max-w-7xl` e padding horizontal responsivo. Aceita classes extras via `class`.

```blade
<x-container class="py-8">
    ...
</x-container>
```

---

## Notificações

`Modal` e `Toast` são globais disponíveis em qualquer Blade ou JS sem import.

### Modal de alerta (via PHP redirect)

Para exibir um modal após um redirect, use `session('alert')`:

```php
// no controller
return back()->with('alert', ['message' => 'Operação realizada.']);
```

O componente `<x-alert>` (incluso no layout) detecta a session e dispara `Modal.loadAlert()` automaticamente.

### Modal via JavaScript

```js
// Tipos: 'success' | 'warning' | 'error' | 'info'
Modal.loadAlert({ type: 'success', title: 'Pronto!', content: 'Salvo com sucesso.' })

// Com callback ao fechar
Modal.loadAlert({ type: 'warning', content: 'Tem certeza?', onClose: () => doSomething() })
```

### Toast via JavaScript

```js
Toast.open({ title: 'Salvo', content: 'Post atualizado.', type: 'success' })
```

---

## Componentes de conteúdo

### `x-posts-list`

Lista de posts com thumbnail, badge "Lido / Não lido / Avulso" e link de edição para autenticados. Requer que os posts tenham sido carregados com `withReadFlag($userId)` e `with('media')`.

```blade
<x-posts-list :posts="$posts" />

{{-- Para posts dentro de cursos, sobrescreva a rota --}}
<x-posts-list :posts="$posts" route="courses.showPost" />
```

### `x-mark-read-post-button`

Renderiza "Marcar como lido" ou "Desmarcar" de acordo com `$isRead`. Visível apenas para usuários autenticados. Funciona somente em posts com `course_id`.

```blade
<x-mark-read-post-button :post="$post" :is-read="$isRead" />
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

---

## Módulos JS — data-attributes

Os módulos registrados em `window.globalModules` expõem comportamentos declarativos via atributos HTML. Nenhum import é necessário nas views.

### `toggle` — alternar classes em elemento

```html
<!-- Abre/fecha dropdown ao clicar; fecha ao clicar fora -->
<button ak-toggle="my-dropdown"
        ak-toggle-classes="hidden"
        ak-toggle-close-on-blur="true">
    Abrir
</button>
<ul id="my-dropdown" class="hidden">...</ul>
```

| Atributo | Descrição |
|----------|-----------|
| `ak-toggle` | ID do elemento alvo |
| `ak-toggle-classes` | Classes a alternar (separadas por espaço) |
| `ak-toggle-event` | Evento a escutar (padrão: `click`) |
| `ak-toggle-close-on-blur` | `"true"` fecha o alvo ao clicar fora |
| `ak-toggle-once` | Remove o listener após o primeiro disparo |
| `ak-toggle-on-mouseout` | Também togla ao sair o mouse do trigger |

Opcionalmente, defina `id="${targetId}-closed-state"` e `id="${targetId}-opened-state"` para alternar visibilidade de ícones de estado.

### `side-panel` — drawer/aside animado

Abre e fecha painéis laterais (mobile menu, índice de posts, etc.) via atributos.

```html
<!-- Abre o painel -->
<button data-side-panel-open="#menu-panel"
        data-side-panel-overlay="#menu-overlay">
    Menu
</button>

<!-- Fecha o painel (geralmente dentro do próprio painel) -->
<button data-side-panel-close
        data-side-panel-target="#menu-panel"
        data-side-panel-overlay="#menu-overlay">
    Fechar
</button>

<!-- Painel (começa escondido com translate-x-full + opacity-0) -->
<aside id="menu-panel" class="fixed ... translate-x-full opacity-0 transition-transform">
    ...
</aside>
<div id="menu-overlay" class="fixed inset-0 opacity-0 pointer-events-none transition-opacity"></div>
```

### `code-highlight` — syntax highlighting

Aplicado automaticamente a todo `pre > code` dentro de elementos `.html-content` (renderização de posts). Usa `highlight.js`. Nenhuma configuração necessária — o módulo é idempotente (`data-highlighted` evita re-processamento).