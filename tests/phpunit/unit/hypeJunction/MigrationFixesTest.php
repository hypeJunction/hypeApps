<?php

namespace hypeJunction;

use Elgg\UnitTestCase;
use hypeJunction\Data\Property;

/**
 * Regression coverage for the Elgg 6.x -> 7.x migration fixes applied to hypeApps.
 *
 * These are static/behavioral guards for defects that only manifest at runtime on
 * Elgg 7 (RCE via unserialize, uppercase log levels, removed core functions, PSR-0
 * autoloading, interface arg-shift). Each asserts the FIXED shape so a regression
 * re-introducing the old code fails here rather than in production.
 */
class MigrationFixesTest extends UnitTestCase {

	public function up() {}
	public function down() {}

	protected function pluginRoot(): string {
		$dir = __DIR__;
		for ($i = 0; $i < 6; $i++) {
			if (is_file($dir . '/elgg-plugin.php')) {
				return $dir;
			}
			$dir = dirname($dir);
		}
		$this->fail('Could not locate plugin root (elgg-plugin.php)');
	}

	protected function source(string $relative): string {
		$path = $this->pluginRoot() . '/' . ltrim($relative, '/');
		$this->assertFileExists($path);
		return (string) file_get_contents($path);
	}

	/**
	 * 6f906f2 — IconServer parses signed icon-request params with json_decode(),
	 * never unserialize(): a crafted serialized payload must not be deserialized
	 * (PHP object-injection RCE).
	 */
	public function testIconServerDecodesSignedParamsWithJsonNotUnserialize() {
		$src = $this->source('classes/hypeJunction/Servers/IconServer.php');
		$this->assertStringContainsString('json_decode(base64_decode(', $src);
		$this->assertDoesNotMatchRegularExpression('/(?<![\w>$:])unserialize\s*\(/', $src);
	}

	/**
	 * 5dd2d39 — the Actions controller catch blocks log at lowercase level 'error';
	 * Elgg 7 rejects uppercase level strings ('ERROR').
	 */
	public function testActionsControllerUsesLowercaseLogLevels() {
		$src = $this->source('classes/hypeJunction/Controllers/Actions.php');
		$this->assertMatchesRegularExpression("/elgg_log\([^;]*,\s*'error'\)/", $src);
		$this->assertDoesNotMatchRegularExpression("/elgg_log\([^;]*,\s*'(ERROR|WARNING|NOTICE|INFO)'\)/", $src);
	}

	/**
	 * 7ab09c3 — get_user_by_username() was removed in 5.x; Graph and Validators must
	 * resolve usernames through elgg_get_user_by_username().
	 */
	public function testUsernameLookupUsesElggPrefixedHelper() {
		foreach (['classes/hypeJunction/Data/Graph.php', 'classes/hypeJunction/Data/Validators.php'] as $file) {
			$src = $this->source($file);
			$this->assertStringContainsString('elgg_get_user_by_username(', $src, "$file should call elgg_get_user_by_username()");
			$this->assertDoesNotMatchRegularExpression('/(?<![\w>$:])get_user_by_username\s*\(/', $src, "$file still calls removed get_user_by_username()");
		}
	}

	/**
	 * 5dd2d39 — isAvailableUsername toggles disabled-entity visibility through
	 * session->setDisabledEntityVisibility(), not the removed access_show_hidden_entities().
	 */
	public function testValidatorsUsesSessionDisabledEntityVisibility() {
		$src = $this->source('classes/hypeJunction/Data/Validators.php');
		$this->assertStringContainsString('setDisabledEntityVisibility', $src);
		$this->assertDoesNotMatchRegularExpression('/access_show_hidden_entities\s*\(/', $src);
	}

	/**
	 * e1e31ff — Elgg 7 loads plugin classes via Composer PSR-4; the hypeJunction\
	 * namespace must map to classes/hypeJunction/ and no PSR-0 fallback may remain.
	 */
	public function testComposerAutoloadIsPsr4() {
		$composer = json_decode($this->source('composer.json'), true);
		$this->assertIsArray($composer);
		$this->assertArrayHasKey('psr-4', $composer['autoload']);
		$this->assertSame('classes/hypeJunction/', $composer['autoload']['psr-4']['hypeJunction\\']);
		$this->assertArrayNotHasKey('psr-0', $composer['autoload']);
	}

	/**
	 * 01fdff0 — Property::validate() realigned to the (object, value, params) interface
	 * with backward-compat arg-shifting: a legacy single-arg caller still has its value
	 * forwarded to validation callbacks, and the new object-first caller works too.
	 */
	public function testPropertyValidateArgShiftForLegacyAndNewCallers() {
		$legacySeen = null;
		$legacy = new Property('title', [
			'validation' => [
				'callbacks' => [
					'capture' => function ($prop, $value, $params) use (&$legacySeen) {
						$legacySeen = $value;
						return true;
					},
				],
			],
		]);
		$legacyResult = $legacy->validate('legacy-value');
		$this->assertSame('legacy-value', $legacySeen, 'legacy single-arg validate() must forward the value');
		$this->assertTrue($legacyResult->valid);

		$modernSeen = null;
		$modern = new Property('title', [
			'validation' => [
				'callbacks' => [
					'capture' => function ($prop, $value, $params) use (&$modernSeen) {
						$modernSeen = $value;
						return true;
					},
				],
			],
		]);
		$modern->validate(new \stdClass(), 'modern-value');
		$this->assertSame('modern-value', $modernSeen, 'new object-first validate() must forward the value');
	}
}
