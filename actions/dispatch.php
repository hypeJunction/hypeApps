<?php

// $action might be defined in context
$result = hypeApps()->actions->execute($action);
if (elgg_is_xhr()) {
	echo $result->output;
}
forward($result->getForwardURL(), $result->getForwardReason());
