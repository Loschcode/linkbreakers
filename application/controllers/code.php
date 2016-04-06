<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Code controller
 *
 * Everything linked with the LBL code higlighter
 *
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Controller
 *
 */

class Code extends CI_Controller {

	public function __construct() {

		parent::__Construct();

		// Set page
		$this->template->set('page', 'code');

	}

	/**
	 * INTRODUCTION
	 *
	 * Profile controller where everything about the user
	 * And his space can be edited/removed
	 *
	 * @access	public
	 * @return	void
	 */
	public function index() {

		//$this->template->launch('profile', $datas);

	}

}