#!/usr/bin/env python3
from pathlib import Path
import re
import shutil

ROOT = Path(__file__).resolve().parents[1]
OUT = ROOT / 'upgrade' / 'laravel12'

CONTROLLERS_SRC = ROOT / 'app' / 'controllers'
MODELS_SRC = ROOT / 'app' / 'models'
VIEWS_SRC = ROOT / 'app' / 'views'

CONTROLLERS_OUT = OUT / 'app' / 'Http' / 'Controllers'
MODELS_OUT = OUT / 'app' / 'Models'
VIEWS_OUT = OUT / 'resources' / 'views'
ROUTES_OUT = OUT / 'routes' / 'web.php'

for p in [CONTROLLERS_OUT, MODELS_OUT, ROUTES_OUT.parent]:
    p.mkdir(parents=True, exist_ok=True)

controller_stub = """<?php

namespace App\\Http\\Controllers;

abstract class Controller
{
    // Laravel 12 base controller placeholder for migrated code.
}
"""
(CONTROLLERS_OUT / 'Controller.php').write_text(controller_stub)


def normalize_controller(content: str) -> str:
    content = re.sub(r"^<\?php\s*", "", content)
    content = re.sub(r"class\s+(\w+)\s+extends\s+\\?BaseController", r"class \1 extends Controller", content)

    # Modern request helper replacements
    content = content.replace('Input::all()', 'request()->all()')
    content = re.sub(r"Input::get\(([^\)]+)\)", r"request()->input(\1)", content)
    content = re.sub(r"Input::has\(([^\)]+)\)", r"request()->has(\1)", content)

    # Laravel 4 helper -> Arr facade
    content = content.replace('array_forget(', 'Arr::forget(')

    # Query builder old list helper
    content = content.replace('->lists(', '->pluck(')

    # Keep legacy require if present; it now points to same folder after copy.
    header = """<?php

namespace App\\Http\\Controllers;

use App\\Models\\Admin;
use App\\Models\\Site;
use App\\Models\\StaffR;
use App\\Models\\TimeSheet;
use App\\Models\\User;
use Illuminate\\Support\\Arr;
use Illuminate\\Support\\Facades\\Auth;
use Illuminate\\Support\\Facades\\DB;
use Illuminate\\Support\\Facades\\Redirect;
use Illuminate\\Support\\Facades\\Validator;
use Illuminate\\Support\\Facades\\View;

"""

    return header + content.lstrip()


def modernize_model(name: str, content: str) -> str:
    content = re.sub(r"^<\?php\s*", "", content)

    # Remove Laravel 4 auth interfaces/traits from all models
    content = re.sub(r"use Illuminate\\Auth\\UserTrait;\n", "", content)
    content = re.sub(r"use Illuminate\\Auth\\UserInterface;\n", "", content)
    content = re.sub(r"use Illuminate\\Auth\\Reminders\\RemindableTrait;\n", "", content)
    content = re.sub(r"use Illuminate\\Auth\\Reminders\\RemindableInterface;\n", "", content)
    content = re.sub(r"class\s+(\w+)\s+extends\s+Eloquent\s+implements\s+UserInterface,\s*RemindableInterface", r"class \1 extends Model", content)
    content = re.sub(r"class\s+(\w+)\s+extends\s+Model\s+implements\s+UserInterface,\s*RemindableInterface", r"class \1 extends Model", content)
    content = re.sub(r"\s*use\s+UserTrait,\s*RemindableTrait;\n", "\n", content)

    if name in {'User.php', 'Admin.php'}:
        content = content.replace('extends Model', 'extends Authenticatable')
        header = """<?php

namespace App\\Models;

use Illuminate\\Foundation\\Auth\\User as Authenticatable;

"""
        return header + content.lstrip()

    content = content.replace('extends Eloquent', 'extends Model')
    header = """<?php

namespace App\\Models;

use Illuminate\\Database\\Eloquent\\Model;

"""
    return header + content.lstrip()


# Port controllers
for src in sorted(CONTROLLERS_SRC.glob('*.php')):
    if src.name in {'.gitkeep', 'BaseController.php'}:
        continue

    if src.name == 'signature-to-image.php':
        shutil.copy2(src, CONTROLLERS_OUT / src.name)
        continue

    text = src.read_text(errors='ignore')
    (CONTROLLERS_OUT / src.name).write_text(normalize_controller(text))

# Port models
for src in sorted(MODELS_SRC.glob('*.php')):
    if src.name == '.gitkeep':
        continue
    text = src.read_text(errors='ignore')
    (MODELS_OUT / src.name).write_text(modernize_model(src.name, text))

# Copy views as-is
if VIEWS_OUT.exists():
    shutil.rmtree(VIEWS_OUT)
shutil.copytree(VIEWS_SRC, VIEWS_OUT)

# Route conversion
routes = """<?php

use App\\Http\\Controllers\\AdminController;
use App\\Http\\Controllers\\CompanyController;
use App\\Http\\Controllers\\LoginController;
use App\\Http\\Controllers\\ReportController;
use App\\Http\\Controllers\\SiteController;
use App\\Http\\Controllers\\StaffController;
use App\\Http\\Controllers\\TimesheetController;
use App\\Http\\Controllers\\UserController;
use Illuminate\\Support\\Facades\\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/', fn () => redirect('/login'));
    Route::resource('login', LoginController::class);
});

Route::middleware('auth')->group(function (): void {
    Route::resource('admin', AdminController::class);
    Route::resource('staff', StaffController::class);
    Route::resource('site', SiteController::class);
    Route::resource('user', UserController::class);
    Route::resource('timesheet', TimesheetController::class);
    Route::resource('company', CompanyController::class);
    Route::resource('report', ReportController::class);

    Route::get('changelocation', [UserController::class, 'changelocation'])->name('changelocation');
    Route::get('show_login/{site_id}/{id}', [UserController::class, 'show_login'])->name('show_login');
    Route::post('loginupdate', [UserController::class, 'loginupdate'])->name('loginupdate');
    Route::get('show_logout/{site_id}/{id}', [UserController::class, 'show_logout'])->name('show_logout');
    Route::post('logoutupdate', [UserController::class, 'logoutupdate'])->name('logoutupdate');
    Route::get('showlogoff/{userid}/{site_id}', [UserController::class, 'showlogoff'])->name('showlogoff');
    Route::get('force_logout/{id}/{site_id}', [UserController::class, 'force_logout'])->name('force_logout');
    Route::post('force_logout/{id}', [UserController::class, 'post_forcelogout'])->name('post_forcelogout');
    Route::get('post_edit/{site_id}/{userid}', [UserController::class, 'post_edit'])->name('post_edit');
    Route::post('post_site', [UserController::class, 'post_site'])->name('post_site');
    Route::post('assign_user', [SiteController::class, 'assign_user'])->name('assign_users');
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('timesheet_store', [TimesheetController::class, 'storeentry'])->name('storeentry');
    Route::post('timesheet_store_ajax', [TimesheetController::class, 'ajax_storeentry'])->name('ajax_storeentry');
});
"""
ROUTES_OUT.write_text(routes)

readme = """# Laravel 12 Port Workspace

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
"""
(OUT / 'README.md').write_text(readme)

print(f"Port created at: {OUT}")
