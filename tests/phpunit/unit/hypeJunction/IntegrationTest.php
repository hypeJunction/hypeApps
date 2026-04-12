<?php

namespace hypeJunction;

use Elgg\UnitTestCase;

class IntegrationTest extends UnitTestCase {

	public function up() {}
	public function down() {}

	public function testGetRootPathReturnsString() {
		$path = Integration::getRootPath();
		$this->assertIsString($path);
		$this->assertNotEmpty($path);
	}

	public function testIsElggVersionBelowReturnsBool() {
		$this->assertIsBool(Integration::isElggVersionBelow('999.0.0'));
	}

	public function testIsElggVersionAboveReturnsBool() {
		$this->assertIsBool(Integration::isElggVersionAbove('0.0.1'));
	}
}
