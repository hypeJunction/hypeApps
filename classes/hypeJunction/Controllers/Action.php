<?php

namespace hypeJunction\Controllers;

use hypeJunction\Exceptions\ActionValidationException;

abstract class Action {

	const ACCESS_PUBLIC = 'public';
	const ACCESS_LOGGED_IN = 'logged_in';
	const ACCESS_ADMIN = 'admin';

	/**
	 * @var ActionResult
	 */
	protected $result;

	/**
	 * Constructor
	 *
	 * @param ActionResult $result Action result
	 */
	public function __construct(ActionResult $result = null) {
		$this->result = ($result) ? : new ActionResult;
	}

	/**
	 * Returns the result object
	 * @return ActionResult
	 */
	public function getResult() {
		$this->result->data = get_object_vars($this);
		return $this->result;
	}

	/**
	 * Populate properties from input
	 * @return void
	 */
	public function setup() {
		$input_keys = array_keys((array) elgg_get_config('input'));
		$request_keys = array_keys((array) $_REQUEST);
		$keys = array_unique(array_merge($input_keys, $request_keys));
		foreach ($keys as $key) {
			if ($key) {
				$this->$key = get_input($key);
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
