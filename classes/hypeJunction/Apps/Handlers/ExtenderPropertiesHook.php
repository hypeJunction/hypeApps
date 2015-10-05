<?php

namespace hypeJunction\Apps\Handlers;

use hypeJunction\Data\Property;

class ExtenderPropertiesHook {

	public function getExtenderProperties($hook, $type, $return, $params) {

		$return[] = new Property('id', array(
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'read_only' => true,
		));

		$return[] = new Property('value', array(
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'read_only' => true,
		));

		$return[] = new Property('entity', array(
			'attribute' => 'entity_guid',
			'getter' => '\hypeJunction\Data\Values::getEntity',
			'read_only' => true,
		));

		$return[] = new Property('owner', array(
			'attribute' => 'owner_guid',
			'getter' => '\hypeJunction\Data\Values::getEntity',
			'read_only' => true,
		));

		$return[] = new Property('access', array(
			'attribute' => 'access_id',
			'getter' => '\hypeJunction\Data\Values::getAccess',
			'read_only' => true,
		));

		return $return;
	}

}
