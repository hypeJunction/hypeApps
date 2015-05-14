<?php

namespace hypeJunction;

/**
 * @property-read string                 $name
 * @property-read \ElggPlugin            $plugin
 */
abstract class Plugin extends \Elgg\Di\DiContainer {

	/**
	 * Constructor
	 *
	 * @param \ElggPlugin $plugin Plugin entity
	 */
	abstract protected function __construct(\ElggPlugin $plugin);

	/**
	 * Public factory
	 * @return self
	 */
	abstract public static function factory();

	/**
	 * Boot time logic
	 * @return void
	 */
	abstract public function boot();

}
