<?php

namespace hypeJunction\Data;

class Graph implements GraphInterface {

	const LIMIT_MAX = 100;

	/**
	 * {@inheritdoc}
	 */
	public function getAliases() {
		$aliases = array(
			'user' => ':user',
			'site' => ':site',
			'group' => ':group',
			'object' => array(
				'blog' => ':blog',
				'comment' => ':comment',
				'file' => ':file',
				'messages' => ':message',
			),
			'river' => array(
				'item' => ':activity',
			),
			'annotation' => array(
				'likes' => ':like',
			),
			'relationship' => array(
				'member' => ':member',
				'membership_request' => ':membership_request',
				'invited' => ':invitation',
				'friend' => ':friend',
			),
		);
		
		return elgg_trigger_plugin_hook('aliases', 'graph', null, $aliases);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAlias($object = null) {
		if (!$this->isExportable($object)) {
			return false;
		}

		$type = $object->getType();
		$subtype = $object->getSubtype();

		$types = elgg_extract($type, $this->getAliases());
		if (is_string($types) && !$subtype) {
			return $types;
		}

		return elgg_extract($subtype, (array) $types, false);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get($uid = '') {

		switch ($uid) {
			case 'me' :
				$uid = "ue" . elgg_get_logged_in_user_guid();
				break;

			case 'site' :
				$uid = "se" . elgg_get_site_entity()->guid;
				break;
		}

		$abbr = substr($uid, 0, 2);
		switch ($abbr) {
			case 'an':
				$id = (int) substr($uid, 2);
				$object = elgg_get_annotation_from_id($id);
				break;

			case 'md' :
				$id = (int) substr($uid, 2);
				$object = elgg_get_metadata_from_id($id);
				break;

			case 'rl' :
				$id = (int) substr($uid, 2);
				$object = get_relationship($id);
				break;

			case 'rv' :
				$id = (int) substr($uid, 2);
				$river = elgg_get_river(array(
					'ids' => sanitize_int($id),
				));
				$object = $river ? $river[0] : false;
				break;

			case 'ue' :
			case 'se' :
			case 'oe' :
			case 'ge' :
				$id = (int) substr($uid, 2);
				$object = get_entity($id);
				break;

			default :
				$object = get_user_by_username($uid);
				if (!$object && is_numeric($uid)) {
					$object = get_entity($uid);
				}
		}

		if (!$this->isExportable($object)) {
			return false;
		}

		return $object;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getUid($object) {
		if (!$this->isExportable($object)) {
			return false;
		}

		switch ($object->getType()) {
			case 'object':
				return "ue{$object->guid}";

			case 'site' :
				return "se{$object->guid}";

			case 'user' :
				return "ue{$object->guid}";

			case 'group' :
				return "ge{$object->guid}";

			case 'river' :
				return "rv{$object->id}";

			case 'relationship' :
				return "rl{$object->id}";

			case 'annotation' :
				return "an{$object->id}";

			case 'metadata' :
				return "md{$object->id}";
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function isExportable($object = null) {
		return $object instanceof \ElggData || $object instanceof \ElggRiverItem;
	}

	/**
	 * {@inheritdoc}
	 */
	public function export($data, array $params = array()) {

		if (empty($data)) {
			return $data;
		}

		if ($data instanceof \ElggBatch) {
			$return = array('items' => array());
			foreach ($data as $v) {
				$return['items'][] = $this->export($v, $params);
			}
			$data = $return;
		}

		$depth = elgg_extract('depth', $params, 0);
		$recursive = elgg_extract('recursive', $params, false);

		$export_params = $params;
		$export_params['depth'] = $depth + 1;
		if ($depth > 0 && !$recursive) {
			$export_params['fields'] = array('type', 'subtype', 'uid', 'guid', 'id', 'url');
		}

		if (is_array($data)) {
			$return = array();
			foreach ($data as $key => $v) {
				$return[$key] = $this->export($v, $export_params);
			}
			return $return;
		} else if ($data instanceof \hypeJunction\BatchResult) {
			return $data->export($export_params);
		} else if ($this->isExportable($data)) {

			$type = is_callable(array($data, 'getType')) ? $data->getType() : 'unknown';
			$subtype = is_callable(array($data, 'getSubtype')) ? $data->getSubtype() : false;

			$fields = (array) elgg_extract('fields', $params, array());
			$properties = $this->getProperties($data, $params);

			$return = array();
			foreach ($properties as $prop) {
				if (empty($fields) || in_array($prop->getIdentifier(), $fields)) {
					$return[$prop->getIdentifier()] = $prop->getValue($data, $params);
				}
			}

			$hook_params = $params;
			$hook_params['object'] = $data;
			$hook_params['fields'] = $fields;

			$return = elgg_trigger_plugin_hook('graph:export', $type, $hook_params, $return);
			if ($subtype) {
				$return = elgg_trigger_plugin_hook('graph:export', "$type:$subtype", $hook_params, $return);
			}

			ksort($return);

			return $this->export($return, $export_params);
		}

		return $data;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getProperties($object = null, array $params = array()) {

		$fields = array();

		if (!$object instanceof \ElggData && !$object instanceof \ElggRiverItem) {
			return $fields;
		}

		$type = is_callable(array($object, 'getType')) ? $object->getType() : 'unknown';
		$subtype = is_callable(array($object, 'getSubtype')) ? $object->getSubtype() : false;

		$params['object'] = $object;

		$fields = elgg_trigger_plugin_hook('graph:properties', $type, $params, $fields);
		if ($subtype) {
			$fields = elgg_trigger_plugin_hook('graph:properties', "$type:$subtype", $params, $fields);
		}

		return (array) $fields;
	}

}
