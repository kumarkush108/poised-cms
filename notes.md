# Developer Notes

_Compiled from a full codebase audit on 2026-06-12. These notes capture observations and decisions inferred from the actual implementation — not aspirational design._

## Architecture decisions

- The project is a **plain Laravel MVC app with no extra layers** — no services, repositories, policies, or domain modules. If you're tempted to add a service layer "to be consistent," there's nothing to be consistent with yet; keep new code simple and Laravel-idiomatic until a real need for abstraction appears.
- Public marketing pages are implemented as **route closures returning views directly** (no controllers). If these pages start needing logic (e.g., dynamic content from a `pages` table), introduce a controller at that point rather than cramming logic into `routes/web.php`.
- The admin area uses a **separate layout and asset set** (`admin/layouts/app.blade.php`, `public/admin/assets/`) from the public site (`layouts/app.blade.php`, `public/assets/`). Don't mix these — admin views should not pull in public site partials and vice versa.

## Database observations

- As of Phase B, the CMS schema exists: `media`, `pages`, `page_sections`, `section_fields`, `section_items`, `item_fields`, `menus`, `menu_items`, `settings`, `cache_versions` — see "Phase B notes" below for the field-based architecture.
- Default connection is **SQLite** (`database/database.sqlite`, local dev file). `config/database.php` also defines a MySQL connection for non-default use.
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

- If building out CMS sections (Home/About/Services/Solutions CMS, Contact Messages — all referenced as dead links in `admin/partials/sidebar.blade.php`), plan the migrations/models/controllers/routes as one coherent unit per section rather than partially wiring one piece — the sidebar already advertises these sections, so half-built features will be immediately visible/clickable.
- The contact and appointment forms in `pages/contact.blade.php` and `pages/home.blade.php` are pure frontend markup with no `action`/backend wiring — confirm with the user what should happen on submission (store to DB? email notification? both?) before implementing, since this affects whether a `contact_messages` table is needed.

## Phase A notes (2026-06-12)

- Admin routes are now split into two groups in `routes/web.php`: a `guest`-middleware group (`admin.login`, `admin.login.submit`, `admin.password.request`, `admin.password.email`) and an `auth`-middleware group (`admin.dashboard`, `admin.logout`). Any new admin route must be added to the correct group.
- Laravel 12's default `Authenticate`/`RedirectIfAuthenticated` middleware normally redirect to a route named `login`, which doesn't exist here. `bootstrap/app.php` sets `redirectGuestsTo`/`redirectUsersTo` to `admin.login`/`admin.dashboard`. **These are global settings** (apply to any `auth`/`guest`-protected route, not just `/admin/*`), so both closures check `$request->is('admin/*')` and fall back to `route('home')` otherwise — this keeps any future non-admin `auth`/`guest`-protected route (e.g. a customer account area) from being redirected into the admin panel. Keep this conditional if route names change.
- Login throttling uses Laravel's built-in `throttle:5,1` middleware on `admin.login.submit` (5 attempts/minute, keyed by IP+email by default) — no extra package needed.
- Seeded test user (`test@example.com` / `password`) is now a real working login.

## Phase B notes (2026-06-12) — CMS schema architecture

- **Field-based, EAV-style content storage**: `page_sections` and `section_items` are *purely structural* (which `section_key`/`item_type`, which page/section, order, active flag). All actual content is rows in `section_fields` (`page_section_id`, `field_key`, `value`, `media_id`) and `item_fields` (`section_item_id`, `field_key`, `value`, `media_id`). This means **new section/item types never require a migration** — only a new entry in `config/cms/templates.php` plus a Blade partial. `PageSection::field($key)` / `SectionItem::field($key)` helper accessors return `value` or the related `Media` model depending on whether `media_id` is set.
- **Template Registry** lives at `config/cms/templates.php` and is loaded via `AppServiceProvider::register()` using `mergeConfigFrom(config_path('cms/templates.php'), 'cms.templates')` — Laravel does **not** auto-load config files in subdirectories of `config/`, so this merge is required. Access via `App\Cms\TemplateRegistry` (`pageTemplates()`, `allowedSections()`, `sectionFields()`, `itemFields()`, etc.). Any `section_key`/`item_type`/`field_key` written to the DB should be validated against this registry at the application layer (DB has no type enforcement on `value`/`media_id`).
- **`pages.is_system`**: the 5 seeded pages (`home`, `about`, `services`, `solutions`, `contact`) have `is_system=true`. `App\Models\Page` boot-time guards throw `\RuntimeException` if code tries to (a) delete an `is_system` page, (b) change `slug`/`template` on an `is_system` page, or (c) change `slug`/`template` on *any* existing page after creation (these two fields are creation-time-only for custom pages). Any future "New Page"/"Edit Page" admin controller must catch/avoid triggering these, not bypass them.
- **Media**: `media` table is schema-only (Phase C will add upload/storage/picker). All image/logo/favicon references across `pages`, `section_fields`, `item_fields`, and `settings` use a `media_id` FK (`ON DELETE SET NULL`), never path strings.
- **Soft deletes**: `media`, `pages`, `page_sections`, `section_items` have `deleted_at`. Note Eloquent soft-delete does **not** cascade to children automatically (only hard-delete FK cascade does) — a future "delete page" admin action must explicitly soft-delete its sections/items too if a cascading trash UX is wanted.
- **No Blade views were changed in Phase B** — `page_sections` rows exist for all 5 system pages (counts: home=8, about=3, services=4, solutions=5, contact=3) per their template's `allowed_sections`, but `section_fields`/`section_items`/`item_fields` are empty. Phase I will populate these per-section (transcribing from the existing Blade partials) and rewire each partial to read from the DB, one section at a time, checking visual parity.
- **Menus**: `menus`/`menu_items` tables + seed data exist (`header`/`footer`, each with 5 items linking to the 5 system pages via `page_id`). No admin CRUD yet — deferred to a later phase as agreed.
- **Settings**: `settings` table seeded with 11 `theme`-group rows (9 color hex values + `logo`/`favicon` as `media_id` placeholders, currently `null`). No admin UI yet.

## Phase B hardening notes (2026-06-12)

- **`Page` model protection is now complete across both Eloquent delete lifecycles**: `static::deleting` blocks `->delete()` (soft delete) and `static::forceDeleting` blocks `->forceDelete()` (hard delete) for `is_system` pages — both throw `\RuntimeException`. `static::updating` still blocks slug/template changes on `is_system` pages, and blocks slug/template changes on *any* page after creation.
- **Remaining bypasses (documented, not yet closed — deferred to Phase F per architecture review)**: query-builder mass operations (`Page::query()->delete()`, `Page::where(...)->update(...)`) and raw DB access (`DB::table('pages')->...`) skip Eloquent model events entirely and are NOT protected by these guards. Any future bulk-admin action must load-and-iterate model instances (`$pages->each->delete()`), never use query-builder mass mutations on `pages`.
- **Recommended for Phase F**: add `Page::deletable()` scope (`where('is_system', false)`) and use it for any admin page-list "select & delete" UI, so `is_system` pages are never offered as deletable. An `editable()` scope was considered but judged low-value since the `updating` guard already enforces slug/template locks per-instance.
- **Testing DB**: `phpunit.xml` now sets `DB_CONNECTION=sqlite` / `DB_DATABASE=:memory:` for the `testing` environment (previously commented out, which meant `php artisan test` would have run `RefreshDatabase` against the real MySQL dev database `poised_cms` per `.env`). All future tests using `RefreshDatabase`/`DatabaseTransactions` now run safely against an in-memory SQLite DB recreated from the full migration set.
- **New test coverage**: `tests/Unit/PageSystemProtectionTest.php` — 9 tests covering every `Page` model protection rule (system-page delete/force-delete/slug/template guards, non-system page creation-time-only slug/template lock, and confirming normal soft-delete/force-delete/field-update still work for non-system pages).
