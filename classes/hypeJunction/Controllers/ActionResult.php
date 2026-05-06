<?php

namespace hypeJunction\Controllers;

/**
 * ActionResult class.
 */
class ActionResult {

	protected $forwardUrl;

	protected $forwardReason;

	protected $messages = [];

	protected $errors = [];

	public $output = '';

	public $data;

	/**
	 * __construct.
	 *
	 * @return mixed
	 */
	public function __construct() {
		$this->setForwardURL();
	}

	/**
	 * setForwardURL.
	 *
	 * @param mixed $url url
	 *
	 * @return mixed
	 */
	public function setForwardURL($url = null) {
		$this->forwardUrl = $url ?? REFERER;
	}

	/**
	 * getForwardURL.
	 *
	 * @return mixed
	 */
	public function getForwardURL() {
		return ($this->forwardUrl) ?: REFERER;
	}

	/**
	 * setForwardReason.
	 *
	 * @param mixed $reason reason
	 *
	 * @return mixed
	 */
	public function setForwardReason($reason = 'system') {
		$this->forwardReason = $reason;
		return $this->forwardReason;
	}

	/**
	 * getForwardReason.
	 *
	 * @return mixed
	 */
	public function getForwardReason() {
		return ($this->forwardReason) ?: 'system';
	}

	/**
	 * addError.
	 *
	 * @param mixed $error error
	 *
	 * @return mixed
	 */
	public function addError($error = '') {
		if ($error) {
			$this->errors[] = $error;
		}

		return $this;
	}

	/**
	 * addMessage.
	 *
	 * @param mixed $message message
	 *
	 * @return mixed
	 */
	public function addMessage($message = '') {
		if ($message) {
			$this->messages[] = $message;
		}

		return $this;
	}

	/**
	 * getErrors.
	 *
	 * @return mixed
	 */
	public function getErrors() {
		return $this->errors;
	}

	/**
	 * getMessages.
	 *
	 * @return mixed
	 */
	public function getMessages() {
		return $this->messages;
	}
}
