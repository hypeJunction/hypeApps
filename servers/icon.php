<?php

$root = dirname(dirname(dirname(dirname(__FILE__))));
require_once "$root/vendor/autoload.php";

$server = new \hypeJunction\Servers\IconServer();
$server->serve();
