<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('array_first')) {
	/**
	 * Select the first result from an array
	 *
	 * @param array $array the array to look in
	 * @return bool
	 */
	function array_first($array) {

		if (isset($array[0])) return $array[0];
		else return FALSE;

	}
}