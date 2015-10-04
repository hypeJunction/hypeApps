<?php

namespace hypeJunction\Data;

use ElggData;
use ElggRiverItem;

interface GraphInterface {

	/**
	 * Returns an array of aliases for Elgg entities and extenders
	 * @return array
	 */
	public function getAliases();

	/**
	 * Get exportable fields for a given object
	 *
	 * @param ElggData|ElggRiverItem $object Entity, metadata, river etc
	 * @param array                  $params Additional params
	 * @return Property[]
	 */
	public function getProperties($object = null, array $params = array());

	/**
	 * Returns an alias for a object
	 *
	 * @param ElggData|ElggRiverItem $object Object
	 * @return string|false
	 */
	public function getAlias($object = null);

	/**
	 * Returns an object from it's uid
	 *
	 * @param string $uid UID of the resource, or GUID of an entity, or username
	 * @return ElggData|ElggRiverItem|false
	 */
	public function get($uid = '');

	/**
	 * Returns uid of an object
	 * 
	 * @param ElggData|ElggRiverItem $object Object
	 * @return string
	 */
	public function getUid($object);

	/**
	 * Normalizes and exports data into a serializable array
	 *
	 * @param mixed $data   Data to export
	 * @param array $params Additionl params
	 * @return array
	 */
	public function export($data, array $params = array());

	/**
	 * Test if object is exportable
	 *
	 * @param mixed $object Object
	 * @return boolean
	 */
	public function isExportable($object = null);
}
