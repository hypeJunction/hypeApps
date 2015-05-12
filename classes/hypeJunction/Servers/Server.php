<?php

namespace hypeJunction\Servers;

abstract class Server {

	const READ = 'read';
	const WRITE = 'write';
	const READ_WRITE = 'readwrite';

	private $config;
	private $dbPrefix;
	private $dbLink;

	/**
	 * Constructor
	 *
	 * @param object $config Elgg config
	 */
	public function __construct($config) {
		$this->config = $config;
		$this->dbPrefix = $config->dbprefix;
	}

	/**
	 * Serves an icon
	 * Terminates the script and sends headers on error
	 * @return void
	 */
	abstract public function serve();

	/**
	 * Returns DB config
	 * @return array
	 */
	protected function getDbConfig() {
		if ($this->isDatabaseSplit()) {
			return $this->getConnectionConfig(self::READ);
		}
		return $this->getConnectionConfig(self::READ_WRITE);
	}

	/**
	 * Connects to DB
	 * @return void
	 */
	protected function openDbLink() {
		$dbConfig = $this->getDbConfig();
		$this->dbLink = @mysql_connect($dbConfig['host'], $dbConfig['user'], $dbConfig['password'], true);
	}

	/**
	 * Closes DB connection
	 * @return void
	 */
	protected function closeDbLink() {
		if ($this->dbLink) {
			mysql_close($this->dbLink);
		}
	}

	/**
	 * Retreive values from datalists table
	 *
	 * @param array $names Parameter names to retreive
	 * @return array
	 */
	protected function getDatalistValue(array $names = array()) {

		if (!$this->dbLink) {
			return array();
		}

		$dbConfig = $this->getDbConfig();
		if (!mysql_select_db($dbConfig['database'], $this->dbLink)) {
			return array();
		}

		if (empty($names)) {
			return array();
		}
		$names_in = array();
		foreach ($names as $name) {
			$name = mysql_real_escape_string($name);
			$names_in[] = "'$name'";
		}
		$names_in = implode(',', $names_in);

		$values = array();

		$q = "SELECT name, value
				FROM {$this->dbPrefix}datalists
				WHERE name IN ({$names_in})";

		$result = mysql_query($q, $this->dbLink);
		if ($result) {
			$row = mysql_fetch_object($result);
			while ($row) {
				$values[$row->name] = $row->value;
				$row = mysql_fetch_object($result);
			}
		}

		return $values;
	}

	/**
	 * Returns request query value
	 *
	 * @param string $name    Query name
	 * @param mixed  $default Default value
	 * @return mixed
	 */
	protected function get($name, $default = null) {
		if (isset($_GET[$name])) {
			return $_GET[$name];
		}
		return $default;
	}

	/**
	 * Are the read and write connections separate?
	 *
	 * @return bool
	 */
	public function isDatabaseSplit() {
		if (isset($this->config->db) && isset($this->config->db['split'])) {
			return $this->config->db['split'];
		}
		// this was the recommend structure from Elgg 1.0 to 1.8
		if (isset($this->config->db) && isset($this->config->db->split)) {
			return $this->config->db->split;
		}
		return false;
	}

	/**
	 * Get the connection configuration
	 *
	 * The parameters are in an array like this:
	 * array(
	 * 	'host' => 'xxx',
	 *  'user' => 'xxx',
	 *  'password' => 'xxx',
	 *  'database' => 'xxx',
	 * )
	 *
	 * @param int $type The connection type: READ, WRITE, READ_WRITE
	 * @return array
	 */
	public function getConnectionConfig($type = self::READ_WRITE) {
		$config = array();
		switch ($type) {
			case self::READ:
			case self::WRITE:
				$config = $this->getParticularConnectionConfig($type);
				break;
			default:
				$config = $this->getGeneralConnectionConfig();
				break;
		}
		return $config;
	}

	/**
	 * Get the read/write database connection information
	 *
	 * @return array
	 */
	protected function getGeneralConnectionConfig() {
		return array(
			'host' => $this->config->dbhost,
			'user' => $this->config->dbuser,
			'password' => $this->config->dbpass,
			'database' => $this->config->dbname,
		);
	}

	/**
	 * Get connection information for reading or writing
	 *
	 * @param string $type Connection type: 'write' or 'read'
	 * @return array
	 */
	protected function getParticularConnectionConfig($type) {
		if (is_object($this->config->db[$type])) {
			// old style single connection (Elgg < 1.9)
			$config = array(
				'host' => $this->config->db[$type]->dbhost,
				'user' => $this->config->db[$type]->dbuser,
				'password' => $this->config->db[$type]->dbpass,
				'database' => $this->config->db[$type]->dbname,
			);
		} else if (array_key_exists('dbhost', $this->config->db[$type])) {
			// new style single connection
			$config = array(
				'host' => $this->config->db[$type]['dbhost'],
				'user' => $this->config->db[$type]['dbuser'],
				'password' => $this->config->db[$type]['dbpass'],
				'database' => $this->config->db[$type]['dbname'],
			);
		} else if (is_object(current($this->config->db[$type]))) {
			// old style multiple connections
			$index = array_rand($this->config->db[$type]);
			$config = array(
				'host' => $this->config->db[$type][$index]->dbhost,
				'user' => $this->config->db[$type][$index]->dbuser,
				'password' => $this->config->db[$type][$index]->dbpass,
				'database' => $this->config->db[$type][$index]->dbname,
			);
		} else {
			// new style multiple connections
			$index = array_rand($this->config->db[$type]);
			$config = array(
				'host' => $this->config->db[$type][$index]['dbhost'],
				'user' => $this->config->db[$type][$index]['dbuser'],
				'password' => $this->config->db[$type][$index]['dbpass'],
				'database' => $this->config->db[$type][$index]['dbname'],
			);
		}
		return $config;
	}

}
