<?php

namespace hypeJunction\Filestore;

use ElggFile;
use hypeJunction\Filestore\Handlers\Uploader\Upload;

/**
 * Handles file uploads
 *
 * @package    HypeJunction
 * @subpackage Filestore
 */
class UploadHandler {

	/**
	 * Create new file entities
	 *
	 * @param string $input      Name of the file input
	 * @param array  $attributes Key value pairs, such as subtype, owner_guid, metadata.
	 * @param array  $config     Additional config
	 * @return ElggFile[] An array of file entities created
	 */
	public function makeFiles($input, array $attributes = array(), array $config = array()) {
		$files = array();
		$uploads = hypeApps()->uploader->handle($input, $attributes, $config);
		foreach ($uploads as $upload) {
			if ($upload->file instanceof \ElggEntity) {
				$files[] = $upload->file;
			}
		}
		return $files;
	}

	/**
	 * Static counterpart of makeFiles, but returns data for processed uploads
	 *
	 * @param string $input      Name of the file input
	 * @param array  $attributes Key value pairs, such as subtype, owner_guid, metadata.
	 * @param array  $config     Additional config
	 * @return Upload[] An array of file entities created
	 */
	public static function handle($input, array $attributes = array(), array $config = array()) {
		return hypeApps()->uploader->handle($input, $attributes, $config);
	}

}
