# Bundle Installer Package

Professional bundle installation system for Vidra with automatic PSR-4 namespace registration and service provider management.

## ⚠️ CRITICAL: ZIP Structure

When creating bundle ZIPs, the **`manifest.json` must be at the ROOT**, not nested in a folder!

**CORRECT ✅:** `zip -r MyBundle.zip .` (from inside MyBundle directory)  
**INCORRECT ❌:** `zip -r MyBundle.zip MyBundle/` (this nests everything)

See QUICK_START.md for examples.

## Features

✨ **Automatic Registration**
- PSR-4 autoloading namespaces automatically registered in `composer.json`
- Service providers automatically registered in `bootstrap/providers.php`
- No manual configuration needed!

📦 **Bundle Management**
- Upload bundles via web UI at `/bundle-installer`
- Validate bundle integrity before installation
- Support for premium (`Pro/`) and core bundles
- Easy bundle removal with automatic cleanup

🎯 **Naming Convention**
- Standardized package paths and namespaces
- Convention-based automatic registration
- Consistent naming across all components

🚀 **Developer Tools**
- `php artisan bundle:create` command for scaffolding
- Sample Pro/DemoBundle as template
- Comprehensive documentation

## Quick Start

### For Users

1. **Visit Bundle Installer UI**: `/bundle-installer`
2. **Upload your bundle ZIP** containing `manifest.json`
3. **System automatically**:
   - Extracts files to `app/Packages/{path}`
   - Registers PSR-4 namespace
   - Registers service provider
   - Clears application caches

### For Developers

#### Create Bundle Using CLI

```bash
php artisan bundle:create MyBundle --pro
```

#### Create Bundle From Template

```bash
# Copy template
cp -r app/Packages/Pro/DemoBundle app/Packages/Pro/MyBundle

# Edit manifest.json and rename provider
# Create ZIP and upload
```

#### Directory Structure

```
MyBundle/
├── manifest.json           # Required
├── README.md              # Recommended
└── src/
    ├── Providers/
    │   └── MyBundleServiceProvider.php
    ├── Controllers/
    ├── Routes/
    ├── Views/
    ├── Models/
    ├── Database/
    │   └── migrations/
    └── Assets/
```

## Documentation

### 📖 For Quick Reference
- **[QUICK_START.md](./QUICK_START.md)** - 60-second bundle creation guide

### 📚 For Complete Details
- **[BUNDLE_NAMING_CONVENTION.md](./BUNDLE_NAMING_CONVENTION.md)** - Complete naming convention and registration process

### 🎓 For Learning
- **[../Pro/DemoBundle/README.md](../Pro/DemoBundle/README.md)** - Sample bundle documentation

## Naming Convention Overview

### Package Path (Directory)
```
Pro/DemoBundle          → app/Packages/Pro/DemoBundle/
Pro/StudentPremium     → app/Packages/Pro/StudentPremium/
ContactBundle          → app/Packages/ContactBundle/
```

### Namespace (Auto-Generated)
```
Pro/DemoBundle         → App\Packages\Pro\DemoBundle\
Pro/StudentPremium    → App\Packages\Pro\StudentPremium\
ContactBundle         → App\Packages\ContactBundle\
```

### Service Provider
```
package_path: "Pro/DemoBundle"
provider_class: "App\Packages\Pro\DemoBundle\Providers\DemoBundleServiceProvider"
```

### View Namespace
```
DemoBundle            → demo-bundle::view
StudentPremium       → student-premium::view
ContactBundle        → contact-bundle::view
```

## Manifest.json Format

```json
{
    "name": "Your Bundle Name",
    "version": "1.0.0",
    "description": "Bundle description",
    "author": "Your Company",
    "license": "MIT",
    "package_path": "Pro/YourBundle",
    "provider_class": "App\\Packages\\Pro\\YourBundle\\Providers\\YourBundleServiceProvider"
}
```

**Required fields:**
- `name` - Display name
- `version` - Semantic versioning
- `description` - Bundle purpose
- `author` - Creator
- `license` - License type
- `package_path` - Directory path (used for auto-registration)
- `provider_class` - Full provider class name

## Service Provider Template

```php
<?php

namespace App\Packages\Pro\YourBundle\Providers;

use Illuminate\Support\ServiceProvider;

class YourBundleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Views', 'your-bundle');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }

    public function register(): void
    {
        //
    }
}
```

## Creating a Bundle

### Method 1: Using Artisan Command (Recommended)

```bash
# Create premium bundle
php artisan bundle:create MyBundle --pro

# Create core bundle
php artisan bundle:create MyBundle

# With custom author and description
php artisan bundle:create MyBundle --pro --author="Your Company" --description="My awesome bundle"

# With menu provider
php artisan bundle:create MyBundle --pro --with-menu
```

### Method 2: From Template

```bash
# Copy template
cp -r app/Packages/Pro/DemoBundle app/Packages/Pro/MyBundle

# Update manifest.json
# Rename DemoBundleServiceProvider.php to MyBundleServiceProvider.php
# Update class name inside provider
# Update routes in src/Routes/web.php

# Create ZIP
cd app/Packages/Pro
zip -r MyBundle.zip MyBundle/
```

## Installation Process (Automatic)

When you upload a bundle:

1. **Validation**
   - File is a valid ZIP
   - Contains `manifest.json`
   - Has required fields

2. **Extraction**
   - Extracts to `app/Packages/{package_path}`
   - Preserves directory structure

3. **PSR-4 Registration**
   - Reads `package_path` from manifest
   - Generates namespace: `App\Packages\{PathSegments}\`
   - Adds to `composer.json`
   - Runs `composer dump-autoload`

4. **Provider Registration**
   - Reads `provider_class` from manifest
   - Adds use statement to `bootstrap/providers.php`
   - Adds provider to application array

5. **Cache Clearing**
   - Clears all application caches
   - Application ready to use immediately

## API & Routes

### Web Routes

| Route | Method | Purpose |
|-------|--------|---------|
| `/bundle-installer` | GET | List installed bundles |
| `/bundle-installer/upload` | POST | Upload new bundle |
| `/bundle-installer/{bundle}/install` | POST | Install bundle |
| `/bundle-installer/{bundle}` | DELETE | Remove bundle |

## File Structure

```
BundleInstaller/
├── src/
│   ├── Providers/
│   │   └── BundleInstallerServiceProvider.php
│   ├── Controllers/
│   │   └── BundleInstallerController.php
│   ├── Services/
│   │   ├── BundleExtractorService.php
│   │   └── BundleRegistrarService.php
│   ├── Console/
│   │   └── Commands/
│   │       └── CreateBundleCommand.php
│   ├── Routes/
│   │   └── web.php
│   ├── Resources/
│   │   └── views/
│       └── bundle-installer/
│           └── index.blade.php
├── BUNDLE_NAMING_CONVENTION.md
├── QUICK_START.md
└── README.md (this file)
```

## Configuration

### Bootstrap Providers File

The system automatically updates `bootstrap/providers.php`. Your bundle's provider should be:

```php
use App\Packages\Pro\YourBundle\Providers\YourBundleServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    // ... other providers ...
    YourBundleServiceProvider::class,
];
```

### Composer Configuration

Your bundle's PSR-4 autoload is automatically added to `composer.json`:

```json
{
    "autoload": {
        "psr-4": {
            "App\\Packages\\Pro\\YourBundle\\": "app/Packages/Pro/YourBundle/src/"
        }
    }
}
```

## Troubleshooting

| Issue | Solution |
|-------|----------|
| **"Invalid ZIP file"** | Ensure file is a valid ZIP archive |
| **"manifest.json not found"** | Root of ZIP must contain manifest.json |
| **"Namespace not working"** | Check `package_path` matches directory structure |
| **"Provider not loading"** | Verify class name in manifest matches your file |
| **"Views not found"** | Ensure view namespace is kebab-case in service provider |
| **"Routes conflict"** | Check route prefixes don't conflict with existing routes |
| **"Migrations failed"** | Place migration files directly in `src/Database/migrations/` |

## Best Practices

1. **Consistent Naming**
   - Use same name for directory, namespace, and provider
   - Example: `Pro/MyBundle` → `MyBundleServiceProvider`

2. **Version Management**
   - Use semantic versioning (1.0.0, 1.0.1, 2.0.0)
   - Update version in manifest when releasing updates

3. **ZIP Structure**
   - Bundle name should match directory inside ZIP
   - No extra wrapper directories
   - Exclude vendor, node_modules, and cache directories

4. **Documentation**
   - Include README.md in bundle
   - Document configuration options
   - Provide usage examples

5. **Testing**
   - Test locally before creating ZIP
   - Run migrations: `php artisan migrate --force`
   - Check routes: `php artisan route:list`
   - Verify views: Visit the bundle routes in browser

6. **Security**
   - Validate user input in your controllers
   - Don't store sensitive data in bundles
   - Use Laravel's security features (CSRF, auth, etc.)

## Example Bundles

### Sample Bundle (Built-in)
- Location: `app/Packages/Pro/DemoBundle`
- Route: `/demo` and `/demo/features`
- Shows: Views, controllers, routes, service provider structure

### Create Your Own
See **QUICK_START.md** for a 60-second bundle creation guide.

## Support & Contributions

For issues or improvements:
1. Check the documentation files
2. Review the sample Pro/DemoBundle
3. Check `BUNDLE_NAMING_CONVENTION.md` for naming issues
4. Review existing bundles for patterns

## Command Reference

```bash
# Create a new bundle (Pro)
php artisan bundle:create MyBundle --pro

# Create a new bundle (Core)
php artisan bundle:create MyBundle

# Create with custom options
php artisan bundle:create MyBundle --pro \
  --author="My Company" \
  --description="My bundle description" \
  --version="2.0.0" \
  --with-menu

# List available bundles
php artisan bundle:list
```

## Files to Review

- **Entry Point**: `src/Controllers/BundleInstallerController.php`
- **Installation Logic**: `src/Services/BundleRegistrarService.php`
- **Extraction Logic**: `src/Services/BundleExtractorService.php`
- **Bundle Generator**: `src/Console/Commands/CreateBundleCommand.php`
- **Service Provider**: `src/Providers/BundleInstallerServiceProvider.php`

## License

MIT License - See individual bundle licenses for details.
