# CLAUDE.md

## Persistent Memory Workflow (Mandatory)

Before starting any task, read `CLAUDE.md`, `PROJECT_PROGRESS.md`, and `NOTES.md`. They are the source of truth for architecture, conventions, status, and known issues — never assume context without checking them first.

After any meaningful change, update project memory:

- **PROJECT_PROGRESS.md** — when a feature, fix, refactor, or milestone changes status. Append a dated Change Log entry (what changed, files touched, why) and update "Active Priorities" / "Roadmap" to reflect the new state.
- **NOTES.md** — when you discover a limitation, risk, architectural quirk, or decision future work needs to know about.
- **CLAUDE.md** — only when architecture, conventions, or product direction actually change.

## Project overview

This repository is a Laravel 12 application for **Poised Technology**. It currently serves two purposes:

1. A public marketing/informational site (home, about, services, solutions, contact).
2. A skeletal admin area: login screen, forgot-password screen, and a dashboard shell with hardcoded metrics.

The implementation is a frontend-and-routing project rather than a full CMS. Public pages are static Blade views with no database-backed content. The admin area is a visual shell only — authentication, content management, and message storage are not implemented.

## Verified technology stack

- PHP 8.2
- Laravel Framework 12.0
- Laravel Tinker, Laravel Pail (log viewer), Laravel Pint, Laravel Sail
- SQLite for local development (`database/database.sqlite`), database drivers for session/cache/queue
- Vite 6 + Laravel Vite Plugin
- Tailwind CSS 4 (via `@tailwindcss/vite`)
- Bootstrap 5 + Bootstrap Icons
- jQuery-era frontend libs: WOW.js, OWL Carousel, Slick Carousel, counterUp, easing.js, Font Awesome 5
- PHPUnit 11 for tests (only stock Laravel example tests present)

## Coding conventions discovered

Standard Laravel structure is followed:

- Routes in `routes/web.php` (no `routes/api.php` exists)
- Controllers in `app/Http/Controllers` (including `Admin/` subdirectory)
- Eloquent models in `app/Models`
- Blade views in `resources/views`
- Migrations in `database/migrations`
- Frontend assets in `resources/css` and `resources/js`, compiled via Vite

Observed patterns:

- Public routes are defined as inline closures returning views directly (no controller layer for the marketing pages)
- Admin routes use `Admin\AuthController` for login
- Blade template inheritance via `@extends`, `@section`, `@yield`, and `@stack` (for per-page `styles`/`scripts`)
- Route names are used throughout views for navigation (`route('home')`, `route('admin.dashboard')`, etc.)
- Simple inline validation in controller methods (`$request->validate([...])`)
- Standard Laravel model/factory/seeder conventions

No custom services, middleware, policies, jobs, events, listeners, or providers (other than the default `AppServiceProvider`, which is empty) were found.

## Architecture overview

Conventional Laravel MVC, currently very thin:

- **Routes** (`routes/web.php`) define 5 public pages (closures → views) and an `admin` route group (login, dashboard, forgot-password).
- **Controllers**: only `App\Http\Controllers\Admin\AuthController` exists, handling the login form display and submission.
- **Views**: Blade templates render all pages; public pages extend `layouts.app`, admin pages extend `admin.layouts.app`.
- **Models**: only the default `App\Models\User` (Laravel's auth model) exists.
- **Database**: only Laravel's default tables exist — `users`, `password_reset_tokens`, `sessions`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`. No CMS-specific tables (pages, services, solutions, messages) exist yet, despite the admin sidebar implying them.
- **Build**: Vite compiles `resources/css/app.css` (Tailwind 4) and `resources/js/app.js`.

There is no service layer, repository pattern, or domain layer.

## Folder structure explanation

- `app/`
  - `Http/Controllers/`: `Controller.php` (empty base), `Admin/AuthController.php` (login only)
  - `Models/`: `User.php` (default Laravel auth model)
  - `Providers/`: `AppServiceProvider.php` (empty register/boot)
- `resources/views/`
  - `layouts/app.blade.php`: public site layout (head, topbar, navbar, content, footer, scripts)
  - `pages/`: `home`, `about`, `services`, `solutions`, `contact` — all extend `layouts.app`
  - `partials/`: `head`, `topbar`, `navbar`, `footer`, `scripts`, `brand` — shared fragments for the public layout
  - `admin/layouts/app.blade.php`: admin layout (Bootstrap 5, sidebar + navbar + content + footer)
  - `admin/auth/`: `login.blade.php`, `forgot-password.blade.php`
  - `admin/dashboard/index.blade.php`: dashboard with hardcoded metric cards
  - `admin/partials/`: `sidebar`, `navbar`, `footer` for the admin layout
- `routes/`
  - `web.php`: public routes (closures) + `admin` prefix group
  - `console.php`: only the default `inspire` Artisan command
- `database/`
  - `migrations/`: 3 default Laravel migration files (users/sessions/password resets, cache, jobs)
  - `seeders/DatabaseSeeder.php`: creates one test user (`test@example.com`)
  - `factories/UserFactory.php`: default Laravel user factory
  - `database.sqlite`: local dev database file
- `public/`
  - `assets/`: frontend site assets (css, js, lib, scss, img, video) — used by `layouts.app` and partials
  - `admin/assets/`: admin dashboard assets (css, js, images, vendors) — used by `admin.layouts.app`
- `config/`: standard Laravel config files; `auth.php` defines the default `web` guard with the `users` Eloquent provider; `database.php` defaults to `sqlite`; `session.php`/`cache.php`/`queue.php` use the `database` driver

## Development workflow

1. Start the app with the normal Laravel development flow (`php artisan serve`, or the `composer run dev` script which also runs queue listener, Pail, and `npm run dev` concurrently).
2. Use Vite (`npm run dev` / `npm run build`) for frontend assets — only `resources/css/app.css` and `resources/js/app.js` are Vite entry points; the public/admin asset directories under `public/` are served as static files, not bundled by Vite.
3. Update Blade templates and `routes/web.php` together for page-level changes.
4. Use migrations under `database/migrations` when database changes are required.

No custom build pipeline, CI workflow, or deployment script is present in the repository.

## Git workflow

No custom Git workflow, branch policy, or release process is documented. The project relies on standard Git usage. (Note: this directory was not a git repository as of the last check.)

## Migration rules

- Add or update migrations in `database/migrations`; keep them incremental and reversible.
- Avoid editing the 3 existing default migrations (users/sessions, cache, jobs) once a shared environment depends on them.
- Any new CMS entities (pages, services, solutions, contact messages) referenced by the admin sidebar do not yet have migrations — these need new migration files, models, controllers, and routes before the corresponding admin links can work.

## Route/view naming rules

- Keep route names stable since they are referenced directly in Blade views via `route(...)` (e.g. `home`, `about`, `solutions`, `services`, `contact`, `admin.login`, `admin.dashboard`, `admin.password.request`).
- Update the corresponding Blade view and route name together when changing routes.

## Deployment precautions

- Ensure `APP_KEY` and other environment variables are configured before deployment (see `.env.example` for the expected keys).
- `.env.example` defaults: `DB_CONNECTION=sqlite`, `SESSION_DRIVER=database`, `QUEUE_CONNECTION=database`, `CACHE_STORE=database`, `MAIL_MAILER=log`, `BROADCAST_CONNECTION=log`.
- No custom deployment hooks, Docker config beyond Sail's dev dependency, or CI workflows exist.
- **The admin area must not be treated as production-ready** — see warnings below.

## Project-specific warnings

- **`Admin\AuthController::loginSubmit` does not authenticate.** It validates `email`/`password` and redirects straight to the dashboard regardless of credentials (the code has a placeholder comment "Authentication logic here").
- **`/admin/dashboard` has no auth middleware.** It is publicly accessible by URL with no login required.
- **The forgot-password flow is a stub.** It validates the email field and returns a generic success response; no reset email is sent and no token is generated.
- **Dashboard metrics are hardcoded** in `admin/dashboard/index.blade.php` (Total Pages: 12, Services: 8, Solutions: 6, Messages: 24) — not backed by any query.
- **Admin sidebar links to "Home CMS", "About CMS", "Solutions CMS", "Services CMS", and "Contact Messages" are placeholders** with no corresponding routes, controllers, or tables.
- **The public contact/appointment forms have no backend handler** — no POST route or controller processes form submissions.

## Known constraints

- No middleware (custom or auth-related) is registered in `bootstrap/app.php`.
- No services, jobs, events, listeners, or policies exist anywhere in `app/`.
- Only one Eloquent model exists (`User`), and only the default Laravel auth-related tables exist in the schema.
- Test coverage is limited to the stock Laravel example tests (`tests/Feature/ExampleTest.php`, `tests/Unit/ExampleTest.php`), which only check that `/` returns HTTP 200.

## Important implementation details

- Public site pages: home, about, services, solutions, contact — all static Blade content extending `layouts.app`.
- `layouts.app` assembles the public page from `partials/head`, `partials/topbar`, `partials/navbar`, the page's `@yield('content')`, `partials/footer`, and `partials/scripts`, with `@stack('styles')`/`@stack('scripts')` for per-page additions.
- `admin.layouts.app` assembles admin pages from `admin/partials/sidebar`, `admin/partials/navbar`, the page content, and `admin/partials/footer`, styled with Bootstrap 5 (`public/admin/assets/css/bootstrap.min.css`, `style.css`) and scripted with `bootstrap.bundle.min.js`/`main.js`.
- The default Laravel `User` model/auth provider is configured (`web` guard, session driver, `users` Eloquent provider) but is not actively used by the admin login flow yet.
- Frontend marketing pages use Bootstrap 5 + Font Awesome 5 + WOW.js + OWL Carousel + Slick Carousel for visual effects, loaded via `public/assets/`.
