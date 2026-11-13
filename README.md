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

Executa em sequência: `pull` → `composer` → `npm` → `artisan` (migrations + caches + `queue:restart` + `horizon:terminate`). Seeders não rodam no deploy: use a task `seed`.

### Tasks avulsas

```bash
php envoy run cache:clear   # limpa todos os caches sem derrubar o site
php envoy run rollback      # reverte o código do último commit (o banco fica como está — ver nota no Envoy)
php envoy run seed --class=WhatsappOfferCourseSeeder   # roda um seeder específico
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

### Configuração versionada

A configuração fica no repositório, em `deploy/supervisor/alekop.conf`: `laravel-horizon` (fila `default`, que inclui o formulário de contato e o e-mail de pedido aprovado) e `laravel-newsletter` (fila `newsletter`, separada para controlar a taxa de envio — veja a seção [Newsletter](#newsletter)), no grupo `alekop`. Os dois rodam como **www-data**, o mesmo usuário do php-fpm e do artisan no deploy.

Para publicar (na primeira vez e sempre que o arquivo mudar; o deploy avisa quando mudou):

```bash
php envoy run supervisor
```

A task copia o arquivo para `/etc/supervisor/conf.d/laravel-horizon.conf` (substituindo o setup manual antigo), roda `reread` + `update` e reinicia o grupo. Status, no servidor: `sudo supervisorctl status 'alekop:*'`; do Mac: `ssh akop "supervisorctl status 'alekop:*'"` (sem as aspas o zsh tenta expandir o `*` e responde "no matches found").

### Deploys seguintes

`php envoy run deploy` executa `queue:restart` (worker da newsletter) e `horizon:terminate` (Horizon); o Supervisor (`autorestart=true`) os traz de volta com o código novo. O `horizon:terminate` manda SIGTERM ao processo, então só funciona com o Horizon rodando como www-data — o mesmo usuário do deploy.

### Migração de root para www-data (uma vez)

Até novembro de 2026, o deploy e os workers rodavam como root. Na primeira vez, nesta ordem:

1. `php envoy run deploy` — devolve `storage/` e `bootstrap/cache/` ao www-data e roda o artisan como www-data. O `horizon:terminate` deste deploy ainda falha (o Horizon antigo é root); é esperado.
2. `php envoy run supervisor` — sobe Horizon e newsletter como www-data, já com o código novo.

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

**Inserir imagem:** o botão de imagem na toolbar abre um diálogo (ALT, TITLE, legenda e link da legenda opcional) e envia o arquivo para `POST /content-images` (rota `content-images.store`, requer autenticação). O upload usa o Spatie MediaLibrary e gera duas conversões: `medium` (700×500, usada inline) e `large` (1100×1100, usada no lightbox). A imagem é inserida no conteúdo como:

```html
<figure>
    <img src="…medium…" data-large-src="…large…" alt="…" title="…">
    <figcaption>legenda opcional</figcaption>
</figure>
```

**Lightbox:** ao clicar em qualquer imagem dentro de `.html-content`, o módulo `imageLightbox` abre um overlay com a versão `large` da imagem. Se houver mais de uma imagem no post, botões de navegação (← →) aparecem automaticamente. Teclas `Escape`, `←` e `→` também funcionam. O cursor muda para `zoom-in` ao passar sobre imagens do conteúdo.

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

## Tipografia

O site usa **duas famílias, e só duas**. Os arquivos ficam em `resources/fonts` (variáveis, subconjunto latin, que cobre o pt-BR) e são declarados em `resources/css/app.css` (`@font-face` + tokens `--font-sans` e `--font-serif` no `@theme`).

| Família | Classe Tailwind | Papel |
|---|---|---|
| **Schibsted Grotesk** (sem serifa) | `font-sans` (padrão) | Interface e títulos |
| **Source Serif 4** (serifada, com tamanho óptico) | `font-serif` | Tudo que é leitura |

Georgia e a fonte do sistema só aparecem se as fontes não carregarem.

### Schibsted Grotesk: interface e títulos

- Cabeçalho, menu, botões e rodapé.
- Títulos da home: a frase principal, os títulos de seção, o título do Sobre, os títulos dos posts na lista e os nomes dos cursos.
- Títulos de página (cursos, contato, área do aluno) e o **título principal do post**.
- Textos pequenos: etiquetas de tema, datas, filtros, preços, número de aulas, quadros laterais e campos de e-mail.
- No post: trilha "Artigos › Assunto", linha do autor, etiquetas finais, quadros (callouts, diálogo, curso) e o título "Continue lendo".

Títulos grandes usam peso forte e letras próximas: `font-bold tracking-[-0.035em]` (700; não usar `font-extrabold`).

### Source Serif 4: leitura

- Na home: texto de apresentação, resumos dos posts, descrições dos cursos, texto do Sobre e citações.
- No post: linha fina, todo o corpo do artigo (incluindo subtítulos e citações), título da newsletter e títulos em "Continue lendo".

Texto em serifa precisa de entrelinha maior que o sem serifa (ex.: `text-lg/8`, `text-3xl/10`).

### Não fazer

- Não adicionar uma terceira família. `font-mono` fica restrito a código (`pre`/`code`) e slugs no painel admin.
- Não usar `font-serif` em botões, menus ou rótulos, nem `font-sans` em parágrafos de leitura.

## Página de post (padrão "post-b")

Todos os posts (avulsos e aulas de curso) seguem o padrão de leitura estilo Substack em `resources/views/posts/show.blade.php`:

- **Coluna de 780px** (`.article-measure`), fundo branco, sem a decoração em gradiente do layout (`<x-layout :decoration="false">`).
- **Trilha** (`<x-article.breadcrumb>`): uma linha só, "Artigos › Assunto" (aulas: "Cursos › Curso"). "Artigos" leva à lista completa da home (`/#posts`); o item atual usa apenas a cor do tema, sem ponto.
- **Cabeçalho** (`<x-article.header>`): título em Schibsted Grotesk (700, `clamp(34px, 5vw, 48px)`, `letter-spacing: -.035em`), linha fina em serifa (vem de `meta.description`) e linha do autor com data, tempo de leitura (`Post::readingMinutes()`, ≈ 200 palavras/min) e botão "Copiar link".
- **Corpo** (`.html-content.article-prose`): Source Serif 4, 20px (19px no celular), entrelinha 1.7, parágrafos com 1.25em de espaço, subtítulos em serifa 600, citação com borda na cor do tema, separador `* * *`, **sem hifenização automática**.
- **Final do post avulso**: link "Mais sobre {tema}", inscrição na newsletter (`<x-article.subscribe>`), o mesmo convite em pop-up ao rolar 40% do texto (`<x-article.subscribe-popup>`, estilo Substack; ao fechar, some por 30 dias via `localStorage`) e "Continue lendo" com os 3 posts avulsos mais recentes (`<x-article.continue-reading>`).
- **Aulas de curso**: mesmo cabeçalho e corpo, com o índice do curso ao lado, "Marcar como lido" e navegação entre aulas.
- **Barra de leitura** no topo e "Copiar link": módulo JS `article` (`resources/js/modules/article.js`).

Estilos em `resources/css/components/article.css`. Os estilos de `.article-prose` sobrescrevem `.html-content` só dentro do post. O preview do editor (`rich-text-editor.css`) continua com o estilo antigo.

### Cor do tema

Cada tag pode ter uma cor (Tags › Editar › **Cor do tema**, salva em `tags.extra.color`). Ela vira a variável CSS `--topic` do post e colore o ponto da etiqueta, os marcadores de lista, a borda das citações, os links e a barra de leitura. `--topic-soft` e `--topic-ink` são derivadas com `color-mix()`. Sem cor definida, vale `Tag::DEFAULT_COLOR` (`#4087C2`, azul do logo).

## Layouts

### `x-layout`

Layout principal com header, footer, dark mode e Vite. Aceita `title` e `description` opcionais, e `:decoration="false"` para esconder o gradiente decorativo do topo (usado nos posts).

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

### `imageLightbox` — lightbox de imagens do conteúdo

Ativado automaticamente em todo `figure img` dentro de `.html-content`. Nenhum atributo HTML necessário.

- Clique na imagem → abre overlay escuro com a versão `large` (`data-large-src`; fallback para `src` em imagens antigas).
- Múltiplas imagens no post → botões ← / → para navegar entre elas.
- Fecha com o botão `×`, clicando no backdrop, ou pressionando `Escape`.
- Cursor `zoom-in` via CSS em todas as imagens de conteúdo.

### `article` — barra de leitura e "Copiar link"

Usado na página de post. `[data-article-progress]` é a barra fixa no topo, calculada sobre `[data-article-body]`. `[data-copy-link]` copia a URL atual e mostra "Link copiado" por 2 segundos.

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

### Formulários de captura e confirmação por código

Todo formulário usa o componente `<x-newsletter.form id="…" form="…">` (módulo JS `newsletter`, estilos em `resources/css/components/newsletter.css`). Está no fim do post, no pop-up do post e na página de download. **A inscrição só vale depois de confirmar o e-mail com um código de 6 dígitos:**

1. `POST /newsletter/subscribe` (`newsletter.subscribe`, 5 req/min por IP): registra o e-mail como `pending` e envia o código por e-mail (`NewsletterVerificationMail`, pela fila `default`, não pela fila `newsletter` das campanhas).
2. `POST /newsletter/verificar` (`newsletter.verify`, 10 req/min por IP): confere o código. Se estiver certo, o inscrito vira `active`, recebe `email_verified_at` e entra na lista. Só nesse momento ele passa a receber campanhas.

Regras do código (`NewsletterVerificationService`): vale 15 minutos, é guardado só como hash, aceita 5 tentativas erradas (depois é preciso pedir outro) e só pode ser reenviado depois de 60 segundos. Pedir um código novo invalida o anterior. Quem já está `active` e preenche o formulário de novo continua ativo; descadastrados só voltam a `active` depois de confirmar. A lista só é associada na confirmação, nunca no primeiro envio.

Validação do e-mail (newsletter e contato): formato `email:rfc` + regra `ReceivableEmailDomain`, no lugar do `email:rfc,dns`. O `dns` do Laravel reprova o endereço inteiro quando a *consulta* de DNS falha (timeout ou cache frio do resolvedor na primeira tentativa), então um e-mail válido podia dar erro e passar na segunda vez. Agora o domínio só é reprovado se não existir de fato: acertos ficam 1 dia em cache, há uma nova tentativa, e se nem `gmail.com` resolver o problema é o DNS e o e-mail é aceito (um endereço errado seria barrado pelo código). Respostas 422 em JSON trazem a primeira mensagem de erro real em `message`, em vez de um texto genérico.

Campos do `POST /newsletter/subscribe`: `email` (obrigatório), `name`, `list` (padrão `newsletter`), `source_url`, `source_form`, UTMs (em `metadata`) e o honeypot `website`.

Depois de confirmar, o site mostra a mensagem de sucesso abaixo do campo e do botão e solta confete na tela toda (`resources/js/modules/confetti.js`, sem dependências; respeita `prefers-reduced-motion`).

Inscritos criados antes da confirmação por código continuam `active` com `email_verified_at` vazio.

#### De qual página veio o inscrito

Cada inscrito guarda a origem do cadastro em colunas próprias de `newsletter_subscribers`, prontas para agregar:

| Coluna | Conteúdo |
|---|---|
| `source_path` | Página onde o formulário foi enviado, normalizada: `/slug`, sem domínio, query ou barra final. URL de outro domínio é ignorada. |
| `source_form` | Qual formulário: `post-end`, `post-popup`, `download`… |
| `source_post_id` | Post ou aula dessa página (`/{slug}`), quando existe. Vira `null` se o post for apagado. |

A origem é a **primeira** (first-touch): se a mesma pessoa se inscrever de novo por outra página, ela não é sobrescrita. UTMs ficam em `metadata`. Exemplo de ranking (só inscritos confirmados):

```php
NewsletterSubscriber::where('status', 'active')->whereNotNull('source_path')
    ->groupBy('source_path')->selectRaw('source_path, count(*) as total')
    ->orderByDesc('total')->get();
```

## Pagamentos (Mercado Pago) e página de oferta

### Página de oferta

`GET /orcamentos-pelo-whatsapp` → `offers.whatsapp` (`OfferController@whatsapp`, view `offers/whatsapp.blade.php`, estilos em `resources/css/components/offer.css`). Nome e preço vêm do curso cujo slug está em `config/offers.php` (`OFFER_WHATSAPP_COURSE_SLUG`); sem esse curso cadastrado a página responde 404. Para criar/atualizar o curso: `php artisan db:seed --class=WhatsappOfferCourseSeeder` (preço inicial de placeholder, ajuste no admin). Trechos de copy marcados com `PLACEHOLDER` na view ainda precisam de revisão.

### Checkout

| Rota | Nome | O que faz |
|---|---|---|
| `GET /checkout/{courseSlug}` | `checkout.show` | Formulário no formato do Stripe: produto à esquerda, pagamento à direita |
| `POST /checkout/{courseSlug}/pagar` | `checkout.pay` | Cria o `Order` e o pagamento no Mercado Pago (JSON) |
| `GET /pedidos/{order:uuid}` | `checkout.order` | Aprovado, Pix aguardando (QR Code), em análise ou recusado |
| `GET /pedidos/{order:uuid}/status` | `checkout.status` | Consulta usada pelo Pix; reconsulta o Mercado Pago a cada 5s no máximo |
| `POST /webhooks/mercadopago` | `webhooks.mercadopago` | Notificações (sem CSRF, validadas por `x-signature`) |

Qualquer curso com `extra.access = paid` e preço tem checkout. Cartão usa os **Secure Fields** do MercadoPago.js: número, validade e CVV são iframes do Mercado Pago e o servidor só recebe o token. Pix devolve QR Code + copia e cola, válido por `MERCADOPAGO_PIX_EXPIRATION_MINUTES`.

Fluxo no servidor: `PayOrder` (monta o payload e chama `MercadoPagoClient`, com `X-Idempotency-Key` = uuid do pedido) → `SyncOrderWithPayment` (copia o status; aprovado → `FulfillOrder`; estornado → acesso `refunded`) → `FulfillOrder` (idempotente: acha ou cria o usuário pelo e-mail, libera o curso por 1 ano com `source = mercadopago` e enfileira `OrderApprovedMail`, que traz o link de "criar senha" quando a conta é nova).

### Configuração

```env
MERCADOPAGO_PUBLIC_KEY=APP_USR-...      # Suas integrações → Credenciais de produção
MERCADOPAGO_ACCESS_TOKEN=APP_USR-...
MERCADOPAGO_WEBHOOK_SECRET=...           # Suas integrações → Webhooks → Assinatura secreta
```

No painel do Mercado Pago, cadastre o webhook `https://alekop.com/webhooks/mercadopago` com o evento **Pagamentos**. Em `local`/`testing` o `notification_url` não é enviado (o Mercado Pago não alcança `.test`); o status do Pix vem pela consulta da página do pedido. Para testar sem cobrar, use as credenciais de teste e os [cartões de teste](https://www.mercadopago.com.br/developers/pt/docs/checkout-api/integration-test/test-cards).
