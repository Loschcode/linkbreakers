<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Log controller
 *
 * Everything linked with log-in / log-out of the users
 *
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Controller
 *
 */

class Log extends CI_Controller {

	public function __construct() {

		parent::__Construct();

		// Load models
		$this->load->model('log_model');

		// Set page
		$this->template->set('page', 'log');

	}

	/**
	 * If it goes to the index we redirect properly the user whether he's already logged-in or not
	 *
	 * @access	public
	 * @return	void
	 */
	public function index() {

		$this->template->set('subpage', 'auth');

		//
		if ($this->pikachu->show('userid')) redirect();
		else $this->template->launch('log_auth');

	}

	/**
	 * Action to log-out the user properly
	 *
	 * @access	public
	 * @return	void
	 */
	public function deco() {

		$this->pikachu->destroy();
		direct(base_url());

	}

	/**
	 * Recover password form
	 *
	 * @access	public
	 * @return	void
	 */
	public function recover_password() {

		// We auto-disconnect the user to avoid collapsing
		if ($this->pikachu->show('userid')) redirect();

		// We transform the 'recoverkey' $_GET in a normed value
		$this->form_validation->set_post('recoverkey', $this->input->get('recoverkey'));
		
		$this->template->launch('log_forgot_password_recover');

	}

	/**
	 * Recover password execution
	 *
	 * @access	public
	 * @return	void
	 */
	public function recover_password_exec() {

		// We auto-disconnect the user to avoid collapsing
		if ($this->pikachu->show('userid')) redirect();
		
		$this->form_validation->set_rules('recoverkey', 'recover key', 'required|xss_clean|alpha_numeric|callback_check_recover_key');
		$this->form_validation->set_rules('newpassword', 'new password', 'required|xss_clean');

		if ($this->form_validation->run()) {

			$this->log_model->change_password_from_recover_key($this->input->post('recoverkey'), $this->input->post('newpassword'));

			redirect(base_url('log'));
			return;

		}

		$this->recover_password();
	}

	/**
	 * Forgot password form
	 *
	 * @access	public
	 * @return	void
	 */
	public function forgot() {

		$this->template->launch('log_forgot_password');

	}

	/**
	 * Forgot password execution
	 *
	 * @access	public
	 * @return	void
	 */
	public function forgot_exec() {

		$this->form_validation->set_rules('useremail', 'email', 'required|xss_clean|valid_email|callback_check_account_email');

		if ($this->form_validation->run()) {

			$useremail = $this->input->post('useremail');

			$key_to_recover = $this->panda->make_recovery_key($useremail);

			/*
			 *
			 * We get the userid and update the recovering key linked with the account
			 * Don't EVER change something else, WE ARE NOT SURE this guy owns this account
			 *
			 */
			if ($userid = $this->log_model->email_exists($useremail)) $this->log_model->add_recovery($userid, 'email_recover', $key_to_recover);
			//$this->log_model->set_user_detail('log', $userid, 'email_recover', $key_to_recover);

			$this->load->library('volt/hermes');

			/*
			 *
			 * We will prepare the link to click, the email template and send it to the user
			 *
			 */
			$page_to_recover = base_url('log/recover_password');
			$link_to_recover = $page_to_recover . '?recoverkey='.$key_to_recover;

			/*
			 *
			 * ... And make the username prettier to display
			 *
			 */
			$username = $this->log_model->get_user_detail('log', $userid, 'username');
			$username_pretty = $this->panda->make_username_pretty($username);

			$email_details = array(

				'username' => $username,
				'username_pretty' => $username_pretty,
				'link_to_recover' => $link_to_recover,
				'key_to_recover' => $key_to_recover,
				'page_to_recover' => $page_to_recover

				);

			if ($this->hermes->send_template_email($useremail, 'forgot_password', 'Linkbreakers Password Recovery', $email_details)) {

				redirect(base_url('log/recover_password'));
				return; // To do not launch forgot()

			}

		}


		$this->forgot();

	}

	/**
	 * Action to log-in the user properly (execution part)
	 *
	 * @access	public
	 * @return	void
	 */
	public function exec() {

		$this->form_validation->set_rules('username', 'username', 'required|xss_clean|callback_check_account_email[TRUE]|callback_check_account['.$this->input->post('password').']');
		$this->form_validation->set_rules('password', 'password', 'required|xss_clean');

		if ($this->form_validation->run()) {

			// If this is an email, we will convert it to the real username before the true process
			if ($this->panda->is_email($this->input->post('username'))) {

					$username_from_email = $this->log_model->username_from_email($this->input->post('username'));

					if ($username_from_email) $this->form_validation->set_post('username', $username_from_email);
					else die ('Logged from email error');

			}

			$user = clean($this->input->post('username'));
			$user_pretty = $this->panda->make_username_pretty($this->input->post('username'));
			$password = $this->input->post('password');

			// Delete the edition mode in case we added something
			$this->panda->delete_edition_mode();

			// Process to create/add a user (we checked the synchronization with another account before)
			$this->log_model->session_auth(array('username' => $user, 'username_pretty' => $user_pretty, 'password' => $password));
			$this->index();

		} else {

			$this->index();

		}


	}

	/**
	 * We check if this email exists within our database
	 *
	 * @access	public
	 * @param string $user user email
	 * @param bool $check_format if the check format is set to TRUE, if the $email isn't an email, it auto-validates
	 * @return bool
	 */
	public function check_account_email($email, $check_format=FALSE) {

		if ($check_format) if (!$this->panda->is_email($email)) return TRUE;

		// Use model to check if this email exists
		if (!$this->log_model->email_exists($email)) {

			$this->form_validation->set_message('check_account_email', 'Cet e-mail ne correspond à aucun utilisateur');
			return FALSE;

		} else return TRUE;


	}

	/**
	 * Check whether this recover key exists within the database or not
	 *
	 * @access	public
	 * @param string $recoverkey the recover key
	 * @return bool
	 */
	public function check_recover_key($recoverkey) {

		// If this recover key exists we'll try to find it in your database first
		if ($this->log_model->recover_key_exists($recoverkey)) 

		return TRUE;

		else {

		$this->form_validation->set_message('check_recover_key', 'Cette clé ne correspond à aucune clé existante.');
		return FALSE;

		}


	}

	/**
	 * During the form_validation within exec() we use this callback to check if this user exists already
	 *
	 * @access	public
	 * @param string $user (name) of the user (or email)
	 * @param string $password of the user
	 * @return bool
	 */
	public function check_account($user, $password) {

		// If this user exist we'll try to find it in your database first
		if ($this->log_model->user_exists($user, TRUE)) {

			if ($this->log_model->auth_user($user, $password, TRUE)) return TRUE;

			else {

				$this->form_validation->set_message('check_account', 'Ce compte existe déjà et vous n\'avez pas inséré le mot de passe correspondant');
				return FALSE;
			}

		} else return TRUE;


	}


}