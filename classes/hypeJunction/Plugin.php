<?php

namespace hypeJunction;

/**
 * @property-read string                 $name
 * @property-read \ElggPlugin            $plugin
 */
abstract class Plugin extends \hypeJunction\Di\DiContainer {

	/**
	 * Constructor
	 *
	 * @param \ElggPlugin $plugin Plugin entity
	 */
	abstract protected function __construct(\ElggPlugin $plugin);

	/**
	 * Boot time logic
	 * @return void
	 */
	abstract public function boot();

}
