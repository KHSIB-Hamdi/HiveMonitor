# HiveMonitor — Repository Audit

Audit date: 2026-09-06. Covers the state of the repository after the cleanup pass that fixed run-blockers and removed dead code.

Everything below is **known and deliberately not fixed** — it is the maintenance backlog. Nothing here was verified at runtime: PHP and Composer were not available on the audit machine, so all findings come from static reading. Line numbers refer to the current files.

---

## 0. Fixed during the cleanup pass

Recorded here so the change of behavior is traceable. **None of these were executed against a PHP runtime** — verify them on a machine with PHP 8 before relying on them.

- **`routes/api.php` no longer fatals.** It imported `DimensionController`, `StateController`, `TypeController` and `HiveController`, none of which exist; the imports and the six `/dimension`, `/type`, `/state` routes that used them were removed. This previously took down the entire API route file, and `route:cache` with it.
- **`php artisan migrate` can now complete.** Two separate ordering defects were corrected:
  - `2022_06_14_181118_create_measurements_table.php` called `Schema::create('sensors', …)` — a byte-for-byte copy of the sensors migration. It created `sensors` twice (a hard failure) and left `measurements` non-existent. Rewritten to create `measurements` with the columns `App\Models\Measurement::$fillable` expects, and re-timestamped to `181320`.
  - `beehives` used `->constrained()` against `sites`, `beehive_types` and `beehive_statuses` while timestamped `2022_06_09`, i.e. **before any of them existed**. `beehives`, `devices` and `sensors` were re-timestamped to `181316`, `181317` and `181318` so every foreign key now resolves to a table created earlier. Only filenames changed; no schema definition was altered.
- **`respondWithSuccess()` now exists.** It was called by 12 controllers (including the live `MeasurementController::index` and `SiteController::index`) but defined nowhere. Added once to `app/Http/Controllers/Controller.php` rather than editing 12 files.
- **The Google Maps key was removed from source** and moved to `config('services.google_maps.key')` / `GOOGLE_MAPS_API_KEY`. See §5 — the exposed key still needs rotating.
- **The `addhive` page works.** It routed to `BeehiveController::display()`, which rendered `pages.beehives` — a view that does not exist — while `pages/addhive.blade.php` sat orphaned and its form POSTed to a GET-only route. The GET route now renders the existing form and a `POST addhive` route was added to the already-present `BeehiveController::add`, whose validated fields match the `beehives` schema. The now-unreachable `display()` method was removed.
- **Dead code deleted:** the `laravel-jwt/` directory (a complete stock Laravel skeleton containing no JWT code and referenced from nowhere), the four unrouted `Insert*` controller classes (`InsertTask`, `InsertInspection` and `InsertApiary` were line-for-line duplicates of the real controllers; `InsertController` wrote columns that do not exist), and four orphan Argon demo Blades (`maps`, `icons`, `tables`, `upgrade`) containing 14 calls to undefined route names.
- **`phpunit.xml`** now sets `DB_CONNECTION=sqlite` / `DB_DATABASE=:memory:` (both were commented out), so the suite no longer runs against the developer's real database.

---

## 1. Broken references — RESOLVED

All 19 defects previously listed here were fixed in the second pass. Verified statically (no PHP runtime available): a namespace-aware scan of `app/` reports **no unresolved `App\` class references**, and a pattern sweep finds zero remaining occurrences of `Measurment`, `Exttemperaturee`, `$this->user`, `$response()`, `$Sound`, `App\Http\Requests\Request`, `Role::class`, or the duplicated `use HasFactory`.

| # | Location | Fix |
|---|---|---|
| 1 | `ExttemperatureController::index()` | `Exttemperaturee::all()` → `Exttemperature::all()`; the misleading `$pressures` variable renamed. Live route restored. |
| 2 | `MeasurementController::store()` | `Beehive::where()` / `Beehive::create()` → `Measurement::…`. Live route restored. See the note below on the duplicate check. |
| 3 | `MeasurementController::update()/destroy()` | `Measurment` → `Measurement` in both signatures and all three docblocks. |
| 4 | `ApiaryController::destroy()` | Removed. It was unrouted, type-hinted an unimported class, and duplicated `BeehiveController::destroy()`. |
| 5 | `ApiController::store()` | `$response()->json(…)` → `response()->json(…)`, twice. |
| 6 | `ApiController::logout()` | Added the missing `use Symfony\Component\HttpFoundation\Response;`. |
| 7 | `ApiController::getAuthenticatedUser()` | Added leading `\` to all three `catch (Tymon\JWTAuth\…)` clauses so they can actually match. |
| 8 | `HomeController`, `UserController` | `use App\Http\Requests\Request;` → `use Illuminate\Http\Request;`. |
| 9 | `DeviceController::add()` | Added `use Illuminate\Support\Facades\DB;`, and corrected `DB::table('beehives')` → `DB::table('devices')` — it was inserting device columns into the hive table. |
| 10 | `MeasurementCategoryController::store()` | Validator result now assigned to `$data`, matching the reads below it. |
| 11 | `SiteController::store()` | Validation rule `'value' => 'required'` (a field that does not exist) replaced with `'country_id' => 'required'`, which the insert actually uses. |
| 12 | `SoundController::destroy()` | `$Sound->delete()` → `$sound->delete()`. |
| 13 | `SoundController::update()` | `'sound' => $request->weight` → `$request->sound`. |
| 14 | `HomeController`, `UserController` | `$request->$name` / `$request->$email` → `$request->name` / `$request->email`; `$user->email = $name` → `= $email`. |
| 15 | `Temperature`, `Humidity`, `Weight`, `Sound`, `Pressure`, `Exttemperature` controllers | `$this->user->…()->find($id)` → `Model::find($id)`. The `$user` property was never populated and the User relations it called (`temperatures()`, `humidities()`, …) do not exist. The unused `protected $user;` declarations were removed. **Note:** these `show()` methods are unrouted, and per-user scoping of readings was never implemented — the schema has no user foreign key on any sensor table. |
| 16 | `app/Models/Country.php` | Removed the duplicated `use HasFactory;` — this was an outright PHP fatal on any use of the model. |
| 17 | `app/Models/User.php` | Removed `roles()`. It targeted a nonexistent `App\Models\Role` and a nonexistent `user_roles` table. **Roles remain unimplemented** — see §4 and §8. |
| 18 | `app/Models/User.php` | Removed the unused `MustVerifyEmail` import. Email verification is still not implemented; only the dead import is gone. |
| 19 | `routes/web.php` | `Route::resource('user', …, ['except' => ['show']])` → `['only' => ['index']]`. The five URLs it exposed were backed by methods that do not exist on `UserController`. Views link only to `user.index`, which is unaffected. |

Two behavioral notes, both deliberate:

- **`MeasurementController::store()` keeps its "already exists" guard**, which rejects a second measurement from the same `device_id` with HTTP 409. That is almost certainly wrong for time-series data — it was copy-pasted from `BeehiveController`'s identifier-uniqueness check — but changing it is a design decision, not a defect fix. Recorded in §8.
- **`HomeController::viewUserSave()` and `UserController::viewUserSave()` remain byte-identical duplicates**, and both are unrouted. They were repaired rather than deleted so the intended behavior stays visible; consolidating them is a separate call.

## 2. Schema / model mismatches

`$fillable` fields with no matching column will silently fail to persist, or fail on insert where the column is `NOT NULL`.

| Model | Declared fillable | Actual columns | Mismatch |
|---|---|---|---|
| `Setting.php:12` | `key`, `value` | `id`, timestamps only | **Both columns missing.** `SettingController` cannot function at all. |
| `Sound.php:12` | `sound`, `symbol`, `beehive` | `audiosample`, `symbol` | `sound` and `beehive` do not exist; `audiosample` is `NOT NULL`, so `POST /api/sound` fails. |
| `Humidity.php:12` | includes `beehive` | no `beehive` column | Readings cannot be linked to a hive. |
| `Weight.php:12` | includes `beehive` | no `beehive` column | Same. |
| `Pressure.php:12` | includes `beehive` | no `beehive` column | Same. |
| `Exttemperature.php:12` | includes `beehive` | no `beehive` column | Same. |

Related design issues:

- **Attribute/relationship name collisions.** `Apiary::beehives()` (`app/Models/Apiary.php:14-18`) has the same name as the `apiaries.beehives` column, and `Beehive::apiary()` (`app/Models/Beehive.php:23-26`) collides with the `beehives.apiary` column. In both cases the relation shadows the column value.
- **Denormalized hive count.** `apiaries.beehives` is a string holding a hive count while `beehives.apiary` is a real foreign key — the same fact stored twice, able to disagree.
- **No seed data for lookup tables.** `beehives` has `NOT NULL` foreign keys to `sites`, `beehive_types` and `beehive_statuses`, none of which have seeders, so no hive can be created on a fresh database.
- **`HasFactory` on 21 models, but only `UserFactory` exists.** `Apiary::factory()` and every other `factory()` call would fail.
- **`CountryController::update():74-76`** silently drops `iso_code_2` and `iso_code_3`.

## 2b. Blade / front-end defects

| Location | Problem |
|---|---|
| `resources/views/pages/adduser.blade.php:94,99` | `$ajaxSetup({` and `$ajax({` — missing the `.`; should be `$.ajaxSetup` / `$.ajax`. JavaScript syntax error, so the form never submits. |
| `resources/views/pages/adduser.blade.php:96` | `$('meta[name="csrf-token"]')attr('content')` — missing `.` before `attr`. |
| `resources/views/pages/adduser.blade.php:86` | `e.preventDefault()` inside `function () {` — no `e` parameter declared. |
| `resources/views/pages/adduser.blade.php:101` | POSTs to `/users`; the resource route is `user` (singular), so the URL 404s. |
| `resources/views/pages/adddevice.blade.php:42` | `<form action="addhive">` — the device form posts to the beehive endpoint. No device POST route exists. |
| `resources/views/pages/addrole.blade.php:43` | `<form action="addhive">` — the role form also posts to the beehive endpoint. |
| `resources/views/pages/apiaries.blade.php:116-121` | `@foreach($beehives as $row)` prints `$row->name`, `$row->beehives`, `$row->status` — none of which are columns on the `beehives` table. Renders blank cells. |
| `resources/views/pages/apiaries.blade.php:168-173` | A third `@foreach($apiaries as $row)` block duplicating the one at `:61-66`. |
| `public/favicon.ico` | Zero-byte file. |

## 3. Unreachable code

Full controllers with no route pointing at them:

`BeehiveStatusController`, `BeehiveTypeController`, `CountryController`, `DeviceController`, `MeasurementCategoryController`, `MeasurementUnitController`, `SensorController`, `SettingController`.

Partially reachable — individual methods with no route: `ApiaryController::index/destroy`, `ApiController::store`, `BeehiveController::index/store/show/update/destroy`, `HomeController::viewUserSave/display` (the latter is an empty body at `:58-62`), `InspectionController::index/formview`, and the `show`/`edit`/`update`/`destroy` methods of most sensor controllers.

`Task`, `Inspection` and `Apiary` are effectively dead models — their controllers bypass Eloquent entirely and use raw `DB::` queries.

## 4. Unused dependencies

| Package | Status |
|---|---|
| `spatie/laravel-permission` 5.5.4 | Provider registered and `config/permission.php` published, but **no permission migration exists**, `User` does not use `HasRoles`, and there are zero calls to `hasRole()`, `assignRole()`, `hasPermissionTo()` or `middleware('role:…')`. The Role pages in the UI are static mock-ups. |
| ~~`consoletvs/charts` 7.3.0~~ | **REMOVED** from `composer.json`. Had zero references anywhere; the dashboard charts are hard-coded Chart.js markup from the Argon theme with static data. |
| ~~`laravel/ui` 3.4.6~~ | **REMOVED** from `composer.json`. Scaffolding-only; its output was superseded by the Argon preset and nothing referenced it at runtime. |
| `vue` / `vue-template-compiler` | `webpack.mix.js` calls `.vue()` and `resources/js/components/ExampleComponent.vue` exists, but **no Blade template renders any Vue component**. The entire Vue toolchain is dead; the UI is server-rendered Blade. |

>  **`composer.lock` is now stale.** Two packages were removed from `composer.json` but the lock file could not be regenerated — Composer is not installed on the machine where this pass ran. Run `composer update consoletvs/charts laravel/ui` (or a full `composer update`) once locally to bring the lock back in sync before committing to a shared branch.

`tymon/jwt-auth` is pinned to **`dev-develop`** with `"minimum-stability": "dev"` in `composer.json`. Pinning the authentication library to an unstable branch is a reproducibility and supply-chain risk. `composer.lock` pins the resolved commit, which mitigates it for existing checkouts only.

## 5. Security

| Severity | Finding |
|---|---|
| **Resolved (needs owner action)** | A live Google Maps API key was hard-coded in `resources/views/pages/apiarysites.blade.php` and the now-deleted `pages/maps.blade.php`. It has been moved to `config('services.google_maps.key')` / `GOOGLE_MAPS_API_KEY`. **The exposed key must still be rotated and referrer-restricted in the Google Cloud Console** — it existed in the distributed archive. |
| Medium | `database/seeders/UsersTableSeeder.php:19,21` seeds an admin account with a hard-coded address and a trivially guessable password, inherited from the Argon preset. Running `db:seed` in production creates a known-credential admin. |
| Medium | `POST addapiary`, `addtask` and `addinspection` (`routes/web.php:50-56`) are registered **outside** the `auth` group — any unauthenticated visitor can insert records. Left unchanged because moving them alters access-control behavior; see Ambiguities. |
| Medium | `GET`/`POST /api/measurement` and `/api/site` sit outside the `jwt.verify` group in `routes/api.php` and are unauthenticated. Left unchanged for the same reason. |
| Low | Page controllers (`ApiaryController`, `TaskController`, `InspectionController`, `BeehiveController::add`) use raw `DB::table()->insert()` with request input. Values are parameter-bound so this is not SQL injection, but it bypasses model casting and mass-assignment protection. |
| Informational | `database/factories/UserFactory.php:24` contains the stock Laravel bcrypt hash of the word "password". Test-only, standard Laravel. |

No AWS keys, private keys, connection strings, or `.env` files were found anywhere in the tree.

## 6. Repository hygiene

- **`npm run prod` fails on a clean install — confirmed by running it.** There is no `package-lock.json`, so npm resolves `laravel-mix@6.0.49`'s loose `webpack: ^5.60.0` range to the newest webpack 5, which no longer ships `webpack/lib/SizeFormatHelpers` — a module Mix's `BuildOutputPlugin` requires. The build dies with `Error: Cannot find module 'webpack/lib/SizeFormatHelpers'`. Installing `webpack@5.89.0` resolves it, confirming the version range is the cause. A second problem follows: because `webpack.mix.js` calls `.vue()`, Mix then auto-runs `npm install vue-loader --save-dev`, mutating `package.json` mid-build. Fixes, in order of preference: commit a `package-lock.json` generated against a working resolution; or pin `webpack` explicitly in `devDependencies`; or drop `.vue()` from `webpack.mix.js` since no Vue component is rendered. Low practical urgency — the UI is served from the pre-built asset trees, not Mix output — but the documented build command does not work as-is.
- **No `package-lock.json` is committed** while `composer.lock` is, so PHP dependencies are reproducible and JavaScript ones are not. This is the direct cause of the build failure above.
- **~29 MB / 4,443 files of committed front-end assets** in `public/argon/` (10.2 MB, 1,724 files) and `public/assets/` (19.2 MB, 2,715 files) — two near-duplicate copies of the same Argon preset, including vendored third-party libraries. Not consolidated: `public/argon/` is still referenced 7 times from live Blade templates, and `img/theme/bees.jpg` and `img/theme/hamdi.jpg` exist **only** under `argon/`. Consolidating requires re-pointing those references and verifying every asset path.
- `public/favicon.ico` is a **0-byte** file.
- `.editorconfig` contains a `[docker-compose.yml]` section for a file that does not exist.
- `.gitattributes` declares `export-ignore` for `/.github` and `CHANGELOG.md`, neither of which exists.
- `composer.json` requires `laravel/sail` in `require-dev`, but Sail's `docker-compose.yml` was never published.
- `.styleci.yml` configures StyleCI, a hosted service that requires the repository to be connected to it.
- **No CI, no Docker, no deployment configuration, no infrastructure-as-code** exists anywhere.

## 7. Test coverage

Effectively zero. `tests/Unit/ExampleTest.php:16` asserts `true`; `tests/Feature/ExampleTest.php:17-19` asserts `GET /` returns 200. Both are stock Laravel stubs. `tests/Feature/ExampleTest.php:5` imports `RefreshDatabase` without using the trait. No controller, model, route or migration is covered.

## 8. Ambiguities requiring an owner decision

These were left exactly as they behave today, because resolving them is a product decision rather than a defect fix.

| Ambiguity | Why it matters | Current behavior | Decision needed |
|---|---|---|---|
| **Which sensor-storage design is authoritative** | Two parallel schemas store the same kind of data; every future sensor feature must pick one. | Both live. Legacy per-quantity tables serve the JWT endpoints; `measurements` serves `/api/measurement`. | Commit to `measurements` and migrate/retire the legacy tables, or formally keep both with a documented boundary. |
| **Unauthenticated write endpoints** | `/api/measurement`, `/api/site` and three web POST routes accept writes with no authentication. | Unauthenticated. | Confirm whether devices genuinely post without a token (in which case another control is needed), or move them behind `jwt.verify` / `auth`. |
| **Whether roles are a real requirement** | Determines whether to wire up `spatie/laravel-permission` or drop it and the Role UI. | Static mock-up pages; dependency installed but unused. | Implement roles properly, or remove the package and the pages. |
| **`apiaries.beehives` (string count) vs the `beehives.apiary` FK** | Two sources of truth for hive membership that can disagree. | Both maintained independently. | Drop the denormalized column and derive the count from the relation, or keep it as a cache and populate it. |
| **`MeasurementController::store()` rejects repeat measurements** | The endpoint devices post readings to returns HTTP 409 if the `device_id` has ever been seen. Time-series data cannot accumulate. | Guard preserved as-found (it was copy-pasted from a uniqueness check on hive identifiers). | Almost certainly remove the guard — but confirm no device relies on the 409 before changing it. |
| **The `settings` table** | Has no columns; `Setting` and `SettingController` are unusable. | Non-functional. | Define what settings the app needs, or delete the table, model and controller. |
| **Whether the legacy `beehive` column on sensor tables was intended** | Four models declare a `beehive` fillable with no such column, so readings cannot be attributed to a hive. | Silently ignored. | Add the column and the FK, or remove it from `$fillable`. |
