<?php

namespace hypeJunction\Apps\Handlers;

use hypeJunction\Data\Property;

/**
 * PropertiesHook class.
 */
class PropertiesHook {

	/**
	 * Returns object property definitions
	 *
	 * @param \Elgg\Event $hook Hook
	 * @return Property[]
	 */
	public static function handle(\Elgg\Event $hook) {

		$return = $hook->getValue();

		$return[] = new Property('alias', [
			'getter' => '\hypeJunction\Data\Values::getAlias',
			'read_only' => true,
		]);

		$return[] = new Property('type', [
			'getter' => '\hypeJunction\Data\Values::getType',
			'read_only' => true,
		]);

		$return[] = new Property('subtype', [
			'getter' => '\hypeJunction\Data\Values::getSubtype',
			'read_only' => true,
		]);

		$return[] = new Property('uid', [
			'getter' => '\hypeJunction\Data\Values::getUid',
			'read_only' => true,
		]);

		$return[] = new Property('url', [
			'getter' => '\hypeJunction\Data\Values::getUrl',
			'read_only' => true,
		]);

		$return[] = new Property('time_created', [
			'getter' => '\hypeJunction\Data\Values::getAtomTime',
			'read_only' => true,
		]);

		$return[] = new Property('enabled', [
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'read_only' => true,
		]);

		return $return;
	}
}
