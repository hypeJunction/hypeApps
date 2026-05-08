<?php

namespace hypeJunction\Data;

/**
 * Files class.
 */
class Files {

	/**
	 * Set entity icon from uploaded file property.
	 *
	 * @param PropertyInterface $prop   Property definition
	 * @param \ElggEntity       $entity Entity to set icon on
	 * @param mixed             $value  Upload data
	 * @param array             $params Hook parameters
	 * @return \ElggEntity|void
	 */
	public static function setIcon(PropertyInterface $prop, \ElggEntity $entity, $value = null, array $params = null) {

		$prop_id = $prop->getIdentifier();
		if (!is_array($value) || !isset($value)) {
			$value = elgg_extract($prop_id, $_FILES, []);
		}

		$name = elgg_extract('name', $value);
		$tmp_name = elgg_extract('tmp_name', $value);
		$error_type = elgg_extract('error', $value);

		$has_uploaded_file = $error_type != UPLOAD_ERR_NO_FILE && $name && $tmp_name;
		if (!$has_uploaded_file) {
			return;
		}

		$icon_sizes = hypeApps()->iconFactory->getSizes($entity);
		$custom_icon_sizes = (array) $prop->{'icon_sizes'};
		$icon_sizes = array_merge($icon_sizes, $custom_icon_sizes);

		if (empty($icon_sizes)) {
			return;
		}

		$image_upload_crop_coords = (array) elgg_extract('image_upload_crop_coords', $params, []);
		$ratio_coords = (array) elgg_extract($prop_id, $image_upload_crop_coords, []);

		list($master_width, $master_height) = getimagesize($_FILES[$prop_id]['tmp_name']);

		foreach ($icon_sizes as $icon_name => $icon_size) {
			$ratio = (int) $icon_size['w'] / (int) $icon_size['h'];
			$coords = (array) elgg_extract("$ratio", $ratio_coords, []);

			$x1 = (int) elgg_extract('x1', $coords);
			$x2 = (int) elgg_extract('x2', $coords);
			$y1 = (int) elgg_extract('y1', $coords);
			$y2 = (int) elgg_extract('y2', $coords);

			if ($x2 <= $x1 || $y2 <= $y1) {
				$tmp_coords = false;
			} else {
				$tmp_coords = $coords;
				$tmp_coords['master_width'] = $master_width;
				$tmp_coords['master_height'] = $master_height;
			}

			if (!isset($icon_size['name'])) {
				$icon_size['name'] = $icon_name;
			}

			$tmp_icon_sizes = [
				$icon_size['name'] => $icon_size,
			];
			$options = [
				'icon_sizes' => $tmp_icon_sizes,
				'coords' => $tmp_coords,
			];

			$reset_hook = static function() use ($tmp_icon_sizes) {
				return $tmp_icon_sizes;
			};

			elgg_register_plugin_hook_handler('entity:icon:sizes', 'object', $reset_hook, 999);
			if (hypeApps()->iconFactory->create($entity, $_FILES[$prop_id]['tmp_name'], $options)) {
				foreach (['x1', 'x2', 'y1', 'y2'] as $c) {
					$entity->{"_coord_{$ratio}_{$c}"} = elgg_extract($c, $coords, 0);
					if ($ratio === 1) {
						$entity->$c = elgg_extract($c, $coords, 0);
					}
				}
			}

			elgg_unregister_plugin_hook_handler('entity:icon:sizes', 'object', $reset_hook);
		}

		return $entity;
	}
}
