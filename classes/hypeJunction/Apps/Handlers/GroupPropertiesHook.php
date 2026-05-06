<?php

namespace hypeJunction\Apps\Handlers;

use ElggGroup;
use hypeJunction\Data\Property;

/**
 * GroupPropertiesHook class.
 */
class GroupPropertiesHook {

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

		$return[] = new Property('name', [
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

		$return[] = new Property('briefdescription', [
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

		$return[] = new Property('content_access_mode', [
			'getter' => '\hypeJunction\Data\Values::getAccess',
			'setter' => '\hypeJunction\Data\Values::setGroupContentAccessMode',
			'type' => 'enum',
			'enum' => [
				elgg_echo('groups:content_access_mode:unrestricted') => ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED,
				elgg_echo('groups:content_access_mode:membersonly') => ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY,
			],
			'input' => 'select',
			'validate' => [
				'rules' => [
					'type' => 'enum',
				]
			]
		]);

		$return[] = new Property('membership', [
			'getter' => '\hypeJunction\Data\Values::getAccess',
			'setter' => '\hypeJunction\Data\Values::setVerbatim',
			'type' => 'enum',
			'enum' => [
				elgg_echo('groups:access:private') => ACCESS_PRIVATE,
				elgg_echo('groups:access:public') => ACCESS_PUBLIC,
			],
			'input' => 'select',
			'validate' => [
				'rules' => [
					'type' => 'enum',
				]
			]
		]);

		$return[] = new Property('group_acl', [
			'getter' => '\hypeJunction\Data\Values::getAccess',
			'read_only' => true,
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

		if ($full_view) {
			$profile_fields = (array) elgg_get_config('group');
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
