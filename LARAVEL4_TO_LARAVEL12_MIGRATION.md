# Laravel 4 → Laravel 12 Migration Guide (Recommended Rebuild Strategy)

This repository is a Laravel 4 codebase. A direct in-place framework jump to Laravel 12 is not recommended.

Instead, use a **fresh Laravel 12 application** and migrate business logic incrementally.

---

## 1) Create a Fresh Laravel 12 Project

1. Install a Laravel 12 compatible PHP version.
2. Create a new Laravel 12 app via Composer.
3. Copy environment values from the old project into `.env`.
4. Configure database, cache, mail, queue, and session drivers.

Example bootstrap flow:

```bash
composer create-project laravel/laravel timesheet-v12
cd timesheet-v12
cp .env.example .env
php artisan key:generate
php artisan migrate
```

---

## 2) Rebuild Project Structure

Move code from Laravel 4 locations into Laravel 12 conventions:

- `app/controllers` → `app/Http/Controllers`
- `app/models` → `app/Models`
- `app/views` → `resources/views`
- `app/routes.php` → `routes/web.php` and `routes/api.php`

Also move service classes into modern folders (for example `app/Services`, `app/Repositories`) as needed.

---

## 3) Migrate Routes

Laravel 4 uses a single routes file. Laravel 12 separates web and API routes.

- Place browser/session routes in `routes/web.php`
- Place stateless JSON endpoints in `routes/api.php`

Modernize route syntax and controller references:

```php
use App\Http\Controllers\UserController;

Route::get('/users', [UserController::class, 'index']);
```

---

## 4) Update Controllers

Laravel 4 controllers commonly extend `BaseController`. In Laravel 12, controllers extend `App\Http\Controllers\Controller` and use namespaces.

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // ...
    }
}
```

Use constructor/method dependency injection instead of static or global patterns where possible.

---

## 5) Update Models (Eloquent)

Move models to `app/Models` and namespace them as `App\Models`.

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = ['name', 'email', 'password'];
}
```

Migration checklist:

- Verify relationships (`hasMany`, `belongsTo`, etc.)
- Replace deprecated query patterns
- Add guarded/fillable rules explicitly
- Validate date casting and attribute casting behavior

---

## 6) Move and Validate Blade Templates

Move old templates:

- `app/views/*` → `resources/views/*`

Keep Blade syntax but modernize assets:

- Prefer `@vite(...)` for frontend assets in Laravel 12
- Keep `asset()` for static files when appropriate

Validate shared layouts/sections and remove deprecated directives.

---

## 7) Replace Deprecated Laravel 4 Features

### Filters → Middleware

Laravel 4 route filters (`before`, `after`) should become middleware classes in `app/Http/Middleware` and route middleware assignments.

### Legacy helpers / APIs

Review and replace outdated helper behavior with modern equivalents and framework contracts.

### Session/Auth internals

Re-implement old auth/session glue using Laravel 12 auth/session middleware stack.

---

## 8) Authentication: Use Default Laravel Auth + Auth Facade

For Laravel 12, use the framework-standard authentication flow and access auth state with the `Auth` facade.

```php
use Illuminate\Support\Facades\Auth;

if (Auth::attempt(['email' => $email, 'password' => $password])) {
    $request->session()->regenerate();
    return redirect()->intended('/dashboard');
}

return back()->withErrors([
    'email' => 'The provided credentials are incorrect.',
]);
```

Useful patterns:

- Current user: `Auth::user()`
- Check status: `Auth::check()`
- Logout: `Auth::logout()` then invalidate and regenerate CSRF token

---

## 9) Recommended Migration Sequence

1. Migrate database schema/migrations.
2. Port models and core domain/business logic.
3. Port controllers and route endpoints.
4. Port Blade views and frontend assets.
5. Rebuild auth and middleware.
6. Add/update automated tests.
7. Run parallel verification against the old app until parity is reached.

---

## 10) Validation Checklist Before Cutover

- Feature parity confirmed for all critical flows
- Authentication/session behavior verified
- API responses match expected contracts
- Background jobs/queues/mail flows validated
- Test suite green in CI
- Monitoring and error logging enabled in production

This approach reduces risk and makes the Laravel 4 → Laravel 12 migration predictable and maintainable.

---

## 11) Can This Be Done Automatically or Manually?

Short answer: **both**.

- **Automatable work (I can do):**
  - Generate a Laravel 12 baseline project structure
  - Port straightforward routes/controllers/models where patterns are simple
  - Convert many filters to middleware skeletons
  - Move Blade files and update obvious path/helper usage
  - Create migration checklists, mapping tables, and parity test scaffolding

- **Manual work (you or team must validate):**
  - Business-rule correctness and domain edge cases
  - Security-sensitive authentication/authorization behavior
  - Payment/reporting/integration flows that require environment credentials
  - UI/UX parity decisions and acceptance testing
  - Production rollout planning (cutover windows, rollback procedures)

### Practical recommendation

Use an **assisted migration** model:

1. I handle repetitive and mechanical refactors.
2. Your team validates behavior and makes product decisions.
3. We run side-by-side verification until parity is confirmed.

This gives you speed from automation without risking correctness in critical workflows.

---
