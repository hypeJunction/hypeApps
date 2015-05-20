<?php

namespace hypeJunction\Servers;

class IconServer extends Server {

	private $uid;
	private $d;
	private $dts;
	private $ts;
	private $path;
	private $hmac;

	/**
	 * {@inheritdoc}
	 */
	public function __construct($config) {
		parent::__construct($config);

		$query = $this->get('q');
		$query = unserialize(base64_decode($query));

		$this->uid = $query['uid'];
		$this->d = $query['d'];
		$this->dts = $query['dts'];
		$this->ts = $query['ts'];
		$this->path = $query['path'];
		$this->hmac = $query['mac'];
	}

	/**
	 * {@inheritdoc}
	 */
	public function serve() {

		if (headers_sent()) {
			return;
		}

		if (!$this->uid || !$this->ts || !$this->path || !$this->hmac) {
			header("HTTP/1.1 400 Bad Request");
			exit;
		}

		$etag = md5($this->ts . $this->uid);
		if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) == "\"$etag\"") {
			header("HTTP/1.1 304 Not Modified");
			exit;
		}

		$this->openDbLink();
		$values = $this->getDatalistValue(array('dataroot', '__site_secret__'));
		$this->closeDbLink();

		if (empty($values)) {
			header("HTTP/1.1 404 Not Found");
			exit;
		}

		$data_root = $values['dataroot'];
		$key = $values['__site_secret__'];

		$hmac = hash_hmac('sha256', $this->uid . $this->path, $key);
		if ($this->hmac !== $hmac) {
			header("HTTP/1.1 403 Forbidden");
			exit;
		}

		if (\hypeJunction\Integration::isElggVersionBelow('1.9.0')) {
			$time_created = date('Y/m/d', $this->dts);
			$d = "{$time_created}/{$this->d}/";
		} else {
			$locator = new \Elgg\EntityDirLocator($this->d);
			$d = $locator->getPath();
		}

		$filename = "{$data_root}{$d}{$this->path}";

		if (!file_exists($filename) || !is_readable($filename)) {
			header("HTTP/1.1 404 Not Found");
			exit;
		}

		$filesize = filesize($filename);
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		switch ($ext) {
			default :
				$mimetype = 'application/otcet-stream';
				break;
			case 'jpg' :
			case 'jpeg' :
				$mimetype = 'image/jpeg';
				break;
			case 'png' :
				$mimetype = 'image/png';
				break;
			case 'gif' :
				$mimetype = 'image/gif';
				break;
		}

		header("Content-type: $mimetype");
		header("Content-disposition: inline");
		header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime("+6 months")), true);
		header("Pragma: public");
		header("Cache-Control: public");
		header("Content-Length: $filesize");
		header("ETag: \"$etag\"");
		readfile($filename);
		exit;
	}

}
