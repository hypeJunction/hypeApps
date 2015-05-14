<?php

namespace hypeJunction;

class Integration {

	static $version;

	public static function getElggVersion() {

		if (isset(self::$version)) {
			return self::$version;
		}

		if (is_callable('elgg_get_version')) {
			return elgg_get_version(true);
		} else {
			$path = dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/version.php';
			if (!include($path)) {
				return false;
			}
			self::$version = $release;
			return self::$version;
		}
	}

	public static function isElggVersionBelow($version = '0.0.0') {
		return version_compare(self::getElggVersion(), $version, '<');
	}

	public static function isElggVersionAbove($version = '0.0.0') {
		return version_compare(self::getElggVersion(), $version, '>');
	}
}
