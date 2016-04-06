<?php

class Tank extends CI_Context {

	public function __construct() {

		parent::__Construct();

	}

	public function get_conflict_rate($string, $url) {

		/* CONFLICT RATE
		 *
	D	 * 0 no similar tag in any way
		 * 1 raw link with some similar tag
		 * 2
		 * 3
		 * 4
		 * 5
	D	 * 6 small conflict potential with multi-vars
		 * 7
	D	 * 8 high conflict potential with multi-var (without strong-type it will get a big conflict for sure)
		 * 9 this entry already exists for sure
		 * 10
		 *
		*/

		//$this->load->model('general_model');

		if ($result = $this->check_potential_double($string)) return (int) $result;

		return 0;

	}

	// Check if this entry already exists in our system
	protected function check_potential_double($str) {

		if ($this->general_model->find_double($str)) {

			// If there's a high chance it's a double entry
			return 8;

		} else {

			// We need to compress double or triple variables ($var $var2 !map > $var !map BIG FAILURE)
			$arr = explode(" ", $str);
			$arr_count = count($arr)-1;
			$int = 0;

			while ($int <= $arr_count) {

				if (!isset($arr[$int+1][0])) break;

				if (($arr[$int][0] === '$') && ($arr[$int+1][0] === '$')) {
					unset($arr[$int]);
				}

				$int++;

			}

			$str = implode(" ", $arr);

			if ($this->general_model->find_double($str)) {

				return 6;


			} else {

				return FALSE;

			}

		}

	}


}