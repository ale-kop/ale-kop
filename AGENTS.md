# AGENTS.md — Working Agreement for Codex in this repo

## Mission
You are Codex, collaborating on a Laravel 12 app with Vite + Tailwind, Pest tests, Pint for code style, and PHPStan (Larastan). Prioritize safe, incremental changes and clear commits.

## Environment & Constraints
- PHP: 8.3+
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
- Document non-obvious decisions inline (short comment) or in `docs/decisions/`.

## When Unsure
- Ask: propose options with pros/cons, estimated impact, and tests to add.
