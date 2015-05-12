<?php

namespace hypeJunction\Files;

/**
 * @property string    $name       Upload name
 * @property string    $tmp_name   Upload temp location
 * @property string    $type       Upload file type
 * @property mixed     $error_code Upload error code
 * @property int       $size       Upload file size
 *
 * @property int       $guid        GUID of the created file
 * @property \ElggFile $file       File entity created from this upload
 * @property string    $error      Human readable error message

 * @property int       $filesize   File size (BC)
 * @property string    $path       File location (BC)
 * @property string    $mimetype   File mime type (BC)
 * @property string    $simpletype File simple type (BC)
 */
class Upload {

	const DEFAULT_FILESTORE_PREFIX = 'file';

	/**
	 * Contructor
	 * 
	 * @param array $props Upload properties
	 */
	public function __construct(array $props = array()) {
		foreach ($props as $key => $val) {
			$this->$key = $val;
		}
	}

	/**
	 * Saves uploaded file to ElggFile with given attributes
	 *
	 * @param array  $attributes New file attributes and metadata
	 * @apara string $prefix     Filestore prefix
	 * @return \Upload
	 */
	public function save(array $attributes = array(), $prefix = self::DEFAULT_FILESTORE_PREFIX) {

		$this->error_code = $this->error;
		$this->error = $this->getError();
		$this->filesize = $this->size;
		$this->path = $this->tmp_name;
		$this->mimetype = $this->detectMimeType();
		$this->simpletype = $this->parseSimpleType();

		if (!$this->isSuccessful()) {
			return $this;
		}

		$prefix = trim($prefix, '/');
		if (!$prefix) {
			$prefix = self::DEFAULT_FILESTORE_PREFIX;
		}

		$id = elgg_strtolower(time() . $this->name);
		$filename = implode('/', array($prefix, $id));

		$type = elgg_extract('type', $attributes, 'object');
		$subtype = elgg_extract('subtype', $attributes, 'file');

		$class = get_subtype_class($type, $subtype);
		if (!$class) {
			$class = '\\ElggFile';
		}

		try {
			$filehandler = new $class();
			foreach ($attributes as $key => $value) {
				$filehandler->$key = $value;
			}
			$filehandler->setFilename($filename);

			$filehandler->title = $this->name;
			$filehandler->originalfilename = $this->name;
			$filehandler->filesize = $this->size;
			$filehandler->mimetype = $this->mimetype;
			$filehandler->simpletype = $this->simpletype;

			$filehandler->open("write");
			$filehandler->close();

			if ($this->simpletype == 'image') {
				$img = new \hypeJunction\Files\Image($this->tmp_name);
				$img->save($filehandler->getFilenameOnFilestore(), hypeApps()->config->getSrcCompressionOpts());
			} else {
				move_uploaded_file($this->tmp_name, $filehandler->getFilenameOnFilestore());
			}

			if ($filehandler->save()) {
				$this->guid = $filehandler->getGUID();
				$this->file = $filehandler;
			}
		} catch (\Exception $ex) {
			elgg_log($ex->getMessage(), 'ERROR');
			$this->error = elgg_echo('upload:error:unknown');
		}

		return $this;
	}

	/**
	 * Check if upload was successful
	 * @return boolean
	 */
	public function isSuccessful() {
		return !($this->getError());
	}

	/**
	 * Get human readable upload error
	 * @return string|boolean
	 */
	public function getError() {
		switch ($this->error_code) {
			case UPLOAD_ERR_OK:
				return false;
			case UPLOAD_ERR_NO_FILE:
				$error = 'upload:error:no_file';
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				$error = 'upload:error:file_size';
			default:
				$error = 'upload:error:unknown';
		}
		return elgg_echo($error);
	}

	/**
	 * Detects mime type of the upload
	 * @return string
	 */
	public function detectMimeType() {
		return \ElggFile::detectMimeType($this->tmp_name, $this->type);
	}

	/**
	 * Parses simple type of the upload
	 * @return string
	 */
	public function parseSimpleType() {
		if (is_callable('elgg_get_file_simple_type')) {
			return elgg_get_file_simple_type($this->detectMimeType());
		}

		$mime_type = $this->detectMimeType();

		switch ($mime_type) {
			case "application/msword":
			case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
			case "application/pdf":
				return "document";

			case "application/ogg":
				return "audio";
		}

		if (preg_match('~^(audio|image|video)/~', $mime_type, $m)) {
			return $m[1];
		}
		if (0 === strpos($mime_type, 'text/') || false !== strpos($mime_type, 'opendocument')) {
			return "document";
		}

		// unrecognized MIME
		return "general";
	}

}
