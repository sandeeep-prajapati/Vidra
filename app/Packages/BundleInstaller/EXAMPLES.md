# Bundle Examples & Reference

Real-world examples of bundle naming conventions and automatic registration.

## Example 1: Premium Demo Bundle

### Directory Structure
```
app/Packages/Pro/DemoBundle/
├── manifest.json
├── README.md
└── src/
    ├── Providers/
    │   └── DemoBundleServiceProvider.php
    ├── Controllers/
    │   └── DemoController.php
    ├── Routes/
    │   └── web.php
    ├── Views/
    │   ├── index.blade.php
    │   └── features.blade.php
    └── Database/
        └── migrations/
```

### manifest.json
```json
{
    "name": "Demo Bundle",
    "version": "1.0.0",
    "description": "A sample premium bundle demonstrating the bundle installation system",
    "author": "Your Company",
    "license": "MIT",
    "package_path": "Pro/DemoBundle",
    "provider_class": "App\\Packages\\Pro\\DemoBundle\\Providers\\DemoBundleServiceProvider"
}
```

### Automatic Registration in composer.json
```json
{
    "autoload": {
        "psr-4": {
            "App\\Packages\\Pro\\DemoBundle\\": "app/Packages/Pro/DemoBundle/src/"
        }
    }
}
```

### Automatic Registration in bootstrap/providers.php
```php
use App\Packages\Pro\DemoBundle\Providers\DemoBundleServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    // ... other providers ...
    DemoBundleServiceProvider::class,
];
```

### Service Provider
```php
<?php

namespace App\Packages\Pro\DemoBundle\Providers;

use Illuminate\Support\ServiceProvider;

class DemoBundleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Views', 'demo-bundle');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }

    public function register(): void
    {
        //
    }
}
```

### Routes
```php
<?php

use App\Packages\Pro\DemoBundle\Controllers\DemoController;
use Illuminate\Support\Facades\Route;

Route::prefix('demo')->group(function () {
    Route::get('/', [DemoController::class, 'index'])->name('demo.index');
    Route::get('/features', [DemoController::class, 'features'])->name('demo.features');
});
```

### Controller
```php
<?php

namespace App\Packages\Pro\DemoBundle\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

class DemoController extends BaseController
{
    public function index(): View
    {
        return view('demo-bundle::index', [
            'title' => 'Demo Bundle',
            'message' => 'Welcome to the Demo Bundle!',
        ]);
    }

    public function features(): View
    {
        return view('demo-bundle::features', [
            'features' => $this->getFeatures(),
        ]);
    }

    private function getFeatures(): array
    {
        return [
            'PSR-4 Auto-Registration' => 'Namespaces are automatically registered',
            'Provider Auto-Loading' => 'Service providers are automatically registered',
            'Route Loading' => 'Routes are automatically loaded',
        ];
    }
}
```

### View (demo-bundle::index)
```blade
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold">{{ $title }}</h1>
    <p class="text-xl">{{ $message }}</p>
</div>
```

## Example 2: Student Premium Bundle

### Naming Convention
```
Bundle Name:    StudentPremium
Package Path:   Pro/StudentPremium
Namespace:      App\Packages\Pro\StudentPremium\
Provider:       App\Packages\Pro\StudentPremium\Providers\StudentPremiumServiceProvider
View Namespace: student-premium::view-name
Route Prefix:   studentpremium
```

### manifest.json
```json
{
    "name": "Student Premium",
    "version": "2.0.0",
    "description": "Premium student management features",
    "author": "Your Company",
    "license": "MIT",
    "package_path": "Pro/StudentPremium",
    "provider_class": "App\\Packages\\Pro\\StudentPremium\\Providers\\StudentPremiumServiceProvider"
}
```

### Directory Structure
```
app/Packages/Pro/StudentPremium/
├── manifest.json
├── src/
│   ├── Providers/
│   │   └── StudentPremiumServiceProvider.php
│   ├── Controllers/
│   │   ├── StudentAnalyticsController.php
│   │   └── PerformanceController.php
│   ├── Models/
│   │   ├── StudentAnalytics.php
│   │   └── PerformanceMetric.php
│   ├── Routes/
│   │   └── web.php
│   ├── Views/
│   │   ├── analytics/
│   │   │   ├── index.blade.php
│   │   │   └── chart.blade.php
│   │   └── performance/
│   │       └── report.blade.php
│   └── Database/
│       └── migrations/
│           ├── 2026_01_01_000000_create_student_analytics_table.php
│           └── 2026_01_01_000001_create_performance_metrics_table.php
```

### Auto-Generated composer.json Entry
```json
{
    "autoload": {
        "psr-4": {
            "App\\Packages\\Pro\\StudentPremium\\": "app/Packages/Pro/StudentPremium/src/"
        }
    }
}
```

### Service Provider
```php
<?php

namespace App\Packages\Pro\StudentPremium\Providers;

use Illuminate\Support\ServiceProvider;

class StudentPremiumServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
        $this->loadViewsFrom(__DIR__.'/../Views', 'student-premium');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }

    public function register(): void
    {
        //
    }
}
```

### Routes
```php
<?php

use App\Packages\Pro\StudentPremium\Controllers\StudentAnalyticsController;
use App\Packages\Pro\StudentPremium\Controllers\PerformanceController;
use Illuminate\Support\Facades\Route;

Route::prefix('studentpremium')->group(function () {
    Route::get('/analytics', [StudentAnalyticsController::class, 'index'])
        ->name('student-premium.analytics');
    
    Route::get('/performance', [PerformanceController::class, 'report'])
        ->name('student-premium.performance');
});
```

### Using the Bundle in Views
```blade
<!-- Access analytics view -->
@include('student-premium::analytics.index')

<!-- Access performance view -->
@include('student-premium::performance.report')
```

### Using Models in Controllers
```php
<?php

namespace App\Packages\Pro\StudentPremium\Controllers;

use App\Packages\Pro\StudentPremium\Models\StudentAnalytics;
use Illuminate\View\View;

class StudentAnalyticsController
{
    public function index(): View
    {
        $analytics = StudentAnalytics::latest()->paginate();
        
        return view('student-premium::analytics.index', [
            'analytics' => $analytics,
        ]);
    }
}
```

## Example 3: Core Bundle (Non-Premium)

### Naming Convention
```
Bundle Name:    ContactBundle
Package Path:   ContactBundle (no Pro/ prefix)
Namespace:      App\Packages\ContactBundle\
Provider:       App\Packages\ContactBundle\Providers\ContactBundleServiceProvider
View Namespace: contact-bundle::view-name
Route Prefix:   contact
```

### manifest.json
```json
{
    "name": "Contact Bundle",
    "version": "1.0.0",
    "description": "Core contact management system",
    "author": "Your Company",
    "license": "MIT",
    "package_path": "ContactBundle",
    "provider_class": "App\\Packages\\ContactBundle\\Providers\\ContactBundleServiceProvider"
}
```

### Auto-Generated composer.json Entry
```json
{
    "autoload": {
        "psr-4": {
            "App\\Packages\\ContactBundle\\": "app/Packages/ContactBundle/src/"
        }
    }
}
```

## Example 4: Nested Bundle Structure

### Naming Convention
```
Bundle Name:    Advanced
Parent Path:    Pro/Reports
Package Path:   Pro/Reports/Advanced
Namespace:      App\Packages\Pro\Reports\Advanced\
Provider:       App\Packages\Pro\Reports\Advanced\Providers\AdvancedServiceProvider
View Namespace: pro-reports-advanced::view-name
Route Prefix:   advanced-reports
```

### manifest.json
```json
{
    "name": "Advanced Reports",
    "version": "1.0.0",
    "description": "Advanced reporting features",
    "author": "Your Company",
    "license": "MIT",
    "package_path": "Pro/Reports/Advanced",
    "provider_class": "App\\Packages\\Pro\\Reports\\Advanced\\Providers\\AdvancedServiceProvider"
}
```

### Directory Structure
```
app/Packages/Pro/Reports/Advanced/
├── manifest.json
└── src/
    ├── Providers/
    │   └── AdvancedServiceProvider.php
    ├── Controllers/
    ├── Routes/
    ├── Views/
    └── Database/
```

### Auto-Generated composer.json Entry
```json
{
    "autoload": {
        "psr-4": {
            "App\\Packages\\Pro\\Reports\\Advanced\\": "app/Packages/Pro/Reports/Advanced/src/"
        }
    }
}
```

## Example 5: Using Artisan Command

### Creating StudentPremium Bundle

```bash
# Create the bundle
php artisan bundle:create StudentPremium --pro \
    --author="Your Company" \
    --description="Premium student management features" \
    --version="2.0.0" \
    --with-menu

# Output:
# Creating bundle: StudentPremium (Pro/StudentPremium)...
# ✓ Directory structure created
# ✓ manifest.json updated
# ✓ Service provider renamed
# ✓ Routes updated
# ✓ Menu provider created
# 
# ✅ Bundle created successfully!
# 
# To zip the bundle:
#   cd app/Packages/Pro
#   zip -r StudentPremium.zip StudentPremium/
```

### Directory Created
```
app/Packages/Pro/StudentPremium/
├── manifest.json
├── README.md
└── src/
    ├── Providers/
    │   └── StudentPremiumServiceProvider.php
    │   └── MenuProvider.php
    ├── Controllers/
    │   └── StudentPremiumController.php
    ├── Models/
    │   └── Demo.php
    ├── Routes/
    │   └── web.php
    ├── Views/
    │   └── index.blade.php
    ├── Database/
    │   └── migrations/
    │       └── 2026_05_05_000000_create_demo_table.php
    └── Assets/
```

## Example 6: Bundle ZIP Contents

### Correct ZIP Structure
```
StudentPremium/
├── manifest.json           ✓ Required
├── README.md              ✓ Recommended
└── src/
    ├── Providers/         ✓ Required
    │   └── StudentPremiumServiceProvider.php
    ├── Controllers/
    │   └── StudentPremiumController.php
    ├── Routes/           ✓ Required (even if empty)
    │   └── web.php
    ├── Views/            ✓ Required (even if empty)
    │   └── index.blade.php
    ├── Models/
    ├── Database/
    │   └── migrations/
    └── Assets/
```

### Create ZIP
```bash
# From your project root
cd app/Packages/Pro
zip -r StudentPremium.zip StudentPremium/

# Result: StudentPremium.zip (ready to upload)
```

## Example 7: Installation Process

### Before Upload
```
bundle.zip uploaded ↓
```

### Step 1: Validation ✓
```
✓ Valid ZIP file
✓ Contains manifest.json
✓ Has all required fields
```

### Step 2: Extraction ✓
```
Extracted to:
app/Packages/Pro/StudentPremium/
```

### Step 3: PSR-4 Registration ✓
```
Added to composer.json:
"App\\Packages\\Pro\\StudentPremium\\": "app/Packages/Pro/StudentPremium/src/"

Ran: composer dump-autoload
```

### Step 4: Provider Registration ✓
```
Added use statement to bootstrap/providers.php:
use App\Packages\Pro\StudentPremium\Providers\StudentPremiumServiceProvider;

Added to provider array:
StudentPremiumServiceProvider::class,
```

### Step 5: Cache Clearing ✓
```
✓ Cleared config cache
✓ Cleared route cache
✓ Cleared view cache
✓ Cleared application cache
```

### After Installation ✓
```
Bundle ready to use!
Routes accessible at: /studentpremium
Views available as: student-premium::*
Models auto-discovered
```

## Pattern Recognition

### Naming Rules Summary

| Component | Format | Example |
|-----------|--------|---------|
| Directory | `{Category}/{Name}` | `Pro/StudentPremium` |
| Namespace | PascalCase with slashes | `App\Packages\Pro\StudentPremium\` |
| Provider | `{Name}ServiceProvider` | `StudentPremiumServiceProvider` |
| View Prefix | kebab-case | `student-premium` |
| Routes | lowercase | `studentpremium` |

### Transformation Examples

```
Input: "UserManagement"
├─ Directory: UserManagement
├─ Namespace: App\Packages\UserManagement\
├─ Provider: UserManagementServiceProvider
├─ Views: user-management::*
└─ Routes: /usermanagement

Input: "Pro/AdvancedAnalytics"
├─ Directory: Pro/AdvancedAnalytics
├─ Namespace: App\Packages\Pro\AdvancedAnalytics\
├─ Provider: AdvancedAnalyticsServiceProvider
├─ Views: advanced-analytics::*
└─ Routes: /advancedanalytics
```

## Quick Reference Table

| Task | Command | Location |
|------|---------|----------|
| Create Bundle (Pro) | `php artisan bundle:create MyBundle --pro` | `app/Packages/Pro/MyBundle/` |
| Create Bundle (Core) | `php artisan bundle:create MyBundle` | `app/Packages/MyBundle/` |
| Create ZIP | `cd app/Packages/Pro && zip -r MyBundle.zip MyBundle/` | `app/Packages/Pro/MyBundle.zip` |
| Upload Bundle | Navigate to `/bundle-installer` | Web UI |
| View Sample | Visit `/demo` or `/demo/features` | Sample DemoBundle |
| View Installed | Visit `/bundle-installer` | Web UI |

## Testing Your Bundle Locally

```bash
# 1. Create bundle
php artisan bundle:create TestBundle --pro

# 2. Edit files
# Modify controllers, views, models, etc.

# 3. Test routes
php artisan route:list | grep testbundle

# 4. Test views
# Visit http://localhost:8000/testbundle

# 5. Test migrations (if any)
php artisan migrate --force

# 6. Verify namespace works
php artisan tinker
>>> class_exists('App\Packages\Pro\TestBundle\Controllers\TestBundleController')
=> true

# 7. Create ZIP when ready
cd app/Packages/Pro
zip -r TestBundle.zip TestBundle/

# 8. Upload via UI
# Go to /bundle-installer
# Upload TestBundle.zip
```

---

For more details, see:
- [QUICK_START.md](./QUICK_START.md) - 60-second setup
- [BUNDLE_NAMING_CONVENTION.md](./BUNDLE_NAMING_CONVENTION.md) - Complete reference
- [README.md](./README.md) - Full documentation
- [../Pro/DemoBundle/README.md](../Pro/DemoBundle/README.md) - Sample bundle
