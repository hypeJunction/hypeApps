<?php

namespace hypeJunction\Apps\Handlers;

use Elgg\Event;
use Elgg\IntegrationTestCase;
use hypeJunction\Data\Property;

/**
 * Smoke tests for type-specialised property hook handlers.
 * Each one must accept an Elgg\Hook and return an array of Property objects.
 */
class TypedPropertiesHookTest extends IntegrationTestCase {

	public function up() {}
	public function down() {}

	public function getPluginID(): string {
		return '';
	}

	protected function mockHook(): Event {
		$hook = $this->getMockBuilder(Event::class)->disableOriginalConstructor()->getMock();
		$hook->method('getValue')->willReturn([]);
		$hook->method('getParams')->willReturn([]);
		$hook->method('getParam')->willReturn(null);
		$hook->method('getType')->willReturn('all');
		$hook->method('getName')->willReturn('graph:properties');
		return $hook;
	}

	public function handlerProvider(): array {
		return [
			[UserPropertiesHook::class],
			[GroupPropertiesHook::class],
			[SitePropertiesHook::class],
			[ObjectPropertiesHook::class],
			[BlogPropertiesHook::class],
			[FilePropertiesHook::class],
			[MessagePropertiesHook::class],
			[ExtenderPropertiesHook::class],
			[RelationshipPropertiesHook::class],
			[RiverPropertiesHook::class],
		];
	}

	/**
	 * @dataProvider handlerProvider
	 */
	public function testHandlerReturnsArrayOfProperties(string $handlerClass) {
		$this->assertTrue(class_exists($handlerClass));
		$this->assertTrue(method_exists($handlerClass, 'handle'));
		$result = call_user_func([$handlerClass, 'handle'], $this->mockHook());
		$this->assertIsArray($result);
		foreach ($result as $prop) {
			$this->assertInstanceOf(Property::class, $prop);
		}
	}
}
