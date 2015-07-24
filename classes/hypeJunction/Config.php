<?php

namespace hypeJunction;

/**
 * Config
 */
abstract class Config {

	private $plugin;
	private $settings;

	/**
	 * Constructor
	 * @param \ElggPlugin $plugin ElggPlugin
	 */
	public function __construct(\ElggPlugin $plugin) {
		$this->plugin = $plugin;
	}

	/**
	 * Returns default config values
	 * @return array
	 */
	abstract public function getDefaults();

	/**
	 * Returns config value
	 *
	 * @param string $name Config parameter name
	 * @return mixed
	 */
	public function __get($name) {
		return $this->get($name);
	}

	/**
	 * Returns all plugin settings
	 * @return array
	 */
	public function all() {
		if (!isset($this->settings)) {
			$this->settings = array_merge($this->getDefaults(), $this->plugin->getAllSettings());
		}
		return $this->settings;
	}

	/**
	 * Returns a plugin setting
	 *
	 * @param string $name Setting name
	 * @return mixed
	 */
	public function get($name, $default = null) {
		return elgg_extract($name, $this->all(), $default);
	}

	/**
	 * Returns plugin path
	 * @return string
	 */
	public function getPath() {
		return $this->plugin->getPath();
	}

	/**
	 * Returns plugin ID
	 * @return string
	 */
	public function getID() {
		return $this->plugin->getID();
	}

}
