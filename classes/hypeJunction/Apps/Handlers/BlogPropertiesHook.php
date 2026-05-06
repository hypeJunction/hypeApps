<?php

namespace hypeJunction\Apps\Handlers;

use hypeJunction\Data\Property;

/**
 * BlogPropertiesHook class.
 */
class BlogPropertiesHook {

	/**
	 * @param \Elgg\Event $hook Hook
	 * @return Property[]
	 */
	public static function handle(\Elgg\Event $hook) {
		$return = $hook->getValue();
		$return[] = new Property('status', [
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'setter' => '\hypeJunction\Data\Values::setVerbatim',
			'type' => 'enum',
			'enum' => [
				//elgg_echo('status:unsaved_draft') => 'unsaved_draft',
				elgg_echo('status:draft') => 'draft',
				elgg_echo('status:published') => 'published',
			],
			'input' => 'select',
			'validate' => [
				'rules' => [
					'type' => 'enum',
				]
			],
		]);

		$return[] = new Property('comments_on', [
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'setter' => '\hypeJunction\Data\Values::setVerbatim',
			'type' => 'enum',
			'enum' => [
				'On',
				'Off',
			],
			'input' => 'select',
			'validate' => [
				'rules' => [
					'type' => 'enum',
				]
			],
		]);

		$return[] = new Property('excerpt', [
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'setter' => '\hypeJunction\Data\Values::setVerbatim',
			'type' => 'string',
			'input' => 'longtext',
			'output' => 'longtext',
			'validation' => [
				'rules' => [
					'maxlength' => 250,
				]
			]
		]);

		return $return;
	}
}
