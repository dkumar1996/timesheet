# Laravel 12 Port Workspace

This folder contains an automated Laravel 4 -> Laravel 12 migration workspace.

## What was ported automatically
- Controllers: `app/controllers` -> `app/Http/Controllers`
- Models: `app/models` -> `app/Models`
- Views: `app/views` -> `resources/views`
- Routes: `app/routes.php` -> `routes/web.php`
- Compatibility pass:
  - `Input::get/all` -> `request()->input/all`
  - `array_forget` -> `Arr::forget`
  - `lists()` -> `pluck()`
  - User/Admin auth model base converted to Laravel `Authenticatable`

## What still needs completion for full production upgrade
- Create or copy a real Laravel 12 app skeleton (`composer.json`, bootstrap, config, providers, artisan) in this folder.
- Resolve deprecated framework patterns endpoint-by-endpoint (validation/request classes, auth guards, middleware contracts).
- Replace legacy view helpers/forms with maintained packages or native blade.
- Port and run automated tests against Laravel 12 runtime.
