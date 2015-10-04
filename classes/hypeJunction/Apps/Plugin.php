<?php

namespace hypeJunction\Apps;

/**
 * @property-read \ElggPlugin                        $plugin
 * @property-read \hypeJunction\Apps\Config          $config
 * @property-read \hypeJunction\Apps\HookHandlers    $hooks
 * @property-read \hypeJunction\Controllers\Actions  $actions
 * @property-read \hypeJunction\Services\Uploader    $uploader
 * @property-read \hypeJunction\Services\IconFactory $iconFactory
 * @property-read \hypeJunction\Data\Graph           $graph
 */
final class Plugin extends \hypeJunction\Plugin {

	/**
	 * Instance
	 * @var self
	 */
	static $instance;

	/**
	 * {@inheritdoc}
	 */
	protected function __construct(\ElggPlugin $plugin) {

		$this->setValue('plugin', $plugin);

		$this->setFactory('config', function (Plugin $p) {
			return new \hypeJunction\Apps\Config($p->plugin);
		});

		$this->setFactory('hooks', function (Plugin $p) {
			return new \hypeJunction\Apps\HookHandlers($p->config, $p->iconFactory);
		});

		$this->setFactory('actions', function(Plugin $p) {
			return new \hypeJunction\Controllers\Actions(new \hypeJunction\Controllers\ActionResult());
		});

		$this->setFactory('uploader', function(Plugin $p) {
			return new \hypeJunction\Services\Uploader($p->config, $p->iconFactory);
		});

		$this->setFactory('iconFactory', function(Plugin $p) {
			return new \hypeJunction\Services\IconFactory($p->config);
		});

		$this->setFactory('graph', function(Plugin $p) {
			return new \hypeJunction\Data\Graph($p->config);
		});
	}

	/**
	 * {@inheritdoc}
	 */
	public static function factory() {
		if (null === self::$instance) {
			$plugin = elgg_get_plugin_from_id('hypeApps');
			self::$instance = new self($plugin);
		}
		return self::$instance;
	}

	/**
	 * {@inheritdoc}
	 */
	public function boot() {
		elgg_register_event_handler('init', 'system', array($this, 'init'));
	}

	/**
	 * 'init','system' callback
	 */
	public function init() {
		elgg_register_plugin_hook_handler('entity:icon:url', 'all', array($this->hooks, 'setEntityIconUrls'));

		elgg_register_plugin_hook_handler('graph:properties', 'all', array($this->hooks, 'getProperties'));

		elgg_register_plugin_hook_handler('graph:properties', 'user', array($this->hooks, 'getUserProperties'));
		elgg_register_plugin_hook_handler('graph:properties', 'group', array($this->hooks, 'getGroupProperties'));
		elgg_register_plugin_hook_handler('graph:properties', 'site', array($this->hooks, 'getSiteProperties'));

		elgg_register_plugin_hook_handler('graph:properties', 'object', array($this->hooks, 'getObjectProperties'));
		elgg_register_plugin_hook_handler('graph:properties', 'object:blog', array($this->hooks, 'getBlogProperties'));
		elgg_register_plugin_hook_handler('graph:properties', 'object:file', array($this->hooks, 'getFileProperties'));
		elgg_register_plugin_hook_handler('graph:properties', 'object:messages', array($this->hooks, 'getMessageProperties'));

		elgg_register_plugin_hook_handler('graph:properties', 'metadata', array($this->hooks, 'getExtenderProperties'));
		elgg_register_plugin_hook_handler('graph:properties', 'annotation', array($this->hooks, 'getExtenderProperties'));
		elgg_register_plugin_hook_handler('graph:properties', 'relationship', array($this->hooks, 'getRelationshipProperties'));
		elgg_register_plugin_hook_handler('graph:properties', 'river:item', array($this->hooks, 'getRiverProperties'));

	}

}
