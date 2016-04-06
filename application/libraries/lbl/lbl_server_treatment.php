<?php

/**
 * LBL Server Treatment class
 *
 * LBL server treatment functions
 *
 * @package 	LBL / Server Treatment
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Class
 *
 */

class Lbl_server_treatment extends Genius {

	public function __construct() {

		parent::__Construct();

		Devlog::add_admin_yellow('LBL-Library '.__CLASS__.' has been initialized');

	}

	// Small benchmark system for unique function (look out Benchmark Tool for something better and more complete)
	public function _BENCHMARK($function) {

		$iterations = 50000;

		if ($function === 'PARSER') $iterations = 1; // We low the HTML function because 50K is too many

		$this->benchmark = new CI_Benchmark();
		$this->benchmark->mark('start');

		$function = '_'.$function;

		if (!method_exists($this, $function)) return FALSE;

		$args = func_get_args();
		unset($args[0]); // Unset our fist argument (because it's the function name)

		$int = 0;
		while ($int < $iterations) {

			call_user_func_array(array($this, $function), $args); // Really slow, fuck it

			++$int;
		}

		$this->benchmark->mark('end');

		return $this->benchmark->elapsed_time('start', 'end');

	}

	// Return date of today with any format, can calculate another day with $day variable
	public static function _DATE($day=0, $arg='d/m/y') {

		$bonus_day = time()+($day*60*60*24);

		if ($day>0) return date($arg, $bonus_day);
		else return date($arg);
	}

	// Resolve DNS on IPs
	public static function _DNS($ip='MYSELF') {

		if ($ip === 'MYSELF') $ip = $_SERVER['REMOTE_ADDR'];
		return @gethostbyaddr($ip);

	}

	// Return user ip address
	public static function _IP($arg='MYSELF') {

		if ($arg === 'MYSELF') return $_SERVER['REMOTE_ADDR'];
		else {

		$result = @gethostbyname($arg);
		if ($arg === $result) return FALSE;
		else return $result;

		}

	}

	// Informations about Linkbreakers server
	public static function _SERVER($detail='') {

		if ($detail === 'IP') return $_SERVER['SERVER_ADDR'];
		// TODO : server hour, server name, server version and stuff like this (about LBL ?)

	}

	// Informations about the user looking for something
	public function _USER($detail='') {

		if ($detail === 'NAME') return $this->pikachu->show('username_pretty');
		elseif ($detail === 'NAME_RAW') return $this->pikachu->show('username');
		elseif ($detail === 'ID') return $this->pikachu->show('userid');

	}

	//public function IP() { return call_user_func_array(array($this, "IP_ADDR"), func_get_args()); }

	// User Language
	public function _LANG($opt='LINKBREAKERS') {

		if ($opt === 'BROWSER') return $this->language->pick_lang_from_browser();
		elseif ($opt === 'LINKBREAKERS') return Language::pick('short');

	}

	public function _MOBILE() {

		$get_os = $this->_OS();

		$mobile_os = array('iPhone', 'Android', 'Blackberry', 'iPod');

		if (in_array($get_os, $mobile_os)) return $get_os;
		else return FALSE;

	}

	// User OS
	public static function _OS() {

		$get_os = $_SERVER["HTTP_USER_AGENT"];
		$get_os = strtolower($get_os); // Over optimization ? Yes.

		// Phones and tablets
		if (strpos($get_os, 'iphone')) return 'iPhone';
		elseif (strpos($get_os, 'ipad')) return 'iPad';
		elseif (strpos($get_os, 'android')) return 'Android';
		elseif (strpos($get_os, 'blackberry')) return 'Blackberry';
		elseif (strpos($get_os, 'palm')) return 'Palm';
		elseif (strpos($get_os, 'ipod')) return 'iPod';

		// Computers
		elseif (strpos($get_os, 'win')) return 'Windows';
		elseif (strpos($get_os, 'linux')) return 'Linux';
		elseif (strpos($get_os, 'mac')) return 'Macintosh';

		// And shits ...
		elseif (strpos($get_os, 'freebsd')) return 'FreeBSD';
		elseif (strpos($get_os, 'openbsd')) return 'OpenBSD';
		elseif (strpos($get_os, 'netbsd')) return 'NetBSD';

		elseif (strpos($get_os, 'os/2')) return 'OS/2';
		elseif (strpos($get_os, 'sunos')) return 'Sunos';
		elseif (strpos($get_os, 'beos')) return 'Beos';
		elseif (strpos($get_os, 'aix')) return 'Aix';
		elseif (strpos($get_os, 'qnx')) return 'QNX';

		return 'Unknown';

	}

	// To get Linkbreakers actual search
	public function _SEARCH($treatments=FALSE) {

		$final_text = $this->input->post('search_text');

		if ($treatments === 'URL') return urlencode($final_text);
		elseif ($treatments === 'RAWURL') return rawurlencode($final_text);

		return $final_text;

	}

}