<?php

namespace hypeJunction;

/**
 * @property-read string                 $name
 * @property-read \ElggPlugin            $plugin
 */
abstract class Plugin extends \Elgg\Di\DiContainer {

	abstract protected function __construct(\ElggPlugin $plugin);

	abstract public static function factory($id);

	abstract public function boot();

	abstract public function deactivate();
}
