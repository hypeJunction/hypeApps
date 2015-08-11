<?php

if (version_compare(PHP_VERSION, '5.5.0', '<')) {
	throw new Exception('hypeApps require PHP 5.5+');
}

$elgg_root = dirname(dirname(dirname(dirname(__FILE__))));
$plugin_root = dirname(dirname(__FILE__));

if (file_exists("{$elgg_root}/vendor/autoload.php")) {
	// check if composer dependencies are distributed with the plugin
	require_once "{$elgg_root}/vendor/autoload.php";
}

if (file_exists("{$plugin_root}/vendor/autoload.php")) {
	// check if composer dependencies are distributed with the plugin
	require_once "{$plugin_root}/vendor/autoload.php";
}

if (\hypeJunction\Integration::isElggVersionBelow('1.9.0')) {
	require_once __DIR__ . "/shims/1_8.php";
}

/**
 * Plugin DI Container
 * @return \hypeJunction\Apps\Plugin
 */
function hypeApps() {
	return \hypeJunction\Apps\Plugin::factory();
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
