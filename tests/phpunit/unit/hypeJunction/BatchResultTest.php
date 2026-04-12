<?php

namespace hypeJunction;

use Elgg\UnitTestCase;

class BatchResultTest extends UnitTestCase {

	public function up() {}
	public function down() {}

	public function testGetCountCallsGetterWithCountFlag() {
		$received = null;
		$getter = function ($options) use (&$received) {
			$received = $options;
			return 7;
		};
		$b = new BatchResult($getter, ['types' => 'object']);
		$this->assertSame(7, $b->getCount());
		$this->assertTrue($received['count']);
		$this->assertSame('object', $received['types']);
	}

	public function testGetCountCoercesToInt() {
		$getter = function ($options) { return '42'; };
		$b = new BatchResult($getter);
		$this->assertSame(42, $b->getCount());
	}

	public function testNullGetterIsPermittedByConstructor() {
		// Constructor signature allows null default
		$b = new BatchResult(null, []);
		$this->assertInstanceOf(BatchResult::class, $b);
	}
}
