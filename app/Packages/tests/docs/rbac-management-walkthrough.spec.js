import { expect, test } from '@playwright/test';

const ADMIN_EMAIL = 'admin@school.com';
const ADMIN_PASSWORD = 'admin123';
const STAMP = Date.now().toString(36).slice(-6);

test('RBAC Management documentation walkthrough', async ({ page }) => {
  test.setTimeout(720_000);

  await page.setViewportSize({ width: 1920, height: 1080 });
  page.setDefaultTimeout(10000);
  page.setDefaultNavigationTimeout(15000);

  await showCaption(page, 'Sign in with the school administrator account');
  await page.goto('/login');
  await expect(page.getByRole('heading', { name: 'Welcome back' })).toBeVisible();
  await fillField(page, '#email', ADMIN_EMAIL);
  await fillField(page, '#password', ADMIN_PASSWORD);
  await clickAndWait(page, page.getByRole('button', { name: 'Sign in' }));
  await expect(page).toHaveURL(/dashboard|roles/);
  await pause(page, 1800);

  // Roles
  await openPage(page, '/roles', 'Roles');
  await showCaption(page, 'Create a custom role to group permissions for a user type');
  await clickAndWait(page, page.getByRole('link', { name: 'New Role' }));
  await expect(page.getByRole('heading', { name: /Role/ }).first()).toBeVisible();
  await fillField(page, '#name', `doc-role-${STAMP}`);
  await fillField(page, '#description', 'Role created for the RBAC documentation walkthrough');
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('button', { name: 'Create Role' }));
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  // Permissions
  await openPage(page, '/permissions', 'Permissions');
  await showCaption(page, 'Define a fine-grained permission for a specific action');
  await clickAndWait(page, page.getByRole('link', { name: 'New Permission' }));
  await expect(page.getByRole('heading', { name: /Permission/ }).first()).toBeVisible();
  await fillField(page, '#name', `view-doc-${STAMP}`);
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('button', { name: 'Create Permission' }));
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  // Role-Permission Matrix
  await openPage(page, '/role-permissions', 'Role-Permission Matrix');
  await showCaption(page, 'Review and toggle which permissions are assigned to each role');
  await highlightText(page, 'Role-Permission Matrix');
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('link', { name: /Admin|Edit/ }).first());
  await expect(page.getByRole('heading', { name: /Role|Permission/ }).first()).toBeVisible();
  await pause(page, 2000);

  // User Roles
  await openPage(page, '/user-roles', 'User Role Assignment');
  await showCaption(page, 'Assign a role to a user to grant them a set of permissions');
  await highlightText(page, 'User Role Assignment');
  await pause(page, 2000);

  // Create User
  await showCaption(page, 'Create a new user account in the system');
  await clickAndWait(page, page.getByRole('link', { name: '+ Create User' }));
  await expect(page.getByRole('heading', { name: /User/ }).first()).toBeVisible();
  await fillField(page, '#name', `DocUser ${STAMP}`);
  await fillField(page, '#email', `docuser.${STAMP}@school.example`);
  await fillField(page, '#password', 'SecurePass@123');
  await fillField(page, '#password_confirmation', 'SecurePass@123');
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('button', { name: /Create|Save/ }).first());
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  await openPage(page, '/roles', 'Roles');
  await showCaption(page, 'RBAC Management walkthrough complete');
  await pause(page, 2500);
});

async function openPage(page, path, title) {
  await showCaption(page, title);
  await page.goto(path);
  await expect(page.getByRole('heading', { name: title }).first()).toBeVisible();
  await pause(page, 1400);
}

async function fillField(page, selector, value) {
  const locator = page.locator(selector);
  await spotlight(locator);
  await locator.fill(value);
  await pause(page, 650);
}

async function clickAndWait(page, locator) {
  await spotlight(locator);
  await locator.click({ timeout: 30000 });
  await page.waitForLoadState('networkidle').catch(() => {});
  await pause(page, 200);
}

async function highlightText(page, text) {
  const locator = page.getByText(text, { exact: false }).first();
  await spotlight(locator);
}

async function spotlight(locator) {
  await locator.scrollIntoViewIfNeeded();
  await locator.evaluate((element) => {
    element.style.outline = '4px solid rgba(79, 70, 229, .85)';
    element.style.outlineOffset = '4px';
    element.style.transition = 'outline-color .2s ease';
  });
}

async function showCaption(page, text) {
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
        maxWidth: '520px',
        padding: '14px 18px',
        borderRadius: '10px',
        background: 'rgba(15, 23, 42, .92)',
        color: '#fff',
        fontFamily: 'Inter, ui-sans-serif, system-ui, sans-serif',
        fontSize: '16px',
        fontWeight: '700',
        lineHeight: '1.45',
        boxShadow: '0 18px 40px rgba(15, 23, 42, .28)',
      });
      document.body.appendChild(caption);
    }
    caption.textContent = captionText;
  }, text);
  if (process.env.PLAYWRIGHT_DOCS_NARRATE) {
    await page.evaluate(async (captionText) => {
      await new Promise((resolve) => {
        if (!('speechSynthesis' in window)) { resolve(); return; }
        window.speechSynthesis.cancel();
        const utter = new SpeechSynthesisUtterance(captionText);
        utter.rate = 0.88;
        utter.pitch = 1.0;
        utter.volume = 1.0;
        utter.onend = resolve;
        utter.onerror = resolve;
        window.speechSynthesis.speak(utter);
      });
    }, text);
  }
  await pause(page, 900);
}

async function pause(page, ms) {
  await page.waitForTimeout(ms);
}
