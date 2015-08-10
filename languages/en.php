<?php

$general = array();

$actions = array(
	'apps:action:error' => 'An error has occurred and has been logged. Please contact site administrator if the error persists',
	'apps:validation:error' => 'One or more inputs are incomplete or contain malformatted data. Please double check your submission',
	'apps:permissions:error' => 'You do not have sufficient permissions for this action',
	'apps:entity:error' => 'Entity does not exist or you do not have permissions to access it',

	'apps:delete:success' => '%s has been deleted',
	'apps:delete:error' => '%s can not be deleted',

	'apps:item' => 'item',
);

add_translation('en', array_merge($general, $actions));