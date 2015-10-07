<?php

namespace hypeJunction\Controllers;

class ParameterBag implements ParameterBagInterface {

	/**
	 * Constructor
	 *
	 * @param array $params An array of parameter keys => $value pairs
	 */
	public function __construct($params = null) {
		foreach ((array) $params as $key => $value) {
			$this->$key = $value;
		}
	}

	/**
	 * Magic get
	 *
	 * @param string $name Property name
	 * @return mixed
	 */
	public function __get($name) {
		return get_input($name);
	}

}
