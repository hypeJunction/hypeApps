<?php

namespace hypeJunction\Data;

class Validators {

	public static function validateRule(PropertyInterface $prop, $value = null, $rule = '', $expectation = null, array $params = array()) {

		$params['value'] = $value;
		$params['rule'] = $rule;
		$params['expectation'] = $expectation;
		$params['property'] = $prop;

		$result = elgg_trigger_plugin_hook("validate:$rule", 'action', $params, true);

		if ($result === false) {
			throw new \hypeJunction\Exceptions\ActionValidationException(elgg_echo('apps:validation:error:prop', array($prop->getIdentifier())));
		}

		return $result;
	}

	public static function isValidUsername(\PropertyInterface $prop, $value = null, array $params = array()) {
		try {
			return validate_username($value);
		} catch (\Exception $ex) {
			throw new \hypeJunction\Exceptions\ActionValidationException($ex->getMessage());
		}
	}

	public static function isAvailableUsername(\PropertyInterface $prop, $value = null, array $params = array()) {
		$access_status = access_get_show_hidden_status();
		access_show_hidden_entities(true);

		$available = true;
		if (get_user_by_username($value)) {
			$available = false;
		}

		access_show_hidden_entities($access_status);

		return $available;
	}

}
