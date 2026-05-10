import { expect, test } from '@playwright/test';

const ADMIN_EMAIL = 'admin@school.com';
const ADMIN_PASSWORD = 'admin123';
const STAMP = Date.now().toString(36).slice(-6);
const ASSIGNED_DATE = '2026-04-01';
const CHECKOUT_DATE = '2027-03-31';

test('Hostel & Transport Management documentation walkthrough', async ({ page }) => {
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
  await expect(page).toHaveURL(/dashboard|hostel/);
  await pause(page, 1800);

  // Hostels
  await openPage(page, '/hostels', 'Hostels');
  await showCaption(page, 'Add a hostel building to the school campus');
  await clickAndWait(page, page.getByRole('link', { name: 'Add Hostel' }));
  await expect(page.getByRole('heading', { name: /Hostel/ }).first()).toBeVisible();
  await fillField(page, '#hostel_name', `Boys Block ${STAMP}`);
  await selectField(page, '#hostel_type', 'Boys');
  await fillField(page, '#total_capacity', '120');
  await fillField(page, '#available_capacity', '30');
  await fillField(page, '#location', 'East Wing, School Campus');
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('button', { name: 'Create Hostel' }));
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  // Hostel Rooms
  await openPage(page, '/hostel-rooms', 'Hostel Rooms');
  await showCaption(page, 'Add rooms to a hostel with capacity and type');
  await clickAndWait(page, page.getByRole('link', { name: 'Add Room' }));
  await expect(page.getByRole('heading', { name: /Room/ }).first()).toBeVisible();
  await selectLastOption(page, '#hostel_id');
  await fillField(page, '#room_number', `R-${STAMP}`);
  await selectField(page, '#room_type', 'Double');
  await fillField(page, '#capacity', '4');
  await fillField(page, '#occupied', '0');
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('button', { name: 'Create Room' }));
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  // Hostel Assignments
  await openPage(page, '/student-hostels', 'Hostel Assignments');
  await showCaption(page, 'Assign a student to a hostel room for the academic year');
  await clickAndWait(page, page.getByRole('link', { name: 'Assign Student' }));
  await expect(page.getByRole('heading', { name: /Hostel Assignment|Assign Student/ }).first()).toBeVisible();
  await fillField(page, '#student_id', '1');
  await selectLastOption(page, '#hostel_id');
  await selectLastOption(page, '#room_id');
  await fillField(page, '#assigned_date', ASSIGNED_DATE);
  await fillField(page, '#checkout_date', CHECKOUT_DATE);
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('button', { name: 'Create Assignment' }));
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  // Transportation
  await openPage(page, '/transportation', 'Transportation Services');
  await showCaption(page, 'Register a transport route or school bus service');
  await clickAndWait(page, page.getByRole('link', { name: 'Add Transport' }));
  await expect(page.getByRole('heading', { name: /Transport/ }).first()).toBeVisible();
  await fillField(page, '#transport_name', `Bus Route ${STAMP}`);
  await selectField(page, '#transport_type', 'Bus');
  await fillField(page, '#capacity', '40');
  await fillField(page, '#departure_time', '07:30');
  await fillField(page, '#route', 'City Centre → School Main Gate');
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('button', { name: 'Create Transport' }));
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  // Transport Assignments
  await openPage(page, '/student-transport', 'Transport Assignments');
  await showCaption(page, 'Assign a student to a transport service');
  await highlightText(page, 'Transport Assignments');
  await pause(page, 2000);

  await openPage(page, '/hostels', 'Hostels');
  await showCaption(page, 'Hostel & Transport Management walkthrough complete');
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
