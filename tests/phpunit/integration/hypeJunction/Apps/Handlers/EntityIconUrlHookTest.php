<?php

namespace hypeJunction\Apps\Handlers;

use Elgg\Event;
use Elgg\IntegrationTestCase;

class EntityIconUrlHookTest extends IntegrationTestCase {

	public function up() {}
	public function down() {}

	public function getPluginID(): string {
		return '';
	}

	protected function mockHook($value, $params = []): Event {
		$hook = $this->getMockBuilder(Event::class)->disableOriginalConstructor()->getMock();
		$hook->method('getValue')->willReturn($value);
$hook->method('getParam')->willReturnCallback(function ($name, $default = null) use ($params) {
			return $params[$name] ?? $default;
		});
		$hook->method('getParams')->willReturn($params);
		$hook->method('getType')->willReturn('all');
		$hook->method('getName')->willReturn('entity:icon:url');
		return $hook;
	}

	public function testReturnsExistingValueIfNotNull() {
		$hook = $this->mockHook('http://example.com/existing.png', []);
		$this->assertSame('http://example.com/existing.png', EntityIconUrlHook::handle($hook));
	}

	public function testReturnsNullForEntityWithoutIcontime() {
		if (!elgg_get_plugin_from_id('hypeapps')) {
			$this->markTestSkipped('hypeapps plugin not installed in test DB');
		}
		$user = $this->createUser();
		$user->icontime = null;
		$hook = $this->mockHook(null, ['entity' => $user, 'size' => 'medium']);
		$this->assertNull(EntityIconUrlHook::handle($hook));
	}
}
