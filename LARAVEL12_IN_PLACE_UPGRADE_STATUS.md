# Laravel 12 In-Place Upgrade Status

This repository now includes the Laravel 12 migration output directly in the **main project paths** (not in a separate `upgrade/` workspace):

- `app/Http/Controllers`
- `app/Models`
- `resources/views`
- `routes/web.php`
- `routes/api.php`

## What was migrated

- Controllers were moved to namespaced Laravel 12 location and updated from Laravel 4 helpers:
  - `Input::all/get/has` -> `request()->all/input/has`
  - `array_forget` -> `Arr::forget`
  - `lists()` -> `pluck()`
- Models were moved to `app/Models` and modernized:
  - `User` and `Admin` now extend `Authenticatable`
  - legacy Laravel 4 auth traits/interfaces removed
- Views were moved to `resources/views`.
- Routes were migrated from `app/routes.php` to `routes/web.php` with middleware groups and class-based controllers.

## Important notes

- Legacy Laravel 4 source files are still present to avoid immediate feature breakage during transition.
- Final production cutover to fully working Laravel 12 runtime still requires updating `composer.json` and installing Laravel 12 dependencies in an environment with package registry access.
- After dependency installation, run full regression testing before removing legacy Laravel 4 files.
