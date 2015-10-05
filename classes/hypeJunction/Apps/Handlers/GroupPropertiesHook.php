<?php

namespace hypeJunction\Apps\Handlers;

use ElggGroup;
use hypeJunction\Data\Property;

class GroupPropertiesHook {

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

		$return[] = new Property('briefdescription', array(
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

		$return[] = new Property('content_access_mode', array(
			'getter' => '\hypeJunction\Data\Values::getAccess',
			'setter' => '\hypeJunction\Data\Values::setGroupContentAccessMode',
			'type' => 'enum',
			'enum' => array(
				elgg_echo("groups:content_access_mode:unrestricted") => ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED,
				elgg_echo("groups:content_access_mode:membersonly") => ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY,
			),
			'input' => 'select',
			'validate' => array(
				'rules' => array(
					'type' => 'enum',
				)
			)
		));

		$return[] = new Property('membership', array(
			'getter' => '\hypeJunction\Data\Values::getAccess',
			'setter' => '\hypeJunction\Data\Values::setVerbatim',
			'type' => 'enum',
			'enum' => array(
				elgg_echo("groups:access:private") => ACCESS_PRIVATE,
				elgg_echo("groups:access:public") => ACCESS_PUBLIC,
			),
			'input' => 'select',
			'validate' => array(
				'rules' => array(
					'type' => 'enum',
				)
			)
		));

		$return[] = new Property('group_acl', array(
			'getter' => '\hypeJunction\Data\Values::getAccess',
			'read_only' => true,
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

		if ($full_view) {
			$profile_fields = (array) elgg_get_config('group');
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
