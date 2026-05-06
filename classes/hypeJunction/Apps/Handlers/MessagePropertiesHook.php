<?php

namespace hypeJunction\Apps\Handlers;

use hypeJunction\Data\Property;

/**
 * MessagePropertiesHook class.
 */
class MessagePropertiesHook {

	/**
	 * @param \Elgg\Event $hook Hook
	 * @return Property[]
	 */
	public static function handle(\Elgg\Event $hook) {

		$return = $hook->getValue();

		$remove = ['owner', 'container', 'tags', 'icon'];
		foreach ($return as $key => $property) {
			if ($property instanceof Property && in_array($property->getIdentifier(), $remove)) {
				unset($return[$key]);
			}
		}

		$return[] = new Property('status', [
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'setter' => '\hypeJunction\Data\Values::setVerbatim',
			'type' => 'enum',
			'enum' => [
				'read',
				'unread',
			],
			'validate' => [
				'rules' => [
					'type' => 'enum',
				]
			],
		]);

		$return[] = new Property('sender', [
			'attribute' => 'fromId',
			'getter' => '\hypeJunction\Data\Values::getEntity',
			'setter' => '\hypeJunction\Data\Values::setEntity',
			'required' => true,
			'type' => 'guid',
			'validation' => [
				'rules' => [
					'type' => 'guid',
				]
			]
		]);

		$return[] = new Property('recipients', [
			'attribute' => 'toId',
			'getter' => '\hypeJunction\Data\Values::getEntityBatch',
			'setter' => '\hypeJunction\Data\Values::setEntityBatch',
			'required' => true,
			'type' => 'guid',
			'validation' => [
				'rules' => [
					'type' => 'guid',
				]
			]
		]);

		return $return;
	}
}
