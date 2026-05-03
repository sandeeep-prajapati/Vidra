---
name: documentation
description: Maintain comprehensive documentation for the school management monorepo (Laravel + Django). Covers API docs, architecture guides, and setup instructions for both services.
---

# Documentation Skill

## API Documentation

### Laravel REST API
- **Tool**: Scribe (`knuckleswtf/scribe`) — auto-generates from docblocks
- Generated docs in `laravel/.scribe/`
- Run: `cd laravel && php artisan scribe:generate`

### Django REST API
- **Tool**: DRF's built-in Browsable API at `/api/python/`
- Auto-generated OpenAPI schema: `python manage.py spectacular --file schema.yml`
- Swagger UI mountable via `drf-spectacular`

## API Endpoints

### Laravel — Student Management
Base URL: `/api/students`
- `GET /api/students` — List students
- `POST /api/students` — Create student
- `GET /api/students/{id}` — Get student
- `PUT /api/students/{id}` — Update student
- `DELETE /api/students/{id}` — Delete student

### Django — Analytics
Base URL: `/api/python/analytics/`
- `POST /api/python/analytics/attendance/monthly-report` — Attendance breakdown
- `POST /api/python/analytics/attendance/student-rate` — Individual rate

### Django — Reports
- `POST /api/python/reports/request` — Queue a report
- `GET /api/python/reports/{job_id}/status` — Poll job status

### Django — AI Insights
- `POST /api/python/ai/insights/predict` — Student performance prediction

## Setup Instructions

### Full Stack (Docker)
```bash
cp laravel/.env.example laravel/.env   # fill values
docker compose -f docker/docker-compose.yml up --build
docker compose ... exec php php artisan migrate
docker compose ... exec django python manage.py migrate
```

### Laravel Only (local)
```bash
cd laravel/
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate && npm run dev
php artisan serve
```

### Django Only (local)
```bash
bash django/scripts/start.sh   # auto-reads laravel/.env for DB config
```

## Architecture Documentation

- **Monorepo layout**: `laravel/` (PHP), `django/` (Python), `docker/` (orchestration)
- **Laravel modules**: each package in `app/Packages/{Module}/src/` — see `package-based-architecture` skill
- **Django modules**: each app in `django/apps/{module}/` — see `django-module-builder` skill
- **Laravel ↔ Django bridge**: HMAC-signed HTTP — see `python-bridge` skill
- **DB convention**: Django reads Laravel tables (managed=False), writes only `python_*` tables

## Code Comments

- Only add comments when the WHY is non-obvious
- PHPDoc on public methods in service classes
- Python docstrings on service methods that have non-obvious side effects

## Goals

- 100% API endpoint documentation for both services
- One-command setup for new developers (`docker compose up`)
- Architecture decision records in `DB_Structure/future_plan/`
