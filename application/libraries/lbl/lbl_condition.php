<?php

/**
 * LBL Conditiion class
 *
 * LBL condition functions
 *
 * @package 	LBL / Condition
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Class
 *
 */

class Lbl_condition extends Genius {

	public function __construct() {

		parent::__Construct();

		Devlog::add_admin_yellow('LBL-Library '.__CLASS__.' has been initialized');

	}

	public static function _ELSE() {

		return TRUE;

	}

	public function _ELSEIF($condition=FALSE) {

		return $this->_IF($condition); // Same system than IF

	}

	public static function _IF($condition=FALSE) {

		// Equal system
		if (empty($condition)) return FALSE;
		else return $condition;

	}

	public static function _ENDIF() {

		
	}

	public static function _THEN() {

		return implode(',', func_get_args());

	}

	/*
	private static function convert_condition($condition) {

		if (strpos($condition, '<')) {

			$inferiority_try = array_map('intval', explode('<', $condition));

			if (intval($inferiority_try[0]) < intval($inferiority_try[1])) return $condition;
			else return FALSE;

		}

	}*/

}