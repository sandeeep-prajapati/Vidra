---
name: django-module-builder
description: Create a new modular Django app in django/apps/{module}/ following the DI-container architecture. Each app mirrors a Laravel package — models, repository, service, serializer, viewset, urls, AppConfig. Use whenever adding a new Python feature to the school management system.
---

# Django Module Builder Skill

## Overview

Each Django module = one folder under `django/apps/`. The structure mirrors Laravel's package system:

| Laravel Package layer   | Django layer              | File              |
|-------------------------|---------------------------|-------------------|
| `ServiceProvider`       | `AppConfig.ready()`       | `apps.py`         |
| `Eloquent Model`        | Django ORM model          | `models.py`       |
| `Repository`            | Repository class          | `repositories.py` |
| `Service class`         | Service class             | `services.py`     |
| `API Resource`          | DRF Serializer            | `serializers.py`  |
| `Controller`            | DRF ViewSet               | `views.py`        |
| `Routes/web.php`        | DRF Router                | `urls.py`         |

---

## Step-by-Step: Adding a New Module

### 1. Create the folder structure

```bash
MODULE=my_feature   # snake_case name
mkdir -p django/apps/$MODULE/migrations
touch django/apps/$MODULE/{__init__,apps,models,repositories,services,serializers,views,urls}.py
touch django/apps/$MODULE/migrations/__init__.py
```

### 2. Write `apps.py` (ServiceProvider equivalent)

```python
# django/apps/my_feature/apps.py
from django.apps import AppConfig

class MyFeatureConfig(AppConfig):
    default_auto_field = "django.db.models.BigAutoField"
    name = "apps.my_feature"
    label = "my_feature"

    def ready(self):
        from core.container import Container
        from .repositories import MyFeatureRepository
        from .services import MyFeatureService

        Container.singleton(MyFeatureRepository)
        Container.singleton(
            MyFeatureService,
            lambda: MyFeatureService(repo=Container.make(MyFeatureRepository)),
        )
```

### 3. Write `models.py`

- Use `managed = False` + `db_table = "laravel_table_name"` for Laravel-owned tables (read-only)
- Use `db_table = "python_my_feature_..."` for Python-owned tables (managed, migratable)

```python
from django.db import models

# Laravel-owned table (read-only from Django)
class Student(models.Model):
    name = models.CharField(max_length=255)
    class_id = models.IntegerField()

    class Meta:
        managed = False
        db_table = "students"

# Python-owned table (writable, Django manages migrations)
class MyFeatureResult(models.Model):
    student_id = models.IntegerField(db_index=True)
    result_value = models.FloatField()
    computed_at = models.DateTimeField(auto_now=True)

    class Meta:
        db_table = "python_my_feature_results"
```

### 4. Write `repositories.py`

```python
from .models import MyFeatureResult

class MyFeatureRepository:
    def find(self, student_id: int):
        return MyFeatureResult.objects.filter(student_id=student_id).first()

    def save(self, student_id: int, value: float) -> MyFeatureResult:
        obj, _ = MyFeatureResult.objects.update_or_create(
            student_id=student_id,
            defaults={"result_value": value},
        )
        return obj
```

### 5. Write `services.py`

```python
from __future__ import annotations
from .repositories import MyFeatureRepository

class MyFeatureService:
    def __init__(self, repo: MyFeatureRepository) -> None:
        self.repo = repo

    def compute(self, student_id: int) -> dict:
        cached = self.repo.find(student_id)
        if cached:
            return {"student_id": student_id, "value": cached.result_value}
        # TODO: actual computation
        value = 42.0
        self.repo.save(student_id, value)
        return {"student_id": student_id, "value": value}
```

### 6. Write `serializers.py`

```python
from rest_framework import serializers

class MyFeatureRequestSerializer(serializers.Serializer):
    student_id = serializers.IntegerField(min_value=1)
```

### 7. Write `views.py`

```python
from rest_framework.decorators import action
from rest_framework.request import Request
from rest_framework.response import Response
from core.base_views import BaseViewSet
from .services import MyFeatureService
from .serializers import MyFeatureRequestSerializer

class MyFeatureViewSet(BaseViewSet):
    service_class = MyFeatureService

    @action(detail=False, methods=["post"], url_path="compute")
    def compute(self, request: Request) -> Response:
        ser = MyFeatureRequestSerializer(data=request.data)
        if not ser.is_valid():
            return self.error("Validation failed", errors=ser.errors)
        data = self.get_service().compute(**ser.validated_data)
        return self.ok(data)
```

### 8. Write `urls.py`

```python
from django.urls import path, include
from rest_framework.routers import DefaultRouter
from .views import MyFeatureViewSet

router = DefaultRouter()
router.register(r"", MyFeatureViewSet, basename="my-feature")

urlpatterns = [path("", include(router.urls))]
```

### 9. Register in two places

**`django/config/settings/base.py`** — add to `INSTALLED_APPS`:
```python
"apps.my_feature",
```

**`django/config/urls.py`** — add one line:
```python
path("api/python/my-feature/", include("apps.my_feature.urls")),
```

### 10. Run migrations

```bash
cd django/
python manage.py makemigrations my_feature
python manage.py migrate
```

---

## DI Container Rules

- **Never** call `MyService()` directly in views — always use `Container.make(MyService)`
- **Never** call `MyRepository()` directly in services — inject via `__init__` (set in `apps.py`)
- `Container.singleton()` = one shared instance per process (like Laravel's singleton binding)
- `Container.bind()` = new instance per call (like Laravel's bind)
- `Container.flush()` = reset (use in tests)

---

## DB Convention

| Source              | Django approach            | Migration?      |
|---------------------|---------------------------|-----------------|
| Laravel table       | `managed=False` model      | Never           |
| Python-owned table  | `managed=True`, `db_table="python_*"` | Yes via `makemigrations` |

Never write directly to Laravel-managed tables from Django — read-only only.

---

## Calling from Laravel

After building the module, call it from PHP via the `PythonBridge` helper:

```php
$result = PythonBridge::call('api/python/my-feature/compute', [
    'student_id' => $student->id,
]);
```

See the `python-bridge` skill for the full helper implementation.
