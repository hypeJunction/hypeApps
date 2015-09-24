<?php

namespace hypeJunction\Apps\Handlers;

class EntityIconUrlHook {

	/**
	 * Filter icon URLs to route requests via a faster handler
	 *
	 * @param string $hook   "entity:icon:url"
	 * @param string $type   "all"
	 * @param string $return URL
	 * @param array  $params Hook params
	 * @return string
	 */
	public function __invoke($hook, $type, $return, $params) {

		if (!is_null($return)) {
			// another plugin has already replaced the icon URL
			return $return;
		}

		$entity = elgg_extract('entity', $params);
		$size = elgg_extract('size', $params, 'medium');

		if (!$entity->icontime || !array_key_exists($size, hypeApps()->iconFactory->getSizes($entity))) {
			// icon has not yet been created or the icon size is unknown
			return $return;
		}

		return hypeApps()->iconFactory->getURL($entity, $size);
	}

}
