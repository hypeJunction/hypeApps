<?php

namespace hypeJunction\Apps;

use Elgg\DefaultPluginBootstrap;

class Bootstrap extends DefaultPluginBootstrap {

	/**
	 * {@inheritdoc}
	 */
	public function boot() {
		require_once dirname(dirname(dirname(__DIR__))) . '/autoloader.php';
	}
}
