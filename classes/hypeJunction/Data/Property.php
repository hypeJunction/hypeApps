<?php

namespace hypeJunction\Data;

/**
 * Property class.
 */
class Property implements PropertyInterface {

	const TYPE_STRING = 'string';
	const TYPE_INT = 'integer';
	const TYPE_ARRAY = 'array';
	const TYPE_BOOL = 'boolean';
	const TYPE_FLOAT = 'float';
	const TYPE_ENUM = 'enum';

	/**
	 * Property identifier
	 * @var string
	 */
	protected $id;

	/**
	 * Property attribute name
	 * @var string
	 */
	protected $attribute;

	/**
	 * Value type(s)
	 * @var string|string[]
	 */
	protected $type;

	/**
	 * Required property or not
	 * @var bool
	 */
	protected $required;

	/**
	 * Callback to retrieve value of property for an object
	 * @var callable
	 */
	protected $getter;

	/**
	 * Callback to assign value of property to an object
	 * @var type
	 */
	protected $setter;

	/**
	 * Callbacks to sanitize/prepare values for validation and setting
	 * @var []
	 */
	protected $sanitizers;

	/**
	 * Default value, if none set
	 * @var mixed
	 */
	protected $default;

	/**
	 * An array of enumeration options as [label => value] pairs
	 * @var array
	 */
	protected $enum;

	/**
	 * An array of validation rules and callbacks
	 * [rules => [rule => expectation], callbacks => [rule => callback]]
	 * @var array
	 */
	protected $validation;

	/**
	 * A string or an array of [lang => label] pairs
	 * @var string|string[]
	 */
	protected $label;

	/**
	 * A string or an array of [lang => description] pairs
	 * @var string|string[]
	 */
	protected $desc;

	/**
	 * Input view data
	 * @var mixed
	 */
	protected $input;

	/**
	 * Output view data
	 * @var mixed
	 */
	protected $output;

	/**
	 * {@inheritdoc}
	 */
	public function __construct($id, $options = []) {
		foreach ((array) $options as $key => $value) {
			$this->$key = $value;
		}

		$this->id = $id;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get($name) {
		return $this->$name;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getDefault($object) {
		return $this->default ?: $this->getValue($object);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEnumOptions() {
		if (is_array($this->enum)) {
			return $this->enum;
		} else if ($this->enum && is_callable($this->enum)) {
			return call_user_func($this->enum, $this);
		}

		return [];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getIdentifier() {
		return $this->id;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAttributeName() {
		return $this->attribute ?: $this->id;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getLabel($object, $lang = null, $raw = false) {
		$key = implode(':', array_filter([
			'label',
			$object->getType(),
			$object->getSubtype(),
			$this->getIdentifier()
		]));

		if ($raw) {
			return $key;
		}

		if ($this->label === false) {
			return false;
		}

		if (!$lang) {
			$lang = get_language();
		}

		if (is_string($this->label)) {
			$translation = $this->label;
		} else if (is_array($this->label)) {
			$translation = elgg_extract($lang, $this->label);
		}

		return ($translation) ? $translation : elgg_echo($key, [], $lang);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDescription($object, $lang = null, $raw = false) {

		$key = implode(':', array_filter([
			'help',
			$object->getType(),
			$object->getSubtype(),
			$this->getIdentifier()
		]));

		if ($raw) {
			return $key;
		}

		if (!isset($this->desc)) {
			$this->desc = $this->help;
		}
		
		if ($this->desc === false) {
			return false;
		}

		if (!$lang) {
			$lang = get_language();
		}

		if (is_string($this->desc)) {
			$translation = $this->desc;
		} else if (is_array($this->desc)) {
			$translation = elgg_extract($lang, $this->desc);
		}


		return ($translation) ? $translation : elgg_echo($key, [], $lang);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getValue($object, array $params = []) {
		if ($this->getter && is_callable($this->getter)) {
			return call_user_func($this->getter, $this, $object, $params);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function setValue(&$object, $value, array $params = []) {
		if ($this->setter && is_callable($this->setter)) {
			call_user_func($this->setter, $this, $object, $value, $params);
		}

		return $object;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isRequired() {
		return (bool) $this->required;
	}

	/**
	 * {@inheritdoc}
	 */
	public function sanitize($object, &$value = null, array $params = []) {
		// Backward-compat: old callers passed (&$value, $params) with two args.
		// With object arg, $object is the owning object; with legacy, $object IS the value.
		if (func_num_args() < 2 || !is_object($object)) {
			$legacyValue = &$object;
			$value = $legacyValue;
		}

		$sanitizers = (array) $this->sanitizers;
		foreach ($sanitizers as $sanitizer) {
			if (is_callable($sanitizer)) {
				$sanitizer($this, $value, $params);
			}
		}
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function validate($object, $value = null, array $params = []) {
		// Backward-compat: old callers passed ($value, $params). Shift if only first arg provided.
		if (func_num_args() === 1 && !is_object($object)) {
			$value = $object;
			$object = null;
		}

		$result = new \stdClass();
		$result->valid = true;
		$result->data = [];

		$rules = (array) elgg_extract('rules', (array) $this->validation, []);
		$callbacks = (array) elgg_extract('callbacks', (array) $this->validation, []);

		foreach ($rules as $rule => $expectation) {
			$valid = true;
			$messages = [];
			if (!$rule) {
				continue;
			}

			try {
				$validation_result = (array) call_user_func('\hypeJunction\Data\Validators::validateRule', $this, $value, $rule, $expectation, $params);
			} catch (\hypeJunction\Exceptions\ActionValidationException $ex) {
				$valid = false;
				$messages[] = $ex->getMessage();
			} catch (\Exception $ex) {
				$valid = false;
				$messages[] = $ex->getMessage();
			}

			if ($valid === false) {
				$result->valid = false;
			}

			$result->data[] = (object) array_merge([
				'rule' => $rule,
				'expecation' => $expectation,
				'valid' => $valid,
				'messages' => $messages,
			], $validation_result);
		}

		foreach ($callbacks as $rule => $callback) {
			$valid = true;
			$messages = [];
			if (!is_callable($callback)) {
				continue;
			}

			try {
				$valid = call_user_func($callback, $this, $value, $params);
			} catch (\hypeJunction\Exceptions\ActionValidationException $ex) {
				$valid = false;
				$messages[] = $ex->getMessage();
			} catch (\Exception $ex) {
				$valid = false;
				$messages[] = $ex->getMessage();
			}

			if ($valid === false) {
				$result->valid = false;
			}

			$result->data[] = (object) [
				'rule' => $rule,
				'callback' => $callback,
				'valid' => $valid,
				'messages' => $messages,
			];
		}

		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getInput() {
		return $this->input;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getOutput() {
		return $this->output;
	}

	/**
	 * Exports property to an array
	 * @return array
	 */
	public function toArray() {
		return array_filter([
			'name' => $this->getAttributeName(),
			'required' => $this->required,
			'type' => $this->type,
			'enum' => $this->getEnumOptions(),
			'default' => $this->default,
		]);
	}
}
