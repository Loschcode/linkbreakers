<?php

class Admin_model extends CI_Model {

	function __construct() {

		parent::__construct();

		// We get correct language from the admin panel (currently not in use)
		$this->sql_lang = Language::pick('short');
	}
	
	/**
	 * Find the lasts ideas
	 * @param integer $limit the limit
	 * @param string $target the target to focus on
	 * @return array the results
	 */
	public function find_lasts($limit=10, $target='results') {

		if ($target === 'results') {

			$table = DB_RESULTS_TABLE;
			$order = 'date';

		} elseif ($target === 'users') {

			$table = DB_LOG_TABLE;
			$order = 'date_subscribe';

		} else return FALSE;

		$query = $this->db
		->select()
		->order_by($order, 'DESC')
		->limit($limit)
		->get($table);

		return $query->result_array();

	}

	/**
	 * Count database results for one specific target
	 * @param  integer $limit the limit
	 * @return array the results
	 */
	public function counter($target) {

		if ($target === 'results') {

		$query = $this->db
		->select('id')
		->get(DB_RESULTS_TABLE);

		}

		elseif ($target === 'users') {

		$query = $this->db
		->select('id')
		->get(DB_LOG_TABLE);

		}

		return $query->num_rows();

	}

	/**
	 * Edit an idea status
	 * @param  integer $id which id in the db ?
	 * @param  string $status the new status to put
	 * @return bool (TRUE)
	 */
	public function edit_status($id, $status) {

		$data = array(
			'status' => $status
			);

		$this->db->where('id', $id);
		$this->db->update(DB_RESULTS_TABLE, $data);

		$this->db->where('id_string', $id);
		$this->db->update(DB_TAGS_TABLE, $data);


		return TRUE;
	}








	// OLD
	public function find_entry($find, $limit=50, $no_check='beta') {

		// It's an ID to search
		if ((int) $find > 0) {

		$query = $this->db
		->select()
		->where('id', $find)
		->limit(1)
		->get(DB_RESULTS_TABLE);

		return $query->result_array();

		}

		$query = $this->db
		->select()
		->like('string_clean', $find)
		->or_like('url', $find)
		->where('status !=', $no_check)
		->where('lang', $this->sql_lang)
		->order_by('date', 'DESC')
		->limit($limit)
		->get(DB_RESULTS_TABLE);

		return $query->result_array();

	}


}