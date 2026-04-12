<?php

namespace hypeJunction\Data;

use Elgg\UnitTestCase;

class ValuesTest extends UnitTestCase {

	public function up() {}
	public function down() {}

	public function testMapTypeStringAliases() {
		$v = new Values();
		$this->assertSame('string', $v->mapType('string'));
		$this->assertSame('string', $v->mapType('text'));
		$this->assertSame('string', $v->mapType('plaintext'));
		$this->assertSame('string', $v->mapType('longtext'));
	}

	public function testMapTypeIntegerAliases() {
		$v = new Values();
		$this->assertSame('integer', $v->mapType('int'));
		$this->assertSame('integer', $v->mapType('integer'));
		$this->assertSame('integer', $v->mapType('access'));
	}

	public function testMapTypeGuidAliases() {
		$v = new Values();
		$this->assertSame('guid', $v->mapType('userpicker'));
		$this->assertSame('guid', $v->mapType('autocomplete'));
		$this->assertSame('guid', $v->mapType('friendspicker'));
	}

	public function testMapTypePassthrough() {
		$v = new Values();
		$this->assertSame('custom', $v->mapType('custom'));
	}

	public function testHtmlSpecialCharsEscapesHtml() {
		$prop = $this->createMock(PropertyInterface::class);
		$result = Values::htmlSpecialChars($prop, '<b>"hi"</b>');
		$this->assertStringContainsString('&lt;b&gt;', $result);
		$this->assertStringContainsString('&quot;', $result);
	}

	public function testHtmlSpecialCharsHandlesNull() {
		$prop = $this->createMock(PropertyInterface::class);
		$this->assertSame('', Values::htmlSpecialChars($prop, null));
	}

	public function testGetVerbatimReturnsObjectAttribute() {
		$prop = $this->createMock(PropertyInterface::class);
		$prop->method('getAttributeName')->willReturn('title');
		$obj = (object) ['title' => 'Hello'];
		$this->assertSame('Hello', Values::getVerbatim($prop, $obj));
	}

	public function testGetVerbatimReturnsNullForMissing() {
		$prop = $this->createMock(PropertyInterface::class);
		$prop->method('getAttributeName')->willReturn('missing');
		$obj = new \stdClass();
		$this->assertNull(Values::getVerbatim($prop, $obj));
	}

	public function testSetVerbatimAssignsAttribute() {
		$prop = $this->createMock(PropertyInterface::class);
		$prop->method('getAttributeName')->willReturn('title');
		$obj = new \stdClass();
		Values::setVerbatim($prop, $obj, 'NewTitle');
		$this->assertSame('NewTitle', $obj->title);
	}

	public function testGetAtomTimeFormatsTimestamp() {
		$prop = $this->createMock(PropertyInterface::class);
		$prop->method('getAttributeName')->willReturn('time_created');
		$obj = (object) ['time_created' => 1704067200];
		$result = Values::getAtomTime($prop, $obj);
		$this->assertIsString($result);
		$this->assertStringContainsString('2024', $result);
	}

	public function testGetAtomTimeReturnsNullForMissing() {
		$prop = $this->createMock(PropertyInterface::class);
		$prop->method('getAttributeName')->willReturn('time_created');
		$obj = new \stdClass();
		$this->assertNull(Values::getAtomTime($prop, $obj));
	}

	public function testSetLocationAssigns() {
		$prop = $this->createMock(PropertyInterface::class);
		$obj = new \stdClass();
		Values::setLocation($prop, $obj, 'NYC');
		$this->assertSame('NYC', $obj->location);
	}

	public function testGetLocationReturnsValue() {
		$prop = $this->createMock(PropertyInterface::class);
		$obj = (object) ['location' => 'NYC'];
		$this->assertSame('NYC', Values::getLocation($prop, $obj));
	}
}
