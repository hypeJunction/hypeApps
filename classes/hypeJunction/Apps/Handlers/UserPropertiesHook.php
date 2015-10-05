<?php

namespace hypeJunction\Apps\Handlers;

use hypeJunction\Data\Property;

class UserPropertiesHook {

	/**
	 * Returns object property definitions
	 *
	 * @param string     $hook
	 * @param string     $type
	 * @param Property[] $return
	 * @param array      $params
	 * @return Property[]
	 */
	public function __invoke($hook, $type, $return, $params) {

		$full_view = elgg_extract('full_view', $params, false);

		$return[] = new Property('guid', array(
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'read_only' => true,
		));

		$return[] = new Property('name', array(
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'setter' => '\hypeJunction\Data\Values::setVerbatim',
			'required' => true,
			'type' => 'string',
			'input' => 'text',
			'output' => 'text',
			'sanitizers' => '\hypeJunction\Data\Values::htmlSpecialCharts',
		));

		$return[] = new Property('username', array(
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'setter' => '\hypeJunction\Data\Values::setVerbatim',
			'required' => true,
			'type' => 'string',
			'validation' => array(
				'rules' => array(
					'type' => 'string',
					'minlength' => elgg_get_config('minusername') ? : 4,
				),
				'callbacks' => array(
					'valid' => '\hypeJunction\Data\Validators::isValidUsername',
					'available' => '\hypeJunction\Data\Validators::isAvailableUsername',
				),
			),
		));

		$return[] = new Property('icon', array(
			'getter' => '\hypeJunction\Data\Values::getIcon',
			'setter' => '\hypeJunction\Data\Files::setIcon',
			'type' => 'file',
			'validation' => array(
				'rules' => array(
					'type' => 'image',
				)
			)
		));

		$return[] = new Property('admin', array(
			'getter' => '\hypeJunction\Data\Values::isAdmin',
			'read_only' => true,
		));

		$return[] = new Property('banned', array(
			'getter' => '\hypeJunction\Data\Values::isBanned',
			'read_only' => true,
		));

		$return[] = new Property('validated', array(
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'read_only' => true,
		));

		if ($full_view) {
			$profile_fields = (array) elgg_get_config('profile_fields');
			foreach ($profile_fields as $shortname => $type) {
				$return[] = new Property($shortname, array(
					'getter' => '\hypeJunction\Data\Values::getVerbatim',
					'setter' => '\hypeJunction\Data\Values::setVerbatim',
					'type' => $type,
					'input' => $type,
					'output' => $type,
				));
			}
		}

		return $return;
	}

}
