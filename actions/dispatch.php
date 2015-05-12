<?php

// After 1.9, $action is available in scope
if (\hypeJunction\Integration::isElggVersionBelow('1.9.0')) {
	$action = get_input('action');
}

$result = hypeApps()->actions->execute($action);
if (elgg_is_xhr()) {
	echo $result->output;
}
forward($result->getForwardURL(), $result->getForwardReason());