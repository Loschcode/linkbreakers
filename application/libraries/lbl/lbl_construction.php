<?php

/**
 * LBL Construction class
 *
 * LBL construction functions
 *
 * @package 	LBL / Construction
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Class
 *
 */

class Lbl_construction extends Genius {

	public function __construct() {

		parent::__Construct();

		Devlog::add_admin_yellow('LBL-Library '.__CLASS__.' has been initialized');

	}

	// Construction functions variables
	protected $virtual_variable = array();
	protected $virtual_session_area = 'PUBLIC';

	public function _JSON($element='') {

		if ($element === 'VARS') return json_encode($this->virtual_variable);
		if ($element === 'SESSIONS') return json_encode($this->pikachu->show_all('virtual_session'));

		return '';

	}

	public function _SAVE() {

		/*
		TO DO : SAVE different variables type into another type (from session to var / from var to sql data)
		*/

	}

	public function _DB_DELETE($label) {

		//$this->load->model('virtualdb_model');
		$this->virtualdb_model->unset_var_from_label($label, $this->phoenix->search_id_creator);

	}

	public function _DB($label='', $value='', $expiration_date=0) {

		$label = (string) $label;
		$value = (string) $value;
		$expiration_date = (int) $expiration_date;

		// For sure we'll use it
		//$this->load->model('virtualdb_model');

		if (empty($value)) { // There's no value it means we should get a var

		return $this->virtualdb_model->get_var($label, $this->phoenix->search_id_creator);

		} else {

		// Can be negative to destroy itself instantly
		if ($expiration_date !== 0) $raw_expiration_date = time() + ($expiration_date * 60); // We convert hour expiration date into true milestamp for our database
		else $raw_expiration_date = 0;

		$this->virtualdb_model->set_var($label, $value, $raw_expiration_date, $this->phoenix->search_id_creator);

		}

	}



	// If the session set has to be PUBLIC or PRIVATE
	public function _SET_SESSION_AREA($type=FALSE) {

		if ($type) $this->virtual_session_area = $type; // We don't need to check, there's just a '===' check after

	}

	public function _SESSION($label='', $value='', $session_area=FALSE) {

		$label = (string) $label;
		$value = (string) $value;
		$session_area = (string) $session_area;

		if (!$session_area) $session_area = $this->virtual_session_area; // Default value {SET_SESSION_AREA};

		if ($session_area === 'PUBLIC') $private_session = 0;
		elseif ($session_area === 'PRIVATE') $private_session = $this->phoenix->search_id_creator; // We will look through search_id_creator, we should improve this system with set/get but i don't care.
		else return '';

		/*

		What does search_id_creator mean ?

		It's the function's creator ID. Has to be differenciated from searching user.
		But the one which created the function in use.

		PUBLIC/PRIVATE system allow users to privatize their sessions system while building functions.
		Thank to that it doesn't collapse with other creators session series

		Sandbox reply : 0

		*/

		$raw_area = 'virtual_session' . $private_session;

		if ($value === 'NULL') { // SESSION unset

			$this->pikachu->delete($label, $raw_area);
			return '';

		}

		if ($value !== '') $this->pikachu->set($label, $value, $raw_area);
		else return $this->pikachu->show($label, $raw_area);

		return '';

	}

	public function _VAR($label='', $value='') {

		$label = (string) $label;
		$value = (string) $value;

		if ($value !== '') $this->virtual_variable[$label] = $value;
		elseif (isset($this->virtual_variable[$label])) return $this->virtual_variable[$label];

		return '';

	}

}
