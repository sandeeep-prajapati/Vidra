---
name: project-goals
description: Define and manage project goals, features, and development roadmap for the school management app. Covers both Laravel (PHP) and Django (Python) services in the monorepo.
---

# Project Goals Skill

## Monorepo Structure (Current)

```
school-management-app/
├── laravel/     ← PHP Laravel (all modules)
├── django/      ← Python Django (analytics, AI, reports)
└── docker/      ← Docker orchestration
```

## Completed

### Monorepo Migration ✅
✅ All Laravel files moved to `laravel/` subdirectory
✅ Django service scaffolded in `django/` subdirectory
✅ Docker Compose stack: Nginx + PHP-FPM + Django + Celery + MySQL + Redis
✅ Django modular architecture with DI Container, base Repository, Service, ViewSet layers
✅ Three initial Django apps: `analytics`, `ai_insights`, `reports`

### CorePackage (Shared Laravel UI Foundation) ✅
✅ Sidebar layout (`core-package::layouts.app`) with icon navigation + toast messages
✅ 10 reusable Blade components: card, page-header, badge, alert, btn, stats-card, form.input, form.select, form.textarea, form.section
✅ `anonymousComponentPath` registered — all modules use `<x-core-package::*>`
✅ Design system documented in `ui-design-components` skill

### Student Management Package ✅
✅ Database schema — 9 tables
✅ Eloquent models with relationships (9 models)
✅ Web CRUD controller + all sub-resource actions
✅ REST API controller with JSON responses
✅ All 4 views rebuilt using CorePackage components
✅ Package-based architecture with ServiceProvider

---

## Architecture Approach

### Laravel side — Package-Based
- Each module is a self-contained package under `app/Packages/{ModuleName}/`
- Own controllers, models, views, routes, and service provider
- Service provider registered in `laravel/bootstrap/providers.php`
- See `package-based-architecture` skill for full detail

### Django side — App-Based with DI
- Each feature is a self-contained Django app under `django/apps/{feature}/`
- Apps: models, serializers, views (ViewSet), urls, services, repositories
- DI wired in `apps.py::ready()` using `core.container.Container`
- See `django-module-builder` skill for full detail

### Communication (Laravel ↔ Django)
- PHP calls Django via `PythonBridge::call()` helper (HMAC-authenticated HTTP)
- URL: `PYTHON_SERVICE_URL/api/python/{module}/...`
- See `python-bridge` skill for full detail

---

## Roadmap

### Laravel Modules Remaining
1. **Staff and Teacher Management** — staff, qualifications, attendance, salary
2. **Class and Section Management** — classes, sections, enrollments
3. **Subjects and Curriculum** — subjects, syllabi, teacher-subject mappings
4. **Attendance Management** — attendance, leave_requests
5. **Examination and Grades** — exams, marks, report_cards
6. **Fee and Finance** — fees, payments, discounts, transactions
7. **Timetable and Scheduling** — timetables, schedules, rooms
8. **Communication and Notifications** — messages, notifications, announcements
9. **Role-Based Access Control** — roles, permissions, user_roles
10. **Hostel, Transport, Facilities** — hostels, transport, allocations

### Django Modules Remaining
1. **analytics** — Attendance & exam trend charts (stub built)
2. **reports** — PDF marksheets, fee receipts async (stub built)
3. **ai_insights** — Student performance prediction (stub built)
4. **notifications** — Bulk SMS / WhatsApp
5. **ocr** — Document scanning
6. **data_import** — Bulk CSV import

---

## Development Workflow

### Adding a new Laravel package
1. Create `app/Packages/{ModuleName}/src/` structure
2. Create Models, Controllers, Views, Routes, ServiceProvider
3. Register in `laravel/bootstrap/providers.php`
4. Add `psr-4` entry in `laravel/composer.json`, run `composer dump-autoload`
5. Test CRUD operations

### Adding a new Django app
Use the `django-module-builder` skill — one command scaffold + two registration lines.

---

## Best Practices

- ✅ Monorepo: Laravel in `laravel/`, Django in `django/`, Docker in `docker/`
- ✅ Package-based modular architecture (Laravel)
- ✅ App-based modular architecture with DI (Django)
- ✅ Service provider / AppConfig pattern for auto-loading
- ✅ View namespace isolation (Laravel)
- ✅ Container.singleton() for services (Django)
- ⏳ Write tests — PHPUnit (Laravel) + pytest (Django)
- ⏳ Queue-based async jobs — Laravel Queue + Celery
- ⏳ Auth — Sanctum (Laravel), PHP bridge token (Django)
- ⏳ Full Docker production deployment
