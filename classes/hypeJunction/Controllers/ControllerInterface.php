<?php

namespace hypeJunction\Controllers;

use hypeJunction\Data\GraphInterface;
use hypeJunction\Data\PropertyInterface;
use hypeJunction\Http\RequestInterface;

/**
 * Controller interface
 */
interface ControllerInterface {

	/**
	 * Constructor
	 * 
	 * @param RequestInterface $request Http Request
	 * @param GraphInterface    $graph  Graph
	 */
	public function __construct(RequestInterface $request, GraphInterface $graph);

	/**
	 * Returns parameter config for a given HTTP method
	 * Returns false to indicate that the method is not allowed
	 * 
	 * @param string $method HTTP request method
	 * @return PropertyInterface[]|false
	 */
	public function params($method);

	/**
	 * Executes a GET request
	 *
	 * @param ParameterBagInterface $params Input params
	 * @return mixed
	 * @throws ControllerException
	 */
	public function get(ParameterBagInterface $params);

	/**
	 * Executes a POST request
	 *
	 * @param ParameterBagInterface $params Input params
	 * @return mixed
	 * @throws ControllerException
	 */
	public function post(ParameterBagInterface $params);

	/**
	 * Executes a PUT request
	 *
	 * @param ParameterBagInterface $params Input params
	 * @return mixed
	 * @throws ControllerException
	 */
	public function put(ParameterBagInterface $params);

	/**
	 * Executes a GET request
	 *
	 * @param ParameterBagInterface $params Input params
	 * @return mixed
	 * @throws ControllerException
	 */
	public function delete(ParameterBagInterface $params);

	/**
	 * Calls the controller
	 * This prepares the parameters and executes the corresponding method
	 * @return mixed
	 * @throws ControllerException
	 */
	public function call();
}
