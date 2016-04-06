<?php

/**
 * LBL About Linkbreakers class
 *
 * LBL about linkbreakers functions
 *
 * @package 	LBL / About Linkbreakers
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Class
 *
 */

class Lbl_about_linkbreakers extends Genius {

	public function __construct() {

		parent::__Construct();

		Devlog::add_admin_yellow('LBL-Library '.__CLASS__.' has been initialized');

	}

	// To set Linkbreakers URL
	public static function _LBURL($search='', $user=FALSE) {

		if ($user === 'MYSELF') $user = $this->pikachu->show('userid');

		if ($user) return base_url($user.'/'.rawurlencode($search));
		else return base_url('/search/?request='.rawurlencode($search));

	}

	// Return Linkbreakers address
	public function _LINKBREAKERS($http_opt='') {

		$http_opt = (string) $http_opt;

		if ($http_opt === 'HTTP') $result = 'http://'. LINKBREAKERS_URL . '/';
		else $result = LINKBREAKERS_URL;

		return $result;

	}

	// To set automatically a TEXT COOL URL
	public static function _TEXT($text='') {

		//mb_convert_encoding($text, 'UTF-8', 'auto');
		return base_url('/search/?request='.rawurlencode('#text ' . utf8_encode($text)));

	}

	// Return Linkbreakers version
	public function _VERSION($arg='') {

		return LINKBREAKERS_VERSION;

	}

}