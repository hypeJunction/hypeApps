<?php

namespace hypeJunction\Apps\Handlers;

use hypeJunction\Data\Property;

class FilePropertiesHook {

	/**
	 * @param \Elgg\Event $hook Hook
	 * @return Property[]
	 */
	public static function handle(\Elgg\Event $hook) {

		$return = $hook->getValue();

		foreach (array('simpletype', 'mimetype', 'originalfilename', 'origin') as $key) {
$return[] = new Property($key, array(
				'getter' => '\hypeJunction\Data\Values::getVerbatim',
				'setter' => '\hypeJunction\Data\Values::setVerbatim',
			));
		}

		return $return;
	}

}
