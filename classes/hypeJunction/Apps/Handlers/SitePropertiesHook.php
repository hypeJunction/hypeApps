<?php

namespace hypeJunction\Apps\Handlers;

use hypeJunction\Data\Property;

class SitePropertiesHook {

	public function __invoke($hook, $type, $return, $params) {

		foreach (array('name', 'description', 'email', 'url', 'guid') as $key) {
			$return[] = new Property($key, array(
				'getter' => '\hypeJunction\Data\Values::getVerbatim',
				'read_only' => true,
			));
		}

		foreach (array('allow_registration', 'default_access', 'debug', 'walled_garden') as $key) {
			$return[] = new Property($key, array(
				'getter' => '\hypeJunction\Data\Values::getElggConfig',
				'read_only' => true,
			));
		}

		return $return;
	}
}
