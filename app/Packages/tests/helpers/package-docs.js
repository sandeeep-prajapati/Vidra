import { expect } from '@playwright/test';

export const ADMIN_EMAIL = 'admin@school.com';
export const ADMIN_PASSWORD = 'admin123';

export async function loginAsAdmin(page) {
  await page.goto('/login');
  await expect(page.getByRole('heading', { name: 'Welcome back' })).toBeVisible();
  await page.locator('#email').fill(ADMIN_EMAIL);
  await page.locator('#password').fill(ADMIN_PASSWORD);
  await page.getByRole('button', { name: 'Sign in' }).click();
  await page.waitForLoadState('networkidle').catch(() => {});
  await expect(page.locator('body')).toContainText(/admin@school.com|Dashboard|Install Bundle/i);
}

export async function visitPackageRoute(page, route) {
  await showCaption(page, route.title);
  const response = await page.goto(route.path, { waitUntil: 'domcontentloaded' });
  await page.waitForLoadState('networkidle').catch(() => {});

  const status = response?.status() ?? 200;
  expect(status, `${route.title} returned HTTP ${status}`).toBeLessThan(500);

  await expect(page.locator('body')).not.toContainText(/Server Error|SQLSTATE|Exception|Stack trace/i);

  if (route.expect) {
    await expect(page.locator('body')).toContainText(route.expect);
  } else {
    await expect(page.locator('body')).toContainText(route.title);
  }

  await highlightMainHeading(page);
  await page.waitForTimeout(route.pause ?? 450);
}

export async function showCaption(page, text) {
  await page.evaluate((captionText) => {
    let caption = document.querySelector('[data-playwright-doc-caption]');
    if (!caption) {
      caption = document.createElement('div');
      caption.setAttribute('data-playwright-doc-caption', 'true');
      Object.assign(caption.style, {
        position: 'fixed',
        left: '24px',
        bottom: '24px',
        zIndex: '99999',
        maxWidth: '560px',
        padding: '12px 16px',
        borderRadius: '10px',
        background: 'rgba(15, 23, 42, .92)',
        color: '#fff',
        fontFamily: 'Inter, ui-sans-serif, system-ui, sans-serif',
        fontSize: '15px',
        fontWeight: '700',
        lineHeight: '1.45',
        boxShadow: '0 18px 40px rgba(15, 23, 42, .28)',
      });
      document.body.appendChild(caption);
    }
    caption.textContent = captionText;
  }, text);
}

async function highlightMainHeading(page) {
  const heading = page.locator('h1').first();
  if (!(await heading.count())) {
    return;
  }

  await heading.evaluate((element) => {
    element.style.outline = '4px solid rgba(79, 70, 229, .85)';
    element.style.outlineOffset = '4px';
  });
}
