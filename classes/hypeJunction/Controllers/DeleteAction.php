<?php

namespace hypeJunction\Controllers;

use ElggEntity;
use hypeJunction\Exceptions\InvalidEntityException;
use hypeJunction\Inbox\Exceptions\PermissionsException;

/**
 * Generic delete action controller
 *
 * @property int         $guid
 * @property ElggEntity $entity
 * @property string      $display_name
 */
class DeleteAction extends Action {

	/**
	 * {@inheridoc}
	 */
	public function setup() {
		parent::setup();
		$this->entity = get_entity($this->guid);
	}

	/**
	 * {@inheritdoc}
	 */
	public function validate() {
		if (!$this->entity) {
			throw new InvalidEntityException(elgg_echo('apps:entity:error'));
		}
		if (is_callable(array($this->entity, 'canDelete'))) {
			if (!$this->entity->canDelete()) {
				throw new PermissionsException('apps:permissions:error');
			}
		} else if (!$this->entity->canEdit()) {
			throw new PermissionsException('apps:permissions:error');
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function execute() {
		// determine what name to show on success
		$display_name = $this->entity->getDisplayName();
		if (!$display_name) {
			$display_name = ucfirst(elgg_echo('apps:item'));
		}

		$container = $this->entity->getContainerEntity();

		// determine forward URL
		$forward_url = REFERER;
		$referrer_url = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
		if ($referrer_url) {
			$path = explode('/', parse_url($referrer_url, PHP_URL_PATH));
			if (in_array("{$this->entity->guid}", $path)) {
				// referrer URL contains a reference to the entity that will be deleted
				$forward_url = ($container) ? $container->getURL() : '';
			}
		}

		if ($this->entity->delete()) {
			unset($this->entity);
			$this->result->addMessage(elgg_echo('apps:delete:success', array($display_name)));
			if ($container) {
				$this->result->setForwardURL($forward_url);
			}
		} else {
			$this->result->addError(elgg_echo('apps:delete:error'));
		}
	}

}
