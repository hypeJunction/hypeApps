<?php

if (\hypeJunction\Integration::isElggVersionBelow('1.9.0')) {
	$action = get_input('action');
} else if (!$action) {
	$uri = trim(get_input('__elgg_uri', ''), '/');
	$segments = explode('/', $uri);
	array_shift($segments);
	$action = implode('/', $segments);
}

$result = hypeApps()->actions->execute($action);
if (elgg_is_xhr()) {
	echo $result->output;
}
forward($result->getForwardURL(), $result->getForwardReason());
