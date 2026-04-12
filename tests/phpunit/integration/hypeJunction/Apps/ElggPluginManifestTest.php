<?php

namespace hypeJunction\Apps;

use Elgg\IntegrationTestCase;

class ElggPluginManifestTest extends IntegrationTestCase {

	public function up() {}
	public function down() {}

	public function getPluginID(): string {
		return '';
	}

	protected function loadManifest(): array {
		$path = dirname(__DIR__, 5) . '/elgg-plugin.php';
		$this->assertFileExists($path);
		return require $path;
	}

	public function testBootstrapClassDeclared() {
		$manifest = $this->loadManifest();
		$this->assertArrayHasKey('bootstrap', $manifest);
		$this->assertSame(Bootstrap::class, $manifest['bootstrap']);
		$this->assertTrue(class_exists(Bootstrap::class));
	}

	public function testBootstrapExtendsDefault() {
		$this->assertTrue(is_subclass_of(Bootstrap::class, \Elgg\DefaultPluginBootstrap::class));
	}

	public function testHooksSectionExists() {
		$manifest = $this->loadManifest();
		$this->assertArrayHasKey('hooks', $manifest);
		$this->assertIsArray($manifest['hooks']);
	}

	public function testEntityIconUrlHookRegistered() {
		$manifest = $this->loadManifest();
		$this->assertArrayHasKey('entity:icon:url', $manifest['hooks']);
		$this->assertArrayHasKey('all', $manifest['hooks']['entity:icon:url']);
		$handlers = $manifest['hooks']['entity:icon:url']['all'];
		$this->assertArrayHasKey(\hypeJunction\Apps\Handlers\EntityIconUrlHook::class . '::handle', $handlers);
	}

	public function testGraphPropertiesHookHasExpectedTypes() {
		$manifest = $this->loadManifest();
		$this->assertArrayHasKey('graph:properties', $manifest['hooks']);
		$types = array_keys($manifest['hooks']['graph:properties']);
		foreach (['all', 'user', 'group', 'site', 'object', 'object:blog', 'object:file', 'object:messages', 'metadata', 'annotation', 'relationship', 'river:item'] as $type) {
			$this->assertContains($type, $types, "Missing graph:properties handler for '$type'");
		}
	}

	public function testEveryHookHandlerClassExists() {
		$manifest = $this->loadManifest();
		foreach ($manifest['hooks'] as $hookName => $byType) {
			foreach ($byType as $type => $handlers) {
				foreach ($handlers as $spec => $opts) {
					[$class, $method] = explode('::', $spec);
					$this->assertTrue(class_exists($class), "Hook handler class $class not found");
					$this->assertTrue(method_exists($class, $method), "Method $method not found on $class");
				}
			}
		}
	}
}
