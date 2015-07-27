<?php

namespace hypeJunction\Controllers;

use Exception;
use hypeJunction\Exceptions\ActionValidationException;
use hypeJunction\Exceptions\InvalidEntityException;
use hypeJunction\Exceptions\PermissionsException;
use hypeJunction\Integration;

/**
 * Actions service
 */
class Actions {

	private $controllers;
	private $result;

	public function __construct(ActionResult $result) {
		$this->controllers = array();
		$this->result = $result;
	}

	public function register($action, $controller, $access = 'logged_in') {
		if (class_exists($controller)) {
			$path = dirname(dirname(dirname(dirname(__FILE__)))) . '/actions/dispatch.php';
			if (elgg_register_action($action, $path, $access)) {
				$this->controllers[$action] = $controller;
			}
		}
	}

	public function unregister($action) {
		if (elgg_unregister_action($action)) {
			if (isset($this->controllers[$action])) {
				unset($this->controllers[$action]);
			}
		}
	}

	public function getController($action) {

		if (isset($this->controllers[$action])) {
			$class = $this->controllers[$action];
			if (class_exists($class)) {
				return new $class($this->result);
			}
		}
		return false;
	}

	/**
	 * Executes an action
	 * Triggers 'action:after', $ation hook that allows you to filter the Result object
	 * 
	 * @param mixed $controller Action name or instance of Action
	 * @param bool  $feedback   Display errors and messages
	 * @return ActionResult
	 */
	public function execute($controller = null, $feedback = true) {

		try {

			$action = $this->parseActionName($controller);
			elgg_make_sticky_form($action);

			if (!$controller instanceof Action) {
				$controller = $this->getController($action);
			}

			if (!$controller instanceof Action) {
				throw new Exception("Not a valid action controller");
			}

			$controller->setup();
			if ($controller->validate() === false) {
				throw new ActionValidationException("Invalid input for action $action");
			}
			$controller->execute();
			$this->result = $controller->getResult();
		} catch (ActionValidationException $ex) {
			$this->result->addError($ex->getMessage());
			elgg_log($ex->getMessage(), 'ERROR');
		} catch (PermissionsException $ex) {
			$this->result->addError(elgg_echo('apps:permissions:error'));
			elgg_log($ex->getMessage(), 'ERROR');
		} catch (InvalidEntityException $ex) {
			$this->result->addError(elgg_echo('apps:entity:error'));
			elgg_log($ex->getMessage(), 'ERROR');
		} catch (Exception $ex) {
			$this->result->addError(elgg_echo('apps:action:error'));
			elgg_log($ex->getMessage(), 'ERROR');
		}

		$errors = $this->result->getErrors();
		$messages = $this->result->getMessages();
		if (empty($errors)) {
			elgg_clear_sticky_form($action);
		} else {
			$this->result->setForwardURL(REFERRER);
		}

		if ($feedback) {
			foreach ($errors as $error) {
				register_error($error);
			}
			foreach ($messages as $message) {
				system_message($message);
			}
		}

		return elgg_trigger_plugin_hook('action:after', $action, null, $this->result);
	}

	/**
	 * Parses action name
	 *
	 * @param string $action Action name
	 * @return string
	 */
	public function parseActionName($action = null) {
		if (is_string($action) && elgg_action_exists($action)) {
			return $action;
		}

		if (Integration::isElggVersionBelow('1.9.0')) {
			return get_input('action');
		}

		$uri = trim(get_input('__elgg_uri', ''), '/');
		$segments = explode('/', $uri);
		$handler = array_shift($segments);
		if ($handler == 'action') {
			return implode('/', $segments);
		}

		return $uri;
	}

}
