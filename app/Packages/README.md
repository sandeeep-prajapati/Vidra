# Package Browser Tests

This folder has a Playwright workspace for browser-testing Laravel packages.

## Commands

```bash
cd /home/sandeep/Desktop/Vidra/app/Packages
npm test
```

Useful variants:

```bash
npm run db:fresh
npm run test:fresh
npm run test:packages
npm run test:packages:docs
npm run test:bundle -- "StudentManagement"
npm run test:docs:attendance
npm run test:docs:timetable
npm run test:attendance:docs
npm run test:timetable:docs
npm run test:headed
npm run test:ui
npm run test:debug
npm run report
```

Every test script starts with a fresh migration and seed. `npm run test:packages`
checks the package screens. `npm run test:packages:docs`,
`npm run test:docs:attendance`, and `npm run test:docs:timetable` record video
and move more slowly for documentation. The older `test:attendance:docs` and
`test:timetable:docs` script names remain as aliases.

To run one bundle with its own fresh migration:

```bash
npm run test:bundle -- "StudentManagement"
npm run test:bundle -- "Pro LibraryManagement"
```

The fresh reset command is:

```bash
npm run db:fresh
```

Chromium runs by default. To run Firefox and WebKit too, install Playwright's
host dependencies first:

```bash
sudo npx playwright install-deps
PLAYWRIGHT_ALL_BROWSERS=1 npm test
```

The Playwright config starts the Laravel app from `/home/sandeep/Desktop/Vidra` with:

```bash
php artisan serve --host=127.0.0.1 --port=8010
```

To use a different port or an already-running app:

```bash
PLAYWRIGHT_PORT=8080 npm test
PLAYWRIGHT_BASE_URL=http://127.0.0.1:8080 npm test
```
