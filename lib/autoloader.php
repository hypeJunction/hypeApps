<?php

if (version_compare(PHP_VERSION, '5.4.0', '<=')) {
	throw new Exception('hypeApps require PHP 5.4+');
}

$path = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR;

if (!file_exists("{$path}vendor/autoload.php")) {
	throw new Exception('hypeApps can not resolve composer dependencies. Run composer install');
}

$classmap = array(
	'\\Elgg\\Di\\DiContainer' => "{$path}lib/core/Elgg/Di/DiContainer.php",
	'\\Elgg\\Di\\FactoryUncallableException' => "{$path}lib/core/Elgg/Di/FactoryUncallableException.php",
	'\\Elgg\\Di\\MissingValueException' => "{$path}lib/core/Elgg/Di/MissingValueException.php",
);

foreach ($classmap as $class => $file) {
	if (!class_exists($class) && file_exists($file)) {
		include $file;
	}
}

require_once "{$path}vendor/autoload.php";

if (\hypeJunction\Integration::isElggVersionBelow('1.9.0')) {
	require_once "{$path}lib/shims/1_8.php";
}
if (\hypeJunction\Integration::isElggVersionBelow('1.10.0')) {
	require_once "{$path}lib/shims/1_9.php";
}
if (\hypeJunction\Integration::isElggVersionBelow('1.11.0')) {
	require_once "{$path}lib/shims/1_10.php";
}
/**
 * Plugin DI Container
 * @return \hypeJunction\Apps\Plugin
 */
function hypeApps() {
	return \hypeJunction\Apps\Plugin::factory('hypeApps');
}

/**
 * BC for hypeFilestore init
 * @return \hypeJunction\Apps\Plugin
 */
if (!elgg_is_active_plugin('hypeFilestore')) {
	function hypeFilestore() {
		return hypeApps();
	}
}
