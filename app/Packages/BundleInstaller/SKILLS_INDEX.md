# Bundle System Skills Index

Complete reference for all bundle creation and management skills.

## 🎯 Quick Access

| Skill | Command | Purpose | Time |
|-------|---------|---------|------|
| **Create Pro Bundle** | `php artisan bundle:create-pro MyBundle` | Create complete bundle with permissions & menu | 1 min |
| **Destroy Bundle** | `php artisan bundle:destroy-pro MyBundle --force` | Remove bundle and clean up all registrations | 30 sec |
| **Upload Bundle** | Go to `/bundle-installer` | Upload ZIP for auto-registration | 2 min |
| **Test Routes** | `php artisan route:list \| grep bundle` | Verify bundle routes loaded | 30 sec |
| **Test Permissions** | `php artisan tinker` | Check permission setup | 1 min |
| **Create ZIP** | `cd bundle && zip -r ../Bundle.zip .` | Package for upload | 30 sec |

## 📚 Documentation Files

### Getting Started
- **[README.md](README.md)** - System overview and features
- **[QUICK_START.md](QUICK_START.md)** - 60-second setup guide
- **[CREATE_PRO_BUNDLE_SKILL.md](CREATE_PRO_BUNDLE_SKILL.md)** - Complete bundle creation skill ⭐

### Deep Dives
- **[PERMISSIONS_GUIDE.md](PERMISSIONS_GUIDE.md)** - Role-based access control
- **[MENU_INJECTION_GUIDE.md](MENU_INJECTION_GUIDE.md)** - Self-contained menu items
- **[COMPLETE_BUNDLE_GUIDE.md](COMPLETE_BUNDLE_GUIDE.md)** - Full working example
- **[SKILLS_SUMMARY.md](SKILLS_SUMMARY.md)** - Quick skills reference

### Technical Reference
- **[BUNDLE_NAMING_CONVENTION.md](BUNDLE_NAMING_CONVENTION.md)** - Naming rules and patterns
- **[EXAMPLES.md](EXAMPLES.md)** - Real-world examples
- **[ZIP_STRUCTURE_GUIDE.md](ZIP_STRUCTURE_GUIDE.md)** - ZIP file format troubleshooting
- **[SKILLS_INDEX.md](SKILLS_INDEX.md)** - This file

## 🚀 Skill Workflows

### Workflow 1: Create Bundle with Single Command

**Time: 2 minutes**

```bash
# 1. Create complete bundle (1 min)
php artisan bundle:create-pro StudentManagement \
  --author="My Company" \
  --description="Student management system" \
  --with-models

# 2. Edit files (customization)
nano app/Packages/Pro/StudentManagement/src/Controllers/StudentManagementController.php
nano app/Packages/Pro/StudentManagement/src/Views/index.blade.php

# 3. Create ZIP (30 sec)
cd app/Packages/Pro/StudentManagement
zip -r ../StudentManagement.zip .
cd ..

# 4. Upload via UI (30 sec)
# Go to http://localhost:8000/bundle-installer
# Upload StudentManagement.zip
# System auto-registers everything!
```

**Result:**
- ✅ PSR-4 namespace registered
- ✅ Service provider loaded
- ✅ Permissions created
- ✅ Roles updated
- ✅ Menu items injected
- ✅ Routes available
- ✅ Views loaded

### Workflow 2: Create Bundle from Template

**Time: 5 minutes**

```bash
# 1. Copy DemoBundle template
cp -r app/Packages/Pro/DemoBundle app/Packages/Pro/MyBundle
cd app/Packages/Pro/MyBundle

# 2. Edit manifest.json
nano manifest.json
# Update: name, description, package_path, provider_class

# 3. Rename service provider
mv src/Providers/DemoBundleServiceProvider.php src/Providers/MyBundleServiceProvider.php
# Update class name inside

# 4. Edit route prefix
nano src/Routes/web.php
# Change prefix from 'demo' to 'mybundle'

# 5. Edit views
nano src/Views/index.blade.php
nano src/Views/features.blade.php

# 6. Create ZIP
zip -r ../MyBundle.zip .

# 7. Upload
# Go to /bundle-installer
```

### Workflow 3: Add Permissions to Existing Bundle

**Time: 5 minutes**

```bash
# 1. Edit service provider
nano app/Packages/Pro/MyBundle/src/Providers/MyBundleServiceProvider.php

# 2. Add permissions in registerPermissions():
$permissions = [
    'view_bundle',
    'create_bundle',
    'edit_bundle',
    'delete_bundle',
    'export_bundle',  // Custom permission
];

# 3. Assign to roles:
$admin->syncPermissions($permissions);
$teacher->syncPermissions(['view_bundle', 'edit_bundle']);

# 4. Protect routes
nano src/Routes/web.php
# Add: ->middleware('permission:view_bundle')

# 5. Update controller
nano src/Controllers/MyBundleController.php
# Add checks: if (!auth()->user()->can('view_bundle')) abort(403);

# 6. Create ZIP and upload
```

### Workflow 4: Add Menu Items to Existing Bundle

**Time: 3 minutes**

```bash
# 1. Create/edit MenuProvider
nano app/Packages/Pro/MyBundle/src/Providers/MenuProvider.php

# 2. Add menu items:
public static function getMenuItems(): array
{
    return [
        [
            'label' => 'My Bundle',
            'route' => 'mybundle.index',
            'icon' => '<svg>...</svg>',
            'permission' => 'view_mybundle',
        ],
    ];
}

# 3. Update main layout
nano resources/views/layouts/app.blade.php
# Add menu discovery:
@php
    use App\Helpers\BundleMenuHelper;
    $menuItems = BundleMenuHelper::discoverMenuItems();
@endphp

# 4. Loop through menu items
@foreach($menuItems as $item)
    <!-- Render menu item -->
@endforeach

# 5. Create ZIP and upload
```

### Workflow 5: Destroy Bundle and Clean Up Everything

**Time: 1 minute**

```bash
# 1. List bundles (optional, see what you have)
ls -la app/Packages/Pro/

# 2. Destroy bundle (asks for confirmation)
php artisan bundle:destroy-pro MyBundle

# 3. Or force without confirmation
php artisan bundle:destroy-pro MyBundle --force

# Result: Automatically removes
# - Bundle directory (app/Packages/Pro/MyBundle/)
# - Service provider registration (bootstrap/providers.php)
# - PSR-4 namespace (composer.json)

# 4. Final cleanup (recommended)
php artisan cache:clear
composer dump-autoload
```

**What Gets Cleaned Up:**
- ✅ Bundle directory completely deleted
- ✅ Service provider removed from bootstrap/providers.php
- ✅ PSR-4 namespace removed from composer.json
- ✅ No leftover provider registrations
```

## 🛠️ Common Tasks

### Task: Create Student Management Bundle

```bash
php artisan bundle:create-pro StudentManagement \
  --author="School Admin" \
  --description="Complete student management system" \
  --with-models

# Then customize:
cd app/Packages/Pro/StudentManagement

# Edit permissions (add export, analytics):
nano src/Providers/StudentManagementServiceProvider.php

# Edit menu (add analytics, admin tools):
nano src/Providers/MenuProvider.php

# Edit controller:
nano src/Controllers/StudentManagementController.php

# Edit views:
nano src/Views/index.blade.php
nano src/Views/edit.blade.php

# Create ZIP:
zip -r ../StudentManagement.zip .

# Upload via /bundle-installer
```

### Task: Customize Permissions

```bash
# 1. Open service provider
nano app/Packages/Pro/MyBundle/src/Providers/MyBundleServiceProvider.php

# 2. Find registerPermissions() method
# 3. Add custom permissions:
$permissions = [
    'view_mybundle',
    'create_mybundle_item',
    'edit_mybundle_item',
    'delete_mybundle_item',
    'export_mybundle',      // NEW
    'view_mybundle_stats',  // NEW
];

# 4. Customize role assignments:
$admin = Role::firstOrCreate(['name' => 'admin']);
$teacher = Role::firstOrCreate(['name' => 'teacher']);
$student = Role::firstOrCreate(['name' => 'student']);

$admin->syncPermissions($permissions);
$teacher->syncPermissions(['view_mybundle', 'edit_mybundle_item', 'view_mybundle_stats']);
$student->syncPermissions(['view_mybundle']);

# 5. Update routes with middleware:
nano src/Routes/web.php
# Add: ->middleware('permission:view_mybundle_stats')

# 6. Update controller:
nano src/Controllers/MyBundleController.php
# Add: if (!auth()->user()->can('view_mybundle_stats')) abort(403);

# 7. Re-create ZIP and upload
```

### Task: Add Submenu Items

```bash
# Edit MenuProvider:
nano app/Packages/Pro/MyBundle/src/Providers/MenuProvider.php

# Add submenu structure:
$items[] = [
    'label' => 'My Bundle',
    'submenu' => [
        [
            'label' => 'Dashboard',
            'route' => 'mybundle.dashboard',
        ],
        [
            'label' => 'Settings',
            'route' => 'mybundle.settings',
        ],
        [
            'label' => 'Export',
            'route' => 'mybundle.export',
        ],
    ],
];

# Add corresponding routes:
nano src/Routes/web.php
# Add: Route::get('/dashboard', ...)->name('mybundle.dashboard');
# Add: Route::get('/settings', ...)->name('mybundle.settings');
# Add: Route::post('/export', ...)->name('mybundle.export');
```

## 📊 Learning Path

### Beginner (1-2 hours)
1. Read: [QUICK_START.md](QUICK_START.md)
2. Run: `php artisan bundle:create-pro DemoBundle --with-models`
3. Create ZIP: `zip -r ../DemoBundle.zip .`
4. Upload via `/bundle-installer`
5. Test routes in browser

### Intermediate (2-4 hours)
1. Study: [PERMISSIONS_GUIDE.md](PERMISSIONS_GUIDE.md)
2. Study: [MENU_INJECTION_GUIDE.md](MENU_INJECTION_GUIDE.md)
3. Read: [CREATE_PRO_BUNDLE_SKILL.md](CREATE_PRO_BUNDLE_SKILL.md)
4. Create bundle with custom permissions
5. Add menu items with submenus
6. Test with different user roles

### Advanced (4+ hours)
1. Read: [COMPLETE_BUNDLE_GUIDE.md](COMPLETE_BUNDLE_GUIDE.md)
2. Study: [BUNDLE_NAMING_CONVENTION.md](BUNDLE_NAMING_CONVENTION.md)
3. Create multi-feature bundle
4. Implement complex permission models
5. Create dynamic menu items
6. Handle nested bundles

## 🎓 Knowledge Base

### Understanding Permissions

```bash
# Permissions are like locks on doors
- view_students = Can you see the list?
- create_student = Can you add new student?
- edit_student = Can you modify student?
- delete_student = Can you remove student?

# Roles are groups of permissions
- Admin role: has ALL permissions
- Teacher role: has view, edit permissions
- Student role: has view permission only

# Users get permissions through roles
User → Role → Permissions
```

### Understanding Menu Injection

```bash
# Traditional way (BAD):
# Edit app/resources/views/layouts/app.blade.php
# Add hardcoded menu items
# Problem: Changes app files, not scalable

# Bundle way (GOOD):
# Bundle has MenuProvider.php
# Returns menu items
# App discovers and renders them
# Problem: None! Bundles are self-contained
```

### Understanding PSR-4

```bash
# PSR-4 = PHP Standard Recommendation 4 (Autoloading)

# Namespace: App\Packages\Pro\StudentManagement\Controllers\StudentController
# File location: app/Packages/Pro/StudentManagement/src/Controllers/StudentController.php

# System maps:
"App\\Packages\\Pro\\StudentManagement\\" => "app/Packages/Pro/StudentManagement/src/"

# So PHP automatically finds the file based on namespace
```

## 🔍 Troubleshooting Guide

### Bundle Routes Not Working
```bash
# Solution 1: Clear route cache
php artisan route:clear

# Solution 2: Check routes loaded
php artisan route:list | grep bundle-name

# Solution 3: Verify service provider
grep -r "StudentManagement" bootstrap/providers.php
```

### Permissions Not Showing
```bash
# Solution 1: Run migrations
php artisan migrate

# Solution 2: Clear permission cache
php artisan cache:clear

# Solution 3: Check database
php artisan tinker
>>> Spatie\Permission\Models\Permission::count()
=> 50
```

### Menu Items Not Appearing
```bash
# Solution 1: Check MenuProvider exists
ls -la app/Packages/Pro/MyBundle/src/Providers/MenuProvider.php

# Solution 2: Verify app layout has menu discovery
grep -n "BundleMenuHelper" resources/views/layouts/app.blade.php

# Solution 3: Clear view cache
php artisan view:clear
```

### ZIP Upload Fails
```bash
# Solution 1: Check ZIP structure
unzip -l MyBundle.zip | head -5
# Should show manifest.json at root (not MyBundle/manifest.json)

# Solution 2: Verify manifest.json syntax
python -m json.tool app/Packages/Pro/MyBundle/manifest.json

# Solution 3: Check file permissions
ls -la app/Packages/Pro/MyBundle/manifest.json
# Should be readable by web server
```

## 📋 Checklists

### Bundle Creation Checklist
- [ ] Run `php artisan bundle:create-pro MyBundle --with-models`
- [ ] Edit controller (add your logic)
- [ ] Customize views (index, edit)
- [ ] Review permissions in service provider
- [ ] Edit MenuProvider (add menu items)
- [ ] Update routes (add new endpoints if needed)
- [ ] Test locally
- [ ] Create ZIP: `zip -r ../MyBundle.zip .`
- [ ] Upload via `/bundle-installer`
- [ ] Test in browser
- [ ] Test with different roles
- [ ] Document in README

### Deployment Checklist
- [ ] All files created and working
- [ ] Tests pass locally
- [ ] ZIP structure correct (manifest.json at root)
- [ ] manifest.json valid JSON
- [ ] Service provider correctly named
- [ ] MenuProvider.php exists (if using menu)
- [ ] Permissions defined
- [ ] Routes with permission middleware
- [ ] Views complete and working
- [ ] Database migration ready
- [ ] README updated

## 🎁 Pro Tips

1. **Use `--with-models`** when creating bundle with database tables
2. **Test locally first** before uploading - use `php artisan route:list`
3. **Clear caches** after upload: `php artisan cache:clear`
4. **Document permissions** in README for other developers
5. **Use meaningful permission names**: `verb_noun` (view_students, create_student)
6. **Add menu items** for discoverability - don't hide features
7. **Test with different roles** - verify permission checks work
8. **Keep bundles focused** - one feature per bundle
9. **Version your bundle** - use semantic versioning (1.0.0, 1.0.1, 2.0.0)
10. **Read the docs** - they answer 90% of questions!

## 📞 Support

| Question | Answer |
|----------|--------|
| How do I create a bundle? | See [CREATE_PRO_BUNDLE_SKILL.md](CREATE_PRO_BUNDLE_SKILL.md) |
| How do permissions work? | See [PERMISSIONS_GUIDE.md](PERMISSIONS_GUIDE.md) |
| How do menu items work? | See [MENU_INJECTION_GUIDE.md](MENU_INJECTION_GUIDE.md) |
| What's a complete example? | See [COMPLETE_BUNDLE_GUIDE.md](COMPLETE_BUNDLE_GUIDE.md) |
| Why is my ZIP failing? | See [ZIP_STRUCTURE_GUIDE.md](ZIP_STRUCTURE_GUIDE.md) |
| What are naming rules? | See [BUNDLE_NAMING_CONVENTION.md](BUNDLE_NAMING_CONVENTION.md) |

## 🚀 Next Steps

1. **Learn**: Start with [QUICK_START.md](QUICK_START.md)
2. **Create**: Run `php artisan bundle:create-pro` command
3. **Customize**: Edit controller, views, permissions
4. **Package**: Create ZIP file
5. **Deploy**: Upload via `/bundle-installer`
6. **Test**: Verify in browser with different roles
7. **Document**: Update README with features
8. **Share**: Share with team!

---

**Version**: 1.0  
**Last Updated**: 2026-05-05  
**Status**: Production Ready ✅

Happy bundling! 🎉
