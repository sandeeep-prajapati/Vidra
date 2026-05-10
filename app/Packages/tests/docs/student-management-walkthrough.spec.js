import { expect, test } from '@playwright/test';

const ADMIN_EMAIL = 'admin@school.com';
const ADMIN_PASSWORD = 'admin123';
const STAMP = Date.now().toString(36).slice(-6);
const DOB = '2012-06-15';
const ADMISSION_DATE = '2026-04-01';

test('Student Management documentation walkthrough', async ({ page }) => {
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
  await expect(page).toHaveURL(/dashboard|students/);
  await pause(page, 1800);

  // Students list
  await showCaption(page, 'Students');
  await page.goto('/students');
  await expect(page.getByText('Students').first()).toBeVisible();
  await pause(page, 1600);
  await highlightText(page, 'Students');

  // Filter demo
  await showCaption(page, 'Filter students by status or search by name');
  await selectField(page, 'select[name="status"]', 'Active');
  await clickAndWait(page, page.getByRole('button', { name: 'Filter' }));
  await pause(page, 1200);
  await clickAndWait(page, page.getByRole('link', { name: 'Reset' }));

  // Create student
  await showCaption(page, 'Register a new student — fill in personal details');
  await clickAndWait(page, page.getByRole('link', { name: 'Add Student' }));
  await expect(page.getByText('Add Student').first()).toBeVisible();
  await pause(page, 1200);

  // Personal info
  await fillField(page, '#first_name', `Doc${STAMP}`);
  await fillField(page, '#last_name', 'Student');
  await fillField(page, '#date_of_birth', DOB);
  await selectField(page, '#gender', 'Male');
  await fillField(page, '#phone_number', '9876543210');
  await fillField(page, '#email', `doc.${STAMP}@school.example`);
  await fillField(page, '#nationality', 'Indian');
  await fillField(page, '#current_address', '12 Documentation Lane, Test City');
  await fillField(page, '#permanent_address', '12 Documentation Lane, Test City');
  await pause(page, 1800);

  // Admission info
  await showCaption(page, 'Fill in admission details for the student record');
  await fillField(page, '#admission_number', `ADM-${STAMP}`);
  await fillField(page, '#date_of_admission', ADMISSION_DATE);
  await pause(page, 1800);

  // Parent info
  await showCaption(page, 'Add parent and guardian contact information');
  await fillField(page, '#father_name', 'Rajesh Student');
  await fillField(page, '#father_phone', '9876500001');
  await fillField(page, '#mother_name', 'Sunita Student');
  await fillField(page, '#mother_phone', '9876500002');
  await pause(page, 2000);

  await clickAndWait(page, page.getByRole('button', { name: 'Save Student' }));
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 2000);

  // Student list with the new record
  await showCaption(page, 'Student Management walkthrough complete');
  await page.goto('/students');
  await expect(page.getByText('Students').first()).toBeVisible();
  await pause(page, 2500);
});

async function fillField(page, selector, value) {
  const locator = page.locator(selector);
  await spotlight(locator);
  await locator.fill(value);
  await pause(page, 650);
}

async function selectField(page, selector, value) {
  const locator = page.locator(selector);
  await spotlight(locator);
  await locator.selectOption(value);
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
