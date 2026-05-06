<?php

namespace hypeJunction\Apps\Handlers;

use hypeJunction\Data\Property;

/**
 * UserPropertiesHook class.
 */
class UserPropertiesHook {

	/**
	 * Returns object property definitions
	 *
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

		$return[] = new Property('name', [
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'setter' => '\hypeJunction\Data\Values::setVerbatim',
			'required' => true,
			'type' => 'string',
			'input' => 'text',
			'output' => 'text',
			'sanitizers' => '\hypeJunction\Data\Values::htmlSpecialCharts',
		]);

		$return[] = new Property('username', [
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'setter' => '\hypeJunction\Data\Values::setVerbatim',
			'required' => true,
			'type' => 'string',
			'validation' => [
				'rules' => [
					'type' => 'string',
					'minlength' => elgg_get_config('minusername') ?: 4,
				],
				'callbacks' => [
					'valid' => '\hypeJunction\Data\Validators::isValidUsername',
					'available' => '\hypeJunction\Data\Validators::isAvailableUsername',
				],
			],
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

		$return[] = new Property('admin', [
			'getter' => '\hypeJunction\Data\Values::isAdmin',
			'read_only' => true,
		]);

		$return[] = new Property('banned', [
			'getter' => '\hypeJunction\Data\Values::isBanned',
			'read_only' => true,
		]);

		$return[] = new Property('validated', [
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'read_only' => true,
		]);

		if ($full_view) {
			$profile_fields = (array) elgg_get_config('profile_fields');
			foreach ($profile_fields as $shortname => $type) {
				$return[] = new Property($shortname, [
					'getter' => '\hypeJunction\Data\Values::getVerbatim',
					'setter' => '\hypeJunction\Data\Values::setVerbatim',
					'type' => $type,
					'input' => $type,
					'output' => $type,
				]);
			}
		}

		return $return;
	}
}
