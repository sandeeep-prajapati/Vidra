import { expect, test } from '@playwright/test';

const ADMIN_EMAIL = 'admin@school.com';
const ADMIN_PASSWORD = 'admin123';

test('Data Transfer documentation walkthrough', async ({ page }) => {
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
  await expect(page).toHaveURL(/dashboard|data-transfer/);
  await pause(page, 1800);

  // Data Transfer page
  await showCaption(page, 'Data Transfer');
  await page.goto('/data-transfer');
  await expect(page.getByRole('heading', { name: 'Data Transfer' }).first()).toBeVisible();
  await pause(page, 1600);

  // Import section
  await showCaption(page, 'Import data from a CSV file into the system');
  await highlightElement(page, '#import-entity');
  await selectField(page, '#import-entity', 'student');
  await pause(page, 1200);
  await highlightElement(page, '#sample-link');
  await pause(page, 1400);

  await showCaption(page, 'Choose the action — insert new records or update existing ones');
  await highlightElement(page, '#action');
  await selectField(page, '#action', 'append');
  await pause(page, 1200);

  await showCaption(page, 'Set the validation strategy for error handling during import');
  await highlightElement(page, '#validation_strategy');
  await selectField(page, '#validation_strategy', 'stop-on-errors');
  await pause(page, 1400);

  await showCaption(page, 'Configure the field separator and allowed error threshold');
  await highlightElement(page, '#field_separator');
  await highlightElement(page, '#allowed_errors');
  await pause(page, 1600);

  // Export section
  await showCaption(page, 'Export records from the system to a CSV file');
  await highlightElement(page, '#export-entity');
  await selectField(page, '#export-entity', 'student');
  await pause(page, 1400);

  await showCaption(page, 'Apply filters before exporting — e.g. export only active students');
  await highlightElement(page, '#status');
  await selectField(page, '#status', 'active');
  await pause(page, 1800);

  await showCaption(page, 'Data Transfer walkthrough complete — import and export ready');
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

async function highlightElement(page, selector) {
  const locator = page.locator(selector).first();
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
