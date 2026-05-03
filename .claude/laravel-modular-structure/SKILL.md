---
name: laravel-modular-structure
description: Create and maintain module-wise folder structure in Laravel for organizing code by features like Student Management, Staff Management, etc. Includes controllers, models, views, routes, and migrations per module.
---

# Laravel Modular Structure Skill

## Package-Based Modular Architecture (RECOMMENDED)

Each module is a self-contained package under `app/Packages/`. See `package-based-architecture` skill for detailed implementation.

### Structure

```
app/Packages/StudentManagement/src/
├── Controllers/
│   ├── StudentController.php (Web)
│   └── Api/
│       └── StudentApiController.php (REST API)
├── Models/
│   ├── Student.php
│   ├── ParentInfo.php
│   ├── PreviousEducation.php
│   └── HealthRecord.php
├── Views/
│   └── student/
│       ├── index.blade.php
│       ├── create.blade.php
│       ├── show.blade.php
│       └── edit.blade.php
├── Routes/
│   ├── web.php
│   └── api.php
├── Providers/
│   └── StudentManagementServiceProvider.php
├── Requests/ (Form Validation)
├── Resources/ (API Resources)
└── Database/
    └── migrations/
```

## Student Management - Implemented ✅

**Package**: `app/Packages/StudentManagement/src/`

- **Controllers**: StudentController (Web), StudentApiController (API)
- **Models**: Student, ParentInfo, PreviousEducation, HealthRecord
- **Views**: index, create, show, edit with Tailwind styling
- **Routes**: Web routes (resource), API routes (apiResource)
- **Service Provider**: StudentManagementServiceProvider registered in `laravel/bootstrap/providers.php`

## Benefits

✅ True modularity - each package is independent
✅ Easy to remove or disable packages
✅ Clear separation of concerns
✅ View namespace isolation (`student-management::`)
✅ Self-contained routing (no main routes file clutter)
✅ Scalable to 20+ modules

## Goals

- Implement all 10 modules as packages
- No dependencies between packages (except models)
- Independent testing and development

## Goals

- Improve code organization and maintainability.
- Allow for easier feature development and testing.
- Scale to handle 10+ modules (Staff, Classes, Subjects, etc.).