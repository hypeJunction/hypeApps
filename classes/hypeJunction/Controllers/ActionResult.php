<?php

namespace hypeJunction\Controllers;

class ActionResult {

	/** @var mixed */
    protected $forwardUrl;
	/** @var mixed */
    protected $forwardReason;
	/** @var mixed */
    protected $messages = array();
	/** @var mixed */
    protected $errors = array();
	/** @var mixed */
    public $output = '';
	/** @var mixed */
    public $data;

	public function __construct() {
		$this->setForwardURL();
	}

	/**
     * @param mixed $url
     */
    public function setForwardURL($url = null) {
		$this->forwardUrl = $url ?? REFERER;
	}

	/**
     * @return mixed
     */
    public function getForwardURL() {
		return ($this->forwardUrl) ? : REFERER;
	}

	/**
     * @param mixed $reason
     * @return mixed
     */
    public function setForwardReason($reason = 'system') {
		return $this->forwardReason = $reason;
	}

	/**
     * @return mixed
     */
    public function getForwardReason() {
		return ($this->forwardReason) ? : 'system';
	}

	/**
     * @param mixed $error
     * @return mixed
     */
    public function addError($error = '') {
		if ($error) {
			$this->errors[] = $error;
		}
		return $this;
	}

	/**
     * @param mixed $message
     * @return mixed
     */
    public function addMessage($message = '') {
		if ($message) {
			$this->messages[] = $message;
		}
		return $this;
	}

	/**
     * @return mixed
     */
    public function getErrors() {
		return $this->errors;
	}

	/**
     * @return mixed
     */
    public function getMessages() {
		return $this->messages;
	}

}
