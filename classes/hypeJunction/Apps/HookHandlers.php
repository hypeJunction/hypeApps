<?php

namespace hypeJunction\Apps;

/**
 * Plugin hooks service
 */
class HookHandlers {

	/**
	 *
	 * @var \hypeJunction\Apps\Config
	 */
	private $config;

	/**
	 *
	 * @var \hypeJunction\Services\IconFactory
	 */
	private $iconFactory;

	/**
	 * Constructor
	 *
	 * @param \hypeJunction\Apps\Config          $config  Config
	 * @param \hypeJunction\Services\IconFactory $factory Icon factory
	 */
	public function __construct(\hypeJunction\Apps\Config $config, \hypeJunction\Services\IconFactory $factory) {
		$this->config = $config;
		$this->iconFactory = $factory;
	}

	/**
	 * Filter icon URLs to route requests via a faster handler
	 *
	 * @param string $hook   "entity:icon:url"
	 * @param string $type   "all"
	 * @param string $return URL
	 * @param array  $params Hook params
	 * @return string
	 */
	public function setEntityIconUrls($hook, $type, $return, $params) {
		
		if (!is_null($return)) {
			// another plugin has already replaced the icon URL
			return $return;
		}

		$entity = elgg_extract('entity', $params);
		$size = elgg_extract('size', $params, 'medium');
		
		if (!$entity->icontime || !array_key_exists($size, $this->iconFactory->getSizes($entity))) {
			// icon has not yet been created or the icon size is unknown
			return $return;
		}

		return $this->iconFactory->getURL($entity, $size);
	}

}
