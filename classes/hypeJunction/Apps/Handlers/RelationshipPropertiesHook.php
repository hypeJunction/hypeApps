<?php

namespace hypeJunction\Apps\Handlers;

use hypeJunction\Data\Property;

class RelationshipPropertiesHook {

	public function __invoke($hook, $type, $return, $params) {
		$return[] = new Property('id', array(
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'read_only' => true,
		));

		$return[] = new Property('subject', array(
			'attribute' => 'guid_one',
			'getter' => '\hypeJunction\Data\Values::getEntity',
			'read_only' => true,
		));

		$return[] = new Property('object', array(
			'attribute' => 'guid_two',
			'getter' => '\hypeJunction\Data\Values::getEntity',
			'read_only' => true,
		));

		return $return;
	}

}
