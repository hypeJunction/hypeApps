<?php

namespace hypeJunction\Services;

class IconFactory {

	/**
	 *
	 * @var \hypeJunction\Apps\Config
	 */
	private $config;

	/**
	 * Constructor
	 *
	 * @param \hypeJunction\Apps\Config $config Config
	 */
	public function __construct(\hypeJunction\Apps\Config $config) {
		$this->config = $config;
	}

	/**
	 * Create icons for an entity
	 *
	 * @param \ElggEntity $entity  Entity
	 * @param mixed       $source  ElggFile, or path, or temp storage to be used as a source for icons
	 * @param array       $options Additional options
	 *                             'icon_sizes'            Additional icon sizes to create
	 *                             'icon_filestore_prefix' Prefix of cropped/resizes icon sizes on the filestore
	 *                             'coords'                Cropping coords
	 * @return \ElggFile[]|false Created icons
	 */
	public function create(\ElggEntity $entity, $source = null, array $options = array()) {

		if (!$entity instanceof \ElggEntity) {
			return false;
		}

		if (!$source) {
			$source = $entity;
		}

		if ($source instanceof \ElggFile) {
			$source = $source->getFilenameOnFilestore();
		}

		if (empty($source) || !file_exists($source)) {
			return false;
		}

		$coords = elgg_extract('coords', $options, false);
		$dir = $this->getIconDirectory($entity, elgg_extract('icon_filestore_prefix', $options));

		$entity->icon_mimetype = \ElggFile::detectMimeType($source, 'image/jpeg');
		$entity->icon_directory = $dir;

		// reset
		unset($entity->icontime);
		foreach (array('x1', 'x2', 'y1', 'y2') as $coord) {
			unset($entity->$coord);
		}

		$error = false;
		$icons = array();
		$icons_meta = array();

		$icon_sizes = $this->getSizes($entity, elgg_extract('icon_sizes', $options, array()));

		foreach ($icon_sizes as $size => $props) {

			if (!isset($props['croppable'])) {
				$props['croppable'] = in_array($size, $this->config->getCroppableSizes());
			}
			if (is_array($coords) && !isset($coords['master_width'])) {
				$coords['master_width'] = $this->config->get('master_size_length');
				$coords['master_height'] = $this->config->get('master_size_length');
			}
			try {
				$icon = $this->getIconFile($entity, $size);

				$image = new \hypeJunction\Files\Image($source);
				$image->resize($props, $coords);
				$image->save($icon->getFilenameOnFilestore(), $this->config->getIconCompressionOpts());

				$icons[$size] = $icon;

				if (isset($props['metadata_name'])) {
					$metadata_name = $props['metadata_name'];
					$icons_meta[$metadata_name] = $icon->getFilename();
				}
			} catch (\Exception $ex) {
				elgg_log($ex->getMessage(), 'ERROR');
				$error = true;
			}
		}

		if ($error) {
			foreach ($icons as $icon) {
				$icon->delete();
			}
			return false;
		}

		if (!$entity instanceof \ElggFile) {
			// store the original icon source file
			$src = $this->getIconFile($entity);
			$srcimg = new \hypeJunction\Files\Image($source);
			$srcimg->save($src->getFilenameOnFilestore(), $this->config->getSrcCompressionOpts());
		}

		if (is_array($coords)) {
			// store cropping coordinates
			foreach ($coords as $coord => $value) {
				$entity->$coord = $value;
			}
		}

		foreach ($icons_meta as $name => $value) {
			$entity->$name = $value;
		}

		$entity->icontime = time();

		return $icons;
	}

	/**
	 * Get icon size config
	 *
	 * @param \ElggEntity $entity     Entity
	 * @param array      $icon_sizes Predefined icon sizes
	 * @return array
	 */
	public function getSizes(\ElggEntity $entity, array $icon_sizes = array()) {

		$defaults = ($entity && $entity->getSubtype() == 'file') ? $this->config->getFileIconSizes() : $this->config->getGlobalIconSizes();
		$sizes = array_merge($defaults, $icon_sizes);

		return elgg_trigger_plugin_hook('entity:icon:sizes', $entity->getType(), array(
			'entity' => $entity,
			'subtype' => $entity->getSubtype(),
				), $sizes);
	}

	/**
	 * Determines and normalizes the directory in which the icon is stored
	 *
	 * @param \ElggEntity $entity    Entity
	 * @param string     $size      Icon size
	 * @param string     $directory Default directory
	 * @return string
	 */
	public function getIconDirectory(\ElggEntity $entity, $size = null, $directory = null) {

		$sizes = $this->getSizes($entity);
		if (isset($sizes[$size]['metadata_name'])) {
			$md_name = $sizes[$size]['metadata_name'];
			if ($entity->$md_name) {
				$directory = '';
			}
		}

		if ($directory === null) {
			$directory = $directory ? : $entity->icon_directory;
			if ($entity instanceof \ElggUser) {
				$directory = 'profile';
			} else if ($entity instanceof \ElggGroup) {
				$directory = 'groups';
			} else {
				$directory = $this->config->getDefaultIconDirectory();
			}
		}

		$directory = elgg_trigger_plugin_hook('entity:icon:directory', $entity->getType(), array(
			'entity' => $entity,
			'size' => $size,
				), $directory);

		return trim($directory, '/');
	}

	/**
	 * Determines icon filename
	 *
	 * @param \ElggEntity $entity Entity
	 * @param string      $size   Size
	 * @return string
	 */
	public function getIconFilename(\ElggEntity $entity, $size = '') {

		$mimetype = $entity->icon_mimetype ? : $entity->mimetype;
		switch ($mimetype) {
			default :
				$ext = 'jpg';
				break;
			case 'image/png' :
				$ext = 'png';
				break;
			case 'image/gif' :
				$ext = 'gif';
				break;
		}

		$sizes = $this->getSizes($entity);
		if (isset($sizes[$size]['metadata_name'])) {
			$md_name = $sizes[$size]['metadata_name'];
			$filename = $entity->$md_name;
		}
		if (!$filename) {
			$filename = "{$entity->guid}{$size}.{$ext}";
		}
		return elgg_trigger_plugin_hook('entity:icon:directory', $entity->getType(), array(
			'entity' => $entity,
			'size' => $size,
				), $filename);
	}

	/**
	 * Returns an ElggFile containing the entity icon
	 *
	 * @param \ElggEntity $entity Entity
	 * @param string      $size   Size
	 * @return \ElggFile
	 */
	public function getIconFile(\ElggEntity $entity, $size = '') {

		$dir = $this->getIconDirectory($entity, $size);
		$filename = $this->getIconFilename($entity, $size);

		$file = new \ElggFile();
		$file->owner_guid = ($entity instanceof \ElggUser) ? $entity->guid : $entity->owner_guid;
		$file->setFilename("{$dir}/{$filename}");
		if (!file_exists($file->getFilenameOnFilestore())) {
			$file->open('write');
			$file->close();
		}
		$file->mimetype = $file->detectMimeType();

		return $file;
	}

	/**
	 * Prepares a URL that can be used to display an icon bypassing the engine boot
	 *
	 * @param \ElggEntity $entity Entity
	 * @param string      $size   Size
	 * @return \ElggFile
	 */
	public function getURL(\ElggEntity $entity, $size = '') {

		$icon = $this->getIconFile($entity, $size);

		$key = get_site_secret();
		$guid = $entity->guid;
		$path = $icon->getFilename();

		$hmac = hash_hmac('sha256', $guid . $path, $key);

		$query = serialize(array(
			'uid' => $guid,
			'd' => ($entity instanceof \ElggUser) ? $entity->guid : $entity->owner_guid, // guid of the dir owner
			'dts' => ($entity instanceof \ElggUser) ? $entity->time_created : $entity->getOwnerEntity()->time_created,
			'path' => $path,
			'ts' => $entity->icontime,
			'mac' => $hmac,
		));

		$url = elgg_http_add_url_query_elements('mod/hypeApps/servers/icon.php', array(
			'q' => base64_encode($query),
		));

		return elgg_normalize_url($url);
	}

	/**
	 * Outputs raw icon
	 *
	 * @param int    $entity_guid GUID of an entity
	 * @param string $size        Icon size
	 * @return void
	 */
	public function outputRawIcon($entity_guid, $size = null) {
		if (headers_sent()) {
			exit;
		}
		$ha = access_get_show_hidden_status();
		access_show_hidden_entities(true);
		$entity = get_entity($entity_guid);
		if (!$entity) {
			exit;
		}
		$size = strtolower($size ? : 'medium');
		$filename = "icons/" . $entity->guid . $size . ".jpg";
		$etag = md5($entity->icontime . $size);
		$filehandler = new \ElggFile();
		$filehandler->owner_guid = $entity->owner_guid;
		$filehandler->setFilename($filename);
		if ($filehandler->exists()) {
			$filehandler->open('read');
			$contents = $filehandler->grabFile();
			$filehandler->close();
		} else {
			forward('', '404');
		}
		$mimetype = ($entity->mimetype) ? $entity->mimetype : 'image/jpeg';
		access_show_hidden_entities($ha);
		header("Content-type: $mimetype");
		header("Etag: $etag");
		header('Expires: ' . date('r', time() + 864000));
		header("Pragma: public");
		header("Cache-Control: public");
		header("Content-Length: " . strlen($contents));
		echo $contents;
		exit;
	}

}
