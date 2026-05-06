<?php

namespace hypeJunction\Apps\Handlers;

use hypeJunction\Data\Property;

/**
 * FilePropertiesHook class.
 */
class FilePropertiesHook {

	/**
	 * @param \Elgg\Event $hook Hook
	 * @return Property[]
	 */
	public static function handle(\Elgg\Event $hook) {

		$return = $hook->getValue();

		foreach (['simpletype', 'mimetype', 'originalfilename', 'origin'] as $key) {
			$return[] = new Property($key, [
				'getter' => '\hypeJunction\Data\Values::getVerbatim',
				'setter' => '\hypeJunction\Data\Values::setVerbatim',
			]);
		}

		return $return;
	}
}
