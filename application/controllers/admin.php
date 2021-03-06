<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Administration controller
 *
 * Everything linked with the administration side of Linkbreakers
 *
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Controller
 *
 */

class Admin extends MY_Controller {

	public function __construct() {

		parent::__Construct();

		$this->load->model('admin_model');

		$this->template->set('page', 'admin'); // We set our page

	}

	/**
	 * Check whether the user is an admin otherwise it quit the section before accessing our controller
	 *
	 * @access	public
	 * @return	void
	 */
	public function _remap($method='', $params=array()) {

		if (!$this->admin_check()) return FALSE;

	    if (method_exists($this, $method)) return call_user_func_array(array($this, $method), $params);

	}

	/**
	 * Check whether the user is logged as admin
	 *
	 * @access	private
	 * @return	void
	 */
	private function admin_check() {

		if ($this->pikachu->show('userstatus') === 'admin') {

			return TRUE;

		} else {

			redirect(base_url('log'));
			return FALSE;
		}

	}

	/**
	 * Route automatically to the dashboard
	 *
	 * @access	public
	 * @return	void
	 */
	public function index() {

		$this->dashboard();

	}

	/**
	 * Load administration dashboard
	 *
	 * @access	public
	 * @return	void
	 */
	public function dashboard() {

		/*$this->template->set('lasts_alpha', $this->admin_model->find_lasts(20, 'alpha'));
		$this->template->set('lasts_beta', $this->admin_model->find_lasts(20, 'beta'));
		$this->template->set('lasts_trans', $this->admin_model->find_lasts(20, 'trans'));
		$this->template->set('lasts_on', $this->admin_model->find_lasts(20, 'on'));
		$this->template->set('lasts_off', $this->admin_model->find_lasts(50, 'off'));*/

		/**
		 * STATISTICS
		 */
		$this->template->set('stats_num_results', $this->admin_model->counter('results'));
		$this->template->set('stats_num_users', $this->admin_model->counter('users'));

		$this->template->set('stats_last_entry', array_first($this->admin_model->find_lasts(1, 'results')));
		$this->template->set('stats_last_user', array_first($this->admin_model->find_lasts(1, 'users')));


		// Setting subpage
		$this->template->set('subpage', 'dashboard');

		// Launching the dashboard
		$this->template->launch_admin('dashboard');

	}

	/**
	 * Find a LB entries from the database and set it as view variable
	 *
	 * @access	public
	 * @return	void
	 */
	public function find() {

		/*$this->form_validation->set_rules('find', 'find', 'required|xss_clean');

		if ($this->form_validation->run()) {

			$find = $this->input->post('find');

			$this->template->set('find', $this->admin_model->find_entry($find));
			$this->template->set('find_text', $find);

			$this->dashboard();

		}*/
	}

	/**
	 * Edit a LB entry status (e.g 'beta')
	 *
	 * @access	public
	 * @param integer $id the entry reference
	 * @param string $status the new status to set
	 * @return	void
	 */
	public function status($id, $status) {

		$this->admin_model->edit_status($id, $status);
		redirect(base_url('admin/dashboard'));

	}

	/**
	 * Delete a LB entry
	 *
	 * @access	public
	 * @param integer $id the entry reference
	 * @return	void
	 */
	public function delete($id) {

		$this->general_model->delete_entry($id);
		redirect(base_url('admin/dashboard'));

	}


}