---
name: python-bridge
description: Call Django Python services from Laravel PHP using the PythonBridge helper. Covers the HMAC authentication handshake, sync HTTP calls, async queue pattern, and how to add the helper to the CorePackage. Use whenever a Laravel controller or service needs to call a Django endpoint.
---

# Python Bridge Skill

## Overview

Laravel calls Django via a simple PHP helper class. Django verifies every request using an HMAC token derived from Laravel's `APP_KEY`.

```
Laravel Controller
    └── PythonBridge::call('api/python/analytics/...', [...])
            └── HTTP POST → Django (port 8001)
                    └── PhpBridgeAuthentication verifies X-Php-Token header
                            └── ViewSet → Service → Repository → response
```

---

## PHP Side — Helper Class

**File**: `app/Packages/CorePackage/src/Helpers/PythonBridge.php`

```php
<?php

namespace App\Packages\CorePackage\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PythonBridge
{
    // ------------------------------------------------------------------
    // Synchronous call — waits for response
    // ------------------------------------------------------------------

    public static function call(
        string $endpoint,
        array  $data   = [],
        string $method = 'post',
        int    $timeout = 30
    ): array {
        $url = rtrim(config('services.python.url'), '/') . '/' . ltrim($endpoint, '/');

        try {
            $response = Http::withHeaders(['X-Php-Token' => self::token()])
                ->timeout($timeout)
                ->{$method}($url, $data);

            if ($response->failed()) {
                Log::error('PythonBridge error', [
                    'endpoint' => $endpoint,
                    'status'   => $response->status(),
                    'body'     => $response->body(),
                ]);
                return ['success' => false, 'message' => 'Python service error', 'data' => null];
            }

            return $response->json() ?? ['success' => false, 'message' => 'Empty response'];

        } catch (\Exception $e) {
            Log::error('PythonBridge exception', ['endpoint' => $endpoint, 'error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Python service unavailable', 'data' => null];
        }
    }

    // ------------------------------------------------------------------
    // GET shorthand
    // ------------------------------------------------------------------

    public static function get(string $endpoint, array $query = []): array
    {
        $url = rtrim(config('services.python.url'), '/') . '/' . ltrim($endpoint, '/');

        try {
            $response = Http::withHeaders(['X-Php-Token' => self::token()])
                ->timeout(15)
                ->get($url, $query);

            return $response->json() ?? ['success' => false];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Python service unavailable'];
        }
    }

    // ------------------------------------------------------------------
    // Async queue — fire-and-forget, returns job_id
    // ------------------------------------------------------------------

    public static function queue(string $job, array $payload = []): ?int
    {
        $result = self::call('api/python/queue/push', ['job' => $job, 'payload' => $payload]);
        return $result['data']['job_id'] ?? null;
    }

    public static function jobStatus(int $jobId): array
    {
        return self::get("api/python/queue/status/{$jobId}");
    }

    // ------------------------------------------------------------------
    // Token — shared secret via HMAC on APP_KEY
    // ------------------------------------------------------------------

    private static function token(): string
    {
        return hash_hmac('sha256', 'python-bridge', config('app.key'));
    }
}
```

---

## Laravel Config

**`laravel/config/services.php`** — add:

```php
'python' => [
    'url' => env('PYTHON_SERVICE_URL', 'http://localhost:8001'),
],
```

**`laravel/.env`** — add:
```env
PYTHON_SERVICE_URL=http://django:8001      # Docker
# PYTHON_SERVICE_URL=http://localhost:8001 # Local dev
```

---

## Usage in Laravel Controllers

```php
use App\Packages\CorePackage\Helpers\PythonBridge;

// Attendance analytics
$result = PythonBridge::call('api/python/analytics/attendance/monthly-report', [
    'class_id' => $classId,
    'month'    => '2026-04',
]);

if ($result['success']) {
    $chartData = $result['data'];
}

// Queue a PDF report (async)
$jobId = PythonBridge::queue('report:marksheet', ['student_id' => $student->id]);
session(['report_job_id' => $jobId]);

// Poll job status
$status = PythonBridge::jobStatus($jobId);
// $status['data']['status'] = 'pending' | 'done' | 'failed'
```

---

## Django Side — Auth Verification

Django's `PhpBridgeAuthentication` (in `django/core/authentication.py`) validates the token automatically on every request — no code needed in individual ViewSets.

The token formula must match on both sides:
- **PHP**: `hash_hmac('sha256', 'python-bridge', config('app.key'))`
- **Python**: `hmac.new(PHP_APP_SECRET.encode(), b"python-bridge", hashlib.sha256).hexdigest()`

`PHP_APP_SECRET` in `django/.env` must equal Laravel's `APP_KEY`.

---

## Graceful Degradation

`PythonBridge::call()` never throws — if Django is down, it returns:
```php
['success' => false, 'message' => 'Python service unavailable', 'data' => null]
```

Always check `$result['success']` before using data:

```php
$result = PythonBridge::call('api/python/ai/insights/predict', [...]);

$prediction = $result['success']
    ? $result['data']
    : null;   // gracefully degrade — show N/A in UI
```

---

## Response Shape (Django → PHP)

All Django ViewSets inherit from `BaseViewSet` and return this consistent shape:

```json
{
    "success": true,
    "message": "Success",
    "data": { ... }
}
```

Or on error:
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": { "class_id": ["This field is required."] }
}
```

---

## Endpoint Reference

| Django module     | Laravel call                                               |
|-------------------|------------------------------------------------------------|
| Analytics         | `PythonBridge::call('api/python/analytics/attendance/monthly-report', [...])`  |
| AI Insights       | `PythonBridge::call('api/python/ai/insights/predict', [...])`                 |
| Reports           | `PythonBridge::call('api/python/reports/request', [...])`                     |
| Report status     | `PythonBridge::get('api/python/reports/{id}/status')`                         |
