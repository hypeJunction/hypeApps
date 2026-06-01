<?php

namespace hypeJunction\Data;

class Values {

	public function mapType($type) {
		switch ($type) {
			case 'string' :
			case 'text' :
			case 'plaintext' :
			case 'longtext' :
				return 'string';

			case 'access' :
				return 'integer';

			case 'int' :
			case 'integer' :
				return 'integer';

			case 'userpicker' :
			case 'autocomplete' :
			case 'friendspicker' :
				return 'guid';
		}

		return $type;
	}

	public static function stringToTagArray(PropertyInterface $prop, $value = null, array $params = null) {
		return string_to_tag_array($value);
	}

	public static function htmlSpecialChars(PropertyInterface $prop, $value = null, array $params = null) {
		return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
	}
	
	public static function setVerbatim(PropertyInterface $prop, $object, $value = null, array $params = null) {
		$id = $prop->getAttributeName();
		$object->$id = $value;
		return $object;
	}

	public static function getVerbatim(PropertyInterface $prop, $object) {
		$id = $prop->getAttributeName();
		return (isset($object->$id)) ? $object->$id : null;
	}

	public static function getAlias(PropertyInterface $prop, $object) {
		return hypeApps()->graph->getAlias($object);
	}

	public static function getUid(PropertyInterface $prop, $object) {
		return hypeApps()->graph->getUid($object);
	}

	public static function getType(PropertyInterface $prop, $object) {
		return is_callable(array($object, 'getType')) ? $object->getType() : null;
	}

	public static function getSubtype(PropertyInterface $prop, $object) {
		return is_callable(array($object, 'getSubtype')) ? $object->getSubtype() : '';
	}

	public static function isBanned(PropertyInterface $prop, $object) {
		return is_callable(array($object, 'isBanned')) ? $object->isBanned() : null;
	}

	public static function isAdmin(PropertyInterface $prop, $object) {
		return is_callable(array($object, 'isAdmin')) ? $object->isAdmin() : null;
	}

	public static function getUrl(PropertyInterface $prop, $object) {
		return is_callable(array($object, 'getUrl')) ? $object->getUrl() : null;
	}

	public static function setLocation(PropertyInterface $prop, $object, $value = null) {
		return is_callable(array($object, 'setLocation')) ? $object->setLocation($value) : null;
	}

	public static function getLocation(PropertyInterface $prop, $object) {
		return is_callable(array($object, 'getLocation')) ? $object->getLocation() : null;
	}

	public static function setEntity(PropertyInterface $prop, $object, $value = null, array $params = null) {
		$id = $prop->getAttributeName();
		$object->$id = $value instanceof \ElggEntity ? $value->guid : $value;
		return $object;
	}

	public static function getEntity(PropertyInterface $prop, $object) {
		$id = $prop->getAttributeName();
		return isset($object->$id) ? get_entity($object->$id) : null;
	}

	public static function getAnnotation(PropertyInterface $prop, $object) {
		$id = $prop->getAttributeName();
		return isset($object->$id) ? elgg_get_annotation_from_id($object->$id) : null;
	}

	public static function setEntityBatch(PropertyInterface $prop, $object, $value = null, array $params = null) {
		$id = $prop->getAttributeName();
		$guids = array();
		foreach ((array) $value as $key => $val) {
			$guids[] = $val instanceof \ElggEntity ? $val->guid : $val;
		}
		$object->$id = $guids;
		return $object;
	}

	public static function getEntityBatch(PropertyInterface $prop, $object, array $params = null) {
		$id = $prop->getAttributeName();
		$options = array(
			'guids' => isset($object->$id) ? $object->$id : 0,
			'limit' => Graph::LIMIT_MAX,
		);
		return new \hypeJunction\Graph\BatchResult('elgg_get_entities', $options);
	}

	public static function getAtomTime(PropertyInterface $prop, $object) {
		$id = $prop->getAttributeName();
		return isset($object->$id) ? date(DATE_ATOM, $object->$id) : null;
	}

	public static function getIcon(PropertyInterface $prop, $object, array $params = array()) {
		$icon = array();
		if (!is_callable(array($object, 'getIconURL'))) {
			return $icon;
		}

		$icon_sizes = hypeApps()->iconFactory->getSizes($object);
		$size = elgg_extract('size', $params);
		if ($size && array_key_exists($size, $icon_sizes)) {
			$icon = $icon_sizes[$size];
			$icon['url'] = $object->getIconURL(array(
				'size' => $size,
				'name' => $prop->getAttributeName(),
			));
			$icon['size'] = $size;
		} else {
			foreach ($icon_sizes as $size => $params) {
				$icon[$size] = $object->getIconURL($size);
			}
		}

		return $icon;
	}

	public static function getAccess(PropertyInterface $prop, $object) {
		$key = $prop->getAttributeName();
		$modes = array_flip($prop->getEnumOptions());
		return array(
			'id' => $object->$key,
			'label' => elgg_extract($key, $modes, get_readable_access_level($object->$key)),
		);
	}

	public static function setGroupContentAccessMode(PropertyInterface $prop, &$object, $value = null, array $params = null) {
		return is_callable(array($object, 'setContentAccessMode')) ? $object->setContentAccessMode($value) : null;
	}

	public static function getElggConfig(PropertyInterface $prop, $object) {
		$key = $prop->getAttributeName();
		return elgg_get_config($key);
	}

	public static function getUrlMetadata(PropertyInterface $prop, $object, array $params = array()) {
		$key = $prop->getAttributeName();
		if (!isset($object->$key)) {
			return array();
		}
		$params['src'] = $object->$key;
		// This has a listener in hypeScraper
		return elgg_trigger_plugin_hook('extract:meta', 'all', $params, array());
	}
}
