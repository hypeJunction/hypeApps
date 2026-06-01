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

	/**
	 * Result object
	 * @var ActionResult
	 */
	private $result;


	public function __construct(ActionResult $result) {
		$this->result = $result;
	}

	/**
	 * Executes an action
	 * Triggers 'action:after', $ation hook that allows you to filter the Result object
	 *
	 * @param Action $controller Action name or instance of Action
	 * @param bool  $feedback    Display errors and messages
	 * @return ActionResult
	 */
	public function execute(Action $controller, $feedback = true) {

		try {

			$action = $this->parseActionName();

			elgg_make_sticky_form($action);

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
	 * @return string
	 */
	public function parseActionName() {
		$uri = trim(get_input('__elgg_uri', ''), '/');
		$segments = explode('/', $uri);
		$handler = array_shift($segments);
		if ($handler == 'action') {
			return implode('/', $segments);
		}
		return $uri;
	}

}
