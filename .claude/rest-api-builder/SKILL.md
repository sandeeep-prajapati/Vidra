---
name: rest-api-builder
description: Build RESTful APIs in Laravel with proper routing, controllers, resources, and authentication. Use for mobile apps, frontend AJAX, or third-party integrations.
---

# REST API Builder Skill

## API Endpoints (v1)

### Student Management
- `GET /api/students` - List all students
- `POST /api/students` - Create a new student
- `GET /api/students/{id}` - Get student details
- `PUT /api/students/{id}` - Update student
- `DELETE /api/students/{id}` - Delete student

## Implementation

- API routes in `laravel/routes/api.php`
- API controllers in `app/Http/Controllers/Api/` (or per-package under `app/Packages/{Module}/src/Controllers/Api/`)
- Structured JSON responses
- Proper HTTP status codes
- Error handling with meaningful messages

## Student API Controller

Created StudentApiController with index, store, show, update, destroy methods.
Returns JSON responses with related data (parent info, education history, health records).

## Goals

- Provide clean, versioned APIs.
- Secure access with tokens (Sanctum).
- Support CRUD operations for all modules.
- Enable mobile app and third-party integrations.