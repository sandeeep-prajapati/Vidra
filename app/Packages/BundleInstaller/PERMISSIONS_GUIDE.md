# Bundle Permissions & Authorization Guide

Add role-based permissions to your bundle routes without modifying the main application code.

## Quick Start

### 1. Define Permissions in Bundle

In your bundle's service provider:

```php
<?php

namespace App\Packages\Pro\MyBundle\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MyBundleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerPermissions();
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Views', 'my-bundle');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }

    private function registerPermissions(): void
    {
        $permissions = [
            'view_my_bundle',
            'create_my_bundle_item',
            'edit_my_bundle_item',
            'delete_my_bundle_item',
        ];

        foreach ($permissions as $permission) {
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create([
                    'name' => $permission,
                    'guard_name' => 'web',
                ]);
            }
        }
    }
}
```

### 2. Protect Routes with Permissions

In your bundle's `src/Routes/web.php`:

```php
<?php

use App\Packages\Pro\MyBundle\Controllers\MyBundleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::prefix('mybundle')->group(function () {
        // View permission
        Route::get('/', [MyBundleController::class, 'index'])
            ->middleware('permission:view_my_bundle')
            ->name('mybundle.index');

        // Create permission
        Route::post('/store', [MyBundleController::class, 'store'])
            ->middleware('permission:create_my_bundle_item')
            ->name('mybundle.store');

        // Edit permission
        Route::put('/{id}', [MyBundleController::class, 'update'])
            ->middleware('permission:edit_my_bundle_item')
            ->name('mybundle.update');

        // Delete permission
        Route::delete('/{id}', [MyBundleController::class, 'destroy'])
            ->middleware('permission:delete_my_bundle_item')
            ->name('mybundle.destroy');
    });
});
```

## Permission Levels

### 1. Super Admin Permissions
Highest level - auto-granted to admins:

```php
// In service provider
private function registerPermissions(): void
{
    $permissions = [
        'view_reports',
        'manage_reports',
        'delete_reports',
    ];

    foreach ($permissions as $permission) {
        Permission::create(['name' => $permission, 'guard_name' => 'web']);
    }

    // Auto-grant to admin role
    $adminRole = Role::firstOrCreate(['name' => 'admin']);
    $adminRole->syncPermissions($permissions);
}
```

### 2. Role-Based Permissions
Different permissions per role:

```php
private function registerPermissions(): void
{
    // Define all permissions
    $permissions = ['view_bundle', 'edit_bundle', 'delete_bundle'];
    foreach ($permissions as $permission) {
        Permission::create(['name' => $permission, 'guard_name' => 'web']);
    }

    // Create roles
    $admin = Role::firstOrCreate(['name' => 'admin']);
    $teacher = Role::firstOrCreate(['name' => 'teacher']);
    $student = Role::firstOrCreate(['name' => 'student']);

    // Assign permissions
    $admin->syncPermissions($permissions);
    $teacher->syncPermissions(['view_bundle', 'edit_bundle']);
    $student->syncPermissions(['view_bundle']);
}
```

### 3. Custom Checks in Controllers

```php
<?php

namespace App\Packages\Pro\MyBundle\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class MyBundleController extends BaseController
{
    public function index(Request $request)
    {
        // Check permission
        if (!$request->user()->can('view_my_bundle')) {
            abort(403, 'Unauthorized');
        }

        return view('my-bundle::index');
    }

    public function destroy(Request $request, $id)
    {
        // Check ownership + permission
        if (!$request->user()->can('delete_my_bundle_item')) {
            abort(403);
        }

        // Your deletion logic
    }
}
```

## Permission Middleware

### Built-in Middleware

```php
// Single permission
->middleware('permission:view_bundle')

// Multiple permissions (ANY)
->middleware('permission:view_bundle|edit_bundle')

// Multiple permissions (ALL)
->middleware('permission:view_bundle,edit_bundle')

// Role-based
->middleware('role:admin')

// Role or Permission
->middleware('role_or_permission:admin|view_bundle')
```

### Custom Middleware in Bundle

Create `src/Http/Middleware/CheckBundlePermission.php`:

```php
<?php

namespace App\Packages\Pro\MyBundle\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckBundlePermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!$request->user()->can($permission)) {
            abort(403, 'Unauthorized access to this bundle');
        }

        return $next($request);
    }
}
```

Register in service provider:

```php
public function boot(): void
{
    $this->app['router']->aliasMiddleware(
        'bundle.permission',
        \App\Packages\Pro\MyBundle\Http\Middleware\CheckBundlePermission::class
    );
}
```

Use in routes:

```php
Route::post('/store', [MyBundleController::class, 'store'])
    ->middleware('bundle.permission:create_my_bundle_item')
    ->name('mybundle.store');
```

## Database Seeding

Create `src/Database/Seeders/BundlePermissionSeeder.php`:

```php
<?php

namespace App\Packages\Pro\MyBundle\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class BundlePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'view_my_bundle',
            'create_my_bundle_item',
            'edit_my_bundle_item',
            'delete_my_bundle_item',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Assign to roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $teacherRole = Role::firstOrCreate(['name' => 'teacher']);

        $adminRole->syncPermissions($permissions);
        $teacherRole->syncPermissions(['view_my_bundle', 'edit_my_bundle_item']);
    }
}
```

Run seeder after installation:

```bash
php artisan db:seed --class="\App\Packages\Pro\MyBundle\Database\Seeders\BundlePermissionSeeder"
```

## Examples

### Example 1: Student Management Bundle

```php
// Permissions
'view_students'      // Teachers, Admin
'create_student'     // Admin only
'edit_student'       // Teachers, Admin
'delete_student'     // Admin only
'export_students'    // Teachers, Admin

// Routes
Route::get('/', [StudentController::class, 'index'])
    ->middleware('permission:view_students');

Route::post('/', [StudentController::class, 'store'])
    ->middleware('permission:create_student');

Route::put('/{id}', [StudentController::class, 'update'])
    ->middleware('permission:edit_student');

Route::delete('/{id}', [StudentController::class, 'destroy'])
    ->middleware('permission:delete_student');

Route::post('/export', [StudentController::class, 'export'])
    ->middleware('permission:export_students');
```

### Example 2: Reports Bundle

```php
// Permissions
'view_reports'       // Everyone
'generate_report'    // Staff
'delete_report'      // Admin
'share_report'       // Staff

// Routes
Route::get('/', [ReportController::class, 'index'])
    ->middleware('permission:view_reports');

Route::post('/generate', [ReportController::class, 'generate'])
    ->middleware(['auth', 'permission:generate_report']);

Route::delete('/{id}', [ReportController::class, 'destroy'])
    ->middleware('permission:delete_report');

Route::post('/{id}/share', [ReportController::class, 'share'])
    ->middleware('permission:share_report');
```

### Example 3: Premium Features Bundle

```php
// Permissions
'access_premium'     // Premium users only
'use_advanced_features'
'upload_bulk_data'
'customize_settings'

// Routes
Route::middleware(['auth', 'permission:access_premium'])->group(function () {
    Route::get('/dashboard', [PremiumController::class, 'dashboard']);
    
    Route::post('/bulk-upload', [PremiumController::class, 'bulkUpload'])
        ->middleware('permission:upload_bulk_data');
    
    Route::patch('/settings', [PremiumController::class, 'updateSettings'])
        ->middleware('permission:customize_settings');
});
```

## Best Practices

✅ **DO:**
- Define permissions in service provider
- Use meaningful permission names: `verb_noun` (view_reports, create_student)
- Group related permissions
- Document permissions in README
- Auto-create permissions on boot
- Use middleware on routes
- Check permissions in controllers too

❌ **DON'T:**
- Hardcode permission checks in views
- Create permissions in migrations
- Use unclear permission names
- Forget to assign roles
- Mix authentication and authorization
- Leave users without role/permission

## Checking Permissions in Views

```blade
@if(auth()->user()->can('edit_my_bundle_item'))
    <button>Edit</button>
@endif

@if(auth()->user()->hasRole('admin'))
    <button>Admin Actions</button>
@endif

@unless(auth()->user()->can('delete_my_bundle_item'))
    <p class="text-muted">Delete restricted</p>
@endunless
```

## CLI Commands

```bash
# List all permissions
php artisan permission:list

# Create permission
php artisan tinker
>>> Spatie\Permission\Models\Permission::create(['name' => 'new_permission']);

# Assign to role
>>> $admin = Role::where('name', 'admin')->first();
>>> $admin->givePermissionTo('new_permission');

# Check user permission
>>> $user->can('new_permission')
=> true
```

## Troubleshooting

**Permission not working?**
1. Clear cache: `php artisan cache:clear`
2. Check permission exists in database
3. Check role has permission assigned
4. Verify user has role assigned

**"User does not have permission" error?**
1. Assign role to user
2. Assign permission to role
3. Cache might be stale - clear it

**New bundle permissions not registering?**
1. Service provider must be registered
2. Check `registerPermissions()` is called in `boot()`
3. Run `php artisan cache:clear`

## Related Documentation

- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission/v6/introduction)
- [Laravel Authorization](https://laravel.com/docs/authorization)
- Bundle Routing Guide
- Menu Integration Guide (next)
