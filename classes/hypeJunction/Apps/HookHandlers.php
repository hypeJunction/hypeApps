<?php

namespace hypeJunction\Apps;

use ElggGroup;
use hypeJunction\Data\Property;
use hypeJunction\Services\IconFactory;

/**
 * Plugin hooks service
 */
class HookHandlers {

	/**
	 *
	 * @var Config
	 */
	private $config;

	/**
	 *
	 * @var IconFactory
	 */
	private $iconFactory;

	/**
	 * Constructor
	 *
	 * @param Config          $config  Config
	 * @param IconFactory $factory Icon factory
	 */
	public function __construct(Config $config, IconFactory $factory) {
		$this->config = $config;
		$this->iconFactory = $factory;
	}

	/**
	 * Filter icon URLs to route requests via a faster handler
	 *
	 * @param string $hook   "entity:icon:url"
	 * @param string $type   "all"
	 * @param string $return URL
	 * @param array  $params Hook params
	 * @return string
	 */
	public function setEntityIconUrls($hook, $type, $return, $params) {

		if (!is_null($return)) {
			// another plugin has already replaced the icon URL
			return $return;
		}

		$entity = elgg_extract('entity', $params);
		$size = elgg_extract('size', $params, 'medium');

		if (!$entity->icontime || !array_key_exists($size, $this->iconFactory->getSizes($entity))) {
			// icon has not yet been created or the icon size is unknown
			return $return;
		}

		return $this->iconFactory->getURL($entity, $size);
	}

	/**
	 * Returns object property definitions
	 *
	 * @param string     $hook
	 * @param string     $type
	 * @param Property[] $return
	 * @param array      $params
	 * @return Property[]
	 */
	public function getProperties($hook, $type, $return, $params) {

		$return[] = new Property('alias', array(
			'getter' => '\hypeJunction\Data\Values::getAlias',
			'read_only' => true,
		));

		$return[] = new Property('type', array(
			'getter' => '\hypeJunction\Data\Values::getType',
			'read_only' => true,
		));

		$return[] = new Property('subtype', array(
			'getter' => '\hypeJunction\Data\Values::getSubtype',
			'read_only' => true,
		));

		$return[] = new Property('uid', array(
			'getter' => '\hypeJunction\Data\Values::getUid',
			'read_only' => true,
		));

		$return[] = new Property('url', array(
			'getter' => '\hypeJunction\Data\Values::getUrl',
			'read_only' => true,
		));

		$return[] = new Property('time_created', array(
			'getter' => '\hypeJunction\Data\Values::getAtomTime',
			'read_only' => true,
		));

		$return[] = new Property('enabled', array(
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'read_only' => true,
		));

		return $return;
	}

	/**
	 * Returns object property definitions
	 *
	 * @param string     $hook
	 * @param string     $type
	 * @param Property[] $return
	 * @param array      $params
	 * @return Property[]
	 */
	public function getUserProperties($hook, $type, $return, $params) {

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

	public function getGroupProperties($hook, $type, $return, $params) {

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

	public function getSiteProperties($hook, $type, $return, $params) {

		foreach (array('name', 'description', 'email', 'url', 'guid') as $key) {
			$return[] = new Property($key, array(
				'getter' => '\hypeJunction\Data\Values::getVerbatim',
				'read_only' => true,
			));
		}

		foreach (array('allow_registration', 'default_access', 'debug', 'walled_garden') as $key) {
			$return[] = new Property($key, array(
				'getter' => '\hypeJunction\Data\Values::getElggConfig',
				'read_only' => true,
			));
		}

		return $return;
	}

	public function getObjectProperties($hook, $type, $return, $params) {
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

	public function getBlogProperties($hook, $type, $return, $params) {
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

	public function getFileProperties($hook, $type, $return, $params) {

		foreach (array('simpletype', 'mimetype', 'originalfilename', 'origin') as $key) {
			$return[] = new Property($key, array(
				'getter' => '\hypeJunction\Data\Values::getVerbatim',
				'setter' => '\hypeJunction\Data\Values::setVerbatim',
			));
		}

		return $return;
	}

	public function getMessageProperties($hook, $type, $return, $params) {
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

	public function getRelationshipProperties($hook, $type, $return, $params) {
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

	public function getRiverProperties($hook, $type, $return, $params) {

		$return[] = new Property('id', array(
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'read_only' => true,
		));

		$return[] = new Property('action', array(
			'attribute' => 'action_type',
			'getter' => '\hypeJunction\Data\Values::getVerbatim',
			'read_only' => true,
		));

		$return[] = new Property('subject', array(
			'attribute' => 'subject_guid',
			'getter' => '\hypeJunction\Data\Values::getEntity',
			'read_only' => true,
		));

		$return[] = new Property('object', array(
			'attribute' => 'object_guid',
			'getter' => '\hypeJunction\Data\Values::getEntity',
			'read_only' => true,
		));

		$return[] = new Property('target', array(
			'attribute' => 'target_guid',
			'getter' => '\hypeJunction\Data\Values::getEntity',
			'read_only' => true,
		));

		$return[] = new Property('annotation', array(
			'attribute' => 'annotation_id',
			'getter' => '\hypeJunction\Data\Values::getAnnotation',
			'read_only' => true,
		));

		$return[] = new Property('access', array(
			'attribute' => 'access_id',
			'getter' => '\hypeJunction\Data\Values::getAccess',
			'read_only' => true,
		));

		foreach ($return as $key => $value) {
			if ($value instanceof Property && $value->getIdentifier() == 'time_created') {
				unset($return[$key]);
			}
		}

		$return[] = new Property('time_created', array(
			'attribute' => 'posted',
			'getter' => '\hypeJunction\Data\Values::getAtomTime',
			'read_only' => true,
		));

		return $return;
	}

}
