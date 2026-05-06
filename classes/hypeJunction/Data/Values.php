<?php

namespace hypeJunction\Data;

/**
 * Values class.
 */
class Values {

	/**
	 * mapType.
	 *
	 * @param mixed $type type
	 *
	 * @return mixed
	 */
	public function mapType($type) {
		switch ($type) {
			case 'string':
			case 'text':
			case 'plaintext':
			case 'longtext':
				return 'string';

			case 'access':
				return 'integer';

			case 'int':
			case 'integer':
				return 'integer';

			case 'userpicker':
			case 'autocomplete':
			case 'friendspicker':
				return 'guid';
		}

		return $type;
	}

	/**
	 * stringToTagArray.
	 *
	 * @param PropertyInterface $prop   prop
	 * @param mixed             $value  value
	 * @param array             $params params
	 *
	 * @return mixed
	 */
	public static function stringToTagArray(PropertyInterface $prop, $value = null, array $params = null) {
		return elgg_string_to_array($value);
	}

	/**
	 * htmlSpecialChars.
	 *
	 * @param PropertyInterface $prop   prop
	 * @param mixed             $value  value
	 * @param array             $params params
	 *
	 * @return mixed
	 */
	public static function htmlSpecialChars(PropertyInterface $prop, $value = null, array $params = null) {
		return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
	}
	
	/**
	 * setVerbatim.
	 *
	 * @param PropertyInterface $prop   prop
	 * @param mixed             $object object
	 * @param mixed             $value  value
	 * @param array             $params params
	 *
	 * @return mixed
	 */
	public static function setVerbatim(PropertyInterface $prop, $object, $value = null, array $params = null) {
		$id = $prop->getAttributeName();
		$object->$id = $value;
		return $object;
	}

	/**
	 * getVerbatim.
	 *
	 * @param PropertyInterface $prop   prop
	 * @param mixed             $object object
	 *
	 * @return mixed
	 */
	public static function getVerbatim(PropertyInterface $prop, $object) {
		$id = $prop->getAttributeName();
		return (isset($object->$id)) ? $object->$id : null;
	}

	/**
	 * getAlias.
	 *
	 * @param PropertyInterface $prop   prop
	 * @param mixed             $object object
	 *
	 * @return mixed
	 */
	public static function getAlias(PropertyInterface $prop, $object) {
		return hypeApps()->graph->getAlias($object);
	}

	/**
	 * getUid.
	 *
	 * @param PropertyInterface $prop   prop
	 * @param mixed             $object object
	 *
	 * @return mixed
	 */
	public static function getUid(PropertyInterface $prop, $object) {
		return hypeApps()->graph->getUid($object);
	}

	/**
	 * getType.
	 *
	 * @param PropertyInterface $prop   prop
	 * @param mixed             $object object
	 *
	 * @return mixed
	 */
	public static function getType(PropertyInterface $prop, $object) {
		return is_callable([$object, 'getType']) ? $object->getType() : null;
	}

	/**
	 * getSubtype.
	 *
	 * @param PropertyInterface $prop   prop
	 * @param mixed             $object object
	 *
	 * @return mixed
	 */
	public static function getSubtype(PropertyInterface $prop, $object) {
		return is_callable([$object, 'getSubtype']) ? $object->getSubtype() : '';
	}

	/**
	 * isBanned.
	 *
	 * @param PropertyInterface $prop   prop
	 * @param mixed             $object object
	 *
	 * @return mixed
	 */
	public static function isBanned(PropertyInterface $prop, $object) {
		return is_callable([$object, 'isBanned']) ? $object->isBanned() : null;
	}

	/**
	 * isAdmin.
	 *
	 * @param PropertyInterface $prop   prop
	 * @param mixed             $object object
	 *
	 * @return mixed
	 */
	public static function isAdmin(PropertyInterface $prop, $object) {
		return is_callable([$object, 'isAdmin']) ? $object->isAdmin() : null;
	}

	/**
	 * getUrl.
	 *
	 * @param PropertyInterface $prop   prop
	 * @param mixed             $object object
	 *
	 * @return mixed
	 */
	public static function getUrl(PropertyInterface $prop, $object) {
		return is_callable([$object, 'getUrl']) ? $object->getUrl() : null;
	}

	/**
	 * setLocation.
	 *
	 * @param PropertyInterface $prop   prop
	 * @param mixed             $object object
	 * @param mixed             $value  value
	 *
	 * @return mixed
	 */
	public static function setLocation(PropertyInterface $prop, $object, $value = null) {
		$object->location = $value;
		return $object;
	}

	/**
	 * getLocation.
	 *
	 * @param PropertyInterface $prop   prop
	 * @param mixed             $object object
	 *
	 * @return mixed
	 */
	public static function getLocation(PropertyInterface $prop, $object) {
		return isset($object->location) ? $object->location : null;
	}

	/**
	 * setEntity.
	 *
	 * @param PropertyInterface $prop   prop
	 * @param mixed             $object object
	 * @param mixed             $value  value
	 * @param array             $params params
	 *
	 * @return mixed
	 */
	public static function setEntity(PropertyInterface $prop, $object, $value = null, array $params = null) {
		$id = $prop->getAttributeName();
		$object->$id = $value instanceof \ElggEntity ? $value->guid : $value;
		return $object;
	}

	/**
	 * getEntity.
	 *
	 * @param PropertyInterface $prop   prop
	 * @param mixed             $object object
	 *
	 * @return mixed
	 */
	public static function getEntity(PropertyInterface $prop, $object) {
		$id = $prop->getAttributeName();
		return isset($object->$id) ? get_entity($object->$id) : null;
	}

	/**
	 * getAnnotation.
	 *
	 * @param PropertyInterface $prop   prop
	 * @param mixed             $object object
	 *
	 * @return mixed
	 */
	public static function getAnnotation(PropertyInterface $prop, $object) {
		$id = $prop->getAttributeName();
		return isset($object->$id) ? elgg_get_annotation_from_id($object->$id) : null;
	}

	/**
	 * setEntityBatch.
	 *
	 * @param PropertyInterface $prop   prop
	 * @param mixed             $object object
	 * @param mixed             $value  value
	 * @param array             $params params
	 *
	 * @return mixed
	 */
	public static function setEntityBatch(PropertyInterface $prop, $object, $value = null, array $params = null) {
		$id = $prop->getAttributeName();
		$guids = [];
		foreach ((array) $value as $key => $val) {
			$guids[] = $val instanceof \ElggEntity ? $val->guid : $val;
		}

		$object->$id = $guids;
		return $object;
	}

	/**
	 * getEntityBatch.
	 *
	 * @param PropertyInterface $prop   prop
	 * @param mixed             $object object
	 * @param array             $params params
	 *
	 * @return mixed
	 */
	public static function getEntityBatch(PropertyInterface $prop, $object, array $params = null) {
		$id = $prop->getAttributeName();
		$options = [
			'guids' => isset($object->$id) ? $object->$id : 0,
			'limit' => Graph::LIMIT_MAX,
		];
		return new \hypeJunction\BatchResult('elgg_get_entities', $options);
	}

	/**
	 * getAtomTime.
	 *
	 * @param PropertyInterface $prop   prop
	 * @param mixed             $object object
	 *
	 * @return mixed
	 */
	public static function getAtomTime(PropertyInterface $prop, $object) {
		$id = $prop->getAttributeName();
		return isset($object->$id) ? date(DATE_ATOM, $object->$id) : null;
	}

	/**
	 * getIcon.
	 *
	 * @param PropertyInterface $prop   prop
	 * @param mixed             $object object
	 * @param array             $params params
	 *
	 * @return mixed
	 */
	public static function getIcon(PropertyInterface $prop, $object, array $params = []) {
		$icon = [];
		if (!is_callable([$object, 'getIconURL'])) {
			return $icon;
		}

		$icon_sizes = hypeApps()->iconFactory->getSizes($object);
		$size = elgg_extract('size', $params);
		if ($size && array_key_exists($size, $icon_sizes)) {
			$icon = $icon_sizes[$size];
			$icon['url'] = $object->getIconURL([
				'size' => $size,
				'name' => $prop->getAttributeName(),
			]);
			$icon['size'] = $size;
		} else {
			foreach ($icon_sizes as $size => $params) {
				$icon[$size] = $object->getIconURL($size);
			}
		}

		return $icon;
	}

	/**
	 * getAccess.
	 *
	 * @param PropertyInterface $prop   prop
	 * @param mixed             $object object
	 *
	 * @return mixed
	 */
	public static function getAccess(PropertyInterface $prop, $object) {
		$key = $prop->getAttributeName();
		$modes = array_flip($prop->getEnumOptions());
		return [
			'id' => $object->$key,
			'label' => elgg_extract($key, $modes, get_readable_access_level($object->$key)),
		];
	}

	/**
	 * setGroupContentAccessMode.
	 *
	 * @param PropertyInterface $prop   prop
	 * @param mixed             $object object
	 * @param mixed             $value  value
	 * @param array             $params params
	 *
	 * @return mixed
	 */
	public static function setGroupContentAccessMode(PropertyInterface $prop, &$object, $value = null, array $params = null) {
		return is_callable([$object, 'setContentAccessMode']) ? $object->setContentAccessMode($value) : null;
	}

	/**
	 * getElggConfig.
	 *
	 * @param PropertyInterface $prop   prop
	 * @param mixed             $object object
	 *
	 * @return mixed
	 */
	public static function getElggConfig(PropertyInterface $prop, $object) {
		$key = $prop->getAttributeName();
		return elgg_get_config($key);
	}

	/**
	 * getUrlMetadata.
	 *
	 * @param PropertyInterface $prop   prop
	 * @param mixed             $object object
	 * @param array             $params params
	 *
	 * @return mixed
	 */
	public static function getUrlMetadata(PropertyInterface $prop, $object, array $params = []) {
		$key = $prop->getAttributeName();
		if (!isset($object->$key)) {
			return [];
		}

		$params['src'] = $object->$key;
		// This has a listener in hypeScraper
		return elgg_trigger_plugin_hook('extract:meta', 'all', $params, []);
	}
}
