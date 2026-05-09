import { expect, test } from '@playwright/test';

test('public landing page renders through the Laravel app', async ({ page }) => {
  await page.goto('/');

  await expect(page).toHaveTitle(/Vidra|Laravel|School/i);
  await expect(page.locator('body')).toContainText(/Vidra|School|Login/i);
});

test('package routes are reachable through the app router', async ({ page }) => {
  await page.goto('/bundle-installer');

  await expect(page.locator('body')).toContainText(/Bundle|Installer|Upload/i);
});

test('protected package routes redirect guests to login', async ({ page }) => {
  await page.goto('/reports/setup');

  await expect(page).toHaveURL(/\/login$/);
});
