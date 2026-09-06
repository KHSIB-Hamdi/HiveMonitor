# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

HiveMonitor — a Laravel 9 beekeeping monitoring application. It combines a session-authenticated Blade dashboard (Argon preset) for beekeepers with a JWT-authenticated REST API that IoT devices/sensors post hive measurements to.

The repository root is this directory (the one holding `artisan`). The PFE deliverables that sit alongside it — `Rapport_pfe.pdf`, `Hivemonitor-final-video.mp4`, `hivemonitor.png` — are kept on disk but git-ignored; the README uses `docs/images/hivemonitor.png` instead.

`docs/AUDIT.md` is the authoritative catalogue of known defects, unused dependencies and open design questions. **Read it before concluding that something is a new bug** — most rough edges in this codebase are already documented there with file:line references.

## Commands

Dependencies are not installed in a fresh checkout (`vendor/` and `node_modules/` are absent):

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan jwt:secret    # required — config/jwt.php reads JWT_SECRET with no default
php artisan migrate
```

Day to day:

```bash
php artisan serve         # dev server
npm run watch             # laravel-mix asset rebuild on change
npm run prod              # production assets
php artisan migrate:fresh # reset schema

vendor/bin/phpunit                                    # all tests
vendor/bin/phpunit tests/Feature/ExampleTest.php      # one file
vendor/bin/phpunit --filter test_that_true_is_true    # one test
```

Tests run against in-memory SQLite (set in `phpunit.xml`). Only the two stock `ExampleTest` stubs exist — there is no real suite to rely on.

## Architecture

**Two authentication systems coexist and must not be confused:**

- `routes/web.php` — Blade pages under the `auth` middleware (session guard, the only guard in `config/auth.php`). Auth scaffolding comes from `laravel/ui` + the Argon preset; views in `resources/views/pages/*.blade.php`, layout in `resources/views/layouts/app.blade.php`.
- `routes/api.php` — `POST /api/login` and `/api/register` handled by `ApiController` via `JWTAuth::attempt`; protected sensor endpoints sit inside `Route::group(['middleware' => ['jwt.verify']])` → `App\Http\Middleware\JwtMiddleware` (aliased in `app/Http/Kernel.php`). `User` implements `JWTSubject` correctly. Sanctum is installed and its stock `/api/user` route exists but is otherwise unused.

Note that `/api/measurement` and `/api/site` sit **outside** the JWT group and are unauthenticated. This is deliberate-as-found, not an oversight to fix casually — see the Ambiguities table in `docs/AUDIT.md`.

**Two overlapping data models for sensor readings.** The legacy design has one table/model/controller per quantity (`Temperature`, `Humidity`, `Weight`, `Sound`, `Pressure`, `Exttemperature`) — these back the JWT-protected endpoints. The normalized design is a single `measurements` table (`Measurement` belongs to `Device`, `Sensor`, `MeasurementCategory`, `MeasurementUnit`). Prefer `Measurement` for new sensor work.

**Domain model:** `Apiary` → `Beehive` (`identifier`, `site_id`, `beehive_type_id`, `beehive_status_id`, levels/frames) → `Device` → `Sensor` → `Measurement`. `Task` and `Inspection` are beekeeper workflow records. `Site`, `Country`, `BeehiveType`, `BeehiveStatus` are lookups.

**Controller style is inconsistent — match the file you are editing.** API resource controllers use Eloquent plus `Validator`, and return through `respondWithSuccess()` on the base `Controller`. The dashboard page controllers (`ApiaryController`, `TaskController`, `InspectionController`) use raw `DB::table()`/`DB::select()`, with `display()` returning a view and `add()` redirecting via `back()->with('success'|'fail', ...)`.

## Gotchas

- **Roughly ten controllers are unreachable** (`SensorController`, `DeviceController`, `CountryController`, `SettingController`, …). Before extending one, check `routes/` — you may be editing code nothing calls.
- **Several models declare `$fillable` fields that have no column** (`Setting`, `Sound`, and the `beehive` field on four sensor models). Verify against `database/migrations/` before trusting a model.
- **`spatie/laravel-permission` and `consoletvs/charts` are installed but entirely unused.** Roles are static mock-up pages. Do not assume role checks exist.
- **The Vue toolchain is dead.** `webpack.mix.js` calls `.vue()` and one stub component exists, but no Blade renders a Vue component. The live UI is the pre-built Argon asset tree in `public/argon/` and `public/assets/`, not Mix output.
- Migrations must be able to run in filename order — `measurements` is timestamped after the tables it references.

## Conventions

`.editorconfig` and `.styleci.yml` (Laravel preset) govern formatting: 4-space PHP indent, LF, trimmed trailing whitespace. Secrets belong in `.env` only; `config/services.php` is the seam for third-party keys.
