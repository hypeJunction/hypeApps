<?php

namespace hypeJunction\Apps\Handlers;

use hypeJunction\Data\Property;

class MessagePropertiesHook {

	public function __invoke($hook, $type, $return, $params) {

		$remove = array('owner', 'container', 'tags', 'icon');
		foreach ($return as $key => $property) {
			if ($property instanceof Property && in_array($property->getIdentifier(), $remove)) {
				unset($return[$key]);
			}
		}

		$return[] = new Property('status', array(
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'setter' => '\hypeJunction\Data\Values::setVerbatim',
			'type' => 'enum',
			'enum' => array(
				'read',
				'unread',
			),
			'validate' => array(
				'rules' => array(
					'type' => 'enum',
				)
			),
		));

		$return[] = new Property('sender', array(
			'attribute' => 'fromId',
			'getter' => '\hypeJunction\Data\Values::getEntity',
			'setter' => '\hypeJunction\Data\Values::setEntity',
			'required' => true,
			'type' => 'guid',
			'validation' => array(
				'rules' => array(
					'type' => 'guid',
				)
			)
		));

		$return[] = new Property('recipients', array(
			'attribute' => 'toId',
			'getter' => '\hypeJunction\Data\Values::getEntityBatch',
			'setter' => '\hypeJunction\Data\Values::setEntityBatch',
			'required' => true,
			'type' => 'guid',
			'validation' => array(
				'rules' => array(
					'type' => 'guid',
				)
			)
		));

		return $return;
	}

}
