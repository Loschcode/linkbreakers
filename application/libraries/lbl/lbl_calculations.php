<?php

/**
 * LBL Calculations class
 *
 * LBL calculations functions
 *
 * @package 	LBL / Calculations
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Class
 *
 */

class Lbl_calculations extends Genius {

	public function __construct() {

		parent::__Construct();
		
		Devlog::add_admin_yellow('LBL-Library '.__CLASS__.' has been initialized');

	}

	// Human calculation function
	public function _CALC($arg=0) {

		// clean string (accents, unexcepted signs)
		$arg = $this->understandable($arg);

		$result = NULL;

		// We clean from spaces
		$arr_replace = array(

			' ' => '',
			'x' => '*',
			'on' => '/',
			'diviserpar' => '/',
			'diviser' => '/',
			'plus' => '+'

			);

		// Largement améliorable : enlever tous les caractères qui n'ont pas été précédemment traité ?
		$arg = $this->panda->str_replace_assoc($arr_replace, $arg);

    	//$result = create_function("", "return (int) ({$arg});" );
		@eval('$result = '.$arg.';');

		if (is_int($result)) return $result;
		else return FALSE;

	}

	public static function _PICK_RAND() {

		$args = func_get_args();
		$num_args = count($args)-1;

		$rand = mt_rand(0, $num_args);

		return $args[$rand];

	}

	public static function _TRY($args) {

		$args = func_get_args();

		foreach ($args as $row) {

			if (!empty($row)) return $row;

		}

	}


	// Random number
	public static function _RAND($min=0, $max=9) {

		return mt_rand($min, $max);

	}

}