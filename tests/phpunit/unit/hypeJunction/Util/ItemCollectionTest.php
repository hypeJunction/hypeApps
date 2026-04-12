<?php

namespace hypeJunction\Util;

use Elgg\UnitTestCase;

class ItemCollectionTest extends UnitTestCase {

	public function up() {}
	public function down() {}

	public function testCreateReturnsInstance() {
		$c = ItemCollection::create([]);
		$this->assertInstanceOf(ItemCollection::class, $c);
	}

	public function testEmptyCollectionHasNoGuids() {
		$c = ItemCollection::create([]);
		$this->assertSame([], $c->guids());
	}

	public function testAddReturnsSelfForChaining() {
		$c = new ItemCollection();
		$this->assertSame($c, $c->add([]));
	}

	public function testInvalidNonExistentGuidsAreFiltered() {
		// toGUID calls exists() on ints — for 0 / non-existent guids, stays empty
		$c = ItemCollection::create([0]);
		$this->assertSame([], $c->guids());
	}
}
