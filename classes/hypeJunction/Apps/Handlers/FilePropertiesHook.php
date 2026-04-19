<?php

namespace hypeJunction\Apps\Handlers;

use hypeJunction\Data\Property;

class FilePropertiesHook {

	/**
	 * @param \Elgg\Hook $hook Hook
	 * @return Property[]
	 */
	public static function handle(\Elgg\Hook $hook) {

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
