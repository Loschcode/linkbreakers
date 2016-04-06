<?php

class Virtualdb_model extends CI_Model {

	function __construct() {

		parent::__construct();

	}

	public function set_var($label, $value, $expiration_date, $id_creator) {

		if ($this->get_var($label, $id_creator)) {

			$data = array(

				'value' => $value

			);

			$this->db
			->where('label', $label)
			->where('id_creator', $id_creator);
			
			$this->db->update(DB_VIRTUALDB_TABLE, $data);


		} else {

		$inserts = array(

			'id_creator' => $id_creator,
			'label' => $label,
			'value' => $value,
			'creation_date' => time(),
			'expiration_date' => $expiration_date,
	
		);

		// Database insertion
		$this->db
		->insert(DB_VIRTUALDB_TABLE, $inserts);

		}

		return TRUE;

	}

	public function get_var($label, $id_creator) {

		$query = $this->db
		->limit(1)
		->select('id, value, creation_date, expiration_date')
		->where('label', $label)
		->where('id_creator', $id_creator)
		->get(DB_VIRTUALDB_TABLE);

		if ($query->num_rows() <= 0) return FALSE;
		else $arr = $query->first_row('array');

		// If the expiration date has been exceeded
		if ((time() > $arr['expiration_date']) && ($arr['expiration_date'] != 0)) {

			$this->unset_var($arr['id']);
			return FALSE;

		}

		return $arr['value'];

	}

	public function unset_var_from_label($label, $id_creator) {

		// Delete properly an entry
		$this->db
		->where('label', $label)
		->where('id_creator', $id_creator)
		->delete(DB_VIRTUALDB_TABLE); // Results table

	}

	// It goes faster, that's why. (see get_var)
	public function unset_var($id) {

		// Delete properly an entry
		$this->db
		->where('id', $id) // Normal reference
		->delete(DB_VIRTUALDB_TABLE); // Results table

	}

}