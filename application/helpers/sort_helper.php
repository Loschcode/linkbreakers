<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('sort_by_key')) { 


	function sort_by_key($array, $index, $order = 'ASC') {
		$sort = array();
		foreach ($array as $key => $val) {
			$sort[$key] = $val[$index];
		}

		natcasesort($sort);
		$output = array();

		foreach($sort as $key => $val) {
			$output[] = $array[$key];
		}

		if($order == 'DESC') {
			$output = array_reverse($output);
		}

		return $output;
	}
}

?>