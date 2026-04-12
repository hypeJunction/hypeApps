<?php

namespace hypeJunction\Apps;

use Elgg\IntegrationTestCase;

class PluginTest extends IntegrationTestCase {

	public function up() {}
	public function down() {}

	public function getPluginID(): string {
		return '';
	}

	protected function skipIfPluginMissing() {
		if (!elgg_get_plugin_from_id('hypeapps')) {
			$this->markTestSkipped('hypeapps plugin not installed in test DB');
		}
	}

	public function testHypeAppsFunctionReturnsPlugin() {
		$this->skipIfPluginMissing();
		$this->assertTrue(function_exists('hypeApps'));
		$p = hypeApps();
		$this->assertInstanceOf(Plugin::class, $p);
	}

	public function testHypeAppsFactoryReturnsSingleton() {
		$this->skipIfPluginMissing();
		$this->assertSame(Plugin::factory(), Plugin::factory());
	}

	public function testPluginExposesConfigService() {
		$this->skipIfPluginMissing();
		$p = Plugin::factory();
		$this->assertInstanceOf(Config::class, $p->config);
	}

	public function testPluginExposesActionsService() {
		$this->skipIfPluginMissing();
		$p = Plugin::factory();
		$this->assertInstanceOf(\hypeJunction\Controllers\Actions::class, $p->actions);
	}

	public function testPluginExposesUploaderService() {
		$this->skipIfPluginMissing();
		$p = Plugin::factory();
		$this->assertInstanceOf(\hypeJunction\Services\Uploader::class, $p->uploader);
	}

	public function testPluginExposesIconFactoryService() {
		$this->skipIfPluginMissing();
		$p = Plugin::factory();
		$this->assertInstanceOf(\hypeJunction\Services\IconFactory::class, $p->iconFactory);
	}

	public function testPluginExposesGraphService() {
		$this->skipIfPluginMissing();
		$p = Plugin::factory();
		$this->assertInstanceOf(\hypeJunction\Data\Graph::class, $p->graph);
	}

	public function testServicesAreShared() {
		$this->skipIfPluginMissing();
		$p = Plugin::factory();
		$this->assertSame($p->config, $p->config);
		$this->assertSame($p->graph, $p->graph);
	}

	public function testPluginHoldsElggPlugin() {
		$this->skipIfPluginMissing();
		$p = Plugin::factory();
		$this->assertInstanceOf(\ElggPlugin::class, $p->plugin);
		$this->assertSame('hypeapps', $p->plugin->getID());
	}
}
