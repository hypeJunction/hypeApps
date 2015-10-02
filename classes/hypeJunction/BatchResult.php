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
	public function __construct(callable $getter = null, array $options = array()) {
		$this->getter = $getter;
		$this->options = $options;
	}

	/**
	 * Returns count of entities in a batch
	 * @return int
	 */
	public function getCount() {
		$options = $this->prepareBatchOptions($options);
		$options['count'] = true;
		return (int) call_user_func($this->getter, $this->options);
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
		$items = array();
		foreach ($batch as $b) {
			$items[] = $b;
		}
		return $items;
	}

	/**
	 * Export batch into an object
	 *
	 * @param string $key Parameter name to hold batch item export
	 * @return stdClass
	 */
	public function export($fields = array(), $key = 'items') {
		$result = new stdClass;

		$result->count = $this->getCount();
		$result->limit = elgg_extract('limit', $this->options, elgg_get_config('default_limit'));
		$result->offset = elgg_extract('offset', $this->options, 0);

		$batch = $this->getBatch();
		$result->{"$key"} = array();
		$i = $result->offset;

		foreach ($batch as $entity) {
			if (is_callable(array($entity, 'toObject'))) {
				$result->{"$key"}["$i"] = $entity->toObject($fields);
			} else {
				$result->{"$key"}["$i"] = $entity;
			}
			$i++;
		}
		
		return $result;
	}

	/**
	 * Prepares batch options
	 *
	 * @param array $options ege* options
	 * @return array
	 */
	protected function prepareBatchOptions(array $options = array()) {

		if (!in_array($this->getter, array(
			'elgg_get_entities',
			'elgg_get_entities_from_metadata',
			'elgg_get_entities_from_relationship',
		))) {
			return $options;
		}
		
		$sort = elgg_extract('sort', $options);
		unset($options['sort']);

		if (!is_array($sort)) {
			$sort = array(
				'time_created' => 'DESC',
			);
		}

		$dbprefix = elgg_get_config('dbprefix');

		$order_by = array();

		foreach ($sort as $field => $direction) {

			$field = sanitize_string($field);
			$direction = strtoupper(sanitize_string($direction));

			if (!in_array($direction, array('ASC', 'DESC'))) {
				$direction = 'ASC';
			}
			
			switch ($field) {

				case 'alpha' :
					if (elgg_extract('types', $options) == 'user') {
						$options['joins']['ue'] = "JOIN {$dbprefix}users_entity ue ON ue.guid = e.guid";
						$order_by[] = "ue.name  {$direction}";
					} else if (elgg_extract('types', $options) == 'group') {
						$options['joins']['ge'] = "JOIN {$dbprefix}groups_entity ge ON ge.guid = e.guid";
						$order_by[] = "ge.name  {$direction}";
					} else if (elgg_extract('types', $options) == 'object') {
						$options['joins']['oe'] = "JOIN {$dbprefix}objects_entity oe ON oe.guid = e.guid";
						$order_by[] = "oe.title {$direction}";
					}
					break;

				case 'type' :
				case 'subtype' :
				case 'guid' :
				case 'owner_guid' :
				case 'container_guid' :
				case 'site_guid' :
				case 'enabled' :
				case 'time_created';
				case 'time_updated' :
				case 'last_action' :
				case 'access_id' :
					$order_by[] = "e.{$field} {$direction}";
					break;
			}
		}

		$options['order_by'] = implode(',', $order_by);
		
		return $options;
	}

}
