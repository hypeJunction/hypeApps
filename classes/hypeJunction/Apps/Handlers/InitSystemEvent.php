<?php

namespace hypeJunction\Apps\Handlers;

class InitSystemEvent {

	/**
	 * 'init','system' callback
	 */
	public function __invoke() {
		elgg_register_plugin_hook_handler('entity:icon:url', 'all', new EntityIconUrlHook());

		elgg_register_plugin_hook_handler('graph:properties', 'all', new PropertiesHook());

		elgg_register_plugin_hook_handler('graph:properties', 'user', new UserPropertiesHook());
		elgg_register_plugin_hook_handler('graph:properties', 'group', new GroupPropertiesHook());
		elgg_register_plugin_hook_handler('graph:properties', 'site', new SitePropertiesHook());

		elgg_register_plugin_hook_handler('graph:properties', 'object', new ObjectPropertiesHook());
		elgg_register_plugin_hook_handler('graph:properties', 'object:blog', new BlogPropertiesHook());
		elgg_register_plugin_hook_handler('graph:properties', 'object:file', new FilePropertiesHook());
		elgg_register_plugin_hook_handler('graph:properties', 'object:messages', new MessagePropertiesHook);

		elgg_register_plugin_hook_handler('graph:properties', 'metadata', new ExtenderPropertiesHook());
		elgg_register_plugin_hook_handler('graph:properties', 'annotation', new ExtenderPropertiesHook());
		elgg_register_plugin_hook_handler('graph:properties', 'relationship', new RelationshipPropertiesHook());
		elgg_register_plugin_hook_handler('graph:properties', 'river:item', new RiverPropertiesHook());
	}

}
