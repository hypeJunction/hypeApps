<?php

namespace hypeJunction\Controllers;

use Elgg\IntegrationTestCase;

class ParameterBagTest extends IntegrationTestCase {

	public function up() {}
	public function down() {}

	public function getPluginID(): string {
		return '';
	}

	public function testConstructorStoresParams() {
		$bag = new ParameterBag(['foo' => 'bar', 'n' => 42]);
		$this->assertSame('bar', $bag->foo);
		$this->assertSame(42, $bag->n);
	}

	public function testConstructorAcceptsNull() {
		$bag = new ParameterBag(null);
		$this->assertInstanceOf(ParameterBag::class, $bag);
	}

	public function testUnsetPropertyFallsBackToGetInput() {
		set_input('dynamic_field', 'from_input');
		$bag = new ParameterBag();
		$this->assertSame('from_input', $bag->dynamic_field);
	}

	public function testImplementsInterface() {
		$this->assertInstanceOf(ParameterBagInterface::class, new ParameterBag());
	}
}
