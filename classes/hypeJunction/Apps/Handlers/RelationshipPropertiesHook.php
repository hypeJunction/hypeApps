<?php

namespace hypeJunction\Apps\Handlers;

use hypeJunction\Data\Property;

class RelationshipPropertiesHook {

	/**
	 * @param \Elgg\Hook $hook Hook
	 * @return Property[]
	 */
	public static function handle(\Elgg\Hook $hook) {
		$return = $hook->getValue();
		$return[] = new Property('id', array(
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'read_only' => true,
		));

		$return[] = new Property('subject', array(
			'attribute' => 'guid_one',
			'getter' => '\hypeJunction\Data\Values::getEntity',
			'read_only' => true,
		));

		$return[] = new Property('object', array(
			'attribute' => 'guid_two',
			'getter' => '\hypeJunction\Data\Values::getEntity',
			'read_only' => true,
		));

		return $return;
	}

}
