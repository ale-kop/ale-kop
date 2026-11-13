# AGENTS.md — Working agreement for coding agents in this repo

Single source of instructions for every agent (Claude Code reads it through `CLAUDE.md`, which only imports this file). Procedures for people (deploy, Supervisor, newsletter, payments setup) live in `README.md`.

## Stack
- Laravel 13, PHP 8.5+
- Blade (no Vue/React), vanilla JavaScript (no jQuery)
- Tailwind CSS 4 via Vite — theme tokens live in `@theme` in `resources/css/app.css` (there is no `tailwind.config.js`)
- PostgreSQL, Redis (cache, session, queues), Horizon
- Pest for tests, Pint for code style
- Package managers: Composer + npm

## Environment & constraints
- Local site: `https://ale-kop.test`. Credentials only in `.env`.
- Do **NOT** expose secrets. Never commit `.env` or build output (`public/build` is built on deploy).
- Avoid destructive commands unless explicitly requested (`rm -rf` outside the repo, `chmod -R 777`, system-level paths).
- Keep edits inside the repo root unless approved.

## How to work
1. **Plan first**: propose a short plan before large refactors or dependency changes.
2. **Small commits**: each step must compile and pass tests. Conventional commits (`feat:`, `fix:`, `refactor:`, `test:`, `docs:`).
3. **Before committing**: `./vendor/bin/pint` and `php vendor/bin/pest`.
4. **Migrations & seeders**: provide a seeder for new features when useful; seeders do not run on deploy (`envoy run seed --class=...`).
5. **PR template**: Summary, Why, Changes, Tests, Risks & roll-back plan.
6. **When unsure**: propose options with pros/cons, estimated impact, and tests to add.

## Commands
```bash
composer dev                    # serve + queue:listen + pail + vite, in parallel
npm run dev | npm run build
php vendor/bin/pest             # or: composer test
php vendor/bin/pest tests/Feature/WithReadFlagTest.php
php vendor/bin/pest --filter "test name here"
./vendor/bin/pint
php artisan pail                # tail logs
```

## Coding guidelines
- Thin controllers: business logic in `app/Actions` or `app/Services`.
- Validate with Form Requests. Complex queries go in scopes or query builders.
- Always name routes with `->name()`.
- Mobile-first with Tailwind.
- Write feature tests for new endpoints and critical flows.
- Document non-obvious decisions with a short inline comment.
- Avoid: fat controllers, committed `dd()`, needless raw queries, heavy logic in Blade files.

## Frontend rules
- **Typography (mandatory, see README › Tipografia):** exactly two families, self-hosted in `resources/fonts`.
    - `font-sans` = **Schibsted Grotesk**: interface (header, nav, buttons, footer, labels, tags, dates, prices, form fields) and **all titles**, including the post title. Large titles: `font-bold tracking-[-0.035em]` (700; never `font-extrabold`).
    - `font-serif` = **Source Serif 4**: everything meant to be read: intros, post excerpts, course descriptions, About text, quotes, post dek/body/subheadings, "Continue lendo" titles. Give serif text more line-height than sans.
    - Never add a third family. `font-mono` only for code and admin slugs. Do not swap the two roles.
- **Post page (mandatory, "post-b" pattern, see README › Página de post):** every post (standalone and course lesson) uses `posts/show.blade.php` with `<x-article.header>`, `.html-content.article-prose` (Source Serif 4, 20px/1.7, 780px column, no auto-hyphenation) and `resources/css/components/article.css`. Topic color comes from `Tag::accentColor()` as the CSS variable `--topic`. New post-related UI must reuse these classes instead of creating new typography.
- **Offer (sales) pages (see README › Pagamentos e página de oferta):** one route + one view per offer in `resources/views/offers/`, declared before the catch-all post route. Name, price and access come from the `Course` (slug in `config/offers.php`); only the copy lives in the view. Styles go in `resources/css/components/offer.css`; reuse `<x-offer.bubble>` and `<x-offer.pdf-file>` for chat mockups. Mark unconfirmed copy with a `{{-- PLACEHOLDER --}}` comment and list it for the user before publishing.
- **Checkout pages** use `<x-layouts.checkout>` (no site header/footer) and `resources/css/components/checkout.css` (`.checkout-input`, `.checkout-submit`, `.checkout-method`).
- **Visual checks with headless Chrome:** it enforces a 500px minimum window width, so a 390px screenshot is silently cropped. Render the page inside a 390px-wide `<iframe>` instead.

## Blade components
- Form components in `resources/views/components/forms/` — use them instead of raw inputs: `<x-forms.input>`, `<x-forms.textarea>`, `<x-forms.button state="idle">`, `<x-forms.label>`, `<x-forms.file>`, `<x-forms.checkbox>`, `<x-forms.radio-group>`, `<x-forms.radio>`.
- Icons: Heroicons, `<x-heroicon-o-home class="w-5 h-5" />` (outline) or `<x-heroicon-s-home />` (solid).
- Unified post sidebar: `<x-post-index :post="$post" :recentPosts="$recentPosts ?? collect()" />` — course index with read/unread badges when `$post->course` exists, recent posts otherwise.

## Architecture

### Request lifecycle
Every request goes through `bootstrap/app.php`, which registers all custom exception renderers (LoginException, ValidationException, NotFoundHttpException, etc.) for both JSON (`wantsJson()`) and HTML. No try/catch in controllers.

### AJAX pattern
Controllers return JSON when the request sends `Accept: application/json`. The frontend `ajax-post.js` module drives this via `[ak-ajax-click]` attributes. Expected response shape:

```json
{ "message": "...", "title": "...", "type": "success|warning", "redirect": "...", "modalIdToClose": "...", "reload": 1, "js": "..." }
```

`Toast` and `Modal` are global singletons available in all Blade views — no import needed in JS.

### JavaScript modules
`app.js` registers modules in `window.globalModules`. After AJAX partial updates, call `window.initListOfModules(['toggle', 'fileUpload'])` to re-initialize only what is needed. Every module exposes an idempotent `init()`.

### Route ordering
The catch-all `Route::get('{post:slug}', ...)` must remain the **last** route in `routes/web.php`.

### Caching
`AppServiceProvider` caches `tagsWithContent` and `coursesWithContent` (60-minute TTL) for the layout/header/footer composers. `PostObserver` invalidates them on any post change; register invalidation there for any new cached key.

### Course vs. standalone posts
A post belongs to a course when `course_id` is set (derived from its `section_id`). `show()` renders `courses.show-post` for course posts and `posts.show` for standalone ones; `x-post-index` adapts automatically.

### Read-flag pattern
`Post::scopeWithReadFlag($query, ?int $userId)` appends `is_read` via `withExists` (single subquery, no N+1). Use it whenever loading posts for an authenticated user. Only course posts can be marked as read.

### Media (Spatie MediaLibrary)
Each model has a single-file collection (`post-image`, `course-image`, …) with conversions `thumb` (180×180), `medium` (497×290), `large` (1100×500). Access via `$post->image('medium')`.

### Rich text editor — inline images
The "Inserir imagem" button in `x-forms.rich-text-editor` opens a `<dialog>` (ALT, TITLE, legenda, link da legenda) and uploads to `POST /content-images` (`content-images.store`, admin-only). Each upload creates a `ContentImage` (own table, `content-image` collection, conversions `medium` 700×500 and `large` 1100×1100) — separate from `Post`/`Course` media because an editor image has no single owner. The endpoint returns `{ url: medium_url, largeSrc: large_url }`, producing:

```html
<figure><img src="…medium…" data-large-src="…large…" alt title><figcaption>…</figcaption></figure>
```

Editor preview styles live in `resources/css/components/rich-text-editor.css`, rendered `.html-content` styles in `resources/css/app.css` — keep them in sync when changing figure/figcaption markup.

### Image lightbox
`resources/js/modules/image-lightbox.js` activates on `.html-content figure img`: opens `data-large-src` (falls back to `src`), arrows between images when there are several, closes with `×`, backdrop click or `Escape`; arrow keys navigate. Styles: `#image-lightbox` / `#lb-*` in `resources/css/app.css`; `zoom-in` cursor on `.html-content figure img`.

### Contact page
`GET /contato` → `contact.show`, `POST /contato` → `contact.send` (throttle 3/5 min). `ContactController@send` uses `Mail::queue()` — never `Mail::send()` — so the response does not block on SMTP. Recipient: `config('mail.contact_address')` → `MAIL_CONTACT_ADDRESS`. `ContactMail` sets `replyTo` to the sender.

### Mail: SMTP for Google Workspace
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=contato@alekop.com
MAIL_PASSWORD="app-password-here"   # Google Workspace App Password
MAIL_FROM_ADDRESS="contato@alekop.com"
MAIL_FROM_NAME="${APP_NAME}"
MAIL_CONTACT_ADDRESS="contato@alekop.com"
```
Do **not** set `MAIL_SCHEME=tls` — port 587 uses STARTTLS, which requires `MAIL_SCHEME` to be absent (null). Setting it to `tls` makes the SMTP connection hang and nginx answers 502.

### Debug tools
`QueryLogger` middleware runs in debug mode and logs all queries. `php artisan pail` tails logs.

## Payments (Mercado Pago)

### How it fits together
- Offer page: `GET /orcamentos-pelo-whatsapp` → `offers.whatsapp`. Name/price come from the `Course` whose slug is in `config/offers.php`; a missing course is a 404 (create it with `WhatsappOfferCourseSeeder`). Copy lives in `resources/views/offers/whatsapp.blade.php`.
- Checkout works for any course with `extra.access = paid` and a price: `checkout.show` → `checkout.pay` (JSON) → `checkout.order` (`/pedidos/{order:uuid}`), plus `checkout.status` (Pix polling) and `webhooks.mercadopago`.
- Flow: `PayOrder` → `MercadoPagoClient::createPayment()` → `SyncOrderWithPayment` → `FulfillOrder` (find/create the user by e-mail, grant the course for 1 year with `source = mercadopago`, queue `OrderApprovedMail`). Refunds set the course access to `refunded`.
- In `local`/`testing` no `notification_url` is sent (Mercado Pago cannot reach `.test`), so Pix status comes from `checkout.status`. Env vars: `MERCADOPAGO_*` (see `.env.example` and README › Pagamentos).

### Mandatory rules
- Course access from a purchase is granted **only** by `App\Actions\Checkout\FulfillOrder` (idempotent, locks the order row). Never call `GrantCourseAccess` from controllers or webhooks directly.
- An order's status changes **only** through `SyncOrderWithPayment`, fed by a payment fetched from the Mercado Pago API (create response, `getPayment()`). Never trust status or amounts from a webhook body or from the browser.
- Card data never reaches the server: the browser tokenizes it with MercadoPago.js Secure Fields and only the token is posted.
- Every `POST /v1/payments` sends `X-Idempotency-Key` = the order uuid. Talk to the API only through `App\Services\MercadoPago\MercadoPagoClient`.
- Webhook routes are excluded from CSRF in `bootstrap/app.php` and must validate `x-signature` before doing anything.
- Messages shown to buyers come from `PaymentException` / `StatusDetail` (Portuguese, actionable). Never surface raw API errors.
- Tests use `Http::fake()` and `Mail::fake()`; they must never hit the real API. Extend `tests/Feature/CheckoutTest.php` for any change in this flow.

## Deploy (Envoy) — mandatory rules
Details and the one-time root → www-data migration: README › Deploy and README › Configuração do Supervisor.
- Every artisan call in `Envoy.blade.php` runs as **www-data** (`$artisan`), the same user as php-fpm and the queue workers. Never add a bare `php artisan` there: root-owned caches and logs break the web with permission errors far from their cause.
- After anything that writes `storage/` or `bootstrap/cache/` as root (`composer install`), run `$ownWritable`.
- Queue workers are defined in `deploy/supervisor/alekop.conf` (group `alekop`, `user=www-data`) and published with `envoy run supervisor`. Never edit them only on the server. Keep `stopwaitsecs` above the longest job timeout.
- The deploy restarts workers with `queue:restart` **and** `horizon:terminate` (Horizon ignores `queue:restart`).
- `rollback` reverts code only, never the schema: `migrate:rollback` after `git reset` cannot find the reverted migrations and would undo the previous batch instead. Undo a schema change with a new forward migration.
