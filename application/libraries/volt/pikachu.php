<?php

/**
 * Pikachu class
 *
 * The cookies and session Linkbreakers manager
 *
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Class
 *
 */

class Pikachu extends CI_Context {

	public function __construct() {

		parent::__Construct();

	}

	/**
	 * Specific to Linkbreakers -> auto_auth and session_auth could be in another library
	 * But we didn't create it, because i don't give a fuck about the file i put my code
	 *
	 * @access public
	 * @return void
	 */
	public function auto_auth() {

		if (!($username = $this->get_cookie('username'))) $username = FALSE;
		if (!($password = $this->get_cookie('userpassword'))) $password = FALSE;

		if (($username) && ($password)) return array('username' => $username, 'password' => $password);

	}

	/**
	 * Useful flag to let the user know if his action has an effectiveness.
	 *
	 * @access public
	 * @param string $kind which kind of flag is it ? An error ? A success ?
	 * @param string $msg what message do you want to set ?
	 * @return void
	 *
	 * NOTE : It returns a filled-with-HTML variable ($profile_msg) that's available in our template class (therefore our views)
	 *
	 */
	public function flag($kind, $msg) {

		if ($kind === 'error') $this->template->set('profile_msg', '<div class="alert alert-danger">'.$msg.'</div>');
		elseif ($kind === 'success') $this->template->set('profile_msg', '<div class="alert alert-info">'.$msg.'</div>');

	}

	/**
	 * Set a cookie
	 *
	 * @access public
	 * @param string $label which name do you want ?
	 * @param string $value what do you want to set in da cookie yo ?
	 * @return void
	 */
	public static function set_cookie($label, $value, $time=NULL) {

		if ($time === NULL) $time = time() + (20 * 365 * 24 * 60 * 60);
		setcookie($label, $value, $time, '/');

		// Synchrone set (thank to that we don't need to refresh this fucking page)
		$_COOKIE[$label] = $value;

	}

	/**
	 * Get a cookie
	 *
	 * @access public
	 * @param string $label which cookie do you want to get ?
	 * @return mixed null or (string) cookie content
	 */
	public static function get_cookie($label) {

		if (isset($_COOKIE[$label])) return $_COOKIE[$label];
		else return NULL;

	}

	/**
	 * Set many cookies in a row
	 *
	 * @access public
	 * @param array $arr (cookie_label => 'cookie_value')
	 * @return void
	 */
	public function cooking($arr=array()) {

		foreach ($arr as $row => $value) {

			$this->set_cookie($row, $value);

		}

	}

	/**
	 * Session setter (when you want to set a session)
	 *
	 * @access public
	 * @param string $label which name do you want to chose ?
	 * @param string $value what value will you put in da session ?
	 * @param string $opt do you want to specify a "section" within the session ?
	 * @param object $obj auto globalize the session set (it reset the variables into our object to go faster - D.R.Y spirit yo)
	 * @return bool
	 */
	public function set($label, $value='', $opt='session', &$obj=false) {

		if (is_array($label)) {

			foreach ($label as $field => $row) {

				$_SESSION[$opt][$field] = $row;
				if ($obj) $obj->$field = $row;

			}


		} else {

			$_SESSION[$opt][$label] = $value;
			if ($obj) $obj->$label = $value;

		}

		return TRUE;
		
	}

	/**
	 * Session multi-setter (when you want to set many session in once thank to an array)
	 *
	 * @access public
	 * @param array $label (session_label => session_value)
	 * @param string $opt do you want to specify a "section" within the session
	 * @param object $obj auto globalize the session set (it reset the variables into our object to go faster - D.R.Y spirit yo)
	 * @return bool
	 *
	 * NOTE ABOUT &$obj : if you set a PikachuSession from somewhere you need to get back immediatly
	 * Just put corresponding object (ex. $this) and your $_SESSION['type']['variable']
	 * Will be set in $this->variable as well.
	 *
	 */
	public function multi_set(array $label, $opt='session', &$obj=FALSE) {

		foreach ($label as $field => $row) {

			$_SESSION[$opt][$field] = $row;
			if ($obj) $obj->$field = $row;

		}

		return TRUE;

	}

	/**
	 * Show a session
	 *
	 * @access public
	 * @param string $session which session do you want to get ?
	 * @param string $opt is it in a special "section" ?
	 * @return mixed bool or (string) session content
	 */
	public static function show($session, $opt='session') {

		if (isset($_SESSION[$opt][$session])) return $_SESSION[$opt][$session]; else return FALSE;

	}

	public static function show_all($opt='session') {

		return $_SESSION[$opt];

	}

	/**
	 * Destroy every single cookie because fuck you that's why
	 *
	 * @access public
	 * @return void
	 */
	public function cookies_destroy() {

		$past = time() - 3600;
		foreach ($_COOKIE as $key => $value) {

			$this->set_cookie($key, $value, $past);

		}

		return TRUE;

	}

	/**
	 * Clean a PikachuSession by "section" or it just clean everything
	 *
	 * @access public
	 * @param string $opt if you want to delete a specific "section" or everything
	 * @return bool
	 */
	public function destroy($opt='') {

		if (empty($opt)) {

			// Asynchrone destruction
			session_destroy();
			$this->cookies_destroy();

			// Synchrone destruction
			unset($_SESSION);
			unset($_COOKIE);

		} else {

			unset($_SESSION[$opt]);

		}

		return TRUE;

	}

	/**
	 * Unset multi session variable
	 *
	 * @access public
	 * @param array $label which session variables ?
	 * @param string $opt is it in a specific "section" ?
	 * @return void
	 */
	public static function multi_delete($label, $opt='session') {

		foreach ($label as $row) {

			unset($_SESSION[$opt][$row]);

		}
		
	}

	/**
	 * Unset a specific session variable
	 *
	 * @access public
	 * @param string/array $label which session variable ?
	 * @param string $opt is it in a specific "section" ?
	 * @return void
	 */
	public static function delete($label, $opt='session') {

		if (isset($_SESSION[$opt][$label])) unset($_SESSION[$opt][$label]);


	}


}

?>