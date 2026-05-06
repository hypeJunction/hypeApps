<?php

namespace hypeJunction\Apps\Handlers;

use hypeJunction\Data\Property;

/**
 * SitePropertiesHook class.
 */
class SitePropertiesHook {

	/**
	 * @param \Elgg\Event $hook Hook
	 * @return Property[]
	 */
	public static function handle(\Elgg\Event $hook) {

		$return = $hook->getValue();

		foreach (['name', 'description', 'email', 'url', 'guid'] as $key) {
			$return[] = new Property($key, [
				'getter' => '\hypeJunction\Data\Values::getVerbatim',
				'read_only' => true,
			]);
		}

		foreach (['allow_registration', 'default_access', 'debug', 'walled_garden'] as $key) {
			$return[] = new Property($key, [
				'getter' => '\hypeJunction\Data\Values::getElggConfig',
				'read_only' => true,
			]);
		}

		return $return;
	}
}
