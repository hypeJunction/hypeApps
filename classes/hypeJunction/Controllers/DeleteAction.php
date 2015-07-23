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
		if (!$this->entity->canDelete()) {
			throw new PermissionsException('apps:permissions:error');
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function execute() {
		$this->display_name = $this->entity->getDisplayName();
		$container = $this->entity->getContainerEntity();
		if ($this->entity->delete()) {
			unset($this->entity);
			$this->result->addMessage(elgg_echo('apps:delete:success', array($this->display_name)));
			if ($container) {
				$this->result->setForwardURL($container->getURL());
			}
		} else {
			$this->result->addError(elgg_echo('apps:delete:error'));
		}
		
	}

}
