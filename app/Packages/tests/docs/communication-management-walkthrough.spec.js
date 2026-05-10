import { expect, test } from '@playwright/test';

const ADMIN_EMAIL = 'admin@school.com';
const ADMIN_PASSWORD = 'admin123';
const STAMP = Date.now().toString(36).slice(-6);
const ISSUED_DATE = '2026-05-10';

test('Communication Management documentation walkthrough', async ({ page }) => {
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
  await expect(page).toHaveURL(/dashboard|message/);
  await pause(page, 1800);

  // Messages
  await openPage(page, '/message', 'Messages');
  await showCaption(page, 'Send a message to students, teachers or staff');
  await clickAndWait(page, page.getByRole('link', { name: 'New Message' }));
  await expect(page.getByRole('heading', { name: /Message/ }).first()).toBeVisible();
  await selectField(page, '#message_type', 'Announcement');
  await selectField(page, '#priority', 'Normal');
  await fillField(page, '#title', `Documentation Announcement ${STAMP}`);
  await fillField(page, '#content', 'This message was created during the documentation walkthrough. No action needed.');
  await pause(page, 2000);
  await clickAndWait(page, page.getByRole('button', { name: 'Create Message' }));
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  // Message Recipients
  await openPage(page, '/messageRecipient', 'Message Recipients');
  await showCaption(page, 'Assign recipients to a message for targeted delivery');
  await clickAndWait(page, page.getByRole('link', { name: 'Add Recipient' }));
  await expect(page.getByRole('heading', { name: /Recipient/ }).first()).toBeVisible();
  await selectLastOption(page, '#message_id');
  await selectFirstOption(page, '#user_id');
  await selectField(page, '#status', 'Sent');
  await pause(page, 1800);
  await clickAndWait(page, page.getByRole('button', { name: /Save|Create|Add/ }).first());
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  // Circulars
  await openPage(page, '/circular', 'Circulars');
  await showCaption(page, 'Publish an official circular to the school community');
  await clickAndWait(page, page.getByRole('link', { name: 'New Circular' }));
  await expect(page.getByRole('heading', { name: /Circular/ }).first()).toBeVisible();
  await fillField(page, '#title', `Annual Day Notice ${STAMP}`);
  await fillField(page, '#content', 'This circular announces the Annual Day celebration. Please note the date and venue. (Documentation example only)');
  await selectField(page, '#target_audience', 'All');
  await fillField(page, '#issued_date', ISSUED_DATE);
  await pause(page, 2000);
  await clickAndWait(page, page.getByRole('button', { name: 'Publish Circular' }));
  await expect(page).not.toHaveURL(/\/create/i, { timeout: 15000 });
  await pause(page, 1600);

  // Notification Preferences
  await openPage(page, '/notificationSetting', 'Notification Preferences');
  await showCaption(page, 'Configure notification preferences for each user');
  await highlightText(page, 'Notification Preferences');
  await pause(page, 2000);

  await openPage(page, '/message', 'Messages');
  await showCaption(page, 'Communication Management walkthrough complete');
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
