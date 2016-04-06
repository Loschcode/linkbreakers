<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Internal Linkbreakers APIs
 *
 * Here we launch our Linkreakers APIs
 *
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Controller
 *
 */

class Doc extends CI_Controller {

	public function __construct() {

		parent::__Construct();

		$this->load->model('log_model');

		// Set page
		$this->template->set('page', 'doc');

		// Check cookie/session secured synchronization -> If not, auto_auth is engaged
		if (!$this->pikachu->show('sync')) if ($arr = $this->pikachu->auto_auth()) $this->log_model->session_auth($arr);
	}

	/**
	 * Documentation access point
	 *
	 * @access	public
	 * @return	void
	 */
	public function welcome() {

		$this->template->launch('doc/doc_test');

	}

}