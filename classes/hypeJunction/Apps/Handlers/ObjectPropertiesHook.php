<?php

namespace hypeJunction\Apps\Handlers;

use hypeJunction\Data\Property;

class ObjectPropertiesHook {

	public function __invoke($hook, $type, $return, $params) {
		$full_view = elgg_extract('full_view', $params, false);

		$return[] = new Property('guid', array(
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'read_only' => true,
		));

		$return[] = new Property('title', array(
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'setter' => '\hypeJunction\Data\Values::setVerbatim',
			'required' => true,
			'type' => 'string',
			'input' => 'text',
			'output' => 'text',
			'sanitizers' => '\hypeJunction\Data\Values::htmlSpecialCharts',
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

		$return[] = new Property('description', array(
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'setter' => '\hypeJunction\Data\Values::setVerbatim',
			'required' => true,
			'type' => 'string',
			'input' => 'longtext',
			'output' => 'longtext',
		));

		$return[] = new Property('owner', array(
			'attribute' => 'owner_guid',
			'getter' => '\hypeJunction\Data\Values::getEntity',
			'setter' => '\hypeJunction\Data\Values::setEntity',
			'required' => true,
			'type' => 'guid',
		));

		$return[] = new Property('container', array(
			'attribute' => 'container_guid',
			'getter' => '\hypeJunction\Data\Values::getEntity',
			'setter' => '\hypeJunction\Data\Values::setEntity',
			'required' => true,
			'type' => 'guid',
		));


		$return[] = new Property('access', array(
			'attribute' => 'access_id',
			'getter' => '\hypeJunction\Data\Values::getAccess',
			'setter' => '\hypeJunction\Data\Values::setVerbatim',
			'type' => 'access',
			'input' => 'select',
			'validate' => array(
				'rules' => array(
					'type' => 'int',
				)
			)
		));

		$return[] = new Property('tags', array(
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'setter' => '\hypeJunction\Data\Values::setVerbatim',
			'type' => 'tags',
			'input' => 'tags',
			'output' => 'tags',
			'validate' => array(
				'rules' => array(
					'type' => 'string',
				)
			),
			'sanitizers' => '\hypeJunction\Data\Values::stringToTagArray',
		));

		return $return;
	}

}
