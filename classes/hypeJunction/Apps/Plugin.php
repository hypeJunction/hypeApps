<?php

namespace hypeJunction\Apps;

/**
 * @property-read \ElggPlugin                        $plugin
 * @property-read \hypeJunction\Apps\Config          $config
 * @property-read \hypeJunction\Apps\HookHandlers    $hooks
 * @property-read \hypeJunction\Controllers\Actions  $actions
 * @property-read \hypeJunction\Services\Uploader    $uploader
 * @property-read \hypeJunction\Services\IconFactory $iconFactory
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
	}

}
