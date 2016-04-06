<?php

/**
 * Language class
 *
 * Everything about the language system
 *
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Class
 *
 */

class Language extends CI_Context {

	private $lang_list = array('en', 'fr');

	private static $lang = '';
	private static $lang_large = '';


	public function __construct() {

		parent::__Construct();

		// We load the language system automatically
		$this->load();

	}

	/**
	 * Loads the user language from session / cookie / browser and set the pikachu sessions and cookies
	 *
	 * @access public
	 * @return bool
	 */
	public function load() {

		if ($this->pikachu->show('lang')) {

			// We get the language from session
			self::$lang = $this->pikachu->show('lang');

		} elseif ($this->pikachu->get_cookie('lang')) {

			// Or we take it from cookies
			self::$lang = $this->pikachu->get_cookie('lang');

			// And we set the session
			$this->pikachu->set('lang', self::$lang);

		} else {

			// Finally, we should take it from the browser settings
			self::$lang = $this->pick_lang_from_browser();

			// And we set everything for the next time (session and cookie)
			$this->pikachu->set('lang', self::$lang);
			$this->pikachu->set_cookie('lang', self::$lang);

		}

		// We set the lang large at the end (in every case we set it)
		// This line is fucking important, don't remove it
		self::$lang_large = $this->lang_full(self::$lang);

		return TRUE;


	}



	/**
	 * Change the actual language (used in lang.php controller for example)
	 *
	 * @access public
	 * @param string $lang the new language
	 * @return void
	 */
	public function change_language($lang, $change_database_prefs=TRUE) {

		if (in_array($lang, $this->listing())) {

			$this->pikachu->set('lang', $lang);
			$this->pikachu->set('lang_large', $this->lang_full($lang));
			$this->input->set_cookie('lang', $lang);

			// If the user is logged-in
			if (($this->pikachu->show('userid')) && ($change_database_prefs)) {

				// We change it in the database as a prefs
				$this->log_model->set_user_detail('prefs', $this->pikachu->show('userid'), 'prefs_language', $lang, TRUE);

			}

			return TRUE;

		} else return FALSE;

	}

	/**
	 * Pick up the language (e.g. 'en' or 'french') from the static variables
	 *
	 * @access public
	 * @return void
	 */
	public static function pick($type='large') {

		if ($type === 'large') return self::$lang_large;
		elseif ($type === 'short') return self::$lang;
		else return FALSE;

	}

	/**
	 * Pick up the language (e.g. 'en') from the browser specifications
	 *
	 * @access public
	 * @return void
	 */
	public function pick_lang_from_browser() {

		if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"])) $browser_lang = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
		else $browser_lang = '';

		if ((isset($browser_lang[0])) && (isset($browser_lang[1]))) $result_lang = $browser_lang[0].$browser_lang[1];
		else $result_lang = '';

		if (in_array($result_lang, $this->lang_list)) return $result_lang;
		else return LINKBREAKERS_DEFAULT_LANG;

	}

	/**
	 * Micro variable launcher within the class
	 *
	 * @access public
	 * @param string $variable the variable to output
	 * @return obj
	 */
	public function show($variable) {

		return $this->$variable;

	}

	/**
	 * Get the small language version and send the large version
	 *
	 * @access public
	 * @param string $lang such as 'fr' or 'en'
	 * @return mixed string / bool
	 */
	public static function lang_full($lang) {

		if ($lang == 'fr') return 'french';
		elseif ($lang == 'en') return 'english';

		else return FALSE;

	}

	/**
	 * To get the different languages within an array
	 *
	 * @access public
	 * @return array
	 */
	public function listing() {

		return $this->lang_list;

	}

}

?>