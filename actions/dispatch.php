<?php

// $action might be defined in context
$result = hypeApps()->actions->execute($action);
if (elgg_is_xhr()) {
	echo $result->output;
}
return elgg_redirect_response($result->getForwardURL());
