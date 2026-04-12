<?php

namespace hypeJunction\Data;

use Elgg\IntegrationTestCase;

class GraphTest extends IntegrationTestCase {

	public function up() {}
	public function down() {}

	public function getPluginID(): string {
		return '';
	}

	protected function skipIfMissing() {
		if (!elgg_get_plugin_from_id('hypeapps')) {
			$this->markTestSkipped('hypeapps plugin not installed in test DB');
		}
	}

	protected function makeGraph(): Graph {
		$plugin = elgg_get_plugin_from_id('hypeapps');
		$config = new \hypeJunction\Apps\Config($plugin);
		return new Graph($config);
	}

	public function testAliasesContainCoreTypes() {
		$this->skipIfMissing();
		$aliases = $this->makeGraph()->getAliases();
		$this->assertArrayHasKey('user', $aliases);
		$this->assertArrayHasKey('group', $aliases);
		$this->assertArrayHasKey('site', $aliases);
		$this->assertArrayHasKey('object', $aliases);
	}

	public function testUserAliasScheme() {
		$this->skipIfMissing();
		$aliases = $this->makeGraph()->getAliases();
		$this->assertSame(':user', $aliases['user']);
	}

	public function testIsExportableAcceptsElggEntity() {
		$this->skipIfMissing();
		$graph = $this->makeGraph();
		$user = $this->createUser();
		$this->assertTrue($graph->isExportable($user));
	}

	public function testIsExportableRejectsPlainObject() {
		$this->skipIfMissing();
		$graph = $this->makeGraph();
		$this->assertFalse($graph->isExportable(new \stdClass()));
		$this->assertFalse($graph->isExportable(null));
	}

	public function testGetUidForUser() {
		$this->skipIfMissing();
		$graph = $this->makeGraph();
		$user = $this->createUser();
		$uid = $graph->getUid($user);
		$this->assertSame('ue' . $user->guid, $uid);
	}

	public function testGetUidForGroup() {
		$this->skipIfMissing();
		$graph = $this->makeGraph();
		$group = $this->createGroup();
		$uid = $graph->getUid($group);
		$this->assertSame('ge' . $group->guid, $uid);
	}

	public function testGetUidReturnsFalseForNonExportable() {
		$this->skipIfMissing();
		$graph = $this->makeGraph();
		$this->assertFalse($graph->getUid(new \stdClass()));
	}

	public function testGetAliasForUser() {
		$this->skipIfMissing();
		$graph = $this->makeGraph();
		$user = $this->createUser();
		$this->assertSame(':user', $graph->getAlias($user));
	}

	public function testLimitMaxConstant() {
		$this->assertSame(100, Graph::LIMIT_MAX);
	}
}
