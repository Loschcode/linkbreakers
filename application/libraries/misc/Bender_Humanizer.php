<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Bender Humanizer
 *
 * With Bender Humanizer you can reduce your business code in your views, enjoy !
 *
 * @package		Bender
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Jeremie Ges
 * @version 	0.1
 */
class Bender_Humanizer extends CI_Context {

	protected $auto_raw_datas = TRUE;

	public function __construct() {

		parent::__construct();
		$this->lang->load('humanize');
	}


	/**
	 * Prototype
	 *
	 * @access	protected
	 * @param	array 	Datas to humanize
	 * @param 	string 	The name of function to execute
	 * @return	mixed
	 */
	protected function _prototype($datas, $function) {

		if (!method_exists($this, $function)) return false;

		$humanize = array();
		$function = '_' . $function;

		if ($this->_multi_array($datas)) {


			foreach ($datas as $key => $data) {

				$raw_datas = array();

				// Automatic system raw datas
				if ($this->auto_raw_datas)
					$raw_datas = $this->_raw_datas($data, $function);

				$humanize[] = array_merge($raw_datas, $this->$function($data));

			}

		} else {

			// Automatic system raw datas
			if ($this->auto_raw_datas)
				$humanize = $this->_raw_datas($datas, $function);

			$humanize = $this->$function($humanize);
		}

		return $humanize;

	}

	/**
	 * Multi Array
	 *
	 * Check if an array is multidimensional
	 *
	 * @access	protected
	 * @param	array 	Array to check
	 * @return	bool
	 */
	protected function _multi_array($datas) {

		if (count($datas) != count($datas, COUNT_RECURSIVE)) 
			return true;

		return false;

	}

	protected function _raw_datas($datas, $name_function) {

		$raw_datas = $datas;

		foreach ($datas as $key => $data) {

			$raw_datas['_raw_' . $name_function . '_' . $key] = $data; 

		}

		return $raw_datas;

	}



}
?>