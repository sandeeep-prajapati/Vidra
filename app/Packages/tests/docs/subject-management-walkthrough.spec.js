import { expect, test } from '@playwright/test';

const ADMIN_EMAIL = 'admin@school.com';
const ADMIN_PASSWORD = 'admin123';
const STAMP = Date.now().toString(36).slice(-6);

test('Subject Management documentation walkthrough', async ({ page }) => {
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
  await expect(page).toHaveURL(/dashboard|subjects/);
  await pause(page, 1800);

  // Subjects
  await openPage(page, '/subjects', 'Subjects');
  await showCaption(page, 'Create a new subject in the curriculum catalogue');
  await clickAndWait(page, page.getByRole('link', { name: 'Add Subject' }));
  await expect(page.getByRole('heading', { name: /Subject/ }).first()).toBeVisible();
  await fillField(page, '#subject_name', `Documentation Science ${STAMP}`);
  await fillField(page, '#subject_code', `SCI-${STAMP}`);
  await selectField(page, '#subject_type', 'Theory');
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('button', { name: 'Create Subject' }));
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  // Class-Subject Assignments
  await openPage(page, '/class-subjects', 'Class-Subject Assignments');
  await showCaption(page, 'Assign a subject to a class for the current year');
  await clickAndWait(page, page.getByRole('link', { name: 'Assign Subject' }));
  await expect(page.getByRole('heading', { name: /Assign|Class-Subject/ }).first()).toBeVisible();
  await selectFirstOption(page, '#class_id');
  await selectLastOption(page, '#subject_id');
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('button', { name: 'Assign Subject' }));
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  // Curriculum
  await openPage(page, '/curriculums', 'Curriculum');
  await showCaption(page, 'Define a curriculum linking subject to an academic year');
  await clickAndWait(page, page.getByRole('link', { name: 'Add Curriculum' }));
  await expect(page.getByRole('heading', { name: /Curriculum/ }).first()).toBeVisible();
  await selectLastOption(page, '#subject_id');
  await selectFirstOption(page, '#academic_year_id');
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('button', { name: 'Create Curriculum' }));
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  // Textbooks
  await openPage(page, '/textbooks', 'Textbooks');
  await showCaption(page, 'Add a textbook linked to a subject');
  await clickAndWait(page, page.getByRole('link', { name: 'Add Textbook' }));
  await expect(page.getByRole('heading', { name: /Textbook/ }).first()).toBeVisible();
  await selectFirstOption(page, '#subject_id');
  await fillField(page, '#title', `Documentation Textbook ${STAMP}`);
  await fillField(page, '#author', 'Doc Author');
  await fillField(page, '#publisher', 'Doc Press');
  await fillField(page, '#edition', '1st Edition');
  await fillField(page, '#isbn', `978-${STAMP}`);
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('button', { name: 'Add Textbook' }));
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  // Teacher-Subject Mappings
  await openPage(page, '/teacher-subject-mappings', 'Teacher-Subject Mappings');
  await showCaption(page, 'Map a teacher to a subject they are qualified to teach');
  await clickAndWait(page, page.getByRole('link', { name: 'Assign Teacher' }));
  await expect(page.getByRole('heading', { name: /Assign Teacher/ }).first()).toBeVisible();
  await selectFirstOption(page, '#teacher_id');
  await selectLastOption(page, '#subject_id');
  await selectFirstOption(page, '#class_id');
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('button', { name: /Save|Create|Assign/ }).first());
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  await openPage(page, '/subjects', 'Subjects');
  await showCaption(page, 'Subject Management walkthrough complete');
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

async function selectLastOption(page, selector) {
  const locator = page.locator(selector);
  await spotlight(locator);
  const value = await locator.evaluate((select) => {
    const options = Array.from(select.options).filter((item) => item.value);
    return options.at(-1)?.value ?? '';
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
