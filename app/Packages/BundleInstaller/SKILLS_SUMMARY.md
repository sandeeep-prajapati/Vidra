# Bundle System Skills Summary

Complete reference for all bundle capabilities including permissions, menu injection, and authorization.

## What You Can Do

### 1. Create Self-Contained Bundles
Bundles can be completely self-contained without modifying the main application files.

```bash
php artisan bundle:create MyBundle --pro
# Creates full structure at app/Packages/Pro/MyBundle/
```

### 2. Add Role-Based Permissions
Bundles automatically register permissions and assign them to roles.

```php
// In service provider
private function registerPermissions(): void
{
    $permissions = ['view_bundle', 'edit_bundle', 'delete_bundle'];
    foreach ($permissions as $permission) {
        Permission::create(['name' => $permission, 'guard_name' => 'web']);
    }
    
    // Auto-assign to roles
    $admin = Role::firstOrCreate(['name' => 'admin']);
    $admin->syncPermissions($permissions);
}
```

### 3. Protect Routes with Permissions
Routes are protected using permission middleware automatically.

```php
Route::get('/', [Controller::class, 'index'])
    ->middleware('permission:view_bundle')
    ->name('bundle.index');

Route::post('/', [Controller::class, 'store'])
    ->middleware('permission:create_bundle')
    ->name('bundle.store');
```

### 4. Inject Menu Items Without App Modifications
Bundles register menu items through MenuProvider - no app file edits needed.

```php
// src/Providers/MenuProvider.php
class MenuProvider
{
    public static function getMenuItems(): array
    {
        return [
            [
                'label' => 'My Bundle',
                'route' => 'mybundle.index',
                'icon' => '<svg>...</svg>',
                'active' => request()->routeIs('mybundle.*'),
                'permission' => 'view_my_bundle',
                'badge' => 5,
            ],
        ];
    }
}
```

### 5. Automatic PSR-4 Registration
No manual composer.json edits - system auto-generates namespaces.

```
Upload bundle with:
package_path: "Pro/StudentManagement"
↓
Auto-creates: "App\Packages\Pro\StudentManagement\"
Auto-adds to: composer.json
Runs: composer dump-autoload
```

### 6. Support for Nested Bundles
Create bundles in any directory structure.

```
Pro/StudentManagement/         → App\Packages\Pro\StudentManagement\
Pro/Reports/Advanced/          → App\Packages\Pro\Reports\Advanced\
Core/ContactManagement/        → App\Packages\Core\ContactManagement\
```

## Key Skills

| Skill | What It Does |
|-------|-------------|
| **Permission Registration** | Auto-create permissions in service provider |
| **Permission Middleware** | Protect routes with `permission:name` |
| **Controller Authorization** | Check permissions in methods |
| **Menu Injection** | Register menu items via MenuProvider |
| **Conditional Menu** | Show/hide based on role or permission |
| **Badge Display** | Show counts on menu items |
| **Submenu Creation** | Create nested menu structures |
| **Custom Middleware** | Add custom permission checks |
| **Dynamic Routes** | Routes accept slashes in parameters |
| **Automatic Discovery** | Bundles auto-discover and register |

## Complete Workflow

### Step 1: Create Bundle Structure
```bash
php artisan bundle:create StudentManagement --pro
```

Creates:
```
Pro/StudentManagement/
├── manifest.json
├── README.md
└── src/
    ├── Providers/
    │   ├── StudentManagementServiceProvider.php
    │   └── MenuProvider.php
    ├── Controllers/
    ├── Routes/web.php
    ├── Views/
    ├── Models/
    └── Database/migrations/
```

### Step 2: Define Permissions
```php
// In StudentManagementServiceProvider.php
private function registerPermissions(): void
{
    $permissions = [
        'view_students',
        'create_student',
        'edit_student',
        'delete_student',
    ];

    foreach ($permissions as $permission) {
        Permission::create(['name' => $permission, 'guard_name' => 'web']);
    }

    $admin = Role::firstOrCreate(['name' => 'admin']);
    $teacher = Role::firstOrCreate(['name' => 'teacher']);
    
    $admin->syncPermissions($permissions);
    $teacher->syncPermissions(['view_students', 'edit_student']);
}
```

### Step 3: Create Routes with Permissions
```php
// In src/Routes/web.php
Route::middleware(['web', 'auth'])->group(function () {
    Route::prefix('students')->group(function () {
        Route::get('/', [StudentController::class, 'index'])
            ->middleware('permission:view_students')
            ->name('students.index');
            
        Route::post('/', [StudentController::class, 'store'])
            ->middleware('permission:create_student')
            ->name('students.store');
    });
});
```

### Step 4: Register Menu Items
```php
// In src/Providers/MenuProvider.php
class MenuProvider
{
    public static function getMenuItems(): array
    {
        if (!auth()->check()) return [];
        
        $items = [];

        if (auth()->user()->can('view_students')) {
            $items[] = [
                'label' => 'Students',
                'route' => 'students.index',
                'icon' => '<svg>...</svg>',
                'active' => request()->routeIs('students.*'),
            ];
        }

        if (auth()->user()->hasRole('admin')) {
            $items[] = ['type' => 'divider'];
            $items[] = [
                'label' => 'Admin Tools',
                'submenu' => [
                    ['label' => 'Export', 'route' => 'students.export'],
                    ['label' => 'Import', 'route' => 'students.import'],
                ],
            ];
        }

        return $items;
    }
}
```

### Step 5: Protect Controllers
```php
// In StudentController.php
public function store(Request $request)
{
    // Middleware checks, but check again for safety
    if (!auth()->user()->can('create_student')) {
        abort(403);
    }

    $student = Student::create($request->validated());
    return redirect()->route('students.show', $student);
}
```

### Step 6: Add to App Layout
```blade
<!-- In resources/views/layouts/app.blade.php -->
<aside class="sidebar">
    @php
        use App\Helpers\BundleMenuHelper;
        $menuItems = BundleMenuHelper::discoverMenuItems();
    @endphp

    <nav>
        @foreach($menuItems as $item)
            @if($item['type'] ?? null === 'divider')
                <hr>
            @elseif(isset($item['submenu']))
                <details>
                    <summary>{{ $item['label'] }}</summary>
                    <nav>
                        @foreach($item['submenu'] as $sub)
                            <a href="{{ route($sub['route']) }}">
                                {{ $sub['label'] }}
                            </a>
                        @endforeach
                    </nav>
                </details>
            @else
                <a href="{{ route($item['route']) }}"
                   class="{{ $item['active'] ?? false ? 'active' : '' }}">
                    {{ $item['label'] }}
                </a>
            @endif
        @endforeach
    </nav>
</aside>
```

### Step 7: Package & Upload
```bash
# Create ZIP with correct structure
cd app/Packages/Pro/StudentManagement
zip -r ../StudentManagement.zip .

# Upload via UI
# http://localhost:8000/bundle-installer
# Upload StudentManagement.zip
```

### Step 8: System Auto-Registers
Automatically:
- ✅ PSR-4 namespace added to composer.json
- ✅ Service provider registered
- ✅ Permissions created
- ✅ Roles updated
- ✅ Menu items discovered
- ✅ Routes loaded
- ✅ Views available
- ✅ Migrations ready

## Quick Reference Commands

```bash
# Create bundle
php artisan bundle:create MyBundle --pro

# Create with custom details
php artisan bundle:create MyBundle --pro \
  --author="Your Company" \
  --description="My bundle description" \
  --with-menu

# Check routes
php artisan route:list | grep students

# Check namespace
php artisan tinker
>>> class_exists('App\Packages\Pro\StudentManagement\Controllers\StudentController')
=> true

# Check permissions
>>> Spatie\Permission\Models\Permission::pluck('name')

# Check roles
>>> Spatie\Permission\Models\Role::pluck('name')

# Assign permission
>>> $user->givePermissionTo('view_students')

# Check permission
>>> $user->hasPermissionTo('view_students')
=> true
```

## Documentation Files

| File | Purpose | When to Read |
|------|---------|------------|
| `README.md` | System overview | Start here |
| `QUICK_START.md` | 60-second setup | Fast reference |
| `PERMISSIONS_GUIDE.md` | Detailed permissions | Permission questions |
| `MENU_INJECTION_GUIDE.md` | Menu system details | Menu questions |
| `COMPLETE_BUNDLE_GUIDE.md` | Full working example | Learn by example |
| `BUNDLE_NAMING_CONVENTION.md` | Naming rules | Naming questions |
| `ZIP_STRUCTURE_GUIDE.md` | ZIP file format | ZIP structure issues |
| `EXAMPLES.md` | Real-world examples | See patterns |
| `SKILLS_SUMMARY.md` | This file | Quick reference |

## Common Patterns

### Pattern 1: View & Edit Only
```php
$teacher->syncPermissions(['view_students', 'edit_student']);
```

### Pattern 2: Admin Full Access
```php
$admin->syncPermissions([
    'view_students',
    'create_student',
    'edit_student',
    'delete_student',
    'export_students',
]);
```

### Pattern 3: Student View Only
```php
$student->syncPermissions(['view_students']);
```

### Pattern 4: Conditional Menu
```php
if (auth()->user()->can('view_analytics')) {
    $items[] = ['label' => 'Analytics', ...];
}
```

## Testing Permissions

```bash
php artisan tinker

# Create test user
$user = User::create([
    'name' => 'Test Teacher',
    'email' => 'teacher@test.com',
    'password' => bcrypt('password'),
]);

# Assign role
$user->assignRole('teacher');

# Check permissions
$user->hasRole('teacher')                    # true
$user->hasPermissionTo('view_students')      # true
$user->hasPermissionTo('delete_student')     # false

# Give permission
$user->givePermissionTo('delete_student');
$user->hasPermissionTo('delete_student')     # true
```

## Troubleshooting

| Problem | Solution |
|---------|----------|
| Menu items not showing | Check permissions, clear cache |
| Permission denied | Assign role/permission to user |
| Routes returning 404 | Clear route cache: `php artisan route:clear` |
| ZIP upload fails | Check manifest.json at root (not nested) |
| Service provider not loading | Check bootstrap/providers.php registration |
| Namespace not found | Clear cache, run `composer dump-autoload` |

## Best Practices ✅

- Define permissions in service provider
- Use meaningful permission names: `verb_noun`
- Group related permissions
- Auto-assign to roles
- Protect both routes and controllers
- Check permissions in views
- Document permissions in README
- Use MenuProvider for menu items
- Keep bundles self-contained
- Test with different roles

## Anti-Patterns ❌

- Hardcoding user checks
- Forgetting middleware checks
- Unclear permission names
- Modifying app files from bundle
- Leaving users without role/permission
- Not clearing caches after changes
- Mixing authentication and authorization
- Creating ZIP with nested folder

## Integration Checklist

- [ ] Created bundle structure
- [ ] Defined permissions in service provider
- [ ] Protected routes with middleware
- [ ] Added permission checks in controllers
- [ ] Created MenuProvider
- [ ] Added menu items discovery to layout
- [ ] Created ZIP with correct structure
- [ ] Tested permissions with different roles
- [ ] Verified menu items show correctly
- [ ] Documented bundle features
- [ ] Uploaded and installed bundle
- [ ] Tested in browser with different users

---

**Version**: 1.0  
**Last Updated**: 2026-05-05  
**Status**: Production Ready ✅
