<?php

namespace hypeJunction\Controllers;

class ActionResult {

	protected $forwardUrl;
	protected $forwardReason;
	protected $messages = array();
	protected $errors = array();
	public $output = '';
	public $data;

	public function __construct() {
		$this->setForwardURL();
	}

	public function setForwardURL($url = REFERER) {
		$this->forwardUrl = $url;
	}

	public function getForwardURL() {
		return ($this->forwardUrl) ? : REFERER;
	}

	public function setForwardReason($reason = 'system') {
		return $this->forwardReason = $reason;
	}

	public function getForwardReason() {
		return ($this->forwardReason) ? : 'system';
	}

	public function addError($error = '') {
		if ($error) {
			$this->errors[] = $error;
		}
		return $this;
	}

	public function addMessage($message = '') {
		if ($message) {
			$this->messages[] = $message;
		}
		return $this;
	}

	public function getErrors() {
		return $this->errors;
	}

	public function getMessages() {
		return $this->messages;
	}

}
