<?php

namespace hypeJunction\Controllers;

class ParameterBag {

	/**
	 * Constructor
	 * 
	 * @param array $params An array of parameter keys => $value pairs
	 */
	public function __construct(array $params = array()) {
		foreach ($params as $key => $value) {
			$this->$key = $value;
		}
	}

}
