<?php

/**
 * env.example.php
 *
 * An example .env file to set the DB credentials and other sensitive info.
 * Copy this file or rename it to env.php to set up your variables.
 */

$variables = [
	'DB_HOST' => 'localhost',
	'DB_USERNAME' => 'root',
	'DB_PASSWORD' => '',
	'DB_NAME' => 'demoDB',
	'DB_PORT' => '3306',
];

foreach ($variables as $key => $value) {
	putenv("$key=$value");
}
