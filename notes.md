# Developer Notes

_Compiled from a full codebase audit on 2026-06-12. These notes capture observations and decisions inferred from the actual implementation — not aspirational design._

## Architecture decisions

- The project is a **plain Laravel MVC app with no extra layers** — no services, repositories, policies, or domain modules. If you're tempted to add a service layer "to be consistent," there's nothing to be consistent with yet; keep new code simple and Laravel-idiomatic until a real need for abstraction appears.
- Public marketing pages are implemented as **route closures returning views directly** (no controllers). If these pages start needing logic (e.g., dynamic content from a `pages` table), introduce a controller at that point rather than cramming logic into `routes/web.php`.
- The admin area uses a **separate layout and asset set** (`admin/layouts/app.blade.php`, `public/admin/assets/`) from the public site (`layouts/app.blade.php`, `public/assets/`). Don't mix these — admin views should not pull in public site partials and vice versa.

## Database observations

- Only Laravel's default tables exist: `users`, `password_reset_tokens`, `sessions`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`. There is **no CMS schema** (no `pages`, `services`, `solutions`, or `messages` tables) despite the admin UI implying these exist.
- Default connection is **SQLite** (`database/database.sqlite`, local dev file, ~86KB). `config/database.php` also defines a MySQL connection for non-default use.
- Session, cache, and queue all use the `database` driver (per `.env.example`), meaning the `sessions`, `cache`/`cache_locks`, and `jobs`/`job_batches`/`failed_jobs` tables are actively used by the framework even though no app-specific tables exist yet.
- `DatabaseSeeder` seeds exactly one user: `test@example.com` (via `UserFactory`, password is the factory default "password", hashed). The `User::factory(10)->create()` line is commented out.

## Integration notes

- **Mail**: `MAIL_MAILER=log` in `.env.example` — no real mail provider configured. Any future password-reset or contact-form-notification emails will just write to the log file until this is changed.
- **Broadcasting**: `BROADCAST_CONNECTION=log` — no real broadcast driver configured.
- **No external services** are configured in `config/services.php` (empty).
- **No API**: `routes/api.php` doesn't exist. If a future need arises for the admin SPA or mobile app, this will need to be created and registered.

## Business rules found in code

- Company is **Poised Technology**, an EV-charging-and-software-solutions business. Sub-brand names appear in marketing content (e.g., "Poisedsol", "Corezone", "Eindhan") — these are content/copy references inside `pages/home.blade.php` and related views, not configuration values. Treat them as marketing copy, not as systems to integrate with, unless told otherwise.
- The home page (`pages/home.blade.php`, ~761 lines) is the largest view and contains: hero carousel, EV solutions section, brand logos, about section, feature highlights, an 8-card services grid, an appointment booking form, and testimonials.
- The admin dashboard's 4 metric cards (Total Pages: 12, Services: 8, Solutions: 6, Messages: 24) are **literal numbers in the Blade file**, not derived from the `services` grid count or any data — don't assume they reflect real counts of anything.

## Reusable patterns

- **Public layout composition**: `layouts/app.blade.php` → `partials/head`, `partials/topbar`, `partials/navbar`, `@yield('content')`, `partials/footer`, `partials/scripts`, with `@stack('styles')` and `@stack('scripts')` available for page-specific additions. New public pages should follow this same `@extends('layouts.app')` + `@section('content')` + optional `@push('styles')`/`@push('scripts')` pattern.
- **Admin layout composition**: `admin/layouts/app.blade.php` → `admin/partials/sidebar`, `admin/partials/navbar`, content, `admin/partials/footer`. New admin pages should extend `admin.layouts.app` and add a sidebar link in `admin/partials/sidebar.blade.php` following the existing markup pattern (currently only the "Dashboard" link is active/linked; others are placeholders to be wired up as their pages are built).
- **Validation pattern**: `Admin\AuthController::loginSubmit` uses inline `$request->validate([...])` — follow this same inline-validation approach for new simple controllers unless complexity grows enough to warrant Form Request classes.

## Areas that must not be changed casually

- **Route names** (`home`, `about`, `solutions`, `services`, `contact`, `admin.login`, `admin.login.submit`, `admin.dashboard`, `admin.password.request`, `admin.password.email`) — these are referenced via `route()` calls throughout the Blade views. Renaming a route requires updating every view that references it.
- **Asset paths under `public/assets/` and `public/admin/assets/`** — these are referenced directly by hardcoded paths in `partials/head.blade.php`, `partials/scripts.blade.php`, and the admin layout/partials (not processed by Vite). Moving or renaming files here will silently break page styling/scripts with no build-time error.
- **`database/database.sqlite`** — this is the active local dev database; don't delete or regenerate it without checking whether it holds data someone is relying on locally.

## Developer notes for future sessions

- Before adding any admin feature, **add auth middleware first** — `/admin/dashboard` and all future admin routes are currently open to the public. This is the single highest-priority gap.
- If building out CMS sections (Home/About/Services/Solutions CMS, Contact Messages — all referenced as dead links in `admin/partials/sidebar.blade.php`), plan the migrations/models/controllers/routes as one coherent unit per section rather than partially wiring one piece — the sidebar already advertises these sections, so half-built features will be immediately visible/clickable.
- The contact and appointment forms in `pages/contact.blade.php` and `pages/home.blade.php` are pure frontend markup with no `action`/backend wiring — confirm with the user what should happen on submission (store to DB? email notification? both?) before implementing, since this affects whether a `contact_messages` table is needed.
- No git repository exists in this working directory as of this audit — if version control is wanted, `git init` will be needed (don't do this without asking, per standard workflow caution).
