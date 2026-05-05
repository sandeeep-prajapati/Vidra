# Create Pro Bundle Skill

Complete guide for creating professional bundles with permissions and menu injection using the `bundle:create-pro` command.

## Quick Start

```bash
php artisan bundle:create-pro StudentManagement
```

This creates a complete premium bundle with:
- ✅ Service provider with auto-permissions
- ✅ MenuProvider for menu injection
- ✅ CRUD controller with permission checks
- ✅ Routes with permission middleware
- ✅ Sample views (index, edit)
- ✅ Database migration
- ✅ Ready-to-upload ZIP structure

## Command Options

### Basic Bundle
```bash
php artisan bundle:create-pro MyBundle
```

### With Custom Author & Description
```bash
php artisan bundle:create-pro StudentManagement \
  --author="Your Company Name" \
  --description="Premium student management system"
```

### With All Options
```bash
php artisan bundle:create-pro StudentManagement \
  --author="Your Company" \
  --description="Comprehensive student management" \
  --version="2.0.0" \
  --with-permissions \
  --with-menu \
  --with-models
```

## Available Options

| Option | Default | Description |
|--------|---------|-------------|
| `--author` | "Your Company" | Bundle author name |
| `--description` | "A premium bundle" | Bundle description |
| `--version` | "1.0.0" | Initial version number |
| `--with-permissions` | enabled | Create permissions in service provider |
| `--with-menu` | enabled | Create MenuProvider |
| `--with-models` | disabled | Create sample model class |

## What Gets Created

### Directory Structure
```
Pro/StudentManagement/
├── manifest.json
├── README.md
└── src/
    ├── Providers/
    │   ├── StudentManagementServiceProvider.php  (with permissions)
    │   └── MenuProvider.php                     (menu injection)
    ├── Controllers/
    │   └── StudentManagementController.php      (with auth checks)
    ├── Models/
    │   └── StudentManagement.php                (if --with-models)
    ├── Routes/
    │   └── web.php                             (with permission middleware)
    ├── Views/
    │   ├── index.blade.php                     (CRUD list view)
    │   └── edit.blade.php                      (Edit form view)
    ├── Database/
    │   └── migrations/
    │       └── 2026_05_05_XXXXXX_create_student_managements_table.php
    └── Assets/
```

### manifest.json
```json
{
    "name": "StudentManagement",
    "version": "1.0.0",
    "description": "Comprehensive student management",
    "author": "Your Company",
    "license": "MIT",
    "package_path": "Pro/StudentManagement",
    "provider_class": "App\\Packages\\Pro\\StudentManagement\\Providers\\StudentManagementServiceProvider"
}
```

### Service Provider (Auto-Permissions)
```php
class StudentManagementServiceProvider extends ServiceProvider
{
    private function registerPermissions(): void
    {
        // Creates these permissions:
        // - view_student_management
        // - create_student_management_item
        // - edit_student_management_item
        // - delete_student_management_item
        
        // Assigns to roles:
        // Admin: all permissions
        // Teacher: view, edit
    }
}
```

### MenuProvider (Auto-Menu)
```php
class MenuProvider
{
    public static function getMenuItems(): array
    {
        // Returns menu item if user can access
        // Shows admin submenu (Settings, Export) for admins only
    }
}
```

### Routes (Protected)
```php
Route::get('/', [StudentManagementController::class, 'index'])
    ->middleware('permission:view_student_management')
    ->name('student-management.index');

Route::post('/', [StudentManagementController::class, 'store'])
    ->middleware('permission:create_student_management_item')
    ->name('student-management.store');
// ... and more
```

### Controller (Authorization Checks)
```php
public function store(Request $request): RedirectResponse
{
    if (!auth()->user()->can('create_student_management_item')) {
        abort(403);
    }
    // Your logic here
}
```

## Step-by-Step Workflow

### Step 1: Create Bundle
```bash
php artisan bundle:create-pro StudentManagement \
  --author="My Company" \
  --description="Premium student management system" \
  --with-models
```

Output:
```
Creating premium bundle: StudentManagement...

✓ Directory structure created
✓ manifest.json created
✓ Service provider created
✓ Menu provider created
✓ Routes created
✓ Controller created
✓ Model created
✓ Views created
✓ Migration created
✓ README created

✨ Premium bundle 'StudentManagement' created successfully!

📋 Next Steps:
1. Edit your files
   - Controllers: app/Packages/Pro/StudentManagement/src/Controllers/
   - Views: app/Packages/Pro/StudentManagement/src/Views/
   - Models: app/Packages/Pro/StudentManagement/src/Models/

2. Create ZIP bundle
   cd app/Packages/Pro/StudentManagement
   zip -r ../StudentManagement.zip .

3. Upload via UI
   Go to http://localhost:8000/bundle-installer
   Upload the ZIP file

4. System auto-registers
   - PSR-4 namespace in composer.json
   - Service provider in bootstrap/providers.php
   - Permissions in database
   - Menu items in navigation
```

### Step 2: Edit Bundle Files
Edit the generated files to add your logic:

**Controller:**
```bash
nano app/Packages/Pro/StudentManagement/src/Controllers/StudentManagementController.php
```

**Views:**
```bash
nano app/Packages/Pro/StudentManagement/src/Views/index.blade.php
nano app/Packages/Pro/StudentManagement/src/Views/edit.blade.php
```

**Model:**
```bash
nano app/Packages/Pro/StudentManagement/src/Models/StudentManagement.php
```

**Routes:**
```bash
nano app/Packages/Pro/StudentManagement/src/Routes/web.php
```

### Step 3: Customize Permissions
Edit `src/Providers/StudentManagementServiceProvider.php`:

```php
private function registerPermissions(): void
{
    $permissions = [
        'view_students',
        'create_student',
        'edit_student',
        'delete_student',
        'export_students',     // Add custom permission
        'view_analytics',      // Add custom permission
    ];

    foreach ($permissions as $permission) {
        if (!Permission::where('name', $permission)->exists()) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
    }

    // Customize role assignments
    $admin = Role::firstOrCreate(['name' => 'admin']);
    $teacher = Role::firstOrCreate(['name' => 'teacher']);
    $student = Role::firstOrCreate(['name' => 'student']);

    $admin->syncPermissions($permissions);
    $teacher->syncPermissions(['view_students', 'edit_student']);
    $student->syncPermissions(['view_students']);
}
```

### Step 4: Customize Menu Items
Edit `src/Providers/MenuProvider.php`:

```php
public static function getMenuItems(): array
{
    if (!auth()->check()) return [];

    $items = [];

    if (auth()->user()->can('view_students')) {
        $items[] = [
            'label' => 'Students',
            'route' => 'student-management.index',
            'icon' => '<svg>...</svg>',
            'active' => request()->routeIs('student-management.*'),
        ];
    }

    if (auth()->user()->can('view_analytics')) {
        $items[] = [
            'label' => 'Analytics',
            'route' => 'student-management.analytics',
            'icon' => '<svg>...</svg>',
            'active' => request()->routeIs('student-management.analytics'),
        ];
    }

    if (auth()->user()->hasRole('admin')) {
        $items[] = ['type' => 'divider'];
        $items[] = [
            'label' => 'Admin Tools',
            'submenu' => [
                ['label' => 'Export', 'route' => 'student-management.export'],
                ['label' => 'Import', 'route' => 'student-management.import'],
            ],
        ];
    }

    return $items;
}
```

### Step 5: Create ZIP
```bash
cd app/Packages/Pro/StudentManagement
zip -r ../StudentManagement.zip .
cd ..
```

### Step 6: Upload via UI
1. Go to `http://localhost:8000/bundle-installer`
2. Upload `StudentManagement.zip`
3. System auto-registers everything!

## Testing

### Check Routes
```bash
php artisan route:list | grep student-management
```

### Check Permissions
```bash
php artisan tinker
>>> Spatie\Permission\Models\Permission::where('name', 'like', '%student%')->get()
```

### Check Menu Items
```blade
@php
    use App\Helpers\BundleMenuHelper;
    dd(BundleMenuHelper::discoverMenuItems());
@endphp
```

### Test Permissions
```bash
php artisan tinker
>>> $user = User::first();
>>> $user->assignRole('teacher');
>>> $user->hasPermissionTo('view_students')
=> true
>>> $user->hasPermissionTo('delete_student')
=> false
```

## Examples

### Student Management Bundle
```bash
php artisan bundle:create-pro StudentManagement \
  --author="School Admin" \
  --description="Complete student management system" \
  --with-models
```

Creates automatic permissions:
- `view_student_management`
- `create_student_management_item`
- `edit_student_management_item`
- `delete_student_management_item`

### Fee Management Bundle
```bash
php artisan bundle:create-pro FeeManagement \
  --author="Accounting Team" \
  --description="Fee collection and tracking" \
  --version="2.0.0" \
  --with-models
```

### Report Generator Bundle
```bash
php artisan bundle:create-pro ReportGenerator \
  --description="Advanced reporting system" \
  --with-models
```

## Customization Guide

After creating, you can customize:

1. **Permissions** - Edit `registerPermissions()` in service provider
2. **Menu Items** - Edit `getMenuItems()` in MenuProvider
3. **Routes** - Add/modify routes in `src/Routes/web.php`
4. **Controller Logic** - Update methods in controller
5. **Views** - Customize Blade templates
6. **Database Schema** - Modify migration file
7. **Models** - Add relationships and methods

## Troubleshooting

**Command not found?**
```bash
php artisan list | grep bundle
```

If not listed, clear cache:
```bash
php artisan cache:clear
php artisan optimize:clear
```

**Bundle not created?**
- Check directory permissions
- Ensure `app/Packages/` exists
- Check bundle name is valid (PascalCase)
- Check disk space

**Permissions not showing up?**
- Run migrations: `php artisan migrate`
- Clear cache: `php artisan cache:clear`
- Check database connection

**Menu not appearing?**
- Check MenuProvider exists
- Verify `BundleMenuHelper::discoverMenuItems()` in layout
- Check user has proper role/permission
- Clear view cache: `php artisan view:clear`

## Complete Bundle Checklist

After creating with `bundle:create-pro`:

- [ ] Edit controller methods
- [ ] Customize views (index.blade.php, edit.blade.php)
- [ ] Add model relationships (if using --with-models)
- [ ] Review permissions and customize if needed
- [ ] Customize menu items and submenu
- [ ] Update routes if needed
- [ ] Modify migration schema
- [ ] Update README with bundle details
- [ ] Test routes: `php artisan route:list | grep bundle-name`
- [ ] Test permissions in tinker
- [ ] Test menu items display
- [ ] Create ZIP: `zip -r ../BundleName.zip .`
- [ ] Upload via `/bundle-installer`
- [ ] Test in browser with different roles

## Tips & Best Practices

✅ **DO:**
- Run command from project root
- Use PascalCase for bundle names
- Customize permissions for your use case
- Test with different roles before uploading
- Document your bundle in README
- Keep controller logic clean
- Use permission middleware on routes

❌ **DON'T:**
- Use spaces or special chars in bundle name
- Modify manifest.json package_path
- Mix permission checks (don't do both middleware AND controller check unless needed)
- Skip permission customization
- Upload without testing
- Forget to clear cache before testing

## Next: Upload & Deploy

Once bundle is created and customized:

```bash
# Create ZIP (inside bundle directory)
cd app/Packages/Pro/StudentManagement
zip -r ../StudentManagement.zip .

# Upload via UI
# http://localhost:8000/bundle-installer
# Select ZIP file and upload

# System automatically:
# ✅ Extracts files
# ✅ Registers PSR-4 namespace
# ✅ Registers service provider
# ✅ Creates permissions
# ✅ Discovers menu items
# ✅ Clears caches
```

## Destroying/Removing Bundles

To completely remove a bundle and clean up all registrations:

```bash
# With confirmation prompt
php artisan bundle:destroy-pro StudentManagement

# Without confirmation (force)
php artisan bundle:destroy-pro StudentManagement --force
```

**What Gets Cleaned Up:**
- ✅ Bundle directory removed completely
- ✅ Service provider removed from `bootstrap/providers.php`
- ✅ PSR-4 namespace removed from `composer.json`
- ✅ Instructions provided for final cleanup steps

**After Destruction (Recommended):**
```bash
php artisan cache:clear
composer dump-autoload
```

**Example Output:**
```
Removing premium bundle: StudentManagement...

✓ Bundle directory removed
✓ Service provider removed from bootstrap/providers.php
✓ PSR-4 namespace removed from composer.json

✨ Bundle 'StudentManagement' removed successfully!

📋 Next Steps:
1. Run: php artisan cache:clear
2. Run: composer dump-autoload
3. Delete any database records or migrations if needed
```

## Support

See related documentation:
- `PERMISSIONS_GUIDE.md` - Permission system details
- `MENU_INJECTION_GUIDE.md` - Menu injection system
- `COMPLETE_BUNDLE_GUIDE.md` - Full working example
- `SKILLS_SUMMARY.md` - Quick skills reference
