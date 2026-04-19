<?php

namespace hypeJunction\Apps\Handlers;

use hypeJunction\Data\Property;

class SitePropertiesHook {

	/**
	 * @param \Elgg\Hook $hook Hook
	 * @return Property[]
	 */
	public static function handle(\Elgg\Hook $hook) {

		$return = $hook->getValue();

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
