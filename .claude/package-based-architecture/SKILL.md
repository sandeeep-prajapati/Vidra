---
name: package-based-architecture
description: Build self-contained packages/modules where each module has its own controllers, models, views, routes, and service provider. CorePackage provides the shared UI layout and Blade component library used by all modules. Enables true modularity and independent feature development with complete separation of concerns.
---

# Package-Based Architecture Skill

## Monorepo Context

Laravel lives in the `laravel/` subdirectory. All paths below are relative to `laravel/`.

```
school-management-app/
├── laravel/          ← PHP Laravel app (all paths below are relative to this)
├── django/           ← Python Django service
└── docker/           ← Docker orchestration
```

## Architecture Overview

Each module is a self-contained package under `app/Packages/{ModuleName}/` with complete isolation.
**CorePackage** is the shared foundation — it owns the app layout, navigation, and the entire UI component library.

```
app/Packages/
├── CorePackage/                     ← SHARED FOUNDATION (UI + Layout)
│   └── src/
│       ├── Controllers/
│       │   └── BaseController.php
│       ├── Models/
│       │   └── BaseModel.php
│       ├── Providers/
│       │   └── CorePackageServiceProvider.php  ← registers components
│       └── Resources/
│           └── views/
│               ├── layouts/
│               │   └── app.blade.php           ← sidebar layout
│               └── components/
│                   ├── card.blade.php
│                   ├── page-header.blade.php
│                   ├── badge.blade.php
│                   ├── alert.blade.php
│                   ├── btn.blade.php
│                   ├── stats-card.blade.php
│                   └── form/
│                       ├── input.blade.php
│                       ├── select.blade.php
│                       ├── textarea.blade.php
│                       └── section.blade.php
├── StudentManagement/
│   └── src/
│       ├── Controllers/
│       │   ├── StudentController.php (Web)
│       │   └── Api/
│       │       └── StudentApiController.php (API)
│       ├── Models/
│       │   ├── Student.php
│       │   ├── ParentInfo.php
│       │   ├── PreviousEducation.php
│       │   ├── HealthRecord.php
│       │   ├── StudentEnrollment.php
│       │   ├── PromotionHistory.php
│       │   ├── StudentDocument.php
│       │   ├── StudentActivityLog.php
│       │   └── StudentContact.php
│       ├── Views/
│       │   └── student/
│       │       ├── index.blade.php    ← uses x-core-package::*
│       │       ├── create.blade.php   ← uses x-core-package::form.*
│       │       ├── show.blade.php     ← two-column detail layout
│       │       └── edit.blade.php     ← same as create, prefilled
│       ├── Routes/
│       │   ├── web.php
│       │   └── api.php
│       ├── Providers/
│       │   └── StudentManagementServiceProvider.php
│       └── Database/
│           └── migrations/            ← 9 migration files
├── StaffManagement/
├── ClassManagement/
└── ... (more modules)
```

## CorePackage — Shared UI Foundation

**CorePackageServiceProvider** does three things:
1. Loads the layout + component views under namespace `core-package`
2. Registers anonymous Blade components via `anonymousComponentPath` so all modules can use `<x-core-package::card>`, `<x-core-package::btn>`, etc.
3. Provides `BaseController` and `BaseModel` for inheritance

All module views must:
- `@extends('core-package::layouts.app')` — uses the shared sidebar layout
- Use `<x-core-package::*>` components for all UI (see `ui-design-components` skill)

```php
// CorePackageServiceProvider.php
public function boot(): void
{
    $this->loadViewsFrom(__DIR__.'/../Resources/views', 'core-package');

    $this->callAfterResolving(BladeCompiler::class, function (BladeCompiler $blade) {
        $blade->anonymousComponentPath(
            __DIR__.'/../Resources/views/components',
            'core-package'
        );
    });
}
```

## Module Service Provider Pattern

Each package includes a ServiceProvider that:
1. Loads views with namespace prefix: `student-management::student.index`
2. Loads migrations from package directory
3. Registers and groups routes (web & API)
4. Can register other services (gates, policies, events)

### StudentManagementServiceProvider

```php
class StudentManagementServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../Views', 'student-management');
        $this->registerRoutes();
    }

    private function registerRoutes()
    {
        Route::middleware('web')
            ->namespace('App\Packages\StudentManagement\Controllers')
            ->group(__DIR__ . '/../Routes/web.php');

        Route::middleware('api')
            ->prefix('api')
            ->namespace('App\Packages\StudentManagement\Controllers')
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
```

## Registration

Register package providers in `laravel/bootstrap/providers.php`:

```php
return [
    AppServiceProvider::class,
    StudentManagementServiceProvider::class,
    StaffManagementServiceProvider::class,
    ClassManagementServiceProvider::class,
    // ... more providers
];
```

## Routes

Main route files (`laravel/routes/web.php` and `laravel/routes/api.php`) remain clean — packages handle their own routes via ServiceProvider.

## Models

```php
namespace App\Packages\StudentManagement\Models;

class Student extends Model { }
```

## Creating New Laravel Packages

1. Create folder: `app/Packages/{ModuleName}/src/`
2. Create subdirectories: Controllers, Models, Views, Routes, Providers, Database/migrations
3. Create ServiceProvider
4. Register in `laravel/bootstrap/providers.php`
5. Add autoload entry in `laravel/composer.json` under `psr-4`
6. Run `composer dump-autoload` from `laravel/`

## Benefits

✅ **Complete Isolation**: Each module is independent
✅ **Easy to Remove**: Delete package folder to remove feature
✅ **Clear Dependencies**: Package routes/controllers define public API
✅ **Scalability**: Add packages without touching core
✅ **Testing**: Package can be tested in isolation

## Goals

- 100% package-based architecture for all modules
- Zero dependencies between packages (except through models/APIs)
- Independent deployment and testing per package
