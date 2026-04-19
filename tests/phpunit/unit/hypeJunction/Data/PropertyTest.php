<?php

namespace hypeJunction\Data;

use Elgg\UnitTestCase;

class PropertyTest extends UnitTestCase {

	public function up() {}
	public function down() {}

	public function testConstructorAssignsIdAndOptions() {
$prop = new Property('title', [
			'type' => 'string',
			'required' => true,
		]);
		$this->assertSame('title', $prop->getIdentifier());
		$this->assertSame('string', $prop->getType());
		$this->assertTrue($prop->isRequired());
	}

	public function testAttributeNameDefaultsToIdentifier() {
		$prop = new Property('title');
		$this->assertSame('title', $prop->getAttributeName());
	}

	public function testAttributeNameOverridable() {
		$prop = new Property('title', ['attribute' => 'name']);
		$this->assertSame('name', $prop->getAttributeName());
	}

	public function testIsRequiredDefaultsFalse() {
		$prop = new Property('title');
		$this->assertFalse($prop->isRequired());
	}

	public function testGetEnumOptionsFromArray() {
$prop = new Property('status', [
			'enum' => ['Published' => 1, 'Draft' => 0],
		]);
		$this->assertSame(['Published' => 1, 'Draft' => 0], $prop->getEnumOptions());
	}

	public function testGetEnumOptionsFromCallable() {
$prop = new Property('status', [
			'enum' => function () { return ['A' => 'a']; },
		]);
		$this->assertSame(['A' => 'a'], $prop->getEnumOptions());
	}

	public function testGetEnumOptionsEmptyByDefault() {
		$prop = new Property('title');
		$this->assertSame([], $prop->getEnumOptions());
	}

	public function testSanitizersAreCalled() {
		$called = false;
$prop = new Property('title', [
			'sanitizers' => [function ($p, &$v) use (&$called) { $called = true; }],
		]);
		$value = 'x';
		$prop->sanitize($value);
		$this->assertTrue($called);
	}

	public function testGetterInvokedOnGetValue() {
$prop = new Property('title', [
			'getter' => function ($p, $obj) { return $obj->title ?? null; },
		]);
		$obj = (object) ['title' => 'Hello'];
		$this->assertSame('Hello', $prop->getValue($obj));
	}

	public function testSetterInvokedOnSetValue() {
$prop = new Property('title', [
			'setter' => function ($p, $obj, $value) { $obj->title = $value; },
		]);
		$obj = new \stdClass();
		$prop->setValue($obj, 'New');
		$this->assertSame('New', $obj->title);
	}

	public function testToArrayExportsFields() {
$prop = new Property('title', [
			'type' => 'string',
			'required' => true,
			'default' => 'foo',
		]);
		$arr = $prop->toArray();
		$this->assertSame('title', $arr['name']);
		$this->assertSame('string', $arr['type']);
		$this->assertTrue($arr['required']);
		$this->assertSame('foo', $arr['default']);
	}

	public function testGetDefaultReturnsDefaultIfSet() {
		$prop = new Property('title', ['default' => 'untitled']);
		$obj = new \stdClass();
		$this->assertSame('untitled', $prop->getDefault($obj));
	}
}
