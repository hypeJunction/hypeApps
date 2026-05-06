<?php

namespace hypeJunction\Apps\Handlers;

use hypeJunction\Data\Property;

/**
 * ObjectPropertiesHook class.
 */
class ObjectPropertiesHook {

	/**
	 * @param \Elgg\Event $hook Hook
	 * @return Property[]
	 */
	public static function handle(\Elgg\Event $hook) {
		$return = $hook->getValue();
		$full_view = $hook->getParam('full_view') ?: false;

		$return[] = new Property('guid', [
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'read_only' => true,
		]);

		$return[] = new Property('title', [
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'setter' => '\hypeJunction\Data\Values::setVerbatim',
			'required' => true,
			'type' => 'string',
			'input' => 'text',
			'output' => 'text',
			'sanitizers' => '\hypeJunction\Data\Values::htmlSpecialCharts',
		]);

		$return[] = new Property('icon', [
			'getter' => '\hypeJunction\Data\Values::getIcon',
			'setter' => '\hypeJunction\Data\Files::setIcon',
			'type' => 'file',
			'validation' => [
				'rules' => [
					'type' => 'image',
				]
			]
		]);

		$return[] = new Property('description', [
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'setter' => '\hypeJunction\Data\Values::setVerbatim',
			'required' => true,
			'type' => 'string',
			'input' => 'longtext',
			'output' => 'longtext',
		]);

		$return[] = new Property('owner', [
			'attribute' => 'owner_guid',
			'getter' => '\hypeJunction\Data\Values::getEntity',
			'setter' => '\hypeJunction\Data\Values::setEntity',
			'required' => true,
			'type' => 'guid',
		]);

		$return[] = new Property('container', [
			'attribute' => 'container_guid',
			'getter' => '\hypeJunction\Data\Values::getEntity',
			'setter' => '\hypeJunction\Data\Values::setEntity',
			'required' => true,
			'type' => 'guid',
		]);


		$return[] = new Property('access', [
			'attribute' => 'access_id',
			'getter' => '\hypeJunction\Data\Values::getAccess',
			'setter' => '\hypeJunction\Data\Values::setVerbatim',
			'type' => 'access',
			'input' => 'select',
			'validate' => [
				'rules' => [
					'type' => 'int',
				]
			]
		]);

		$return[] = new Property('tags', [
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'setter' => '\hypeJunction\Data\Values::setVerbatim',
			'type' => 'tags',
			'input' => 'tags',
			'output' => 'tags',
			'validate' => [
				'rules' => [
					'type' => 'string',
				]
			],
			'sanitizers' => '\hypeJunction\Data\Values::stringToTagArray',
		]);

		return $return;
	}
}
