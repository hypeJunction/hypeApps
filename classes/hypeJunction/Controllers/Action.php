<?php

namespace hypeJunction\Controllers;

use hypeJunction\Exceptions\ActionValidationException;

abstract class Action {

	const ACCESS_PUBLIC = 'public';
	const ACCESS_LOGGED_IN = 'logged_in';
	const ACCESS_ADMIN = 'admin';

	/**
	 * Parameter bag
	 * @var ParameterBag
	 */
	protected $params;

	/**
	 * Result object
	 * @var ActionResult
	 */
	protected $result;

	/**
	 * Constructor
	 *
	 * @param ActionResult $result Action result
	 * @param ParameterBag $params Parameter bag
	 */
	public function __construct(ActionResult $result = null, ParameterBag $params = null) {
		$this->result = ($result) ? : new ActionResult();
		$this->params = ($params) ? : new ParameterBag();
	}

	/**
	 * Access value from a parameter bag
	 * 
	 * @param string $name Param name
	 * @return mixed
	 */
	public function __get($name) {
		return $this->params->$name;
	}

	/**
	 * Add a value to parameter bag
	 *
	 * @param string $name  Parameter name
	 * @param mixed  $value Parameter value
	 * @return void
	 */
	public function __set($name, $value) {
		$this->params->$name = $value;
	}

	/**
	 * Check if value is set
	 * 
	 * @param string $name Parameter name
	 * @return bool
	 */
	public function __isset($name) {
		return isset($this->params->$name);
	}

	/**
	 * Get parameter bag
	 * @return ParameterBag
	 */
	public function getParams() {
		return $this->params;
	}

	/**
	 * Returns the result object
	 * @return ActionResult
	 */
	public function getResult() {
		$this->result->data = $this->getParams();
		return $this->result;
	}

	/**
	 * Populate params from input values
	 * @return void
	 */
	public function setup() {
		$input_keys = array_keys((array) elgg_get_config('input'));
		$request_keys = array_keys((array) $_REQUEST);
		$keys = array_unique(array_merge($input_keys, $request_keys));
		foreach ($keys as $key) {
			if ($key) {
				$this->params->$key = get_input($key);
			}
		}
	}

	/**
	 * Validates user input
	 * @throws ActionValidationException
	 * @return bool
	 */
	abstract function validate();

	/**
	 * Executes an action
	 * @return void
	 */
	abstract function execute();
}
