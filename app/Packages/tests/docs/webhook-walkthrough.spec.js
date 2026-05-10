import { expect, test } from '@playwright/test';

const ADMIN_EMAIL = 'admin@school.com';
const ADMIN_PASSWORD = 'admin123';

test('Webhook documentation walkthrough', async ({ page }) => {
  test.setTimeout(720_000);

  await page.setViewportSize({ width: 1920, height: 1080 });
  page.setDefaultTimeout(10000);
  page.setDefaultNavigationTimeout(60000);

  await showCaption(page, 'Sign in with the school administrator account');
  await page.goto('/login');
  await expect(page.getByRole('heading', { name: 'Welcome back' })).toBeVisible();
  await fillField(page, '#email', ADMIN_EMAIL);
  await fillField(page, '#password', ADMIN_PASSWORD);
  await clickAndWait(page, page.getByRole('button', { name: 'Sign in' }));
  await expect(page).toHaveURL(/dashboard|webhook/);
  await pause(page, 1800);

  // Webhook Settings
  await showCaption(page, 'Webhook Settings');
  await page.goto('/webhook/settings', { waitUntil: 'domcontentloaded' });
  await expect(page.getByText('Webhook Settings').first()).toBeVisible();
  await pause(page, 1600);

  await showCaption(page, 'Configure an endpoint URL to receive real-time school events');
  await highlightElement(page, '#webhook_url');
  await pause(page, 1200);
  await page.locator('#webhook_url').fill('https://your-server.example/webhook');
  await pause(page, 1400);

  await showCaption(page, 'Enable the webhook to start sending events to the configured URL');
  await highlightElement(page, '#webhook_active');
  await pause(page, 1800);

  await showCaption(page, 'Save your webhook configuration');
  await clickAndWait(page, page.getByRole('button', { name: 'Save Settings' }));
  await pause(page, 1600);

  // Webhook Logs
  await showCaption(page, 'Webhook Logs');
  await page.goto('/webhook/logs', { waitUntil: 'domcontentloaded' });
  await expect(page.getByText('Webhook Logs').first()).toBeVisible();
  await pause(page, 1600);

  await showCaption(page, 'Review the history of all outbound webhook calls and their status');
  await highlightText(page, 'Webhook Logs');
  await pause(page, 2000);

  await showCaption(page, 'Webhook walkthrough complete');
  await pause(page, 2500);
});

async function fillField(page, selector, value) {
  const locator = page.locator(selector);
  await spotlight(locator);
  await locator.fill(value);
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
