<?php

namespace hypeJunction\Data;

use ElggData;
use ElggRiverItem;

interface PropertyInterface {

	/**
	 * Constructor
	 * 
	 * @param string $id      Property identifier
	 * @param array  $options Property options
	 */
	public function __construct($id, array $options = array());

	/**
	 * Get property identifier (shortname)
	 * @return string
	 */
	public function getIdentifier();

	/**
	 * Get property attribute name, e.g. property 'access' maps to attribute 'access_id'
	 * @return string
	 */
	public function getAttributeName();

	/**
	 * Get acceptable value type(s)
	 * @return string|string[]
	 */
	public function getType();

	/**
	 * Is this property required for the object to be created
	 * @return bool
	 */
	public function isRequired();

	/**
	 * Returns enumeration options for field values
	 * An array of options, or an array of [value => label] pairs
	 * @return array
	 */
	public function getEnumOptions();

	/**
	 * Get human readable name for this property
	 *
	 * @param ElggData|ElggRiverItem $object Object
	 * @param string                 $lang   Language code
	 * @param boolean                $raw    Get raw language key
	 * @return string
	 */
	public function getLabel($object, $lang = null, $raw = false);

	/**
	 * Get human readable description for this property
	 *
	 * @param ElggData|ElggRiverItem $object Object
	 * @param string                 $lang   Language code
	 * @param boolean                $raw    Get raw language key
	 * @return string
	 */
	public function getDescription($object, $lang = null, $raw = false);

	/**
	 * Get value of this property for the given object
	 *
	 * @param ElggData|ElggRiverItem $object
	 * @param array                  $params Additional params
	 * @return mixed
	 */
	public function getValue($object, array $params = array());

	/**
	 * Set value of this property on the given object and return it
	 *
	 * @param ElggData|ElggRiverItem $object
	 * @param mixed                  $value  Value to set
	 * @param array                  $params Additional params
	 * @return ElggData|ElggRiverItem
	 */
	public function setValue(&$object, $value, array $params = array());

	/**
	 * Get default value of this property
	 *
	 * @param ElggData|ElggRiverItem $object
	 * @return mixed
	 */
	public function getDefault($object);

	/**
	 * Validate the value before it is set on an entity
	 * 
	 * @param ElggData|ElggRiverItem $object
	 * @param mixed                  $value  Value to validate
	 * @param array                  $params Additional params
	 * @return \stdClass with 'valid' & 'data' properties
	 */
	public function validate($object, $value, array $params = array());

	/**
	 * Sanitize and prepare the value
	 * 
	 * @param ElggData|ElggRiverItem $object
	 * @param mixed                  $value  Value to sanitize
	 * @param array                  $params Additional params
	 * @return mixed
	 */
	public function sanitize($object, &$value, array $params = array());

	/**
	 * Get input data
	 * @return mixed
	 */
	public function getInput();

	/**
	 * Get output data
	 * @return mixed
	 */
	public function getOutput();
}
