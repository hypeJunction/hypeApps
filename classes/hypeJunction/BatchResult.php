<?php

namespace hypeJunction;

use ElggBatch;
use stdClass;

/**
 * Wrapper for ElggBatch
 */
class BatchResult {

	/**
	 * Getter function
	 * @var callable
	 */
	protected $getter;

	/**
	 * Batch options
	 * @var array
	 */
	protected $options;

	/**
	 * Constructor
	 *
	 * @param callable $getter  Getter function
	 * @param array    $options Accepts all options of the getter function plus $options['sort'] an a array of $field => $direction pairs
	 */
	public function __construct(callable $getter = null, array $options = []) {
		$this->getter = $getter;
		$this->options = $options;
	}

	/**
	 * Returns count of entities in a batch
	 * @return int
	 */
	public function getCount() {
		$options = $this->prepareBatchOptions($this->options);
		$options['count'] = true;
		return (int) call_user_func($this->getter, $options);
	}

	/**
	 * Returns an iterator instance
	 * @return \ElggBatch
	 */
	public function getBatch() {
		return new ElggBatch($this->getter, $this->prepareBatchOptions($this->options));
	}

	/**
	 * Returns an array of items in the batch
	 * @return array
	 */
	public function getItems() {
		$batch = $this->getBatch();
		$items = [];
		foreach ($batch as $b) {
			$items[] = $b;
		}

		return $items;
	}

	/**
	 * Export batch into an array
	 *
	 * @param array $params Export params
	 * @return array
	 */
	public function export(array $params = []) {
		$result = ['type' => 'list', 'count' => $this->getCount(), 'limit' => elgg_extract('limit', $this->options, elgg_get_config('default_limit')), 'offset' => elgg_extract('offset', $this->options, 0), 'items' => []];
		$batch = $this->getBatch();
		foreach ($batch as $entity) {
			$result['items'][] = hypeApps()->graph->export($entity, $params);
		}

		return $result;
	}

	/**
	 * Prepares batch options
	 *
	 * @param array $options ege* options
	 * @return array
	 */
	protected function prepareBatchOptions(array $options = []) {
		if (!in_array($this->getter, ['elgg_get_entities', 'elgg_get_entities', 'elgg_get_entities'])) {
			return $options;
		}

		$sort = elgg_extract('sort', $options);
		unset($options['sort']);
		if (!is_array($sort)) {
			return $options;
		}

		$order_by = [];
		foreach ($sort as $field => $direction) {
			$field = preg_replace('/[^a-z_]/', '', $field);
			$direction = strtoupper(preg_replace('/[^A-Z]/', '', strtoupper($direction)));
			if (!in_array($direction, ['ASC', 'DESC'])) {
				$direction = 'ASC';
			}

			switch ($field) {
				case 'alpha':
					// In Elgg 3.x, name/title are metadata - use metadata sorting
					if (elgg_extract('types', $options) == 'user' || elgg_extract('types', $options) == 'group') {
						$options['sort_by'] = [
							'property' => 'name',
							'direction' => $direction,
						];
					} else if (elgg_extract('types', $options) == 'object') {
						$options['sort_by'] = [
							'property' => 'title',
							'direction' => $direction,
						];
					}
					break;
				case 'type':
				case 'subtype':
				case 'guid':
				case 'owner_guid':
				case 'container_guid':
				case 'enabled':
				case 'time_created':
				case 'time_updated':
				case 'last_action':
				case 'access_id':
					$order_by[] = "e.{$field} {$direction}";
					break;
			}
		}

		if (!empty($order_by)) {
			$options['order_by'] = implode(',', $order_by);
		}

		return $options;
	}
}
