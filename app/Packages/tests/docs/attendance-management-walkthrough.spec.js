import { expect, test } from '@playwright/test';

const ADMIN_EMAIL = 'admin@school.com';
const ADMIN_PASSWORD = 'admin123';
const DEMO_DATE = '2026-05-08';
const LEAVE_END_DATE = '2026-05-10';

test('Attendance Management documentation walkthrough', async ({ page }) => {
  test.setTimeout(480_000);

  await page.setViewportSize({ width: 1440, height: 900 });

  await showCaption(page, 'Sign in with the school administrator account');
  await page.goto('/login');
  await expect(page.getByRole('heading', { name: 'Welcome back' })).toBeVisible();
  await fillField(page, '#email', ADMIN_EMAIL);
  await fillField(page, '#password', ADMIN_PASSWORD);
  await clickAndWait(page, page.getByRole('button', { name: 'Sign in' }));
  await expect(page).toHaveURL(/dashboard|attendance-overview/);
  await pause(page, 1800);

  await openPage(page, '/attendance-overview', 'Attendance Overview');
  await highlightText(page, 'Students Today');
  await highlightText(page, 'Teachers Today');
  await highlightText(page, 'Pending & Holidays');
  await pause(page, 1600);

  await openPage(page, '/studentAttendance', 'Student Attendance');
  await fillField(page, '#date', DEMO_DATE);
  await selectField(page, '#status', 'Present');
  await clickAndWait(page, page.getByRole('button', { name: 'Filter' }));
  await pause(page, 1200);
  await clickAndWait(page, page.getByRole('link', { name: 'Reset' }));

  await clickAndWait(page, page.getByRole('link', { name: 'Mark Attendance' }));
  await expect(page.getByRole('heading', { name: /Student Attendance|Mark/i })).toBeVisible();
  await showCaption(page, 'Preview the student attendance form without saving data');
  await fillField(page, '#student_id', '101');
  await fillField(page, '#date', DEMO_DATE);
  await selectField(page, '#status', 'Present');
  await fillField(page, '#batch_id', '5');
  await fillField(page, '#marked_by', '1');
  await fillField(page, '#remarks', 'Documentation walkthrough only');
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('link', { name: 'Cancel' }));

  await openPage(page, '/teacherAttendance', 'Teacher Attendance');
  await fillField(page, '#date', DEMO_DATE);
  await selectField(page, '#status', 'Leave');
  await clickAndWait(page, page.getByRole('button', { name: 'Filter' }));
  await pause(page, 1200);
  await clickAndWait(page, page.getByRole('link', { name: 'Reset' }));

  await clickAndWait(page, page.getByRole('link', { name: 'Mark Attendance' }));
  await showCaption(page, 'Preview the teacher attendance form');
  await fillField(page, '#staff_id', '10');
  await fillField(page, '#date', DEMO_DATE);
  await selectField(page, '#status', 'Absent');
  await fillField(page, '#remarks', 'Shown for documentation video');
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('link', { name: 'Cancel' }));

  await openPage(page, '/studentLeaveRequest', 'Student Leave Requests');
  await selectField(page, '#status', 'Pending');
  await clickAndWait(page, page.getByRole('button', { name: 'Filter' }));
  await pause(page, 1200);
  await clickAndWait(page, page.getByRole('link', { name: 'Reset' }));

  await openPage(page, '/studentLeaveRequest/create', 'Submit Leave Request');
  await showCaption(page, 'Preview a student leave request');
  await fillLeaveForm(page, '#student_id');
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('link', { name: 'Cancel' }));

  await openPage(page, '/teacherLeaveRequest', 'Teacher Leave Requests');
  await fillField(page, '#staff_id', '10');
  await selectField(page, '#status', 'Approved');
  await clickAndWait(page, page.getByRole('button', { name: 'Filter' }));
  await pause(page, 1200);
  await clickAndWait(page, page.getByRole('link', { name: 'Reset' }));

  await openPage(page, '/teacherLeaveRequest/create', 'Submit Leave Request');
  await showCaption(page, 'Preview a teacher leave request');
  await fillLeaveForm(page, '#staff_id');
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('link', { name: 'Cancel' }));

  await openPage(page, '/holiday', 'Holidays');
  await fillField(page, '#search', 'Annual');
  await selectField(page, '#recurring', '1');
  await clickAndWait(page, page.getByRole('button', { name: 'Filter' }));
  await pause(page, 1200);
  await clickAndWait(page, page.getByRole('link', { name: 'Reset' }));

  await clickAndWait(page, page.getByRole('link', { name: 'Add Holiday' }));
  await showCaption(page, 'Preview adding a holiday to the school calendar');
  await fillField(page, '#title', 'Annual Sports Day');
  await fillField(page, '#date', DEMO_DATE);
  await fillField(page, '#description', 'Shown for documentation video only');
  await selectField(page, '#is_recurring', '1');
  await pause(page, 2200);
  await clickAndWait(page, page.getByRole('link', { name: 'Cancel' }));

  await openPage(page, '/attendance-overview', 'Attendance Overview');
  await showCaption(page, 'Attendance Management walkthrough complete');
  await pause(page, 2500);
});

async function openPage(page, path, title) {
  await showCaption(page, title);
  await page.goto(path);
  await expect(page.getByRole('heading', { name: title }).first()).toBeVisible();
  await pause(page, 1400);
}

async function fillLeaveForm(page, personSelector) {
  await fillField(page, personSelector, personSelector.includes('student') ? '101' : '10');
  await fillField(page, '#applied_on', DEMO_DATE);
  await fillField(page, '#start_date', DEMO_DATE);
  await fillField(page, '#end_date', LEAVE_END_DATE);
  await selectField(page, '#status', 'Pending');
  await fillField(page, '#approved_by', '1');
  await fillField(page, '#reason', 'Family function documentation example');
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

async function clickAndWait(page, locator) {
  await spotlight(locator);
  await locator.click();
  await page.waitForLoadState('networkidle').catch(() => {});
  await pause(page, 1000);
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
  await pause(page, 900);
}

async function pause(page, ms) {
  await page.waitForTimeout(ms);
}
