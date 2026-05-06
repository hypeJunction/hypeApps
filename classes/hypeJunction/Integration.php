<?php

namespace hypeJunction;

/**
 * Smoother integration with core
 */
class Integration {

	public static $version;

	/**
	 * Returns root directory of Elgg installation
	 * @return string
	 */
	public static function getRootPath() {
		return dirname(dirname(dirname(dirname(dirname(__FILE__)))));
	}

	/**
	 * Returns Elgg ServiceProvider instance
	 * @return ServiceProvider
	 */
	public static function getServiceProvider() {
		if (is_callable('_elgg_services')) {
			return _elgg_services();
		}

		if (!isset($CONFIG)) {
			$path = self::getRootPath() . '/engine/settings.php';
			if (!is_file($path)) {
				$path = self::getRootPath() . '/elgg-config/settings.php';
			}

			require_once $path;
		}

		return new \Elgg\Di\ServiceProvider(new \Elgg\Config($CONFIG));
	}

	/**
	 * Returns Elgg version
	 * @return string|false
	 */
	public static function getElggVersion() {
		if (isset(self::$version)) {
			return self::$version;
		}

		if (is_callable('elgg_get_version')) {
			self::$version = elgg_get_version(true);
			return self::$version;
		}

		if (is_callable('elgg_get_config')) {
			$v = elgg_get_config('version');
			if ($v) {
				self::$version = $v;
				return self::$version;
			}
		}

		return false;
	}

	/**
	 * Compares a given version to current version
	 *
	 * @param string $version Version to compare
	 * @return boolean
	 */
	public static function isElggVersionBelow($version = '0.0.0') {
		return version_compare(self::getElggVersion(), $version, '<');
	}

	/**
	 * Compares a given version to current version
	 *
	 * @param string $version Version to compare
	 * @return boolean
	 */
	public static function isElggVersionAbove($version = '0.0.0') {
		return version_compare(self::getElggVersion(), $version, '>');
	}
}
