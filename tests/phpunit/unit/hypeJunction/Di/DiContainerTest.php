<?php

namespace hypeJunction\Di;

use Elgg\UnitTestCase;

class DiContainerTest extends UnitTestCase {

	public function up() {}
	public function down() {}

	public function testSetValueReturnsStoredValue() {
		$c = new DiContainer();
		$c->setValue('answer', 42);
		$this->assertSame(42, $c->answer);
	}

	public function testSetValueRejectsTrailingUnderscore() {
		$this->expectException(\InvalidArgumentException::class);
		(new DiContainer())->setValue('bad_', 1);
	}

	public function testSetFactoryProducesValueFromCallable() {
		$c = new DiContainer();
		$c->setFactory('service', function () {
			return new \stdClass();
		});
		$this->assertInstanceOf(\stdClass::class, $c->service);
	}

	public function testSharedFactoryReturnsSameInstance() {
		$c = new DiContainer();
		$c->setFactory('svc', function () {
			return new \stdClass();
		}, true);
		$a = $c->svc;
		$b = $c->svc;
		$this->assertSame($a, $b);
	}

	public function testNonSharedFactoryReturnsNewInstance() {
		$c = new DiContainer();
		$c->setFactory('svc', function () {
			return new \stdClass();
		}, false);
		$a = $c->svc;
		$b = $c->svc;
		$this->assertNotSame($a, $b);
	}

	public function testFactoryReceivesContainerAsArg() {
		$c = new DiContainer();
		$received = null;
		$c->setFactory('svc', function ($container) use (&$received) {
			$received = $container;
			return 1;
		});
		$c->svc;
		$this->assertSame($c, $received);
	}

	public function testSetClassNameInstantiates() {
		$c = new DiContainer();
		$c->setClassName('obj', \stdClass::class);
		$this->assertInstanceOf(\stdClass::class, $c->obj);
	}

	public function testSetClassNameRejectsInvalidClassName() {
		$this->expectException(\InvalidArgumentException::class);
		(new DiContainer())->setClassName('x', '123-not-valid');
	}

	public function testHasReturnsTrueAfterSet() {
		$c = new DiContainer();
		$c->setValue('a', 1);
		$this->assertTrue($c->has('a'));
		$this->assertFalse($c->has('b'));
	}

	public function testRemoveClearsEntry() {
		$c = new DiContainer();
		$c->setValue('a', 1);
		$c->remove('a');
		$this->assertFalse($c->has('a'));
	}

	public function testGetMissingThrows() {
		$this->expectException(\Exception::class);
		$c = new DiContainer();
		$unused = $c->missing;
	}

	public function testGetNamesIncludesFactories() {
		$c = new DiContainer();
		$c->setFactory('one', function () { return 1; });
		$c->setValue('two', 2);
		$names = $c->getNames();
		$this->assertContains('one', $names);
		$this->assertContains('two', $names);
	}
}
