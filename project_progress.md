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

CMS transformation (Phases A-J per approved roadmap). Phase A complete; Phase B (CMS Core Database Schema) is next, pending review.

## Pending modules

- **Real forgot-password flow**: token generation, reset email (mail is currently `log` driver), and a reset-password form/route.
- **CMS data model for public content**: pages, services, and solutions are static Blade content; no `pages`, `services`, or `solutions` tables/models/migrations exist. Building "Home CMS", "About CMS", "Services CMS", "Solutions CMS" (as implied by the admin sidebar) requires creating these from scratch.
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

1. Phase B — CMS Core Database Schema: create `pages`, `page_sections`, `section_items`, `cache_versions` migrations/models and the Template Registry skeleton.
2. Decide on and create the CMS data model (migrations + models) for `services`, `solutions`, and `contact_messages` before building out the corresponding admin sidebar sections.
3. Wire the public contact/appointment forms to a real controller that persists submissions (depends on `contact_messages` table, Phase H).
4. Replace hardcoded dashboard metrics with real counts once the above models exist.
5. Expand test coverage to cover admin auth flows (now in place) and any new CMS controllers as they're built.

## Change Log

### 2026-06-12 — Phase A: Admin Authentication & Security

- **What changed**: Implemented real Laravel-native authentication for the admin panel. `Admin\AuthController::loginSubmit` now uses `Auth::attempt()` with session regeneration; added `logout()` with session invalidation + token regeneration. Protected `/admin/dashboard` with the `auth` middleware and moved login/forgot-password routes under `guest` middleware. Added `admin.logout` route (POST) and login throttling (`throttle:5,1`). Wired the navbar "Logout" link to a real POST form. Configured `redirectGuestsTo`/`redirectUsersTo` in `bootstrap/app.php` to point at `admin.login`/`admin.dashboard` respectively (since the app has no default `login`-named route).
- **Files modified**: `routes/web.php`, `app/Http/Controllers/Admin/AuthController.php`, `bootstrap/app.php`, `resources/views/admin/partials/navbar.blade.php`.
- **Why**: Phase A of the approved CMS roadmap — the admin area was previously fully open and login accepted any credentials.
- **Verified**: Manual end-to-end test via `php artisan serve` — unauthenticated `/admin/dashboard` redirects to `/admin/login` (302); valid login (`test@example.com`/`password`) redirects to dashboard (200); authenticated visit to `/admin/login` redirects to dashboard; logout invalidates session and dashboard becomes inaccessible again; invalid credentials redirect back to login with validation error.
