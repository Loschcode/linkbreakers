<?php

class MY_Model extends CI_Model {

	function __construct() {

		parent::__construct();

	}

	// Get details from tables (get_table) depending on id (checked_id)
	public function get_detail(array $infos, $id, $detail) {

		// Allow arrays
		if (is_array($detail)) $select = implode(',',$detail);
		else $select = $detail;

		$query = $this->db
		->select($select)
		->where($infos['checked_id'], $id)
		->get(DB_TABLE_PREFIX.$infos['get_table']);

		$arr = $query->row_array();



		if (is_array($detail)) {

			if ($arr) return $arr;

		} elseif ($detail == '*') {

			return $arr;

		} else {

			return $arr[$detail];

		}

		return FALSE;

	}

}