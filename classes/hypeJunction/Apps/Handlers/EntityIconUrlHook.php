<?php

namespace hypeJunction\Apps\Handlers;

class EntityIconUrlHook {

	/**
	 * Filter icon URLs to route requests via a faster handler
	 *
	 * @param \Elgg\Event $hook Hook
	 * @return string|void
	 */
	public static function handle(\Elgg\Event $hook) {

		$return = $hook->getValue();

		if (!is_null($return)) {
			// another plugin has already replaced the icon URL
			return $return;
		}

		$entity = $hook->getParam('entity');
		$size = $hook->getParam('size') ?: 'medium';

		if (!$entity->icontime || !array_key_exists($size, hypeApps()->iconFactory->getSizes($entity))) {
			// icon has not yet been created or the icon size is unknown
			return $return;
		}

		return hypeApps()->iconFactory->getURL($entity, $size);
	}

}
