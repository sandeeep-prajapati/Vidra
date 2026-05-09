#!/usr/bin/env bash
set -euo pipefail

APP_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../../.." && pwd)"

cd "$APP_ROOT"

php artisan migrate:fresh --seed --force
php artisan optimize:clear
