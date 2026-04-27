<?php

return [
	'bootstrap' => \hypeJunction\Apps\Bootstrap::class,
	'events' => [
		'entity:icon:url' => [
			'all' => [
				'hypeJunction\Apps\Handlers\EntityIconUrlHook::handle' => [],
			],
		],
		'graph:properties' => [
			'all' => [
				'hypeJunction\Apps\Handlers\PropertiesHook::handle' => [],
			],
			'user' => [
				'hypeJunction\Apps\Handlers\UserPropertiesHook::handle' => [],
			],
			'group' => [
				'hypeJunction\Apps\Handlers\GroupPropertiesHook::handle' => [],
			],
			'site' => [
				'hypeJunction\Apps\Handlers\SitePropertiesHook::handle' => [],
			],
			'object' => [
				'hypeJunction\Apps\Handlers\ObjectPropertiesHook::handle' => [],
			],
			'object:blog' => [
				'hypeJunction\Apps\Handlers\BlogPropertiesHook::handle' => [],
			],
			'object:file' => [
				'hypeJunction\Apps\Handlers\FilePropertiesHook::handle' => [],
			],
			'object:messages' => [
				'hypeJunction\Apps\Handlers\MessagePropertiesHook::handle' => [],
			],
			'metadata' => [
				'hypeJunction\Apps\Handlers\ExtenderPropertiesHook::handle' => [],
			],
			'annotation' => [
				'hypeJunction\Apps\Handlers\ExtenderPropertiesHook::handle' => [],
			],
			'relationship' => [
				'hypeJunction\Apps\Handlers\RelationshipPropertiesHook::handle' => [],
			],
			'river:item' => [
				'hypeJunction\Apps\Handlers\RiverPropertiesHook::handle' => [],
			],
		],
	],
];
