<?php

class MY_Lang extends CI_Lang {

	function __construct() {

		parent::__construct();
		die('fuck');

	}

	/**
	 * Fetch a single line of text from the language array
	 *
	 * Hook : Autoload the language file if we can't load it
	 * - Linked with Linkbreakers (statics)
	 *
	 * @access	public
	 * @param	string $line the language line
	 * @return	string
	 */
	function line($line = '') {

		var_dump($line);
		die();

		$value = ($line == '' OR ! isset($this->language[$line])) ? FALSE : $this->language[$line];

		// Because killer robots like unicorns!
		if ($value === FALSE) {

			log_message('error', 'Could not find the language line "'.$line.'"');

		}

		return $value;
	}

}

	?>