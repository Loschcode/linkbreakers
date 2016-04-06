<?php

/**
 * Genius class
 *
 * LBL builders functions (to use within other LBL functions)
 *
 * @package 	LBL
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Class
 *
 */

class Genius extends CI_Context {

	protected $cache = array();

	public function __construct() {

		parent::__Construct();

	}

	/**
	 *  Make the sentences undersantable from the genius and global LBL system
	 *  
	 * @access public
	 * @param string $str to become understandable
	 * @return the understandable string
	 */
	protected static function understandable($str) {

		$str = clean($str, TRUE);
		return $str;

	}

	/**
	 *  Load a LBL specific library
	 *
	 *  Useful if we want to use a LBL function within another LBL section
	 *
	 * @access private
	 * @param $library a LBL section correspondant string (e.g. 'string_treatment')
	 * @return bool the library load feedback (go through the CI documentation if you want to know, fucker)
	 */
	protected function lbl_load($library) {

		return $this->load->Library('lbl/lbl_'.$library);

	}

	/**
	 * Get the genius cache
	 *
	 *  This intern cache work for each function and can contain anything
	 *  The first method to use it is the parser which has to save the page content to avoid multi-loads
	 *
	 * @access private
	 * @param string $method the method name (e.g. _GEO)
	 * @param string $path the exact path of the method (e.g. /usr/...)
	 * @return mixed / bool
	 */
	protected function get_cache($method, $path) {

		if (isset($this->cache[$method][$path])) return $this->cache[$method][$path];
		else return FALSE;

	}

	/**
	 * Set the genius cache
	 *
	 *  This method set the genius cache
	 *  If you change this method don't forget to change the other one (get_cache)
	 *
	 * @access private
	 * @param string $method the method name (e.g. _GEO)
	 * @param string $path the exact path of the method (e.g. /usr/...)
	 * @return mixed (3rd argument content)
	 */
	protected function set_cache($method, $path, $datas) {

		$this->cache[$method][$path] = $datas;
		return $datas;

	}

	/**
	 *  Interrogation system recognizer
	 *
	 *  It compare a language file line containg '?' and smartly return the matching word
	 *  For example : "in 3 days" -> [in_days] = "in ? day(s)" will return '3' (as string)
	 *
	 * @access private
	 * @param string $str the cleaned string taped by the user
	 * @param string $lang the language file line containing the '?'
	 * @return string matched from '?' position and such
	 */
	protected function check_interrogations($str, $lang) {

		$this->lang->load('lbl/lbl_semantic', Language::pick());
		
		$mask = (string) $this->lang->line('phoenix_'.$lang); // with '?'
		$mask = str_replace('?', '%', $mask);

		// Delete optional characters from the both strings
		$arr_full_cleaned = $this->replace_optional($mask, $str);

		$mask = $arr_full_cleaned['mask'];
		$str = $arr_full_cleaned['real'];

		// We check with LIKE
		if ($this->panda->like($str, $mask)) {

			// If it's a similar string, we check '?' position and return it
			$arr_mask = explode(' ', $mask);
			$pos_final = array_search('%', $arr_mask);

			// We have '?' position, we take the equivalent from $str
			$arr_str = explode(' ', $str);

			// In case there's 'l'%' or '%'s' word
			$arr_str_final = substr($arr_str[$pos_final], strpos($arr_mask[$pos_final], '%')); // l'% case

			$arr_str_final = substr($arr_str_final, 0, strlen($arr_str_final) - 

			(strlen($arr_mask[$pos_final]) - strpos($arr_mask[$pos_final], '%') - 1) // %'s case

			); // -1 = %

			return $arr_str_final;
			//return $arr_str[$pos_final];

		}

		return FALSE;

	}

	/**
	 *  Delete optional characters from strings (checking + real sentence)
	 *
	 *  It compare checking strings (e.g day(s)) and real sentence (days or day) and normalize the results
	 *  For the both to be the same (the result will be "day" at the end)
	 *
	 * @access private
	 * @param string $checking string such as day(s)
	 * @param string $real string such as day or days which will be compared to the $checking string
	 * @return array('mask' => final mask, 'real' => final real)
	 */
	protected function replace_optional($checking, $real) {

		$arr_checking = explode(' ', $checking);
		$arr_real = explode(' ', $real);

		$int = 0;
		$pos_optional = FALSE;

		foreach ($arr_checking as $row) {

			if ((strpos($row, '(')) && (strpos($row, ')'))) {

				$pos_optional = $int;
				break;

			}

			++$int;

		}


		// If there's no optional
		if ($pos_optional === FALSE) {

			return array(
				'mask' => $checking,
				'real' => $real
				);
		}

		// If there's no set to the correct thing
		if (!isset($arr_real[$pos_optional])) {

			return array(
				'mask' => $checking,
				'real' => $real
				);

		}


		$start = strpos($arr_checking[$pos_optional], '(');
		$end = strpos($arr_checking[$pos_optional], ')');

		$optional_entity = substr($arr_checking[$pos_optional], $start, $end-$start+1);
		$optional_entity_pur = substr($arr_checking[$pos_optional], $start+1, $end-$start-1);

		$arr_checking[$pos_optional] = str_replace($optional_entity, '', $arr_checking[$pos_optional]);

		// Real has to be reset too, maybe it's not the same
		// We check -> comparing jour/jours en substract the first to the other, checking after if the end is the optional entity
		if (str_replace($arr_checking[$pos_optional], '', $arr_real[$pos_optional]) == $optional_entity_pur) $arr_real[$pos_optional] = $arr_checking[$pos_optional];

		// We implode everything as before, with some news
		$checking_final = implode(' ', $arr_checking);
		$real_final = implode(' ', $arr_real);

		if ((strpos($checking, '(')) && (strpos($checking, ')'))) $this->replace_optional($checking_final, $real_final);

		return array(
			'mask' => $checking_final,
			'real' => $real_final
			);

	}

}


