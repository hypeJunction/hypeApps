<?php

$plugin_root = dirname(dirname(__FILE__));
$root = dirname(dirname($plugin_root));
$alt_root = dirname(dirname(dirname($root)));

foreach (array($plugin_root, $root, $alt_root) as $autoloader_path) {
	if (file_exists("$autoloader_path/vendor/autoload.php")) {
		require_once "$autoloader_path/vendor/autoload.php";
	}
}

$server = new \hypeJunction\Servers\IconServer();
$server->serve();
