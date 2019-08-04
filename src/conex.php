<?php
require_once 'utils/autoload.php';

define('DB_SERVER', env('DB_HOST'));
define('DB_USERNAME', env('DB_USERNAME'));
define('DB_PASSWORD', env('DB_PASSWORD'));
define('DB_DATABASE', env('DB_NAME'));
define("BASE_URL", "http://localhost/test/"); // Eg. http://yourwebsite.com

/**
 * Database Connection
 */
class DataConnection {

	private static $instance = NULL;
	var $conn;


	private function __construct($hostname, $user, $pass, $db) {
		$mysqli = new mysqli($hostname, $user, $pass, $db);
		if ($mysqli->connect_error) {
			var_dump("Failed to connect to MySQL database");
	        die();
			// return null;
		}
		$mysqli->set_charset('utf8');
		$this->conn = $mysqli;
	}
	// Initializer
	static function getDBConnection($hostname = DB_SERVER, $user = DB_USERNAME, $pass = DB_PASSWORD, $db = DB_DATABASE) {
		if(self::$instance == NULL) {
			self::$instance = new DataConnection($hostname, $user, $pass, $db);
		}
		return self::$instance;
 	}

	function __destruct() {
		if($this->conn !== null) {
			$this->conn->close();
		}
	}
}
