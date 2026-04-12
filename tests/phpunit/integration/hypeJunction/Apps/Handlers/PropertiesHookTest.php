<?php

namespace hypeJunction\Apps\Handlers;

use Elgg\Hook;
use Elgg\IntegrationTestCase;
use hypeJunction\Data\Property;

class PropertiesHookTest extends IntegrationTestCase {

	public function up() {}
	public function down() {}

	public function getPluginID(): string {
		return '';
	}

	protected function mockHook($value = []): Hook {
		$hook = $this->getMockBuilder(Hook::class)->getMock();
		$hook->method('getValue')->willReturn($value);
		$hook->method('getParams')->willReturn([]);
		$hook->method('getParam')->willReturn(null);
		$hook->method('getType')->willReturn('all');
		$hook->method('getName')->willReturn('graph:properties');
		return $hook;
	}

	public function testHandleReturnsArrayOfProperties() {
		$hook = $this->mockHook([]);
		$result = PropertiesHook::handle($hook);
		$this->assertIsArray($result);
		$this->assertNotEmpty($result);
		foreach ($result as $prop) {
			$this->assertInstanceOf(Property::class, $prop);
		}
	}

	public function testHandleIncludesCoreIdentifiers() {
		$hook = $this->mockHook([]);
		$result = PropertiesHook::handle($hook);
		$ids = array_map(function (Property $p) { return $p->getIdentifier(); }, $result);
		foreach (['alias', 'type', 'subtype', 'uid', 'url', 'time_created', 'enabled'] as $expected) {
			$this->assertContains($expected, $ids, "Missing property '$expected'");
		}
	}

	public function testHandlePreservesExistingValues() {
		$existing = [new Property('custom')];
		$hook = $this->mockHook($existing);
		$result = PropertiesHook::handle($hook);
		$this->assertGreaterThan(count($existing), count($result));
		$ids = array_map(function (Property $p) { return $p->getIdentifier(); }, $result);
		$this->assertContains('custom', $ids);
	}
}
