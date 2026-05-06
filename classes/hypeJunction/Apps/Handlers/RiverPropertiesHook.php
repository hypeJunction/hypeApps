<?php

namespace hypeJunction\Apps\Handlers;

use hypeJunction\Data\Property;

/**
 * RiverPropertiesHook class.
 */
class RiverPropertiesHook {

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

		$return[] = new Property('action', [
			'attribute' => 'action_type',
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'read_only' => true,
		]);

		$return[] = new Property('subject', [
			'attribute' => 'subject_guid',
			'getter' => '\hypeJunction\Data\Values::getEntity',
			'read_only' => true,
		]);

		$return[] = new Property('object', [
			'attribute' => 'object_guid',
			'getter' => '\hypeJunction\Data\Values::getEntity',
			'read_only' => true,
		]);

		$return[] = new Property('target', [
			'attribute' => 'target_guid',
			'getter' => '\hypeJunction\Data\Values::getEntity',
			'read_only' => true,
		]);

		$return[] = new Property('annotation', [
			'attribute' => 'annotation_id',
			'getter' => '\hypeJunction\Data\Values::getAnnotation',
			'read_only' => true,
		]);

		$return[] = new Property('access', [
			'attribute' => 'access_id',
			'getter' => '\hypeJunction\Data\Values::getAccess',
			'read_only' => true,
		]);

		foreach ($return as $key => $value) {
			if ($value instanceof Property && $value->getIdentifier() == 'time_created') {
				unset($return[$key]);
			}
		}

		$return[] = new Property('time_created', [
			'attribute' => 'posted',
			'getter' => '\hypeJunction\Data\Values::getAtomTime',
			'read_only' => true,
		]);

		return $return;
	}
}
