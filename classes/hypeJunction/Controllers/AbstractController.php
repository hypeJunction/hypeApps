<?php

namespace hypeJunction\Controllers;

use hypeJunction\Data\Graph;
use hypeJunction\Data\GraphInterface;
use hypeJunction\Http\Request;
use hypeJunction\Http\RequestInterface;
use hypeJunction\Http\Response;

/**
 * Abstract resource controller
 */
abstract class AbstractController implements ControllerInterface {

	/**
	 * Http request
	 * @var Request
	 */
	protected $request;

	/**
	 * Graph lib
	 * @var Graph
	 */
	protected $graph;

	/**
	 * Request
	 * 
	 * @param RequestInterface $request Http request
	 * @param GraphInterface   $graph   Node lib
	 */
	public function __construct(RequestInterface $request, GraphInterface $graph) {
		$this->request = $request;
		$this->graph = $graph;
	}

	/**
	 * Throws API exception for unknown methods
	 * 
	 * @param string $name      Method name
	 * @param array  $arguments Arguments
	 * @thorws ControllerException
	 */
	public function __call($name, $arguments) {
		throw new ControllerException("Method $name not allowed", Response::HTTP_METHOD_NOT_ALLOWED);
	}

	/**
	 * {@inheritdoc}
	 */
	public function call($method = null) {

		if (!$method) {
			$method = $this->request->getMethod();
		}

		$call = strtolower($method);
		$params = $this->params($method);

		if ($params === false || $params === null || !is_callable(array($this, $call))) {
			throw new ControllerException("Method not allowed", Response::HTTP_METHOD_NOT_ALLOWED);
		}

		return $this->$call(new ParameterBag());
	}

	/**
	 * {@inheritdoc}
	 */
	public function get(ParameterBagInterface $params) {
		throw new ControllerException("Method not allowed", Response::HTTP_METHOD_NOT_ALLOWED);
	}

	/**
	 * {@inheritdoc}
	 */
	public function post(ParameterBagInterface $params) {
		throw new ControllerException("Method not allowed", Response::HTTP_METHOD_NOT_ALLOWED);
	}

	/**
	 * {@inheritdoc}
	 */
	public function put(ParameterBagInterface $params) {
		throw new ControllerException("Method not allowed", Response::HTTP_METHOD_NOT_ALLOWED);
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete(ParameterBagInterface $params) {
		throw new ControllerException("Method not allowed", Response::HTTP_METHOD_NOT_ALLOWED);
	}

}
