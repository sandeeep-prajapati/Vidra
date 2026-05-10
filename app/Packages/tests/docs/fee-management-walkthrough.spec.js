import { expect, test } from '@playwright/test';

const ADMIN_EMAIL = 'admin@school.com';
const ADMIN_PASSWORD = 'admin123';
const STAMP = Date.now().toString(36).slice(-6);
const DUE_DATE = '2026-06-30';
const PAYMENT_DATE = '2026-05-10';
const REPORT_START = '2026-04-01';
const REPORT_END = '2026-06-30';

test('Fee Management documentation walkthrough', async ({ page }) => {
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
  await expect(page).toHaveURL(/dashboard|fee/);
  await pause(page, 1800);

  // Fee Categories
  await openPage(page, '/feeCategory', 'Fee Categories');
  await showCaption(page, 'Create a fee category to group related charges');
  await clickAndWait(page, page.getByRole('link', { name: 'Add Category' }));
  await expect(page.getByRole('heading', { name: /Fee Category/i }).first()).toBeVisible();
  await fillField(page, '#category_name', `Tuition Fee ${STAMP}`);
  await fillField(page, '#description', 'Monthly tuition charges for all students');
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('button', { name: 'Save Category' }));
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  // Discounts
  await openPage(page, '/discount', 'Discounts');
  await showCaption(page, 'Create a discount scheme — e.g. sibling or merit discount');
  await clickAndWait(page, page.getByRole('link', { name: 'Add Discount' }));
  await expect(page.getByRole('heading', { name: /Discount/i }).first()).toBeVisible();
  await fillField(page, '#discount_name', `Merit Scholarship ${STAMP}`);
  await selectField(page, '#discount_type', 'Percentage');
  await fillField(page, '#discount_amount', '15');
  await fillField(page, '#description', 'Merit-based scholarship for top performers');
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('button', { name: /Save|Create/ }).first());
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  // Fee Structures
  await openPage(page, '/feeStructure', 'Fee Structures');
  await showCaption(page, 'Define how much each class is charged per fee category');
  await clickAndWait(page, page.getByRole('link', { name: 'Add Structure' }));
  await expect(page.getByRole('heading', { name: /Fee Structure/i }).first()).toBeVisible();
  await selectFirstOption(page, '#academic_year_id');
  await selectFirstOption(page, '#class_id');
  await selectFirstOption(page, '#fee_category_id');
  await fillField(page, '#amount', '5000');
  await fillField(page, '#due_date', DUE_DATE);
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('button', { name: 'Save Structure' }));
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  // Student Fees
  await openPage(page, '/studentFee', 'Student Fees');
  await showCaption(page, 'Assign a fee structure to a student — set amount and due date');
  await clickAndWait(page, page.getByRole('link', { name: 'Assign Fee' }));
  await expect(page.getByRole('heading', { name: /Assign Fee/i }).first()).toBeVisible();
  await selectFirstOption(page, '#student_id');
  await selectFirstOption(page, '#fee_structure_id');
  await fillField(page, '#amount_due', '5000');
  await fillField(page, '#discount_amount', '0');
  await fillField(page, '#penalty_amount', '0');
  await fillField(page, '#due_date', DUE_DATE);
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('button', { name: /Assign|Save|Create/ }).first());
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  // Fee Payments
  await openPage(page, '/feePayment', 'Fee Payments');
  await showCaption(page, 'Record a fee payment received from a student');
  await clickAndWait(page, page.getByRole('link', { name: 'Record Payment' }));
  await expect(page.getByRole('heading', { name: /Payment/i }).first()).toBeVisible();
  await selectFirstOption(page, '#student_fee_id');
  await fillField(page, '#payment_date', PAYMENT_DATE);
  await fillField(page, '#amount_paid', '5000');
  await selectField(page, '#payment_mode', 'Cash');
  await fillField(page, '#transaction_reference', `TXN-${STAMP}`);
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('button', { name: /Save|Create|Record/ }).first());
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  // Expenses
  await openPage(page, '/expense', 'Expenses');
  await showCaption(page, 'Log a school expense to track operational costs');
  await clickAndWait(page, page.getByRole('link', { name: 'Add Expense' }));
  await expect(page.getByRole('heading', { name: /Expense/i }).first()).toBeVisible();
  await fillField(page, '#expense_date', PAYMENT_DATE);
  await fillField(page, '#amount', '12000');
  await fillField(page, '#expense_category', 'Maintenance');
  await fillField(page, '#description', 'Annual maintenance of school premises');
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('button', { name: 'Save Expense' }));
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  // Financial Reports
  await openPage(page, '/financialReport', 'Financial Reports');
  await showCaption(page, 'Generate a financial summary report for a selected period');
  await clickAndWait(page, page.getByRole('link', { name: 'Generate Report' }));
  await expect(page.getByRole('heading', { name: /Financial Report/i }).first()).toBeVisible();
  await selectFirstOption(page, '#report_type');
  await fillField(page, '#report_period_start', REPORT_START);
  await fillField(page, '#report_period_end', REPORT_END);
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('button', { name: /Save|Generate|Create/ }).first());
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  await openPage(page, '/feeCategory', 'Fee Categories');
  await showCaption(page, 'Fee Management walkthrough complete');
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
