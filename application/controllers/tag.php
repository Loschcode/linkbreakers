<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Tag controller
 *
 * Everything linked with the new creations or its edition
 *
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Controller
 *
 */

class Tag extends CI_Controller {

	public function __construct() {

		parent::__Construct();

		// Load models
		$this->load->model('general_model');
		$this->load->model('log_model');

		// Set page
		$this->template->set('page', 'tag');

		// Load lang because of label_button
		$lang_large = Language::pick();

		// We need to load some bonus functions because it's a special controller with many language implications (one button-sentence is in the controller / alert messages are from CodeIgniter Coreé)
		$this->lang->load('tag', $lang_large);
		$this->config->set_item('language', $lang_large);

	}

	/**
	 * Check if the user is logged in
	 *
	 * @access	public
	 * @return	void
	 */
	private function auth_check() {

		if ($this->pikachu->show('userstatus')) return TRUE;
		else {
			$this->template->launch('log_auth');
			return FALSE;
		}

	}

	/**
	 * Tag page controller
	 *
	 * @access	public
	 * @return	void
	 */
	public function index() {

		if (($this->input->get('string')) && ($this->input->get('url'))) {

			$this->form_validation->set_post('string', rawurldecode($this->input->get('string')));
			$this->form_validation->set_post('url', rawurldecode($this->input->get('url')));
			$this->form_validation->set_post('method', 'GET');

			$string_and_url_filled = TRUE;

		}

		$opt = $this->input->get('opt');

		if (($opt === 'show') || (!isset($string_and_url_filled))) $this->add();
		else $this->exec();

	}

	/**
	 * Add a creation page
	 *
	 * @access	public
	 * @return	void
	 */
	public function add() {

		$this->template->set('subpage', 'add_tag');

		$data['button_label'] = $this->lang->line('tag_button_label_build_your_creation');;
		$this->template->launch('add_tag', $data);

	}


	/**
	 * Edit your creations page
	 *
	 * @access	public
	 * @return	void
	 */
	public function edit() {

		if (!$this->pikachu->show('canEdit')) redirect(base_url('tag/add'));

		$this->template->set('subpage', 'edit_tag');

		$last_string = $this->pikachu->show('edit_string');
		$last_url = $this->pikachu->show('edit_url');
		$last_id = $this->pikachu->show('edit_id');

		// LinkedURL peculiar system (we need to replace correct autocomplete before editing it)
		if ($last_url[0] === '@') $last_url = '@' . $this->general_model->find_autocomplete_by_id(substr($last_url, 1));

		$this->form_validation->set_post('string', $last_string);
		$this->form_validation->set_post('url', $last_url);
		$this->form_validation->set_post('edit_id', $last_id); // Temporary edit mode activated

		$this->template->launch('add_tag');

	}

	/**
	 * Edit a specific entry
	 *
	 * @access	public
	 * @param integer the entry $id
	 * @return void
	 */
	public function edit_specific($id=0) {

		// Protection
		$id = (int) $id;

		if (!$this->auth_check()) return FALSE;
		$userid = $this->pikachu->show('userid');

		// We will look to recompose datas
		if ($recomposed = $this->log_model->recompose_by_id($id, $userid)) {

			$this->pikachu->multi_set(

				array(
				
				'canEdit' => TRUE,
				'edit_string' => $recomposed['string'],
				'edit_url' => $recomposed['url'],
				'edit_id' => $id

				)

			);

			$this->edit();

		} else {

			$this->add();

		}

	}

	/**
	 * Delete a specific entry
	 *
	 * @access	public
	 * @param integer the entry $id
	 * @return	void
	 */
	public function delete_specific($id=0) {

		// Protection
		$id = (int) $id;

		if (!$this->auth_check()) return FALSE;
		$userid = $this->pikachu->show('userid');

		// We remove the edition mode
		$this->panda->delete_edition_mode();

		/**
		 * We will look to delete this entry
		 * 
		 * - We will delete from its id
		 * - Depending on the userid because it cannot be an unknown user
		 * 
		 * NOTE : The linkedURL will be deleted as well
		 * 
		 */
		
		$this->general_model->remove_creation($id, $userid);
		
		// We refresh user number of creations
		$usercreations = $this->log_model->get_num_creations($userid);
		$this->pikachu->set('usercreations', $usercreations);
		$this->pikachu->set_cookie('usercreations', $usercreations);

		redirect(base_url('profile/creations'));

	}

	/**
	 * Public ask for a specific entry
	 *
	 * @access	public
	 * @param integer the entry $id
	 * @return void
	 */
	public function ask_global($id) {

		// Protection
		$id = (int) $id;

		if (!$this->auth_check()) return FALSE;
		$userid = $this->pikachu->show('userid');

		$this->panda->delete_edition_mode();

		// We will look to delete this entry
		$this->general_model->switch_status($id, $userid, 'alpha'); // IP unknown, by id, userid and linkedurl will be delete as well

		redirect(base_url('profile/creations')); // And then user will go trough his profile page

	}

	/**
	 * Execute the process to add a new idea to the database
	 *
	 * @access	public
	 * @return	void
	 */
	public function exec() {

		// If we're on edition mode, we won't check some things (like double entries) -> we'll need to get a STRONG security right after, that return/redirect if there's anything wrong (like fake edit_id mainly)
		//if (!$this->input->post('edit_id')) $creation_mode_options = '';
		//else $creation_mode_options = ''; // TOTALLY DEPRECATED ON 30/12/12 (we could erase it, but we never know what could be built afterwards)

		Devlog::add('Accessing the add entry module ; the server will check your datas format');

		// We wrap the url textarea elements into a correct input
		//$textarea_url = $this->panda->trim_lines($this->input->post('url'));
		$textarea_url = $this->input->post('url');
		$this->form_validation->set_post('url', $textarea_url);

		/**
		 *
		 * Lbl_validation_model is a specific portable validation model used in CI form_validation system
		 *
		 */

		$this->load->model('lbl_validation_model');

		$arr_rules = array(

			'string' => array('string', 'required|xss_clean|'.LBL_STRING_RULES),
			'url' => array('url', 'required|trim|max_length[2500]|'.LBL_URL_RULES),
			'method' => array('method', 'required|xss_clean'),

			'edit_id' => array('edit id', 'xss_clean')

			);

		if ($this->lbl_validation_model->validate_lbl($arr_rules)) {

			$post = $this->input->post();

			Devlog::add('You requested to add the entry (STRING) "'.$post['string'].'" / (URL) "'.$post['url'].'" to Linkbreakers');

			// If it's a standard URL, we add http:// if the user forgot it
			$post['url'] = $this->panda->check_and_correct_url($post['url']);

			/**
			 * EDITION MODE
			 * 
			 * We will check the IP according to this ID and destroy then add it again (easiest way)
			 * With a specific system
			 *
			 * NOTE : Before we were using the insert() classic mode but it cannot be done like that now
			 * The creation is totally virtual and the dates will be faked so we need to insert with
			 * a weird "update" mode which remove and add the entry again
			 * 
			 */
			if (isset($post['edit_id'])) {

				Devlog::add('The request has been recognized as an edition request');

				$edit_id = (int) $this->input->post('edit_id'); // Totally securised via (int)

				// This check if the user is logged-in or not, which will change everything after that
				if (!$userid = $this->pikachu->show('userid')) $userid = NULL;

				// Delete while we're in edition mode
				$insert_id = $this->general_model->update_creation($post, $edit_id, $userid);

			} else {

				Devlog::add('The new entry will be added to the database');

				$insert_id = $this->general_model->insert($post, LINKBREAKERS_INSERTION_STATUS);

			}

			// Edition activation after insertion
			$this->pikachu->multi_set(

				array(

					'canEdit' => TRUE,
					'edit_string'=> $post['string'],
					'edit_url' => $post['url'],
					'edit_id' => $insert_id

				)

			);

			// Refresh user creations number
			if ($userid = $this->pikachu->show('userid')) {

				$usercreations = $this->log_model->get_num_creations($userid);
				$this->pikachu->set('usercreations', $usercreations);
				$this->pikachu->set_cookie('usercreations', $usercreations);

			}

			Devlog::save();

			redirect();
			return TRUE;

		} else {

			Devlog::save();

			$this->add();

		}

	}


}