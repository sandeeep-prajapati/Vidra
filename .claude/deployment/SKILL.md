---
name: deployment
description: Deploy the monorepo school management app (Laravel + Django) using Docker Compose, Nginx, and CI/CD. Covers local dev, Docker stack, and production setup for both services.
---

# Deployment Skill

## Monorepo Structure

```
school-management-app/
├── laravel/          ← PHP Laravel (PHP-FPM)
├── django/           ← Python Django (Gunicorn)
└── docker/
    ├── docker-compose.yml
    ├── nginx/default.conf
    └── php/Dockerfile
```

## Local Development (Without Docker)

### Laravel

```bash
cd laravel/
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install && npm run dev
php artisan serve        # http://localhost:8000
```

### Django

```bash
bash django/scripts/start.sh   # reads DB creds from laravel/.env automatically
# http://localhost:8001
```

## Docker Stack (Recommended)

All services defined in `docker/docker-compose.yml`:

| Service        | Image / Build           | Port  | Role                      |
|----------------|------------------------|-------|---------------------------|
| `nginx`        | nginx:1.27-alpine       | 80    | Reverse proxy entry point |
| `php`          | docker/php/Dockerfile   | 9000  | Laravel PHP-FPM           |
| `django`       | django/Dockerfile       | 8001  | Django Gunicorn           |
| `celery`       | django/Dockerfile       | —     | Async Django workers      |
| `mysql`        | mysql:8.0               | 3306  | Shared database           |
| `redis`        | redis:7-alpine          | 6379  | Cache + queue             |

### Start Full Stack

```bash
# From project root
cp laravel/.env.example laravel/.env   # fill DB/APP_KEY values
docker compose -f docker/docker-compose.yml up --build

# Run Laravel migrations
docker compose -f docker/docker-compose.yml exec php php artisan migrate

# Run Django migrations
docker compose -f docker/docker-compose.yml exec django python manage.py migrate
```

### Nginx Routing

- `/*`             → Laravel PHP-FPM (port 9000)
- `/api/python/*`  → Django Gunicorn (port 8001)
- `/health/django` → Django health check

Config: `docker/nginx/default.conf`

## Environment Variables

### Laravel (`laravel/.env`)

```env
APP_KEY=base64:...
DB_HOST=mysql
DB_DATABASE=school_db
DB_USERNAME=app
DB_PASSWORD=secret
REDIS_HOST=redis
PYTHON_SERVICE_URL=http://django:8001   ← points to Django container
```

### Django (`django/.env`)

```env
DJANGO_SETTINGS_MODULE=config.settings.production
DJANGO_SECRET_KEY=change-me
DB_HOST=mysql
DB_NAME=school_db
DB_USER=app
DB_PASS=secret
REDIS_URL=redis://redis:6379/1
PHP_APP_SECRET=base64:...              ← same as Laravel APP_KEY
```

## Production Deployment Steps

1. Clone repo and `cd school-management-app/`
2. Fill `laravel/.env` and `django/.env`
3. `docker compose -f docker/docker-compose.yml up -d --build`
4. `docker compose ... exec php php artisan migrate --force`
5. `docker compose ... exec php php artisan config:cache`
6. `docker compose ... exec django python manage.py migrate`
7. `docker compose ... exec django python manage.py collectstatic --noinput`

## CI/CD (GitHub Actions)

```yaml
jobs:
  deploy:
    steps:
      - uses: actions/checkout@v4
      - name: Build & push images
        run: docker compose -f docker/docker-compose.yml build
      - name: Run Laravel tests
        run: docker compose ... exec php php artisan test
      - name: Run Django tests
        run: docker compose ... exec django python manage.py test
```

## Monitoring

- Uptime: Pingdom or UptimeRobot
- Error tracking: Sentry (both PHP and Python SDKs)
- Performance: New Relic or Datadog
- Logs: `docker compose logs -f php` / `docker compose logs -f django`

## Goals

- Zero-downtime deployments
- Single `docker compose up` to start the full stack
- 99.9% uptime
