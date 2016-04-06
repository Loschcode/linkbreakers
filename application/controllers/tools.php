<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Tools controller
 *
 * This invisible controller display our tools without changing the page we searched on
 * Don't change it if you don't know what it exactly does to the system
 *
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Controller
 *
 */

class Tools extends CI_Controller {

	public function __construct() {

		parent::__Construct();

		// Load models
		$this->load->model('general_model');
		$this->load->library('volt/mistertools');

		$this->template->set('page', 'tools');
	}

	/*
	It seems useless but this system is important for the autocomplete and other stuff
	We NEED to KEEP these controllers when they're showing LB ; be careful.
	*/

	public function index() {

		return FALSE;

	}

	/**
	 * Sandbox controller
	 *
	 * @access	public
	 * @return	mixed
	 */
	public function sandbox($args='') {

		return $this->mistertools->sandbox($args);

	}

	/**
	 * Stick controller
	 *
	 * @access	public
	 * @return	mixed
	 */
	public function stick($args='') {

		return $this->mistertools->stick($args);

	}

	/**
	 * Text controller
	 *
	 * @access	public
	 * @return	mixed
	 */
	public function text($args='') {

		return $this->mistertools->text($args);

	}

}