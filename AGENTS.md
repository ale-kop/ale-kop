# AGENTS.md — Working Agreement for Codex in this repo

## Mission
You are Codex, collaborating on a Laravel 12+ app with Vite + Tailwind, Pest tests, Pint for code style, and PHPStan (Larastan). Prioritize safe, incremental changes and clear commits.

## Environment & Constraints
- PHP: 8.5+
- Node: LTS
- Package managers: Composer + PNPM (preferred) or NPM
- Database: MySQL (MAMP) locally; use `.env` for credentials
- Do **NOT** expose secrets. Never commit `.env` or vendor builds.
- Avoid destructive commands unless explicitly requested:
    - DO NOT run `rm -rf /`, `chmod -R 777`, or alter system-level paths
    - DO NOT modify global MAMP configs
- Keep edits inside the repo root unless approved.

## How to Work
1. **Plan first**: Propose a short plan before large refactors or dependency changes.
2. **Small PRs/commits**: Prefer small, testable steps. Each step must compile and pass tests.
3. **App structure**: Follow Laravel defaults. If creating services, use `app/Services` and `app/Actions`.
4. **Style & QA**:
    - Run `./vendor/bin/pint` before committing.
    - Run tests with `php artisan test` (Pest).
    - Run static analysis with `./vendor/bin/phpstan analyse` (level set in `phpstan.neon`).
5. **Frontend**:
    - Tailwind via Vite. Put components in `resources/js` and styles in `resources/css`.
    - Keep `tailwind.config.js` minimal and documented.
    - **Typography (mandatory, see README › Tipografia):** exactly two families, self-hosted in `resources/fonts`.
        - `font-sans` = **Schibsted Grotesk**: interface (header, nav, buttons, footer, labels, tags, dates, prices, form fields) and **all titles**, including the post title. Large titles: `font-bold tracking-[-0.035em]` (700; never `font-extrabold`).
        - `font-serif` = **Source Serif 4**: everything meant to be read: intros, post excerpts, course descriptions, About text, quotes, post dek/body/subheadings, "Continue lendo" titles. Give serif text more line-height than sans.
        - Never add a third family. `font-mono` only for code and admin slugs. Do not swap the two roles.
    - **Offer (sales) pages (see README › Pagamentos e página de oferta):** one route + one view per offer in `resources/views/offers/`, declared before the catch-all post route. Name, price and access come from the `Course` (slug in `config/offers.php`); only the copy lives in the view. Styles go in `resources/css/components/offer.css`; reuse `<x-offer.bubble>` and `<x-offer.pdf-file>` for chat mockups. Mark unconfirmed copy with a `{{-- PLACEHOLDER --}}` comment and list it for the user before publishing.
    - **Checkout pages** use `<x-layouts.checkout>` (no site header/footer) and `resources/css/components/checkout.css` (`.checkout-input`, `.checkout-submit`, `.checkout-method`).
    - **Visual checks with headless Chrome:** it enforces a 500px minimum window width, so a 390px screenshot is silently cropped. Render the page inside a 390px-wide `<iframe>` instead.
    - **Post page (mandatory, "post-b" pattern, see README › Página de post):** every post (standalone and course lesson) uses `posts/show.blade.php` with `<x-article.header>`, `.html-content.article-prose` (Source Serif 4, 20px/1.7, 780px column, no auto-hyphenation) and `resources/css/components/article.css`. Topic color comes from `Tag::accentColor()` as the CSS variable `--topic`. New post-related UI must reuse these classes instead of creating new typography.
6. **Migrations & Seeders**:
    - Backfill using `php artisan migrate --path=` for scoped runs.
    - Provide seeders for new features when useful.
7. **Commits**: Conventional commits (`feat:`, `fix:`, `refactor:`, `test:`). Write clear messages.
8. **PR Template** (follow this when opening PRs):
    - Summary
    - Why
    - Changes
    - Tests
    - Risks & roll-back plan

## Commands You May Use
- Install/update: `composer install`, `composer update`, `pnpm install`
- Build/dev: `pnpm dev`, `pnpm build`
- Tests: `php artisan test`, `./vendor/bin/pest`
- Lint/format: `./vendor/bin/pint`, `./vendor/bin/phpstan analyse`
- Generate: `php artisan make:*` (controllers, models, actions, events, jobs)

## Coding Guidelines
- Keep controllers thin; move business logic to Actions/Services.
- Validate with Form Requests.
- Prefer DTOs when passing complex data.
- Write feature tests for new endpoints and critical flows.

## Payments (Mercado Pago) — mandatory rules
- Course access from a purchase is granted **only** by `App\Actions\Checkout\FulfillOrder` (idempotent, locks the order row). Never call `GrantCourseAccess` from controllers or webhooks directly.
- An order's status changes **only** through `SyncOrderWithPayment`, fed by a payment fetched from the Mercado Pago API (create response, `getPayment()`). Never trust status or amounts from a webhook body or from the browser.
- Card data never reaches the server: the browser tokenizes it with MercadoPago.js Secure Fields and only the token is posted.
- Every `POST /v1/payments` sends `X-Idempotency-Key` = the order uuid. Talk to the API only through `App\Services\MercadoPago\MercadoPagoClient`.
- Webhook routes are excluded from CSRF in `bootstrap/app.php` and must validate `x-signature` before doing anything.
- Messages shown to buyers come from `PaymentException` / `StatusDetail` (Portuguese, actionable). Never surface raw API errors.
- Tests use `Http::fake()` and `Mail::fake()`; they must never hit the real API. Extend `tests/Feature/CheckoutTest.php` for any change in this flow.
- Document non-obvious decisions inline (short comment) or in `docs/decisions/`.

## When Unsure
- Ask: propose options with pros/cons, estimated impact, and tests to add.
