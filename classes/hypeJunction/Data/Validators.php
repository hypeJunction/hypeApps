<?php

namespace hypeJunction\Data;

/**
 * Validators class.
 */
class Validators {

	/**
	 * validateRule.
	 *
	 * @param PropertyInterface $prop        prop
	 * @param mixed             $value       value
	 * @param mixed             $rule        rule
	 * @param mixed             $expectation expectation
	 * @param array             $params      params
	 *
	 * @return mixed
	 */
	public static function validateRule(PropertyInterface $prop, $value = null, $rule = '', $expectation = null, array $params = []) {

		$params['value'] = $value;
		$params['rule'] = $rule;
		$params['expectation'] = $expectation;
		$params['property'] = $prop;

		$result = elgg_trigger_plugin_hook("validate:$rule", 'action', $params, true);

		if ($result === false) {
			throw new \hypeJunction\Exceptions\ActionValidationException(elgg_echo('apps:validation:error:prop', [$prop->getIdentifier()]));
		}

		return $result;
	}

	/**
	 * isValidUsername.
	 *
	 * @param PropertyInterface $prop   prop
	 * @param mixed             $value  value
	 * @param array             $params params
	 *
	 * @return mixed
	 */
	public static function isValidUsername(PropertyInterface $prop, $value = null, array $params = []) {
		try {
			elgg()->accounts->assertValidUsername($value);
			return true;
		} catch (\Exception $ex) {
			throw new \hypeJunction\Exceptions\ActionValidationException($ex->getMessage());
		}
	}

	/**
	 * isAvailableUsername.
	 *
	 * @param PropertyInterface $prop   prop
	 * @param mixed             $value  value
	 * @param array             $params params
	 *
	 * @return mixed
	 */
	public static function isAvailableUsername(PropertyInterface $prop, $value = null, array $params = []) {
		$access_status = elgg()->session->getDisabledEntityVisibility();
		access_show_hidden_entities(true);

		$available = true;
		if (elgg_get_user_by_username($value)) {
			$available = false;
		}

		access_show_hidden_entities($access_status);

		return $available;
	}
}
