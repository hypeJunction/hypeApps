<?php

namespace hypeJunction\Apps\Handlers;

use hypeJunction\Data\Property;

class FilePropertiesHook {

	public function __invoke($hook, $type, $return, $params) {

		foreach (array('simpletype', 'mimetype', 'originalfilename', 'origin') as $key) {
			$return[] = new Property($key, array(
				'getter' => '\hypeJunction\Data\Values::getVerbatim',
				'setter' => '\hypeJunction\Data\Values::setVerbatim',
			));
		}

		return $return;
	}

}
