<?php

namespace hypeJunction\Apps;

/**
 * @property-read \ElggPlugin                               $plugin
 * @property-read \hypeJunction\Apps\Config                 $config
 * @property-read \hypeJunction\Apps\HookHandlers           $hooks
 * @property-read \hypeJunction\Controllers\Actions         $actions
 * @property-read \hypeJunction\Services\Uploader           $uploader
 * @property-read \hypeJunction\Services\IconFactory        $iconFactory
 * @property-read \hypeJunction\Services\Geopositioning     $geopositioning
 */
final class Plugin extends \hypeJunction\Plugin {

	static $singleton;

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

		$this->setClassName('geopositioning', '\hypeJunction\Services\Geopositioning');
	}

	public static function factory($id) {
		if (null === self::$singleton) {
			$plugin = elgg_get_plugin_from_id($id);
			self::$singleton = new self($plugin);
		}
		return self::$singleton;
	}

	public function boot() {
		elgg_register_event_handler('init', 'system', array($this, 'init'));
	}

	public function init() {
		elgg_register_plugin_hook_handler('entity:icon:url', 'all', array($this->hooks, 'setEntityIconUrls'));
	}

	public function deactivate() {
		$this->plugin->deactivate();
	}

}
