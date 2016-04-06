<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Home controller
 *
 * Everything linked with the home page
 *
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Controller
 *
 */

class Home extends CI_Controller {

	public function __construct() {

		parent::__Construct();

		// Load models
		$this->load->model('general_model');
		$this->load->model('autocomplete_model');
		$this->load->model('log_model');

		// Set page
		$this->template->set('page', 'search');

		// Check cookie/session secured synchronization -> If not, auto_auth is engaged
		if (!$this->pikachu->show('sync')) if ($arr = $this->pikachu->auto_auth()) $this->log_model->session_auth($arr);

	}

	/**
	 * Home page access point
	 *
	 * @access	public
	 * @return	void
	 */
	public function index() {

		// In case we'd try linkbreakers.com/?request=test
		if ($this->input->get('request')) redirect(base_url('/search/?request='.urlencode($this->input->get('request'))));

		// Autoresult system display
		/*if ($this->pikachu->get_cookie('autoresult')) $data['autoresult'] = $this->pikachu->get_cookie('autoresult');
		else $data['autoresult'] = 'checked="checked"';*/

		// Stick tool display
		if ($data['stick_content'] = $this->pikachu->show('tools/stick')) {

			// We set the subpage (useful to do not display some home options)
			$this->template->set('subpage', 'tools/stick');
			$this->pikachu->delete('tools/stick');

		}
		
		// We launch the result + autocomplete words
		$this->template->launch('search', $data);

	}

	/**
	 * Space user special access
	 *
	 * @access	public
	 * @param mixed $user name or id of the user
	 * @return	void
	 */
	public function from_user($user) {

		if ($id_from_user = $this->log_model->find_id_by_username($user)) {

		// If space isn't activated we shouldn't be able to visit this space
		if (!$this->log_model->get_user_detail('space', $id_from_user, 'space')) $this->index();

		// If the space_home isn't activated, it's the same
		if (!$this->log_model->get_user_detail('space', $id_from_user, 'space_home')) $this->index();

		// Reset page
		//$this->template->set('focus', 'search_user');
		$this->template->set('page', 'search_from_user');
		$this->template->set('space_user', $user);
		$this->template->set('space_avatar', $this->log_model->get_user_detail('space', $id_from_user, 'space_avatar')); // See below (should be optimized)

		// Space description (LBL understanding layer)
		$space_description_raw = $this->log_model->get_user_detail('space', $id_from_user, 'space_description');
		$space_description = $this->panda->understand_functions($space_description_raw);

		$this->template->set('space_description', $space_description); // Should be optimized
		$this->template->set('id_from_user', $id_from_user);

		// Stick tool display
		if ($data['stick_content'] = $this->pikachu->show('tools/stick')) {
			
			// We set the subpage (useful to do not display some home options)
			$this->template->set('subpage', 'tools/stick');
			$this->pikachu->delete('tools/stick');

		}

		Devlog::save();

		// Search from user
		$this->template->launch('search_from_user', $data);

		} else $this->index();

	}

}