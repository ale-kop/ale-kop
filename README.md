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

## Configuração do Supervisor (produção)

O Supervisor mantém os workers de fila (Horizon e newsletter) sempre rodando, reiniciando-os automaticamente caso caiam ou o servidor reinicie.

### Pré-requisito no `.env`

```env
QUEUE_CONNECTION=redis
```

Horizon só funciona com a driver `redis` (veja `config/horizon.php`). Confirme que o Redis está instalado e rodando (`redis-cli ping` → `PONG`).

### 1. Arquivo de configuração
```bash
sudo nano /etc/supervisor/conf.d/laravel-horizon.conf
```

### 2. Conteúdo da configuração

```ini
[program:laravel-horizon]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/alekop.com/public_html/artisan horizon
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=root
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/alekop.com/public_html/storage/logs/horizon.log

[program:laravel-newsletter]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/alekop.com/public_html/artisan queue:work --queue=newsletter --sleep=3 --tries=3 --backoff=60 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=root
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/alekop.com/public_html/storage/logs/newsletter-worker.log
```

> `laravel-horizon` processa a fila `default` (inclui o envio do formulário de contato). `laravel-newsletter` processa a fila `newsletter`, separada para controlar a taxa de envio — veja a seção [Newsletter](#newsletter).

### 3. Carregar e iniciar

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-horizon:*
sudo supervisorctl start laravel-newsletter:*
```

Verificar status:

```bash
sudo supervisorctl status
```

### Deploys seguintes

Não é necessário repetir o setup acima. `php envoy run deploy` já executa `php artisan queue:restart`, que sinaliza os workers a reiniciarem com o código novo — o Supervisor (`autorestart=true`) os traz de volta automaticamente.

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

---

## Formulário de contato

Página pública em `/contato` com links para redes sociais (LinkedIn, X, Instagram, YouTube) e formulário de envio de mensagem.

- **Rota GET:** `contact.show` → `ContactController@show`
- **Rota POST:** `contact.send` → `ContactController@send` (throttle: 3 req / 5 min por IP)
- **Mailable:** `App\Mail\ContactMail` — define `replyTo` com o e-mail do remetente; assunto fixo `"Contato AleKop.com"`
- **Envio:** `Mail::queue()` — assíncrono via fila `default`, nunca bloqueia o request HTTP
- **Destinatário:** configurado em `MAIL_CONTACT_ADDRESS` no `.env`
- **Anti-spam:** campo honeypot `website` (deve chegar vazio)

### Configuração no `.env`

```env
MAIL_CONTACT_ADDRESS="contato@alekop.com"
```

### Atenção ao `MAIL_SCHEME`

Para Google Workspace (porta 587 / STARTTLS), **não defina** `MAIL_SCHEME`. Qualquer valor (inclusive `tls`) cria um DSN inválido no Symfony Mailer, fazendo a conexão travar e o nginx retornar 502. Deixe a chave ausente ou com valor `null`.

---

## Newsletter

Sistema próprio de captura, gerenciamento e envio de newsletters, sem dependência de SendPulse.

### Painel administrativo

| URL | Descrição |
|-----|-----------|
| `/admin/newsletter/subscribers` | Lista de inscritos com filtro por status e lista |
| `/admin/newsletter/lists` | CRUD de listas de inscritos |
| `/admin/newsletter/campaigns` | CRUD de campanhas |
| `/admin/newsletter/campaigns/{id}` | Status de envio da campanha (total / enviados / falhas / pendentes) |

### Queue dedicada

Os e-mails são enviados via fila `newsletter`, separada da fila `default`. Isso permite controlar a taxa de envio sem bloquear outras operações da aplicação.

**Iniciar worker apenas para newsletter (recomendado em produção):**

```bash
php artisan queue:work --queue=newsletter --sleep=3 --tries=3 --backoff=60
```

**Iniciar ambas as filas juntas (desenvolvimento):**

```bash
php artisan queue:work --queue=newsletter,default --sleep=3 --tries=3 --backoff=60
```

> Em produção, adicione um processo separado no Supervisor para a fila `newsletter` (veja configuração abaixo).

### Configuração do Supervisor para newsletter

O worker dedicado para a fila `newsletter` já está incluído na configuração do Supervisor — veja [Configuração do Supervisor (produção)](#configuração-do-supervisor-produção).

### Configuração de e-mail (`.env`)

O sistema é compatível com qualquer driver SMTP. Exemplos:

```env
# Google Workspace (Gmail) — porta 587 STARTTLS
# IMPORTANT: não definir MAIL_SCHEME (deve ficar ausente/null para STARTTLS)
# Gere uma App Password em: Conta Google → Segurança → Senhas de app
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=contato@alekop.com
MAIL_PASSWORD="xxxx xxxx xxxx xxxx"
MAIL_FROM_ADDRESS="contato@alekop.com"
MAIL_FROM_NAME="${APP_NAME}"
MAIL_CONTACT_ADDRESS="contato@alekop.com"

# KingHost / Locaweb (SMTP compartilhado)
MAIL_MAILER=smtp
MAIL_HOST=smtp.kinghost.net       # ou smtp.locaweb.com.br
MAIL_PORT=587
MAIL_USERNAME=seu@dominio.com.br
MAIL_PASSWORD=sua_senha
MAIL_FROM_ADDRESS=seu@dominio.com.br
MAIL_FROM_NAME="Ale Kop"

# Mailgun
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=mg.seudominio.com
MAILGUN_SECRET=key-xxxxxxxxxxxx
```

### Como funciona o envio

1. No painel, acesse uma campanha em status **Rascunho** ou **Agendado** e clique em **Enviar agora**
2. O job `ProcessNewsletterCampaign` é despachado para a fila `newsletter`
3. Ele coleta todos os inscritos ativos das listas vinculadas à campanha e cria um log de envio por inscrito
4. Em seguida, despacha jobs `SendNewsletterEmail` em **chunks de 50** com **60 segundos de intervalo entre lotes** — seguro para SMTP compartilhado
5. Cada job individual tem **3 tentativas** com **60 segundos de backoff** em caso de falha
6. Os contadores da campanha (enviados / falhas) são atualizados em tempo real; quando `enviados + falhas = total`, o status muda para **Concluído**

### Descadastro

Cada e-mail enviado contém um link de descadastro único no rodapé (`/newsletter/descadastrar/{token}`). O inscrito confirma clicando em um botão e o status é marcado como `unsubscribed` — o registro nunca é removido do banco.

### Formulários de captura

Os formulários nas páginas públicas (download e página temporária) enviam para `POST /newsletter/subscribe` com:

- `name` — nome (opcional)
- `email` — obrigatório
- `list` — slug da lista de destino (padrão: `newsletter`)
- Parâmetros UTM capturados automaticamente e salvos no campo `metadata`
- Campo honeypot `website` (oculto) para bloqueio básico de bots
- Rate limit de 5 requisições por minuto por IP

Se o e-mail já existir, o cadastro **não é duplicado**: os metadados são atualizados e o inscrito é adicionado à lista caso ainda não esteja nela.