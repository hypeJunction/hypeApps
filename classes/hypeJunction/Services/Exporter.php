<?php

namespace hypeJunction\Services;

class Exporter {

	/**
	 * Normalizes and exports data into a serializable array
	 *
	 * @param mixed $data      Object to export
	 * @param array $params    Additionl params
	 * @uses $params['fields'] An array of fields to export
	 * @uses $params['recursive'] Export recursively
	 * @return array
	 */
	public function export($data, array $params = array()) {
		if (empty($data)) {
			return $data;
		}

		if ($data instanceof \ElggBatch) {
			$return = array('items' => array());
			foreach ($data as $v) {
				$return['items'][] = $this->export($v, $params);
			}
			$data = $return;
		}

		if (is_scalar($data)) {
			return $data;
		} else if (is_array($data)) {
			$return = array();
			foreach ($data as $key => $v) {
				$return[$key] = $this->export($v, $params);
			}
			return $return;
		} else if ($data instanceof \hypeJunction\BatchResult) {
			return $data->export($params);
		} else if ($data instanceof \ElggData || $data instanceof \ElggRiverItem) {

			$array = is_callable(array($data, 'toObject')) ? (array) $data->toObject() : array();
			$type = is_callable(array($data, 'getType')) ? $data->getType() : 'unknown';
			$subtype = is_callable(array($data, 'getSubtype')) ? $data->getSubtype() : false;

			$hook_params = $params;
			$hook_params['object'] = $data;

			$array = elgg_trigger_plugin_hook('to:array', $type, $hook_params, $array);
			if ($subtype) {
				$array = elgg_trigger_plugin_hook('to:array', "$type:$subtype", $hook_params, $array);
			}

			return $this->export($array, $params);
		}

		return $data;
	}

}
