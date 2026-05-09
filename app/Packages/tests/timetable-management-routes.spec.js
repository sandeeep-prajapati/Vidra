import { test } from '@playwright/test';
import { loginAsAdmin, visitPackageRoute } from './helpers/package-docs.js';
import { timetableRoutes } from './helpers/timetable-routes.js';

test.describe.configure({ mode: 'serial' });

test.describe('TimetableManagement routes', () => {
  test.beforeEach(async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 900 });
    await loginAsAdmin(page);
  });

  for (const route of timetableRoutes) {
    test(route.title, async ({ page }) => {
      await visitPackageRoute(page, route);
    });
  }
});
