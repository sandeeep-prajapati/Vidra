# Bundle Naming Convention & Automatic Registration

This document explains how bundle packages are automatically registered in the Vidra application without manual PSR-4 configuration.

## Overview

The bundle installation system uses a **convention-based approach** to automatically:
- Register PSR-4 autoloading namespaces
- Register service providers
- Load routes, views, and migrations
- Handle database migrations

**No manual `composer.json` edits required!** ✨

## Naming Convention

### 1. Package Path (Directory Structure)

The `package_path` in your `manifest.json` defines the directory structure:

```
package_path: "Pro/DemoBundle"
                ↓
Directory: app/Packages/Pro/DemoBundle/
```

**Rules:**
- Use PascalCase for each segment
- Separate segments with forward slashes
- Premium bundles should be under `Pro/` prefix
- Core bundles directly under root (e.g., `ContactBundle`)

**Examples:**
```
Pro/DemoBundle          → app/Packages/Pro/DemoBundle/
Pro/PremiumFeature     → app/Packages/Pro/PremiumFeature/
StudentManagement      → app/Packages/StudentManagement/
ContactBundle          → app/Packages/ContactBundle/
```

### 2. Namespace (Automatic Generation)

The namespace is **automatically generated** from the `package_path`:

```
package_path: "Pro/DemoBundle"
                ↓
namespace: App\Packages\Pro\DemoBundle\
```

**Transformation rules:**
- Start with `App\Packages\`
- Each path segment becomes a namespace segment
- Each segment is in PascalCase (uppercase first letter)
- End with backslash

**Auto-registration in composer.json:**
```json
{
  "autoload": {
    "psr-4": {
      "App\\Packages\\Pro\\DemoBundle\\": "app/Packages/Pro/DemoBundle/src/"
    }
  }
}
```

**Calculation:**
- Base namespace: `App\Packages\`
- Path segments: `Pro`, `DemoBundle`
- Final namespace: `App\Packages\Pro\DemoBundle\`
- Source directory: `app/Packages/Pro/DemoBundle/src/`

### 3. Service Provider Class Name

The service provider class is **manually specified** but follows a pattern:

```json
{
  "provider_class": "App\\Packages\\Pro\\DemoBundle\\Providers\\DemoBundleServiceProvider"
}
```

**Pattern:**
```
App\Packages\{namespace_path}\Providers\{BundleName}ServiceProvider
```

**Examples:**
```
Package: Pro/DemoBundle
Provider: App\Packages\Pro\DemoBundle\Providers\DemoBundleServiceProvider

Package: Pro/StudentPremium
Provider: App\Packages\Pro\StudentPremium\Providers\StudentPremiumServiceProvider

Package: ContactBundle
Provider: App\Packages\ContactBundle\Providers\ContactBundleServiceProvider
```

**File location:**
```
app/Packages/{package_path}/src/Providers/{BundleName}ServiceProvider.php
```

### 4. View Namespace

Views are loaded with a kebab-case namespace:

```php
// In your service provider
$this->loadViewsFrom(__DIR__.'/../Views', 'demo-bundle');
```

**Pattern:**
- Convert bundle name to lowercase
- Replace spaces with hyphens
- Used as `view('demo-bundle::view-name')`

**Examples:**
```
DemoBundle           → demo-bundle::index
PremiumFeatures      → premium-features::dashboard
ContactManagement    → contact-management::form
```

### 5. Route Prefix

Suggested pattern for routes:

```php
// In routes/web.php
Route::prefix('demo')->group(function () {
    Route::get('/', [DemoController::class, 'index']);
});
```

**Pattern:**
- Use lowercase version of your bundle name
- Example: `DemoBundle` → prefix `demo`

## Manifest.json Structure

```json
{
    "name": "Demo Bundle",
    "version": "1.0.0",
    "description": "A sample bundle",
    "author": "Your Company",
    "license": "MIT",
    "package_path": "Pro/DemoBundle",
    "provider_class": "App\\Packages\\Pro\\DemoBundle\\Providers\\DemoBundleServiceProvider"
}
```

**Required fields:**
- `name`: Display name
- `version`: Semantic versioning
- `description`: Bundle purpose
- `author`: Creator/company
- `license`: License type
- `package_path`: Directory path (used for auto-registration)
- `provider_class`: Full provider class name

## Directory Structure Template

```
Pro/DemoBundle/
├── manifest.json                           # Required
├── README.md                               # Recommended
└── src/
    ├── Providers/
    │   └── DemoBundleServiceProvider.php   # Required
    ├── Controllers/
    │   └── DemoController.php              # Your controllers
    ├── Routes/
    │   └── web.php                        # Routes
    ├── Views/
    │   ├── index.blade.php
    │   └── features.blade.php
    ├── Models/                            # Eloquent models
    ├── Database/
    │   └── migrations/                    # Database changes
    ├── Console/
    │   └── Commands/                      # Artisan commands
    └── Assets/                            # CSS, JS, images
```

## Automatic Registration Process

### Step 1: Upload Bundle ZIP

When you upload a bundle through the UI:

1. File is validated
2. `manifest.json` is checked for required fields
3. ZIP is extracted to `app/Packages/{package_path}/`

### Step 2: PSR-4 Registration (Automatic)

The system automatically:

1. **Reads** `package_path` from manifest
2. **Generates** namespace: `App\Packages\{PathSegments}\`
3. **Calculates** source path: `app/Packages/{package_path}/src`
4. **Adds** to `composer.json` in alphabetical order
5. **Runs** `composer dump-autoload`

**Result in composer.json:**
```json
"autoload": {
  "psr-4": {
    "App\\Packages\\Pro\\DemoBundle\\": "app/Packages/Pro/DemoBundle/src/",
    "App\\Packages\\Pro\\StudentPremium\\": "app/Packages/Pro/StudentPremium/src/"
  }
}
```

### Step 3: Provider Registration (Automatic)

The system automatically:

1. **Reads** `provider_class` from manifest
2. **Adds** use statement to `bootstrap/providers.php`
3. **Adds** class to provider array in correct position
4. **Clears** all application caches

**Result in bootstrap/providers.php:**
```php
use App\Packages\Pro\DemoBundle\Providers\DemoBundleServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    // ... other providers
    DemoBundleServiceProvider::class,
];
```

### Step 4: Service Provider Boot

Your service provider's `boot()` method is called:

```php
public function boot(): void
{
    // Load migrations
    $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
    
    // Load views
    $this->loadViewsFrom(__DIR__.'/../Views', 'demo-bundle');
    
    // Load routes
    $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    
    // Publish assets
    $this->publishes([
        __DIR__.'/../Assets' => public_path('vendor/demo-bundle'),
    ], 'demo-bundle-assets');
}
```

## Creating a New Bundle

### 1. Copy the Template

```bash
cp -r app/Packages/Pro/DemoBundle app/Packages/Pro/YourBundle
```

### 2. Update manifest.json

```json
{
    "name": "Your Bundle Name",
    "version": "1.0.0",
    "description": "Your bundle description",
    "author": "Your Company",
    "license": "MIT",
    "package_path": "Pro/YourBundle",
    "provider_class": "App\\Packages\\Pro\\YourBundle\\Providers\\YourBundleServiceProvider"
}
```

### 3. Rename Service Provider

- File: `src/Providers/DemoBundleServiceProvider.php` → `src/Providers/YourBundleServiceProvider.php`
- Class name: `DemoBundleServiceProvider` → `YourBundleServiceProvider`

### 4. Update Service Provider

```php
namespace App\Packages\Pro\YourBundle\Providers;

class YourBundleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Views', 'your-bundle');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }
}
```

### 5. Customize Routes

```php
// src/Routes/web.php
Route::prefix('your-route')->group(function () {
    Route::get('/', [YourController::class, 'index']);
});
```

### 6. Create ZIP Bundle

```bash
cd app/Packages/Pro
zip -r YourBundle.zip YourBundle/
```

The ZIP should contain:
```
YourBundle/
├── manifest.json
├── README.md
└── src/
    ├── Providers/
    │   └── YourBundleServiceProvider.php
    ├── Controllers/
    ├── Routes/
    ├── Views/
    ├── Models/
    ├── Database/
    │   └── migrations/
    └── Assets/
```

### 7. Upload via UI

Go to `/bundle-installer` and upload your `YourBundle.zip`. The system will:
- Extract the files
- Auto-register the PSR-4 namespace
- Auto-register the service provider
- Clear caches

Done! Your bundle is ready to use.

## Common Issues & Solutions

### Issue: Namespace not working after upload

**Solution:**
- Ensure `package_path` matches your directory structure
- Run `composer dump-autoload` manually
- Clear `bootstrap/cache/` directory

### Issue: Views not found

**Solution:**
- Verify view namespace in service provider matches your bundle name
- Use kebab-case for view namespace: `demo-bundle`
- Check views are in `src/Views/` directory

### Issue: Routes not working

**Solution:**
- Ensure `loadRoutesFrom()` path is correct
- Routes should be in `src/Routes/web.php`
- Check route prefix doesn't conflict with existing routes

### Issue: Migrations not running

**Solution:**
- Place migration files directly in `src/Database/migrations/`
- Follow Laravel naming convention: `YYYY_MM_DD_HHMMSS_description.php`
- Run `php artisan migrate` to execute

## Best Practices

1. **Use consistent naming** across bundle name, namespace, and provider
2. **Keep directory structure** consistent with the template
3. **Use kebab-case** for view namespaces
4. **Version your bundle** semantically (1.0.0, 1.0.1, etc.)
5. **Document features** in README.md
6. **Test locally** before creating ZIP
7. **Include only necessary files** in ZIP (exclude vendor, node_modules)

## Reference

**Base namespace:** `App\Packages\`

**Core bundles (non-premium):**
- Directory: `app/Packages/{BundleName}/`
- Namespace: `App\Packages\{BundleName}\`
- Example: `StudentManagement` → `App\Packages\StudentManagement\`

**Premium bundles:**
- Directory: `app/Packages/Pro/{BundleName}/`
- Namespace: `App\Packages\Pro\{BundleName}\`
- Example: `Pro/DemoBundle` → `App\Packages\Pro\DemoBundle\`

**Nested bundles:**
- Directory: `app/Packages/{Category}/{BundleName}/`
- Namespace: `App\Packages\{Category}\{BundleName}\`
- Example: `Pro/Reports/Advanced` → `App\Packages\Pro\Reports\Advanced\`
