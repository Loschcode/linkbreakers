<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Lang controller
 *
 * Everything linked with the changing lang system (en.linkbreakers.com / fr.linkbreakers.com routing)
 *
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Controller
 *
 */

class Lang extends CI_Controller {

	public function __construct() {

		parent::__Construct();

	}

	/**
	 * Entry point of the language system, there's nothing else yet
	 *
	 * @access	public
	 * @return	void
	 */
	public function index($lang) {

		// We save our path to use it with the new language redirection
		$args = func_get_args();
		unset($args[0]); // We unset the first arg ($lang) - this function is to replace in redirection
		$new_uri = implode("/", $args);

		if ($this->language->change_language($lang)) redirect(base_url($new_uri)); // We redirect to the correct URI with the new language
		else redirect(base_url()); // There's a problem with the lang change

	}


}