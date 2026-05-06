<?php

namespace hypeJunction\Apps\Handlers;

use hypeJunction\Data\Property;

/**
 * RelationshipPropertiesHook class.
 */
class RelationshipPropertiesHook {

	/**
	 * @param \Elgg\Event $hook Hook
	 * @return Property[]
	 */
	public static function handle(\Elgg\Event $hook) {
		$return = $hook->getValue();
		$return[] = new Property('id', [
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'read_only' => true,
		]);

		$return[] = new Property('subject', [
			'attribute' => 'guid_one',
			'getter' => '\hypeJunction\Data\Values::getEntity',
			'read_only' => true,
		]);

		$return[] = new Property('object', [
			'attribute' => 'guid_two',
			'getter' => '\hypeJunction\Data\Values::getEntity',
			'read_only' => true,
		]);

		return $return;
	}
}
