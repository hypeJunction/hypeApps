import { test, expect } from '@playwright/test';

/**
 * E2E smoke for hypeapps.
 *
 * hypeapps is a library plugin: it ships a DI-style Plugin/Config base
 * for other hype* plugins, an IconServer at servers/icon.php, and a
 * Bootstrap class plus a swathe of 'graph:properties' and
 * 'entity:icon:url' hook handlers registered in elgg-plugin.php. It
 * registers no actions, routes, entity subtypes, or view extensions.
 *
 * The smoke surface is therefore minimal: "the plugin loads without
 * fatalling the request pipeline". Two probes catch it:
 *   1. the homepage renders below a 5xx and contains no PHP fatal
 *      markers — catches Bootstrap, hook-handler class, or autoloader
 *      regressions that would blow up on every request;
 *   2. the default css aggregate still compiles — catches any view
 *      system drift the plugin's boot might introduce (cache_handler
 *      runs the same bootCore path the homepage does, so a broken
 *      hook handler class-resolution surfaces here too).
 */
test.describe('hypeapps', () => {
  test('homepage renders with no PHP fatal markers', async ({ page }) => {
    const response = await page.goto('/');
    expect(response, 'response object should be defined').toBeTruthy();
    expect(response!.status(), `unexpected status ${response!.status()} on /`).toBeLessThan(500);

    const body = await page.content();
    expect(body, 'page body should not contain Fatal error').not.toContain('Fatal error');
    expect(body, 'page body should not contain Uncaught').not.toContain('Uncaught');
    expect(body, 'page body should not contain ParseError').not.toContain('ParseError');
  });

  test('default css aggregate compiles', async ({ page }) => {
    const response = await page.goto('/cache/0/default/elgg.css');
    expect(response).toBeTruthy();
    if (response!.status() !== 404) {
      expect(response!.status()).toBeLessThan(400);
      const ct = response!.headers()['content-type'] || '';
      expect(ct).toMatch(/css|text/);
    }
  });
});
