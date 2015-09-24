<?php

namespace hypeJunction\Apps\Handlers;

class InitSystemEvent {

	/**
	 * 'init','system' callback
	 */
	public function __invoke() {
		elgg_register_plugin_hook_handler('entity:icon:url', 'all', new EntityIconUrlHook());
	}

}
