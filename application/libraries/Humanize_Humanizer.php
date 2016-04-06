<?php
/**
 * LinkBreakers
 *
 * The very first instant search engine
 *
 * @package		Linkbreakers
 * @author		Laurent Schaffner / Jeremie Ges
 * @link		http://www.linkbreakers.com/
 */

// ------------------------------------------------------------------------

/**
 * Humanize Humanizer
 *
 * Pretty way to humanize datas after db searches
 *
 * @package		Linkbreakers
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Jeremie Ges
 */
class Humanize_Humanizer extends Bender_Humanizer {

	/**
	 * Constructor
	 *
	 * Get methods of parent class
	 */
	public function __construct() {
		parent::__construct();
	}


	/**
	 * Results
	 *
	 * Humanize datas with results table schema
	 *
	 */	
	public function result($datas) {

		// Load array helper to use element function
		$this->load->helper('array');

		return $this->_prototype($datas, 'result');

	}


	public function _result($datas) {

		/*
		|--------------------------------------------------------------------------
		| Color status
		|--------------------------------------------------------------------------
		|
		| Here you can change label color for status. 
		| You can pick values below : 
		| 
		| label-default : Grey and white text
		| label-success : Green and white text
		| label-danger  : Red and white text
		| label-info    : Blue sky and white text
		| label-warning : Yellow and white text
		| label-pink    : Pink and white text
		| 
		|
		*/
		$color_status = array(

			'private' => 'label-default',
			'global' => 'label-success',
			'trans' => 'label-pink',
			'alpha' => 'label-info',
			'beta' => 'label-warning',
			'off'  =>  'label-danger'

			);

		// Treat json 
		$jsoned = json_decode($datas['edit'], TRUE);

		$this->load->library('volt/code');

		$datas['string_pure'] = $this->code->make_string_shaping($jsoned['string']);

		$datas['url_pure'] = $jsoned['url'];

		// If it's a linkedurl, we should replace it, and let it be more watchable
		if ($datas['url_pure'][0] === '@') {

			$datas['url_pure'] = '@' . $this->general_model->find_autocomplete_by_id( substr($datas['url_pure'], 1) );

		}

		// Then we convert it, not before.
		$datas['url_pure'] = $this->code->make_url_shaping($datas['url_pure']);

		$datas['hits'] = number_format($datas['hits']);


		// Load humanize status
		$status = $this->lang->line('humanize_status');

		$datas['color_status'] = element($datas['status'], $color_status, 'label-default'); 
		$datas['status'] = ucfirst( element($datas['status'], $status) );

		$datas['elapsed_time'] = strtolower( elapsed_time($datas['date']) );

		if ($datas['last_update'] > $datas['date']) $datas['last_update'] = strtolower( elapsed_time($datas['last_update']) );
		else $datas['last_update'] = FALSE;

		return $datas;

	}


	// ------------------------------------------------------------------------


	public function log($datas) {

		return $this->_prototype($datas, 'log');

	}

	public function _log($datas) {

		$datas['date_subscribe'] = date('d-m-Y', $datas['date_subscribe']);
		$datas['date_login'] = date('d-m-Y', $datas['date_login']);


		return $datas;

	}



}

?>