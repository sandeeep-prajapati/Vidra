import { expect, test } from '@playwright/test';

const ADMIN_EMAIL = 'admin@school.com';
const ADMIN_PASSWORD = 'admin123';
const EVENT_DATE = '2026-05-08';
const DOC_STAMP = Date.now().toString(36).slice(-6);
const DOC_ROOM_NAME = `Documentation Lab ${DOC_STAMP}`;
const DOC_DAY_NAME = `Doc Day ${DOC_STAMP}`;

test('Timetable Management documentation walkthrough', async ({ page }) => {
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
  await expect(page).toHaveURL(/dashboard|timetable/);

  await openPage(page, '/timetable', 'Timetable');
  await showCaption(page, 'Open the seeded Class 1 section timetable');
  await page.goto('/timetable?class_id=1&section_id=1&academic_year_id=1');
  await expect(page.getByRole('heading', { name: 'Timetable' }).first()).toBeVisible();
  await pause(page, 1400);
  await highlightText(page, 'Period / Day');
  await highlightText(page, 'Mathematics');
  await pause(page, 1800);

  await openPage(page, '/room', 'Rooms');
  await showCaption(page, 'Create a room for scheduling classes and events');
  await clickAndWait(page, page.getByRole('link', { name: 'Add Room' }));
  await expect(page.getByRole('heading', { name: 'Add Room' })).toBeVisible();
  await fillField(page, '#room_name', DOC_ROOM_NAME);
  await selectField(page, '#room_type', 'Lab');
  await fillField(page, '#capacity', '32');
  await fillField(page, '#description', 'Room added during the Timetable documentation walkthrough');
  await clickAndWait(page, page.getByRole('button', { name: 'Save Room' }));
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1400);

  await openPage(page, '/period', 'Periods');
  await showCaption(page, 'Add a new teaching period');
  await clickAndWait(page, page.getByRole('link', { name: 'Add Period' }));
  await expect(page.getByRole('heading', { name: 'Add Period' })).toBeVisible();
  await fillField(page, '#start_time', '15:00');
  await fillField(page, '#end_time', '15:45');
  await clickAndWait(page, page.getByRole('button', { name: 'Save Period' }));
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1400);

  await openPage(page, '/day', 'Days');
  await showCaption(page, 'Add a custom timetable day');
  await clickAndWait(page, page.getByRole('link', { name: 'Add Day' }));
  await expect(page.getByRole('heading', { name: 'Add Day' })).toBeVisible();
  await fillField(page, '#day_name', DOC_DAY_NAME);
  await clickAndWait(page, page.getByRole('button', { name: 'Save Day' }));
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1400);

  await openPage(page, '/timetable/create', 'Add Timetable Entry');
  await showCaption(page, 'Schedule a class into the timetable grid');
  await selectField(page, '#class_id', '1');
  await selectField(page, '#section_id', '1');
  await selectField(page, '#academic_year_id', '1');
  await selectLastOption(page, '#day_id');
  await selectLastOption(page, '#period_id');
  await selectFirstOption(page, '#subject_id');
  await selectFirstOption(page, '#teacher_id');
  await selectLastOption(page, '#room_id');
  await clickAndWait(page, page.getByRole('button', { name: 'Save Entry' }));
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  await openPage(page, '/substituteAssignment', 'Substitute Assignments');
  await showCaption(page, 'Record teacher substitutions against timetable slots');
  await clickAndWait(page, page.getByRole('link', { name: 'Add Substitution' }));
  await expect(page.getByRole('heading', { name: 'Add Substitute Assignment' })).toBeVisible();
  await selectLastOption(page, '#timetable_id');
  await selectField(page, '#original_teacher_id', '1');
  await selectField(page, '#substitute_teacher_id', '2');
  await fillField(page, '#date_of_substitution', EVENT_DATE);
  await clickAndWait(page, page.getByRole('button', { name: 'Save Substitution' }));
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  await openPage(page, '/specialEvent', 'Special Events');
  await showCaption(page, 'Create a calendar event linked to a school space');
  await clickAndWait(page, page.getByRole('link', { name: 'Add Event' }));
  await expect(page.getByRole('heading', { name: 'Add Special Event' })).toBeVisible();
  await fillField(page, '#event_name', 'Documentation Planning Meeting');
  await fillField(page, '#event_date', EVENT_DATE);
  await fillField(page, '#start_time', '10:00');
  await fillField(page, '#end_time', '11:30');
  await selectLastOption(page, '#room_id');
  await fillField(page, '#description', 'Event created for the Timetable Management documentation video');
  await clickAndWait(page, page.getByRole('button', { name: 'Save Event' }));
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  await openPage(page, '/timetable?class_id=1&section_id=1&academic_year_id=1', 'Timetable');
  await showCaption(page, 'Timetable Management walkthrough complete');
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
        maxWidth: '560px',
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
