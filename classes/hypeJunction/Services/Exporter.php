<?php

namespace hypeJunction\Services;

class Exporter {

	/**
	 * Normalizes and exports data into a serializable array
	 *
	 * @param mixed $data      Object to export
	 * @param array $fields    Fields to export
	 * @param bool  $recursive Should entity owner and containers be exported in full?
	 * @return array
	 */
	public function export($data, $fields = array(), $recursive = false) {
		if ($data instanceof \ElggBatch) {
			$return = array('items' => array());
			foreach ($data as $v) {
				$return['items'][] = $this->export($v, $fields, $recursive);
			}
			$data = $return;
		}

		if (is_scalar($data)) {
			return $data;
		} else if (is_array($data)) {
			$return = array();
			foreach ($data as $key => $v) {
				$return[$key] = $this->export($v, $fields, $recursive);
			}
			return $return;
		} else if ($data instanceof \hypeJunction\BatchResult) {
			return $data->export($fields, $recursive);
		} else if ($data instanceof \ElggData || $data instanceof \ElggRiverItem) {

			$array = is_callable(array($data, 'toObject')) ? (array) $data->toObject() : array();
			$type = is_callable(array($data, 'getType')) ? $data->getType() : 'unknown';
			$subtype = is_callable(array($data, 'getSubtype')) ? $data->getSubtype() : false;

			$params = array(
				'object' => $data,
				'fields' => $fields,
				'recursive' => $recursive,
			);

			$array = elgg_trigger_plugin_hook('to:array', $type, $params, $array);
			if ($subtype) {
				$array = elgg_trigger_plugin_hook('to:array', "$type:$subtype", $params, $array);
			}

			return $this->export($array, $fields, $recursive);
		}

		return $data;
	}

}
