<?php

namespace hypeJunction\Filestore;

use ElggEntity;
use ElggFile;

/**
 * Handles entity icons
 *
 * @package    HypeJunction
 * @subpackage Filestore
 * @deprecated since version 3.1
 */
class IconHandler {

	/**
	 * List of croppabe icon sizes
	 * @static array
	 */
	static $croppable = array('topbar', 'tiny', 'small', 'medium', 'large');

	/**
	 * Create icons for an entity
	 *
	 * @param ElggFile $entity      An entity that will use the icons
	 * @param mixed    $source_file ElggFile, or remote path, or temp storage from where the source for the icons should be taken from
	 * @param array    $config      Additional parameters, such as 'icon_sizes', 'icon_filestore_prefix', 'coords'
	 * @uses array  $config['icon_sizes']            Additional icon sizes to create
	 * @uses string $config['icon_filestore_prefix'] Prefix of cropped/resizes icon sizes on the filestore
	 * @uses array  $config['coords']                Cropping coords
	 * @return array|boolean An array of filehandlers for created icons or false on error
	 */
	public static function makeIcons($entity, $source_file = null, array $config = array()) {
		return hypeApps()->iconFactory->create($entity, $source_file, $config);
	}

	/**
	 * Get icon size config
	 *
	 * @param ElggEntity $entity     Entity
	 * @param array      $icon_sizes An array of predefined icon sizes
	 * @return array
	 */
	public static function getIconSizes($entity, $icon_sizes = array()) {
		return hypeApps()->iconFactory->getSizes($entity, $icon_sizes);
	}

	/**
	 * Outputs raw icon
	 *
	 * @param int    $entity_guid GUID of an entity
	 * @param string $size        Icon size
	 * @return void
	 */
	public static function outputRawIcon($entity_guid = 0, $size = null) {
		return hypeApps()->iconFactory->outputRawIcon($entity_guid, $size);
	}

}
