<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('exists')) {
	/**
 	*
 	* Check whether a variable exist or not, return 
 	*
 	* @param string $string to check
 	* @return string/bool whether it goes well or not
 	*
 	*/
 	function exists(&$string, $op=FALSE) {

 		return isset($string) ? $string : $op;

 	}
 }

if ( ! function_exists('lbl_doc_function_url')) {
	 /**
	 * Return the documentation function URL
	 *
	 * @param string $url the url to check
	 * @return bool
	 */
	function lbl_doc_function_url($function) {

		return LINKBREAKERS_DOC . '/fonction-'. strtolower($function);

	}
 }

if ( ! function_exists('is_intern_url')) {
	 /**
	 * Check whether an URL is also an intern_url
	 * WHY THIS METHOD ? This checking is repeated everywhere in the system
	 * So I made a quick helper
	 *
	 * @param string $url the url to check
	 * @return bool
	 */
	function is_intern_url($url) {

		if (empty($url)) return FALSE;

		if ($url[0] === '#') return TRUE;
		else return FALSE;

	}
 }

if ( ! function_exists('inject_var_dump')) {
	/**
 	*
 	* Inject a var_dump() within a variable
 	*
 	* @param mixed the data to var_dump()
 	* @return string with the var_dump() result
 	*
 	*/
 	function inject_var_dump($var_dumped) {

 		ob_start();
 		var_dump($var_dumped);
 		$final_text = ob_get_clean();

 		return $final_text;

 	}
 }

if ( ! function_exists('delete_double_spaces')) {
	/**
 	*
 	* Delete any double spaces (or more)
 	*
 	* @param string $string to check
 	* @return string/bool whether it goes well or not
 	*
 	*/
 	function delete_double_spaces($string) {

 		while (strpos($string, "  ")) $string = str_replace("  ", " ", $string);

 		return $string;

 	}
 }

if ( ! function_exists('direct')) {
	/**
 	*
 	* Redirection
 	*
 	* @param string $url where to redirect
 	* @return string redirection
 	*
 	*/
 	function direct($url) {

 		return header("Location: ".$url);

 	}
 }


 if ( ! function_exists('encode')) {
	/**
 	*
 	* Encode into ASCII system
 	*
 	* @param string $string to encode
 	* @return string encoded
 	*
 	*/
 	function encode($string) {

 		$length = strlen($string);
 		$current = 0;
 		$encoded = '';

 		while ($current < $length) {

 			$encoded .= ord($string[$current]).'#';

 			$current++;

 		}

 		// Null byte error avoid
 		$encoded = rtrim($encoded, '#');

 		return $encoded;

 	}
 }

 if ( ! function_exists('decode')) {
	/**
 	*
 	* Decode into ASCII system
 	*
 	* @param string $string to decode (such as "91#97#90#93#")
 	* @return string encoded
 	*
 	*/
 	function decode($string) {

 		$ascii_chars_array = explode("#", $string);
 		$decoded = '';

 		foreach ($ascii_chars_array as $ascii) {

 			if ($ascii !== '') $decoded .= chr($ascii);

 		}

 		return $decoded;

 	}
 }

if ( ! function_exists('strong_encrypt')) {
	/**
 	*
 	* A strong encryption to do not let any chance for someone to find something
 	* /!\ Be careful : this is a function originally created to encrypt account password
 	* There no link between this kind of function and encrypt() / decrypt() which are used into the LBL system
 	*
 	* @param string $string the string to convert
 	* @return string encrypted
 	*
 	*/
 	function strong_encrypt($string) {

 		$string = sha1(md5($string . 'linkbreakers-strong-encryption') . 'big-encryption');
 		$string = hash_hmac('sha256', $string, 'crazy-key');

 		return $string;

 	}
 }

if ( ! function_exists('encrypt')) {
	/**
 	*
 	* Case encryption -> To encrypt properly some characters, to avoid interpretation in some cases
 	*
 	* @param string $char The character to convert
 	* @param string $string The string to check
 	* @return string encrypted
 	*
 	*/
 	function encrypt($char, $string) {

 		return str_replace($char, md5($char . 'linkbreakers-protection'), $string);

 	}
 }

 if ( ! function_exists('decrypt')) {
	/**
 	*
 	* Case decryption -> To decrypt properly some characters, to avoid interpretation in some cases
 	*
 	* @param string $char The character to convert
 	* @param string $string The string to check
 	* @return string decrypted
 	*
 	*/
 	function decrypt($char, $string, $replace=FALSE) {

 		if ($replace) return str_replace(md5($char . 'linkbreakers-protection'), $replace, $string);
 		else return str_replace(md5($char . 'linkbreakers-protection'), $char, $string);

 	}

 }

  if ( ! function_exists('check_backslash_exception')) {
	/**
 	*
 	* We check possible \$ exceptions (or alike)
 	*
 	* @param string $string string where the \$ exception is
 	* @param integer $integer position of the $ character
 	* @return bool whether it returns an exception or not
 	*
 	*/
	function check_backslash_exception($string, $integer) {

		// We check a possible \$ exception (we will ignore it as a variable)
		if (!isset($string[$integer-1])) return FALSE;
		elseif ($string[$integer-1] === '\\') return TRUE;
		else return FALSE;

	}
}

 if ( ! function_exists('find')) {
 	/**
 	*
 	* Find from $first to $end in $text
 	* $inc means include or no $first/$end in final returned string
 	* -> It cans understand entity within entity within entity
 	*
 	* @param string $string contains functions to understand
 	* @param string $left start function delimiter
 	* @param string $right end function delimiter
 	* @return string cleaned url ready to be launched
 	*
 	*/
 	function find($text='', $first='', $end='', $inc=0) {

 		$pos_end = strpos($text, $end);

 		$inverse_pos_end = strlen($text) - $pos_end;
 		$pos_first = strrpos($text, $first, 0-$inverse_pos_end);

		// If there's no result
 		if ((!is_numeric($pos_first)) || (!is_numeric($pos_end))) return false;

		// If $first/$end not included in final result
 		if ($inc == 0) {

 			$pos_first += strlen($first);

		// Otherwise
 		} else {

 			$pos_end += strlen($end);

 		}

 		$pos_diff = $pos_end - $pos_first;

 		return substr($text, $pos_first, $pos_diff);

 	}
 }

 if ( ! function_exists('get_boolean_prefs')) {
	/**
 	*
 	* Transform prefs cookies into real boolean values
 	*
 	* @param string $label like 'prefs_default_result_enabled'
 	* @return bool
 	*
 	*/
 	function get_boolean_prefs($label) {

 		//if (!isset($_COOKIE[$label])) return NULL;

 		if ($_COOKIE[$label] !== FALSE) {

 			$selected_pref = $_COOKIE[$label];

 			if ($selected_pref === '0') return FALSE;
 			elseif ($selected_pref === '1') return TRUE;

 		} else return FALSE;

 	}

 }

 if ( ! function_exists('str_replace_once')) {
	/**
 	*
 	* Str replace for the first occurence
 	*
 	* @param $str_pattern what we are looking for
 	* @param $str_replacement what we want to replace with
 	* @param $string the source
 	* @return string cleaned string
 	*
 	*/
 	function str_replace_once($str_pattern, $str_replacement, $string){

 		if (strpos($string, $str_pattern) !== false){
 			$occurrence = strpos($string, $str_pattern);
 			return substr_replace($string, $str_replacement, strpos($string, $str_pattern), strlen($str_pattern));
 		}

 		return $string;
 	}

 }

 if ( ! function_exists('clean')) {
	/**
 	*
 	* Deep cleaner of a string to be compared in our system
 	*
 	* @param string $string to clean
 	* @param bool $replace if we need to delete some no understandable characters such as ?=-
 	* @return string cleaned string
 	*
 	*/
 	function clean($string, $replace=FALSE) {

 		$string = strtolower($string);

		// Characters replacement -> BE CAREFUL -> IT WILL BE CHECKED DURING add_tag AND find AND find_double
 		if ($replace) {

			// If you change this don't forget to check PHOENIX lang files and '?' include

			// Exceptions (as a unique word 'bordeaux - paris') or whatever
 			$trans_exceptions = array(
 				' ? ' => '8089ac9672237732feac38e5ee43ed66',
 				' - ' => 'e32c9290d8d3fb71920283c87b3dd780'
 				);

 			$string = strtr($string, $trans_exceptions);

 			$trans = array(
 				'?' => '',
				'=' => '',
 				'-' => ' '
 				);

 			$string = strtr($string, $trans);

 			$reverse_exceptions = array(
 				'8089ac9672237732feac38e5ee43ed66' => ' ? ',
 				'e32c9290d8d3fb71920283c87b3dd780' => ' - '
 				);

 			$string = strtr($string, $reverse_exceptions);

 		}

 		if (!preg_match('/[\x80-\xff]/', $string)) return $string;

 		$str = $string;

 		$length = strlen($str);

 		for ($i=0; $i < $length; $i++) {

 			$c = ord($str[$i]);
 			if ($c < 0x80) $n = 0;
 			elseif (($c & 0xE0) == 0xC0) $n=1;
 			elseif (($c & 0xF0) == 0xE0) $n=2;
 			elseif (($c & 0xF8) == 0xF0) $n=3;
 			elseif (($c & 0xFC) == 0xF8) $n=4;
 			elseif (($c & 0xFE) == 0xFC) $n=5;
 			else {

 				$n = 0; // Added 28/08/2013 by Laurent SCHAFFNER (i don't know exactly why there were a bug and you know the story)
 				$seems_utf8 = FALSE;

 			}

 			for ($j=0; $j<$n; $j++)

 				if ((++$i >= $length) || ((ord($str[$i]) & 0xC0) != 0x80)) $seems_utf8 = FALSE;

 		}


 		if (!isset($seems_utf8)) $seems_utf8 = TRUE;

 		if ($seems_utf8) {

 			$chars = array(
        // Decompositions for Latin-1 Supplement
 				chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
 				chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
 				chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
 				chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
 				chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
 				chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
 				chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
 				chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
 				chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
 				chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
 				chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
 				chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
 				chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
 				chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
 				chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
 				chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
 				chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
 				chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
 				chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
 				chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
 				chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
 				chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
 				chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
 				chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
 				chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
 				chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
 				chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
 				chr(195).chr(191) => 'y',
        // Decompositions for Latin Extended-A
 				chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
 				chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
 				chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
 				chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
 				chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
 				chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
 				chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
 				chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
 				chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
 				chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
 				chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
 				chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
 				chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
 				chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
 				chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
 				chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
 				chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
 				chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
 				chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
 				chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
 				chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
 				chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
 				chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
 				chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
 				chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
 				chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
 				chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
 				chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
 				chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
 				chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
 				chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
 				chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
 				chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
 				chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
 				chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
 				chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
 				chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
 				chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
 				chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
 				chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
 				chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
 				chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
 				chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
 				chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
 				chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
 				chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
 				chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
 				chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
 				chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
 				chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
 				chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
 				chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
 				chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
 				chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
 				chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
 				chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
 				chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
 				chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
 				chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
 				chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
 				chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
 				chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
 				chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
 				chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
        		// Euro Sign
 				chr(226).chr(130).chr(172) => 'E',
       			 // GBP (Pound) Sign
 				chr(194).chr(163) => '');

$string = strtr($string, $chars);

} else {

        // Assume ISO-8859-1 if not UTF-8
	$chars['in'] = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158)
	.chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194)
	.chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202)
	.chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210)
	.chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218)
	.chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227)
	.chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235)
	.chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243)
	.chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251)
	.chr(252).chr(253).chr(255);

	$chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

	$string = strtr($string, $chars['in'], $chars['out']);
	$double_chars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
	$double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
	$string = str_replace($double_chars['in'], $double_chars['out'], $string);

}

return $string;
}
}