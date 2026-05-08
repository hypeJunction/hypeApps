<?php

namespace hypeJunction\Controllers;

use Elgg\UnitTestCase;

// REFERRER constant is defined by Elgg core but fall back for unit tests
if (!defined('REFERRER')) {
	define('REFERRER', -1);
}

class ActionResultTest extends UnitTestCase {

	public function up() {}
	public function down() {}

	public function testNewResultHasNoErrorsOrMessages() {
		$r = new ActionResult();
		$this->assertSame([], $r->getErrors());
		$this->assertSame([], $r->getMessages());
	}

	public function testAddErrorAccumulates() {
		$r = new ActionResult();
		$r->addError('oops');
		$r->addError('again');
		$this->assertCount(2, $r->getErrors());
		$this->assertContains('oops', $r->getErrors());
	}

	public function testAddEmptyErrorIgnored() {
		$r = new ActionResult();
		$r->addError('');
		$this->assertCount(0, $r->getErrors());
	}

	public function testAddMessageAccumulates() {
		$r = new ActionResult();
		$r->addMessage('hello');
		$this->assertCount(1, $r->getMessages());
	}

	public function testForwardUrlSetAndGet() {
		$r = new ActionResult();
		$r->setForwardURL('/foo');
		$this->assertSame('/foo', $r->getForwardURL());
	}

	public function testForwardReasonDefaultsSystem() {
		$r = new ActionResult();
		$this->assertSame('system', $r->getForwardReason());
	}

	public function testForwardReasonSetter() {
		$r = new ActionResult();
		$r->setForwardReason('walled_garden');
		$this->assertSame('walled_garden', $r->getForwardReason());
	}

	public function testAddErrorReturnsSelf() {
		$r = new ActionResult();
		$this->assertSame($r, $r->addError('x'));
	}

	public function testAddMessageReturnsSelf() {
		$r = new ActionResult();
		$this->assertSame($r, $r->addMessage('x'));
	}
}
