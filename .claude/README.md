# School Management App — Project Summary

## Architecture: Package-Based Modularity

Each module is a **self-contained package** under `app/Packages/{ModuleName}/src/` with:
- Own Controllers (Web + API)
- Own Models with relationships
- Own Views (namespaced, extend CorePackage layout)
- Own Routes (web.php + api.php)
- Own ServiceProvider
- Own Database migrations

**CorePackage** is the shared foundation — it owns the sidebar layout and the entire Blade component library used by all modules. See `ui-design-components` skill for the full design system.

---

## Skills Index

| Skill | Description |
|---|---|
| **package-based-architecture** | Package folder structure, CorePackage, ServiceProvider pattern |
| **ui-design-components** | Full design system: tokens, components, page patterns, rules |
| **laravel-modular-structure** | Module folder layout, namespacing, isolation |
| **laravel-db-generator** | Generate migrations/models from DB_Structure markdown |
| **rest-api-builder** | REST API endpoints for each module |
| **ajax-laravel-integration** | AJAX with Axios, CSRF, queues |
| **queue-data-sync** | Background jobs for data syncing |
| **project-goals** | Roadmap, completed tasks, next modules |
| **testing-strategy** | PHPUnit unit, feature, API tests |
| **authentication-authorization** | Sanctum tokens, RBAC (9 roles) |
| **performance-optimization** | Query optimization, caching, indexing |
| **documentation** | API docs, architecture guides |
| **deployment** | Docker, CI/CD, monitoring |

---

## CorePackage UI Components (Available to all modules)

Use prefix `<x-core-package::*>`. Full docs in `ui-design-components` skill.

| Component | Tag |
|---|---|
| Card container | `<x-core-package::card>` |
| Page title + breadcrumb | `<x-core-package::page-header>` |
| Status pill | `<x-core-package::badge>` |
| Success/error/warning banner | `<x-core-package::alert>` |
| Button or link | `<x-core-package::btn>` |
| Metric card | `<x-core-package::stats-card>` |
| Text input + validation | `<x-core-package::form.input>` |
| Select + validation | `<x-core-package::form.select>` |
| Textarea + validation | `<x-core-package::form.textarea>` |
| Form section divider | `<x-core-package::form.section>` |

---

## Student Management Package — COMPLETED ✅

### Models & Migrations (9 tables)
- `students` — core profile, admission, status
- `parents` — father / mother / guardian
- `previous_educations` — past school records (one-to-many)
- `health_records` — height, weight, allergies, vaccination
- `student_enrollments` — batch + academic year enrollment
- `promotion_history` — batch-to-batch promotions
- `student_documents` — file/certificate storage
- `student_activity_logs` — audit trail
- `student_contacts` — extra phone/emergency contacts

### Features Implemented
All 14 features from `DB_Structure/feature_list/01_Student_Management.md`:
- Student profile (name, DOB, gender, blood group, nationality, religion, photo, addresses)
- Admission management (date, admission number, status)
- Status tracking (Active / Inactive / Graduated / Transferred)
- Parent/guardian records (father, mother, guardian full details)
- Multiple previous education records
- Health records (height, weight, allergies, medical conditions, vaccination)
- Student enrollment into batches with academic year
- Promotion history between batches
- Documents storage (10 document types)
- Activity logging (audit trail)
- Multiple contact numbers

### Views (all use CorePackage components)
- `index` — table with avatar initials, stats row, filter, fixed pagination
- `create` — 6-section form with validation errors, dynamic add/remove rows
- `edit` — same as create + prefilled + contacts section (was missing, now fixed)
- `show` — two-column detail + sidebar with 6 action forms

---

## Package Structure Template

```
app/Packages/{ModuleName}/src/
├── Controllers/
│   ├── {Module}Controller.php         Web (Blade views)
│   └── Api/
│       └── {Module}ApiController.php  API (JSON)
├── Models/
│   └── {Model}.php
├── Views/
│   └── {module}/
│       ├── index.blade.php            @extends('core-package::layouts.app')
│       ├── create.blade.php
│       ├── show.blade.php
│       └── edit.blade.php
├── Routes/
│   ├── web.php                        Route::resource(...)
│   └── api.php                        Route::apiResource(...)
├── Providers/
│   └── {Module}ServiceProvider.php
└── Database/
    └── migrations/
```

Register provider in `bootstrap/providers.php`.

---

## Next Modules (Roadmap)

1. **StaffManagement** — staff, qualifications, attendance, salary, performance
2. **ClassManagement** — classes, sections, enrollments, promotions
3. **SubjectManagement** — subjects, syllabi, teacher-subject mappings
4. **AttendanceManagement** — attendance, leave requests, approvals
5. **ExaminationManagement** — exams, marks, report cards, grades
6. **FeeManagement** — fees, payments, discounts, transactions
7. **TimetableManagement** — timetables, schedules, rooms, substitutes
8. **CommunicationManagement** — messages, notifications, announcements
9. **RbacManagement** — roles, permissions, user roles
10. **HostelTransportManagement** — hostel, transport, facilities

---

## Tech Stack

| Layer | Tech |
|---|---|
| Backend | Laravel 11, PHP 8.1+ |
| Database | MySQL 8.0 |
| Frontend | Blade templates, Tailwind CSS 4.0 |
| Build | Vite.js |
| API | RESTful JSON (Sanctum) |
| Testing | PHPUnit |
| Cache | Redis |
| Queue | AWS SQS / Redis |
| Deploy | Docker + Docker Compose |

---

**Last Updated**: April 26, 2026
**Status**: CorePackage UI system complete — all Student Management features implemented — ready for multi-module development
