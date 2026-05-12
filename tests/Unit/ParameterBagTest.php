<?php

namespace hypeJunction\Tests\Unit;

use hypeJunction\Controllers\ParameterBag;
use PHPUnit\Framework\TestCase;

class ParameterBagTest extends TestCase {

    public function testConstructorAcceptsParamArray(): void {
        $bag = new ParameterBag(['foo' => 'bar', 'baz' => 42]);
        $this->assertEquals('bar', $bag->foo);
        $this->assertEquals(42, $bag->baz);
    }

    public function testConstructorHandlesNullParams(): void {
        $bag = new ParameterBag(null);
        $this->assertInstanceOf(ParameterBag::class, $bag);
    }

    public function testConstructorHandlesEmptyArray(): void {
        $bag = new ParameterBag([]);
        $this->assertInstanceOf(ParameterBag::class, $bag);
    }
}
