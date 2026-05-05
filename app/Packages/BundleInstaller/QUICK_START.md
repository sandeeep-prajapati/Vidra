# Quick Start: Creating & Installing Bundles

## 60-Second Bundle Creation

### 1. Copy Template
```bash
cp -r app/Packages/Pro/DemoBundle app/Packages/Pro/MyBundle
```

### 2. Update Three Files

**manifest.json:**
```json
{
    "name": "My Bundle",
    "version": "1.0.0",
    "description": "My awesome bundle",
    "author": "You",
    "license": "MIT",
    "package_path": "Pro/MyBundle",
    "provider_class": "App\\Packages\\Pro\\MyBundle\\Providers\\MyBundleServiceProvider"
}
```

**Rename provider file:**
- `src/Providers/DemoBundleServiceProvider.php` → `src/Providers/MyBundleServiceProvider.php`
- Update class name inside

**Update src/Routes/web.php:**
```php
Route::prefix('mybundle')->group(function () {
    // Your routes
});
```

### 3. Create ZIP & Upload

⚠️ **IMPORTANT: ZIP Structure**

The `manifest.json` must be at the **ROOT** of the ZIP file, not nested in a folder.

**CORRECT ✅:**
```bash
cd app/Packages/Pro/MyBundle
zip -r ../MyBundle.zip .
cd ..
# Now upload MyBundle.zip
```

**INCORRECT ❌:**
```bash
cd app/Packages/Pro
zip -r MyBundle.zip MyBundle/  # This nests everything - DON'T DO THIS!
```

Upload ZIP via `/bundle-installer` UI → **Done!** ✨

---

## Naming Rules

| Component | Format | Example |
|-----------|--------|---------|
| **Directory** | `Pro/BundleName` | `Pro/MyBundle` |
| **Namespace** | Auto-generated | `App\Packages\Pro\MyBundle` |
| **Provider** | `{Namespace}\Providers\{Name}ServiceProvider` | `App\Packages\Pro\MyBundle\Providers\MyBundleServiceProvider` |
| **View Namespace** | lowercase-with-hyphens | `my-bundle::view` |
| **Routes Prefix** | lowercase | `mybundle` |

---

## File Structure

```
MyBundle/
├── manifest.json                    ← Update this
├── README.md                        ← Optional
└── src/
    ├── Providers/
    │   └── MyBundleServiceProvider.php  ← Rename & update class
    ├── Controllers/
    │   └── MyController.php
    ├── Routes/
    │   └── web.php                 ← Update routes
    ├── Views/
    │   └── index.blade.php
    ├── Models/
    ├── Database/
    │   └── migrations/
    └── Assets/
```

---

## What's Automatic?

✅ **You just provide:**
- `package_path` in manifest.json

✅ **System automatically:**
- Generates PSR-4 namespace
- Registers in `composer.json`
- Registers service provider
- Loads routes, views, migrations
- Clears caches

---

## Testing Locally

Before creating ZIP:

```bash
# In your bundle provider
php artisan route:list  # Check routes appear
php artisan migrate --force  # Test migrations
```

Then visit:
- `/demo` → see DemoBundle example
- `/mybundle` → see your bundle (once installed)

---

## Common Bundle Types

### Premium Bundle (Pro/)
```
package_path: "Pro/FeatureName"
namespace: App\Packages\Pro\FeatureName\
```

### Core Bundle
```
package_path: "StudentManagement"
namespace: App\Packages\StudentManagement\
```

### Nested Bundle
```
package_path: "Pro/Reports/Advanced"
namespace: App\Packages\Pro\Reports\Advanced\
```

---

## ZIP Contents (for Upload)

When creating `MyBundle.zip`, include:

```
MyBundle/
├── manifest.json        ✓ Required
├── README.md           ✓ Recommended
└── src/                ✓ Required
    ├── Providers/      ✓ Required
    ├── Routes/         ✓ Required (even if empty)
    ├── Views/          ✓ Required (even if empty)
    ├── Controllers/    ✓ Recommended
    ├── Models/
    ├── Database/
    └── Assets/
```

**Exclude:**
- ❌ `vendor/`
- ❌ `node_modules/`
- ❌ `.git/`
- ❌ `composer.lock`
- ❌ `package.lock.json`

---

## Troubleshooting

| Problem | Solution |
|---------|----------|
| "Namespace not found" | Check `package_path` matches your directory |
| "Provider not loading" | Verify class name in manifest matches your file |
| "Views not found" | Ensure view namespace in provider is kebab-case |
| "Routes not working" | Check `src/Routes/web.php` exists |

---

## Need More Details?

See `BUNDLE_NAMING_CONVENTION.md` for comprehensive documentation.
