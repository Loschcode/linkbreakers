<?php

/**
 * LBL Strings Treatment class
 *
 * LBL strings treatment functions
 *
 * @package 	LBL / String treatment
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Class
 *
 */

class Lbl_strings_treatment extends Genius {

	public function __construct() {

		parent::__Construct();

		Devlog::add_admin_yellow('LBL-Library '.__CLASS__.' has been initialized');

	}

	public function _CLEAN($arg) {

		return $this->understandable($arg);

	}


	// Convert string to int
	/*public static function _INT($arg='') {

		return (int) $arg;

	}*/

	public static function _ENCODE($type, $arg) {

		if ($type === 'RAWURL') return rawurlencode($arg);
		elseif ($type === 'URL') return urlencode($arg);
		else return FALSE;

	}

	public function _TINYURL($url) {

		return $this->panda->generate_tinyurl($url);

	}

	public function _URL() { $args = func_get_args(); array_unshift($args, 'URL'); return call_user_func_array(array($this, "_ENCODE"), $args); }
	public function _RAWURL() { $args = func_get_args(); array_unshift($args, 'RAWURL'); return call_user_func_array(array($this, "_ENCODE"), $args); }


	public static function _DECODE($type, $arg) {

		if ($type === 'RAWURL') return rawurldecode($arg);
		elseif ($type === 'URL') return urldecode($arg);
		else return FALSE;

	}

	public static function _ENCRYPT($type, $arg) {

		if ($type === 'MD5') return md5($arg);
		else return FALSE;

	}

	public function _MD5() { $args = func_get_args(); array_unshift($args, 'MD5'); return call_user_func_array(array($this, "_ENCRYPT"), $args); }

	// Return lowercased $arg
	public static function _STR_LOW($arg='') {

		return mb_convert_case($arg, MB_CASE_LOWER, "UTF-8");

	}

	// Get a gravatar picture
	public function _GRAVATAR($email, $size=160) {

		return get_gravatar($email, $size);

	}

	// TinyURL advanced
	public function _STR_TINYURL($text) {

		$arr_text = explode(' ', $text);
		$arr_final = array();

		foreach ($arr_text as $row) {

			if ($this->panda->is_correct_strong_type($row, 'url')) $arr_final[] = $this->panda->generate_tinyurl($row);
			else $arr_final[] = $row;

		}

		$text_final = implode(' ', $arr_final);

		return $text_final;

	}

	// Return uppercased $arg
	public static function _STR_UP($arg='') {

		return mb_convert_case($arg, MB_CASE_UPPER, "UTF-8");

	}

	// Return crazy string
	public function _STR_CRAZY($arg='') {

		$arg = str_split($this->STR_LOW($arg));
		$arg_crazy = '';
		$bool = TRUE;

		foreach ((array) $arg as $letter) {

			if ($bool === FALSE) {
				$arg_crazy .= $letter;
				$bool = TRUE;
			} elseif ($bool === TRUE) {
				$arg_crazy .= mb_convert_case($letter, MB_CASE_UPPER, "UTF-8");
				$bool = FALSE;
			}

		}

		return $arg_crazy;

	}


	// Return uppcaser first letter
	public function _STR_UP_FIRST($arg='',$opt=FALSE) {

		if ($opt === 'LOW_OTHERS') $arg = $this->STR_LOW($arg);

		return mb_convert_case($arg, MB_CASE_TITLE, "UTF-8");
	}

}