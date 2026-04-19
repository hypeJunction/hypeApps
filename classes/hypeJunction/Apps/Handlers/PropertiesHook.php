<?php

namespace hypeJunction\Apps\Handlers;

use hypeJunction\Data\Property;

class PropertiesHook {

	/**
	 * Returns object property definitions
	 *
	 * @param \Elgg\Hook $hook Hook
	 * @return Property[]
	 */
	public static function handle(\Elgg\Hook $hook) {

		$return = $hook->getValue();

$return[] = new Property('alias', array(
			'getter' => '\hypeJunction\Data\Values::getAlias',
			'read_only' => true,
		));

$return[] = new Property('type', array(
			'getter' => '\hypeJunction\Data\Values::getType',
			'read_only' => true,
		));

$return[] = new Property('subtype', array(
			'getter' => '\hypeJunction\Data\Values::getSubtype',
			'read_only' => true,
		));

$return[] = new Property('uid', array(
			'getter' => '\hypeJunction\Data\Values::getUid',
			'read_only' => true,
		));

$return[] = new Property('url', array(
			'getter' => '\hypeJunction\Data\Values::getUrl',
			'read_only' => true,
		));

$return[] = new Property('time_created', array(
			'getter' => '\hypeJunction\Data\Values::getAtomTime',
			'read_only' => true,
		));

$return[] = new Property('enabled', array(
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'read_only' => true,
		));

		return $return;
	}

}
