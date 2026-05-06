<?php

namespace hypeJunction\Apps\Handlers;

use hypeJunction\Data\Property;

/**
 * ExtenderPropertiesHook class.
 */
class ExtenderPropertiesHook {

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

		$return[] = new Property('value', [
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'read_only' => true,
		]);

		$return[] = new Property('entity', [
			'attribute' => 'entity_guid',
			'getter' => '\hypeJunction\Data\Values::getEntity',
			'read_only' => true,
		]);

		$return[] = new Property('owner', [
			'attribute' => 'owner_guid',
			'getter' => '\hypeJunction\Data\Values::getEntity',
			'read_only' => true,
		]);

		$return[] = new Property('access', [
			'attribute' => 'access_id',
			'getter' => '\hypeJunction\Data\Values::getAccess',
			'read_only' => true,
		]);

		return $return;
	}
}
