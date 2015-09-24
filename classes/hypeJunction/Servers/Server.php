<?php

namespace hypeJunction\Servers;

/**
 * Abstract server
 */
abstract class Server {

	/**
	 * Serves an icon
	 * Terminates the script and sends headers on error
	 * @return void
	 */
	abstract public function serve();

	/**
	 * Retreive values from datalists table
	 *
	 * @param array $names Parameter names to retreive
	 * @return array
	 */
	protected function getDatalistValue(array $names = array()) {

		$services = \hypeJunction\Integration::getServiceProvider();
		foreach ($names as $name) {
			$values[$name] = $services->datalist->get($name);
		}

		return $values;
	}

	/**
	 * Returns request query value
	 *
	 * @param string $name    Query name
	 * @param mixed  $default Default value
	 * @return mixed
	 */
	protected function get($name, $default = null) {
		if (isset($_GET[$name])) {
			return $_GET[$name];
		}
		return $default;
	}
}
