<?php

/**
 * autoload.php
 *
 * This file loads the environmental variables from the env.php file
 * Include it in any script where you need the variables.
 */
if(file_exists('./utils/env.php')) {
	require './utils/env.php';
}
if(!function_exists('env')) {
	function env($key, $default = null) {
		$value = getenv($key);
		if ($value === false) {
			return $default;
		}

		return $value;
	}
}
