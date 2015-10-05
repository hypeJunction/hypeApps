<?php

namespace hypeJunction\Apps\Handlers;

use hypeJunction\Data\Property;

class BlogPropertiesHook {

	public function __invoke($hook, $type, $return, $params) {
		$return[] = new Property('status', array(
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'setter' => '\hypeJunction\Data\Values::setVerbatim',
			'type' => 'enum',
			'enum' => array(
				//elgg_echo('status:unsaved_draft') => 'unsaved_draft',
				elgg_echo('status:draft') => 'draft',
				elgg_echo('status:published') => 'published',
			),
			'input' => 'select',
			'validate' => array(
				'rules' => array(
					'type' => 'enum',
				)
			),
		));

		$return[] = new Property('comments_on', array(
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'setter' => '\hypeJunction\Data\Values::setVerbatim',
			'type' => 'enum',
			'enum' => array(
				'On',
				'Off',
			),
			'input' => 'select',
			'validate' => array(
				'rules' => array(
					'type' => 'enum',
				)
			),
		));

		$return[] = new Property('excerpt', array(
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'setter' => '\hypeJunction\Data\Values::setVerbatim',
			'type' => 'string',
			'input' => 'longtext',
			'output' => 'longtext',
			'validation' => array(
				'rules' => array(
					'maxlength' => 250,
				)
			)
		));

		return $return;
	}

}
