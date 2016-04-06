<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Profile controller
 *
 * Everything linked with the users profiles
 *
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Controller
 *
 */

class Profile extends CI_Controller {

	public function __construct() {

		parent::__Construct();

		// Load models
		$this->load->model('log_model');
		$this->load->model('general_model');

		// Load libraries
		$this->load->library('misc/Bender_Humanizer');
		$this->load->library('Humanize_Humanizer', '', 'humanize');

		// Set page
		$this->template->set('page', 'profile');

		// Check cookie/session secured synchronization -> If not, auto_auth is engaged
		if (!$this->pikachu->show('sync')) if ($arr = $this->pikachu->auto_auth()) $this->log_model->session_auth($arr);


	}

	/**
	 * Check whether the user is logged otherwise it quit the section before accessing our controller
	 *
	 * @access	public
	 * @return	void
	 */
	public function _remap($method='', $params=array()) {

		if (!$this->auth_check()) redirect(base_url('log'));

	    if (method_exists($this, $method)) return call_user_func_array(array($this, $method), $params);

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

		$userid = $this->pikachu->show('userid');

		// Get datas about the user 
		$user_detail = $this->log_model->get_user_detail('log', $userid, '*');

		// Count creations
		$user_num_creations = $this->log_model->get_num_creations($userid);

		// Humanize and inject in view
		$datas['user_detail'] = $this->humanize->log($user_detail);	
		$datas['user_num_creations'] = $user_num_creations;

		// Profile picture
		$datas['profile_picture'] = $this->panda->get_gravatar($this->pikachu->show('useremail'));

		$this->template->launch('profile', $datas);

	}

	/**
	 * AUTH CHECK
	 *
	 * Check whether the client has been logged
	 * If not, it redirects on the log-in board
	 *
	 * @access	private
	 * @return	void
	 */
	private function auth_check() {

		if ($this->pikachu->show('userstatus')) return TRUE;
		else {

			redirect(base_url('log'));
			//$this->template->launch('log_auth');
			return FALSE;

		}

	}

	/**
	 * UPLOAD
	 *
	 * Upload details about the user and his space
	 *
	 * @access	public
	 * @param string $type which kind of profil data we will change
	 * @param string $opt any option we could add tu an update (such as 'delete')
	 * @return void
	 */
	public function update($type='', $opt=NULL) {

		if ($type === 'password') {

			$this->form_validation->set_rules('old_password', 'old password', 'required|xss_clean|callback_check_account');
			$this->form_validation->set_rules('new_password', 'new password', 'required|xss_clean');
			$this->form_validation->set_rules('new_password_repeat', 'new password confirmation', 'required|xss_clean|matches[new_password]');

			if ($this->form_validation->run()) {

				$post = $this->input->post();
				$this->log_model->change_password($this->pikachu->show('userid'), $post['old_password'], $post['new_password']);

			}


		} elseif ($type === 'email') {

			$this->form_validation->set_rules('email', 'email', 'xss_clean|valid_email|callback_check_unique_email');

			if ($this->form_validation->run()) {

				$email = $this->input->post('email');

				// We check if we changed something to this email (NOTE : it's all about the emails notification we will send right after that)
				if ($this->pikachu->show('useremail') === $email) $email_was_changed = FALSE;
				else $email_was_changed = TRUE;

				$this->log_model->set_user_detail('log', $this->pikachu->show('userid'), 'email', $email, TRUE, 'useremail');

				// This will work only if EFFECTIVELY we changed something to this email
				if ($email_was_changed) {

					// ... and set the validation key
					$email_valid_key = $this->panda->make_recovery_key($email);

					// We refresh the database, adding a recovery key and canceling the email_valid from log table
					$this->log_model->add_recovery($this->pikachu->show('userid'), 'email_valid', $email_valid_key);
					$this->log_model->set_user_detail('log', $this->pikachu->show('userid'), 'email_valid', FALSE, TRUE, 'useremailvalid');

					// We prepare an email
					$link_to_valid = base_url('profile/update/email_valid?key='.$email_valid_key);

					// We will send an email to validate everything
					$email_details = array(

						'username' => $this->pikachu->show('username'),
						'username_pretty' => $this->pikachu->show('username_pretty'),
						'link_to_valid' => $link_to_valid,

						);

					$this->load->library('volt/hermes');

					$this->hermes->send_template_email($email, 'email_validation', 'Linkbreakers Email Validation', $email_details);

				}

			}

		} elseif ($type === 'email_valid') { // update/email_valid?key=KEY

			// We convert the get to a normalized post
			if ($this->input->get('key')) $this->form_validation->set_post('key', $this->input->get('key'));

			$this->form_validation->set_rules('key', 'key', 'xss_clean|alpha_numeric');

			if ($this->form_validation->run()) {

				$key = $this->input->post('key');

				/**
				 *
				 * This constant term means the key is valid so we must set it as an exception when we update this database field
				 * Theorically impossible thank to the alpha_numeric set_rules protection (the term contains _), in any case we need to check this exception
				 * 
				 */

				// We check the email valid and update it
				if ($this->log_model->email_valid_exists($this->pikachu->show('userid'), $key)) {

				$this->log_model->set_user_detail('log', $this->pikachu->show('userid'), 'email_valid', TRUE, TRUE, 'useremailvalid');
				$this->log_model->remove_recovery($this->pikachu->show('userid'), 'email_valid');

				}

			}

			redirect(base_url('profile'));

		} elseif ($type === 'prefs_default_result_url') {

			$this->load->model('lbl_validation_model');

			$arr_rules = array(

				'prefs_default_result_url_edit' => array('default result url', 'xss_clean|'.LBL_URL_ALONE_RULES)

				);

			if ($this->lbl_validation_model->validate_lbl($arr_rules)) {

				$prefs_default_result_url_edit = $this->input->post('prefs_default_result_url_edit');
				if (empty($prefs_default_result_url_edit)) $prefs_default_result_url_edit = LINKBREAKERS_NO_RESULT_REDIRECTION;

				$lbl_treated_fields = $this->update_lbl_url_field(

					'prefs', 
					$this->pikachu->show('userid'),
					'prefs_default_result_url',
					$prefs_default_result_url_edit,
					FALSE // There's no session for this one, just cookies

				);

				// There's no method to auto-set cookies with pikachu so i put it here, we don't need session for t his one
				$this->pikachu->cooking(

					array(

						'prefs_default_result_url' => $lbl_treated_fields['raw'],
						'prefs_default_result_url_edit' => $lbl_treated_fields['edit']

					)

				);

			}


		} elseif ($type === 'space') {

			if ($opt === 'activate') {

				$this->log_model->set_user_detail('space', $this->pikachu->show('userid'), 'space', TRUE, TRUE, 'userspace');
				$this->pikachu->flag('success', 'Bienvenue dans votre espace personnel !');
				redirect(base_url('profile/space'));

			} elseif ($opt === 'deactivate') {

				$this->log_model->set_user_detail('space', $this->pikachu->show('userid'), 'space', FALSE, TRUE, 'userspace');
				$this->pikachu->flag('success', 'Espace personnel desactivé avec succès');
				$this->index();
			}

		} elseif ($type === 'space_home') {

			if ($opt === 'activate') {

				$this->log_model->set_user_detail('space', $this->pikachu->show('userid'), 'space_home', TRUE, TRUE);

			} elseif ($opt === 'deactivate') {

				$this->log_model->set_user_detail('space', $this->pikachu->show('userid'), 'space_home', FALSE, TRUE);

			}

			redirect(base_url('profile/space#opt'));

		} elseif ($type === 'space_avatar') {

			if ($opt === NULL) {

				$this->load->library('volt/flower');

				$upload_feedback = $this->flower->upload_picture(array(

					'name_file' => 'avatar_file',
					'destination' => 'avatars'

					));

				if ($upload_feedback['success_upload']) {

					$pic_src = base_url('assets/uploads/avatars/'.$upload_feedback['about_upload']['file_name']);

					$old_space_avatar = $this->pikachu->show('space_avatar');

					// We delete our previous picture (cutting before 'assets/')
					if (!empty($old_space_avatar)) unlink(substr($old_space_avatar, (strpos($old_space_avatar, '/assets')+1)));

					// If it succeeds we'll resize the picture
					//$this->flower->resize_picture($upload_feedback['about_upload']['full_path'], 800, 180, 'width');

					// After we uploaded it we set it in our profile
					$this->log_model->set_user_detail('space', $this->pikachu->show('userid'), 'space_avatar', $pic_src, TRUE);

					$this->pikachu->flag('success', 'Changement d\'avatar effectué !');

				} else {

				$this->pikachu->flag('error', $upload_feedback['about_errors']);

				}

			} elseif ($opt === 'delete') {

				$old_space_avatar = $this->pikachu->show('space_avatar');
				unlink(substr($old_space_avatar, (strpos($old_space_avatar, '/assets')+1)));
				$this->log_model->set_user_detail('space', $this->pikachu->show('userid'), 'space_avatar', '', TRUE);
				
				$this->pikachu->flag('success', 'Avatar supprimé avec succès !');

			}

			$this->space();


		} elseif ($type === 'space_redirection') {

		$this->load->model('lbl_validation_model');

		$arr_rules = array(

			'space_redirection_edit' => array('space redirection', 'xss_clean|'.LBL_URL_ALONE_RULES)

			);

		if ($this->lbl_validation_model->validate_lbl($arr_rules)) {

				// This method check/replace everything (LBL) and put a JSON version within the database
				// I know, i did it well for this one.
				$this->update_lbl_url_field(

					'space', 
					$this->pikachu->show('userid'),
					'space_redirection',
					$this->input->post('space_redirection_edit'),
					TRUE

				);

				$this->pikachu->flag('success', 'Changement de redirection effectué !');

			} else $this->pikachu->flag('error', 'Erreur : changement de redirection impossible.');

			$this->space();	

		} elseif ($type === 'space_description') {

			$this->load->model('lbl_validation_model');

			$arr_rules = array(

				'space_description_edit' => array('space description', 'xss_clean|'.LBL_URL_ALONE_RULES)

				);

			if ($this->lbl_validation_model->validate_lbl($arr_rules)) {

				$this->update_lbl_url_field(

					'space', 
					$this->pikachu->show('userid'),
					'space_description',
					$this->input->post('space_description_edit'),
					TRUE

				);

				$this->pikachu->flag('success', 'Changement de description effectué !');

			} else $this->pikachu->flag('error', 'Erreur : changement de description impossible.');
			
			$this->space();

		}

		$this->index();

	}

	/**
	 * We will update a LBL field
	 *
	 * The LBL field has to get a strict format within the database :
	 *
	 * - There's the efficient field which is 'my_field'
	 * - There's a JSONED field which is 'my_field_edit'
	 *
	 * The '_edit' field will be displayed in the system
	 * The other one will be executed by the LBL core
	 *
	 * Example : space_description / space_description_edit 
	 *
	 * @access	public
	 * @param string $table match with a table within the database
	 * @param string $lbl_url_field the label of the field such as 'space_description'
	 * @param string $lbl_content the LBL content corresponding to the field
	 * @param bool $session_refresh Do we need to refresh this field within sessions ? The name will be the same than the field
	 * @return array the variables 'raw' and 'edit' corresponding to the changed variables
	 */
	public function update_lbl_url_field($table, $userid, $lbl_url_field, $lbl_content, $session_refresh=FALSE) {

		// We set the label of the 'edit' field
		$lbl_url_field_edit_label = $lbl_url_field . '_edit';

		/*
		 * We normalize the entry to be understood by our LBL system
		 * And we set it within the database
		 */
		$lbl_url_analyzed = $this->panda->prepare_lbl_url_to_be_understood($lbl_content);
		$this->log_model->set_user_detail($table, $userid, $lbl_url_field, $lbl_url_analyzed);

		/* 
		 * We put the watchable version within the database (JSON ENCODED)
		 *
		 * And set the pikachu-session to refresh the datas the user cans see
		 * WHY DON'T SET IT ABOVE ? Because the data above doesn't have to be shown at all
		 */
		$json_content = json_encode($lbl_content);

		$this->log_model->set_user_detail($table, $userid, $lbl_url_field_edit_label, $json_content);
		if ($session_refresh) $this->pikachu->set($lbl_url_field_edit_label, $json_content);

		return array('raw' => $lbl_url_analyzed, 'edit' => $json_content);

	}

	/**
	 * During the form_validation within update() we use this callback to check if this user exists already
	 *
	 * @access	public
	 * @param string $user (name) of the user
	 * @param string $password of the user
	 * @return	void
	 */
	public function check_account($password) {

		$user = $this->pikachu->show('username');

		// If this user exist we'll try to find it in your database first
		if ($this->log_model->user_exists($user)) {

			if ($this->log_model->auth_user($user, $password)) return TRUE;

			else {

				$this->form_validation->set_message('check_account', 'Vous n\'avez pas inséré le mot de passe correspondant');
				return FALSE;
			}

		} else return TRUE;


	}

	/**
	 * SPACE
	 *
	 * Space settings and board
	 *
	 * @access	public
	 * @return	void
	 */
	public function space() {

		// If userspace isn't enabled
		if (!$this->pikachu->show('userspace')) $this->index();

		$this->template->set('subpage', 'space');

		$datas['space_avatar'] = $this->pikachu->show('space_avatar');
		//$datas['space_home'] = $this->pikachu->show('space_home'); // Autoloaded within the template launcher
		$datas['space_description_edit'] = json_decode($this->pikachu->show('space_description_edit'));
		$datas['space_redirection_edit'] = json_decode($this->pikachu->show('space_redirection_edit'));

		$this->template->launch('profile/space', $datas);

	}

	/**
	 * CREATIONS
	 *
	 * Creations listing
	 *
	 * @access	public
	 * @return	void
	 */
	public function creations() {

		// Load date helper because we use elapsed_time() function in the view
		$this->load->helper('date');

		$this->template->set('page', 'creations');

		// Get different values by session & get
		$userid = $this->pikachu->show('userid');
		$search = $this->input->get('search');
		$filter = $this->input->get('filter');

		// Get datas
		$arr = $this->log_model->find_by_user($userid, false, array('global', 'trans', 'beta', 'private'), $search);

		if ($arr) {

			// Humanize datas
			$my_creations_cleaned = $this->humanize->result($arr);

		} else {

			$my_creations_cleaned = FALSE;

		}

		$my_hits = number_format($this->general_model->counter_from_user($userid, 'hits'));

		$this->template->set('search_creation', $search);
		$this->template->set('my_creations', $my_creations_cleaned);
		$this->template->set('my_hits', $my_hits);

		// We show our view
		$this->template->launch('profile/creations');

	}

	/**
	 * Load user Devlog
	 *
	 * @access	public
	 * @return	void
	 */
	public function devlog($action='', $opt='') {

		if ($action === 'clean') {

			Devlog::clean();
			direct(base_url('/profile/devlog'));

		} elseif ($action === 'logs_mode') {

			Devlog::logs_mode($opt);
			direct(base_url('profile/devlog'));

		}

		// We will sort it to be HTMLy watchable 

		$devlog_datas = Devlog::show_stored();

		// We set our content
		$datas['devlog_content'] = $devlog_datas;
		$datas['userstatus'] = $this->pikachu->show('userstatus');
		
		// We launch our template
		$this->load->view('profile/devlog', $datas);

	}


	/**
	 * During the form_validation within the email change we use this callback to check if this email is owned by someone else
	 *
	 * @access	public
	 * @param string $user (name) of the user
	 * @param string $password of the user
	 * @return	void
	 */
	public function check_unique_email($email) {

		// If this doesn't exists or/and isn't owned by someone else
		$userid_from_email = $this->log_model->email_exists($email);

		if ($userid_from_email !== FALSE) {

			// The userid of this email is the same than the user, everything is ok we can "update" it
			if ($userid_from_email === $this->pikachu->show('userid')) return TRUE;

		// This email matchs with nothing, we can update it
		} else return TRUE;

		$this->form_validation->set_message('check_unique_email', 'This %s is already in use from another account.');
		return FALSE;



	}


}