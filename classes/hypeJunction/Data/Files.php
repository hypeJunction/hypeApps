<?php

namespace hypeJunction\Data;

class Files {

	public static function setIcon(PropertyInterface $prop, \ElggEntity $entity, $value = null, array $params = null) {

		$prop_id = $prop->getIdentifier();
		if (!is_array($value) || !isset($value)) {
			$value = elgg_extract($prop_id, $_FILES, array());
		}

		$name = elgg_extract('name', $value);
		$tmp_name = elgg_extract('tmp_name', $value);
		$error_type = elgg_extract('error', $value);

		$has_uploaded_file = $error_type != UPLOAD_ERR_NO_FILE && $name && $tmp_name;
		if (!$has_uploaded_file) {
			return;
		}

		$icon_sizes = hypeApps()->iconFactory->getSizes($entity);
		$custom_icon_sizes = (array) $prop->{"icon_sizes"};
		$icon_sizes = array_merge($icon_sizes, $custom_icon_sizes);

		if (empty($icon_sizes)) {
			return;
		}

		$image_upload_crop_coords = (array) elgg_extract('image_upload_crop_coords', $params, array());
		$ratio_coords = (array) elgg_extract($prop_id, $image_upload_crop_coords, array());

		list($master_width, $master_height) = getimagesize($_FILES[$prop_id]['tmp_name']);

		foreach ($icon_sizes as $icon_name => $icon_size) {
			$ratio = (int) $icon_size['w'] / (int) $icon_size['h'];
			$coords = (array) elgg_extract("$ratio", $ratio_coords, array());

			$x1 = (int) elgg_extract('x1', $coords);
			$x2 = (int) elgg_extract('x2', $coords);
			$y1 = (int) elgg_extract('y1', $coords);
			$y2 = (int) elgg_extract('y2', $coords);

			if ($x2 <= $x1 || $y2 <= $y1) {
				// do not crop
				$this->tmp_coords = false;
			} else {
				$this->tmp_coords = $coords;
				$this->tmp_coords['master_width'] = $master_width;
				$this->tmp_coords['master_height'] = $master_height;
			}

			if (!isset($icon_size['name'])) {
				$icon_size['name'] = $icon_name;
			}
			$this->tmp_icon_sizes = array(
				$icon_size['name'] => $icon_size,
			);
			$options = array(
				'icon_sizes' => $this->tmp_icon_sizes,
				'coords' => $this->tmp_coords,
			);

			elgg_register_plugin_hook_handler('entity:icon:sizes', 'object', array($this, 'resetIconSizesHook'), 999);
			if (hypeApps()->iconFactory->create($entity, $_FILES[$prop_id]['tmp_name'], $options)) {
				foreach (array('x1', 'x2', 'y1', 'y2') as $c) {
					$entity->{"_coord_{$ratio}_{$coord}"} = elgg_extract($c, $coords, 0);
					if ($ratio === 1) {
						$entity->$c = elgg_extract($c, $coords, 0);
					}
				}
			}
			elgg_unregister_plugin_hook_handler('entity:icon:sizes', 'object', array($this, 'resetIconSizesHook'));
		}

		return $entity;
	}

	/**
	 * Callback for icon size hook
	 * We do not want to regenerate default icons due to custom cropping logic
	 * @return array
	 */
	public function resetIconSizesHook() {
		return $this->tmp_icon_sizes;
	}

}
