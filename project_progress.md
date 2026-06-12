# Project Progress

_Last rebuilt from a full codebase audit on 2026-06-12._

## Current project status

This is a Laravel 12 corporate website for Poised Technology with a public marketing site and an early-stage admin panel skeleton. The public site is functionally complete (static content). The admin panel exists visually but has no working backend logic — no real authentication, no content management, no message handling.

## Completed modules

- **Public marketing site**: home, about, services, solutions, contact pages — all built out with full content, carousels, brand sections, testimonials, and forms (UI only).
- **Public layout system**: `layouts/app.blade.php` + partials (`head`, `topbar`, `navbar`, `footer`, `scripts`, `brand`) — reusable across all public pages.
- **Admin layout shell**: `admin/layouts/app.blade.php` + partials (`sidebar`, `navbar`, `footer`) — Bootstrap 5 based, with sidebar navigation and profile dropdown.
- **Admin login screen**: `admin/auth/login.blade.php` with full form markup, validation error display, "remember me" checkbox, and theme toggle.
- **Admin forgot-password screen**: `admin/auth/forgot-password.blade.php` view exists with a route that validates an email field.
- **Admin dashboard shell**: `admin/dashboard/index.blade.php` with 4 metric cards (currently hardcoded values).
- **Base Laravel scaffolding**: default `User` model, default migrations (users/sessions/password resets, cache, jobs/queues), `UserFactory`, `DatabaseSeeder` (seeds one test user, `test@example.com`).
- **Frontend build pipeline**: Vite 6 + Tailwind CSS 4 configured and working (`resources/css/app.css`, `resources/js/app.js`).

## In-progress modules

CMS transformation (Phases A-J per approved roadmap). Phase A complete. Phase B (CMS Core Database Schema) complete; Phase C (Media Library) is next, pending review.

## Pending modules

- **Real forgot-password flow**: token generation, reset email (mail is currently `log` driver), and a reset-password form/route.
- **CMS admin UI**: `pages`/`page_sections`/`section_fields`/`section_items`/`item_fields` schema now exists (Phase B), but there is no admin controller/UI yet to edit them — "Home CMS", "About CMS", "Solutions CMS", "Services CMS" sidebar links are still dead.
- **Frontend conversion**: Blade partials still render hardcoded markup; Phase I will rewire `partials/sections/*` to read from `page_sections`/`section_fields`/`section_items`/`item_fields`.
- **Media Library (Phase C)**: `media` table/model exist (schema only); no upload UI, storage handling, or media picker yet.
- **Menu management (deferred CRUD)**: `menus`/`menu_items` schema and seed data exist; no admin CRUD yet.
- **Theme settings UI**: `settings` table seeded with 11 `theme`-group rows; no admin form to edit them yet.
- **Contact message storage**: the public contact/appointment forms have no backend route/controller; the admin sidebar references "Contact Messages" but no `messages` table or model exists.
- **Dashboard real metrics**: replace hardcoded counts (12 pages / 8 services / 6 solutions / 24 messages) with real queries once the above models exist.
- **API routes**: `routes/api.php` does not exist; no API layer is configured.

## Known bugs/issues

- Forgot-password submission always returns a generic success message regardless of whether the email exists.
- Admin sidebar contains dead links ("Home CMS", "About CMS", "Solutions CMS", "Services CMS", "Contact Messages") with no backing routes.

## Technical debt

- No services/jobs/events/listeners/policies — any future business logic will need this scaffolding built up from nothing.
- Test suite only contains the default Laravel example tests (HTTP 200 check on `/`); no coverage of admin auth, forms, or any future CMS logic.
- Public contact/appointment forms render in Blade but have no server-side handling — form submissions currently go nowhere.

## Recent discoveries

- Confirmed via full codebase read that `CLAUDE.md`, `project_progress.md`, and `notes.md` did not previously exist on disk — this audit is the first time these docs are being generated from the actual implementation rather than assumptions.
- `database/database.sqlite` exists locally (~86KB) and is the active dev database; it contains only the default Laravel tables plus the seeded test user.
- Confirmed no git repository is initialized in this working directory.

## Recommended next priorities

1. Phase C — Media Library: build upload handling, storage, and a media picker UI on top of the `media` table created in Phase B.
2. Build admin CRUD for `pages`/`page_sections`/`section_fields`/`section_items`/`item_fields` (Phase F), using `App\Cms\TemplateRegistry` to drive forms.
3. Decide on and create the CMS data model (migrations + models) for `contact_messages` before building out that admin sidebar section (Phase H).
4. Wire the public contact/appointment forms to a real controller that persists submissions (depends on `contact_messages` table, Phase H).
5. Replace hardcoded dashboard metrics with real counts once the above models exist.
6. Expand test coverage to cover admin auth flows (now in place) and any new CMS controllers as they're built.

## Change Log

### 2026-06-12 — Phase A: Admin Authentication & Security

- **What changed**: Implemented real Laravel-native authentication for the admin panel. `Admin\AuthController::loginSubmit` now uses `Auth::attempt()` with session regeneration; added `logout()` with session invalidation + token regeneration. Protected `/admin/dashboard` with the `auth` middleware and moved login/forgot-password routes under `guest` middleware. Added `admin.logout` route (POST) and login throttling (`throttle:5,1`). Wired the navbar "Logout" link to a real POST form. Configured `redirectGuestsTo`/`redirectUsersTo` in `bootstrap/app.php` to point at `admin.login`/`admin.dashboard` respectively (since the app has no default `login`-named route).
- **Files modified**: `routes/web.php`, `app/Http/Controllers/Admin/AuthController.php`, `bootstrap/app.php`, `resources/views/admin/partials/navbar.blade.php`.
- **Why**: Phase A of the approved CMS roadmap — the admin area was previously fully open and login accepted any credentials.
- **Verified**: Manual end-to-end test via `php artisan serve` — unauthenticated `/admin/dashboard` redirects to `/admin/login` (302); valid login (`test@example.com`/`password`) redirects to dashboard (200); authenticated visit to `/admin/login` redirects to dashboard; logout invalidates session and dashboard becomes inaccessible again; invalid credentials redirect back to login with validation error.

### 2026-06-12 — Phase A follow-up: scope global auth redirects to /admin

- **What changed**: `redirectGuestsTo`/`redirectUsersTo` in `bootstrap/app.php` are global Laravel settings affecting any `auth`/`guest`-protected route, not just admin ones. Updated both closures to check `$request->is('admin/*')` — admin paths still redirect to `admin.login`/`admin.dashboard`, any other `auth`/`guest`-protected route (none exist yet) falls back to `home`. Prevents a future public-facing auth flow from being redirected into the admin panel.
- **Files modified**: `bootstrap/app.php`.
- **Why**: Reviewer flagged that the original global redirects could unintentionally affect future frontend authentication.
- **Verified**: Re-ran the full Phase A manual test suite — all admin login/logout/redirect behaviors unchanged.

### 2026-06-12 — Phase B: CMS Core Database Schema

- **What changed**: Implemented the field-based CMS schema approved after design review. Created 10 new tables: `media`, `pages`, `page_sections`, `section_fields`, `section_items`, `item_fields`, `menus`, `menu_items`, `settings`, `cache_versions`. `page_sections`/`section_items` are purely structural (page/section/order/active); all content lives in `section_fields`/`item_fields` as `field_key` → `value`/`media_id` rows, with the field set per `section_key`/`item_type` declared in `config/cms/templates.php` (the Template Registry, exposed via `App\Cms\TemplateRegistry`). Added 10 Eloquent models with relationships. Registered the registry config via `AppServiceProvider::register()` (`mergeConfigFrom`). Seeded: 11 `theme`-group `settings` rows (colors + logo/favicon); the 5 existing public pages (`home`, `about`, `services`, `solutions`, `contact`) as `is_system=true` rows with their `page_sections` populated per their template's `allowed_sections` (no field content yet — Blade views unchanged); `header`/`footer` `menus` each with 5 `menu_items` linking to the 5 pages. `Page` model guards (`static::deleting`/`static::updating`) prevent deleting `is_system` pages and prevent changing `slug`/`template` on `is_system` pages or after creation on any page.
- **Files created**: 10 migrations (`database/migrations/2026_06_12_1000{00..09}_*.php`), 10 models (`app/Models/{Media,Page,PageSection,SectionField,SectionItem,ItemField,Menu,MenuItem,Setting,CacheVersion}.php`), `config/cms/templates.php`, `app/Cms/TemplateRegistry.php`, `database/seeders/{SettingsSeeder,PagesSeeder,MenusSeeder}.php`.
- **Files modified**: `app/Providers/AppServiceProvider.php` (merge `config/cms/templates.php`), `database/seeders/DatabaseSeeder.php` (call new seeders).
- **Database changes**: 10 new tables (see above); existing tables untouched.
- **Routes**: none added/changed.
- **Packages**: none added.
- **Why**: Phase B of the approved CMS roadmap — establishes a schema that supports new section/item types via Template Registry config entries alone, with no future migrations.
- **Verified**: `php artisan migrate` ran clean (all 10 new tables created); seeders produced 11 settings rows, 5 pages with correct templates and section counts (home=8, about=3, services=4, solutions=5, contact=3), 2 menus with 5 items each; tinker test confirmed `is_system` page delete and slug/template change both throw `RuntimeException`; `php artisan serve` smoke test confirmed `/`, `/about`, `/admin/login` all return 200 — no regression to existing routes/views.
- **Rollback**: `php artisan migrate:rollback --step=10` (drops the 10 new tables in reverse order); delete the 10 migration files, 10 model files, `config/cms/templates.php`, `app/Cms/TemplateRegistry.php`, 3 seeder files; revert `AppServiceProvider.php` and `DatabaseSeeder.php`.
