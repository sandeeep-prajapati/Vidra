import { expect, test } from '@playwright/test';

const ADMIN_EMAIL = 'admin@school.com';
const ADMIN_PASSWORD = 'admin123';
const EXAM_DATE = '2026-05-20';
const EXAM_START = '2026-05-01';
const EXAM_END = '2026-05-30';

test('Exam Management documentation walkthrough', async ({ page }) => {
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
  await expect(page).toHaveURL(/dashboard|exam/);
  await pause(page, 1800);

  // Exams
  await openPage(page, '/exam', 'Exams');
  await showCaption(page, 'Create a new exam for the academic term');
  await clickAndWait(page, page.getByRole('link', { name: 'Create Exam' }));
  await expect(page.getByRole('heading', { name: 'Create Exam' })).toBeVisible();
  await fillField(page, '#exam_name', 'Term 1 Final Examination');
  await selectFirstOption(page, '#academic_year_id');
  await fillField(page, '#start_date', EXAM_START);
  await fillField(page, '#end_date', EXAM_END);
  await selectField(page, '#is_final', '1');
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('button', { name: 'Create Exam' }));
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  // Grading Schemes
  await openPage(page, '/gradingScheme', 'Grading Schemes');
  await showCaption(page, 'Define a grading scheme for converting marks to grades');
  await clickAndWait(page, page.getByRole('link', { name: 'Add Grade' }));
  await expect(page.getByRole('heading', { name: /Grade/ }).first()).toBeVisible();
  await fillField(page, '#grade', 'A+');
  await fillField(page, '#min_percentage', '90');
  await fillField(page, '#max_percentage', '100');
  await fillField(page, '#remarks', 'Outstanding');
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('button', { name: /Save|Create/ }).first());
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  // Exam Schedules
  await openPage(page, '/examSchedule', 'Exam Schedules');
  await showCaption(page, 'Schedule a subject exam on a specific date and time');
  await clickAndWait(page, page.getByRole('link', { name: 'Add Schedule' }));
  await expect(page.getByRole('heading', { name: /Schedule/ }).first()).toBeVisible();
  await selectFirstOption(page, '#exam_id');
  await selectFirstOption(page, '#class_id');
  await selectFirstOption(page, '#subject_id');
  await fillField(page, '#exam_date', EXAM_DATE);
  await fillField(page, '#start_time', '09:00');
  await fillField(page, '#end_time', '11:00');
  await fillField(page, '#total_marks', '100');
  await fillField(page, '#passing_marks', '35');
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('button', { name: /Save|Create/ }).first());
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  // Student Marks
  await openPage(page, '/studentMark', 'Student Marks');
  await showCaption(page, 'Record marks obtained by a student in a scheduled exam');
  await clickAndWait(page, page.getByRole('link', { name: 'Add Marks' }));
  await expect(page.getByRole('heading', { name: /Mark/ }).first()).toBeVisible();
  await selectStudentFromPicker(page, '');
  await selectLastOption(page, '#schedule_id');
  await fillField(page, '#marks_obtained', '87');
  await fillField(page, '#grade', 'A');
  await fillField(page, '#remarks', 'Very good performance');
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('button', { name: /Save|Create/ }).first());
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  // Student Report Cards
  await openPage(page, '/studentReportCard', 'Report Cards');
  await showCaption(page, 'View generated report cards for students');
  await highlightText(page, 'Report Cards');
  await pause(page, 2000);

  await openPage(page, '/exam', 'Exams');
  await showCaption(page, 'Exam Management walkthrough complete');
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

async function selectStudentFromPicker(page, query) {
  // Highlight the trigger for visual context
  const trigger = page.locator('#student_search');
  await spotlight(trigger);

  // Use JS to reliably open the picker (avoids onfocus/click event inconsistencies)
  await page.evaluate(() => {
    if (typeof window.openStudentPicker === 'function') {
      window.openStudentPicker();
    }
  });

  // Wait for the panel to be visible
  await page.locator('#student-panel.open').waitFor({ state: 'visible', timeout: 5000 });
  await pause(page, 900); // allow auto-fetch (openStudentPicker calls fetchStudents(''))

  // If a specific query is needed, type it to filter
  if (query) {
    const searchBox = page.locator('#student-search-box');
    await searchBox.fill(query);
    await pause(page, 700);
  }

  // Wait for at least one result option
  const firstOption = page.locator('#student-results .ls-option').first();
  await firstOption.waitFor({ state: 'visible', timeout: 12000 });
  await spotlight(firstOption);
  await pause(page, 500);

  // Click the first result to select it
  await firstOption.click();
  await pause(page, 400);
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
