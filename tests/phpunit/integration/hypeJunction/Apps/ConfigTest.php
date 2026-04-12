<?php

namespace hypeJunction\Apps;

use Elgg\IntegrationTestCase;

class ConfigTest extends IntegrationTestCase {

	public function up() {}
	public function down() {}

	public function getPluginID(): string {
		return '';
	}

	protected function getPlugin(): \ElggPlugin {
		$plugin = elgg_get_plugin_from_id('hypeapps');
		if (!$plugin) {
			$this->markTestSkipped('hypeapps plugin not installed in test DB');
		}
		return $plugin;
	}

	public function testDefaultsContainExpectedKeys() {
		$config = new Config($this->getPlugin());
		$defaults = $config->getDefaults();
		$this->assertArrayHasKey('filestore_prefix', $defaults);
		$this->assertArrayHasKey('icon_filestore_prefix', $defaults);
		$this->assertArrayHasKey('default_size', $defaults);
		$this->assertArrayHasKey('master_size_length', $defaults);
	}

	public function testGetDefaultFilestorePrefix() {
		$config = new Config($this->getPlugin());
		$this->assertSame('file', $config->getDefaultFilestorePrefix());
	}

	public function testGetDefaultIconDirectory() {
		$config = new Config($this->getPlugin());
		$this->assertSame('icons', $config->getDefaultIconDirectory());
	}

	public function testCroppableSizesIncludesStandardSet() {
		$config = new Config($this->getPlugin());
		$sizes = $config->getCroppableSizes();
		$this->assertContains(Config::SIZE_LARGE, $sizes);
		$this->assertContains(Config::SIZE_MEDIUM, $sizes);
		$this->assertContains(Config::SIZE_SMALL, $sizes);
		$this->assertContains(Config::SIZE_TINY, $sizes);
		$this->assertContains(Config::SIZE_TOPBAR, $sizes);
	}

	public function testFileIconSizesStructure() {
		$config = new Config($this->getPlugin());
		$sizes = $config->getFileIconSizes();
		$this->assertArrayHasKey('small', $sizes);
		$this->assertArrayHasKey('medium', $sizes);
		$this->assertArrayHasKey('large', $sizes);
		foreach ($sizes as $name => $opts) {
			$this->assertArrayHasKey('w', $opts);
			$this->assertArrayHasKey('h', $opts);
			$this->assertArrayHasKey('metadata_name', $opts);
		}
	}

	public function testIconCompressionOptsReturnsAllKeys() {
		$config = new Config($this->getPlugin());
		$opts = $config->getIconCompressionOpts();
		$this->assertArrayHasKey('jpeg_quality', $opts);
		$this->assertArrayHasKey('png_compression', $opts);
		$this->assertArrayHasKey('png_filter', $opts);
	}

	public function testSrcCompressionOptsReturnsAllKeys() {
		$config = new Config($this->getPlugin());
		$opts = $config->getSrcCompressionOpts();
		$this->assertArrayHasKey('jpeg_quality', $opts);
		$this->assertArrayHasKey('png_compression', $opts);
		$this->assertArrayHasKey('png_filter', $opts);
	}

	public function testMagicGetReturnsConfigValue() {
		$config = new Config($this->getPlugin());
		$this->assertSame('file', $config->filestore_prefix);
		$this->assertSame('icons', $config->icon_filestore_prefix);
	}

	public function testGetWithFallback() {
		$config = new Config($this->getPlugin());
		$this->assertSame('fallback', $config->get('nonexistent_key', 'fallback'));
	}

	public function testGetIDReturnsPluginId() {
		$config = new Config($this->getPlugin());
		$this->assertSame('hypeapps', $config->getID());
	}

	public function testGetPathReturnsPluginPath() {
		$config = new Config($this->getPlugin());
		$this->assertIsString($config->getPath());
		$this->assertNotEmpty($config->getPath());
	}

	public function testSizeConstants() {
		$this->assertSame('topbar', Config::SIZE_TOPBAR);
		$this->assertSame('tiny', Config::SIZE_TINY);
		$this->assertSame('small', Config::SIZE_SMALL);
		$this->assertSame('medium', Config::SIZE_MEDIUM);
		$this->assertSame('large', Config::SIZE_LARGE);
		$this->assertSame('master', Config::SIZE_MASTER);
	}
}
