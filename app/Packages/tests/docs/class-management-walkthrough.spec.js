import { expect, test } from '@playwright/test';

const ADMIN_EMAIL = 'admin@school.com';
const ADMIN_PASSWORD = 'admin123';
const STAMP = Date.now().toString(36).slice(-6);
const DOC_CLASS_NAME = `Grade ${STAMP}`;
const DOC_SECTION_NAME = `Sec-${STAMP.slice(-4)}`;
const START_DATE = '2026-04-01';
const END_DATE = '2027-03-31';

test('Class Management documentation walkthrough', async ({ page }) => {
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
  await expect(page).toHaveURL(/dashboard|classes/);
  await pause(page, 1800);

  // Academic Years
  await openPage(page, '/academic-years', 'Academic Years');
  await showCaption(page, 'Create a new academic year for the school calendar');
  await clickAndWait(page, page.getByRole('link', { name: 'Add Academic Year' }));
  await expect(page.getByRole('heading', { name: /Academic Year/ }).first()).toBeVisible();
  await fillField(page, '#year_range', `${STAMP} Batch`);
  await fillField(page, '#start_date', START_DATE);
  await fillField(page, '#end_date', END_DATE);
  await clickAndWait(page, page.getByRole('button', { name: 'Create Academic Year' }));
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  // Classes
  await openPage(page, '/classes', 'Classes');
  await showCaption(page, 'Create a new class to organise students by grade level');
  await clickAndWait(page, page.getByRole('link', { name: 'Add Class' }));
  await expect(page.getByRole('heading', { name: /Class/ }).first()).toBeVisible();
  await fillField(page, '#class_name', DOC_CLASS_NAME);
  await fillField(page, '#class_code', `CLS-${STAMP}`);
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('button', { name: 'Create Class' }));
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  // Sections
  await openPage(page, '/sections', 'Sections');
  await showCaption(page, 'Add a section to divide a class into smaller groups');
  await clickAndWait(page, page.getByRole('link', { name: 'Add Section' }));
  await expect(page.getByRole('heading', { name: /Section/ }).first()).toBeVisible();
  await fillField(page, '#section_name', DOC_SECTION_NAME);
  await fillField(page, '#capacity', '40');
  await selectFirstOption(page, '#class_id');
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('button', { name: 'Create Section' }));
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  // Batches
  await openPage(page, '/batches', 'Batches');
  await showCaption(page, 'Create a batch linking a class, section and academic year');
  await clickAndWait(page, page.getByRole('link', { name: 'Add Batch' }));
  await expect(page.getByRole('heading', { name: /Batch/ }).first()).toBeVisible();
  await fillField(page, '#batch_name', `${STAMP} Batch`);
  await selectFirstOption(page, '#class_id');
  await pause(page, 1200);
  await selectFirstOption(page, '#section_id');
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('button', { name: 'Create Batch' }));
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  await openPage(page, '/classes', 'Classes');
  await showCaption(page, 'Class Management walkthrough complete');
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

async function selectField(page, selector, value) {
  const locator = page.locator(selector);
  await spotlight(locator);
  await locator.selectOption(value);
  await pause(page, 650);
}

async function selectFirstOption(page, selector) {
  const locator = page.locator(selector);
  await spotlight(locator);
  const value = await locator.evaluate((select) => {
    const option = Array.from(select.options).find((item) => item.value);
    return option?.value ?? '';
  });
  await locator.selectOption(value);
  await pause(page, 650);
}

async function clickAndWait(page, locator) {
  await spotlight(locator);
  await locator.click({ timeout: 30000 });
  await page.waitForLoadState('networkidle').catch(() => {});
  await pause(page, 200);
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
