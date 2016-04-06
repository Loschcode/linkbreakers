<?php

class Autocomplete_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	protected $table_results = 'results';

	public function get_autocomplete($arr) {

		// Use utf8_encode to manage accents in string
		$search_string = utf8_encode($arr['search_string']);
		$type_autocomplete = $arr['type_autocomplete'];
		$max_results = $arr['max_results'];
		$id_from_user = $arr['id_from_user'];

		$like_sql = $this->prepare_sql($search_string);
		$result = $this->exec_sql($type_autocomplete, $like_sql, $max_results, $id_from_user);

		return $result['results'];
	}

	private function prepare_sql($search_string) {

		$explode_search = explode(' ', $search_string);
		$count = count($explode_search);

		$like_sql = '';
		$like_sql .= 'AND autocomplete LIKE \'%' . $explode_search[0] . '%\'';

		if ($count == 1) return $like_sql;

		$like_sql .= ' AND (';

			for ($i=0;$i!==$count;$i++) {

				if ($i == 0) $like_sql .= ' autocomplete LIKE \'' . $explode_search[$i] . '%\'';
				else $like_sql .= ' OR autocomplete LIKE \'' . $explode_search[$i] . '%\'';

			}

			$like_sql .= ' ) ';

		return $like_sql;

	}

	private function exec_sql($type_autocomplete, $like_sql, $max_results, $id_from_user) {

	// Take user id
	$user_id = $this->pikachu->show('userid');

	switch ($type_autocomplete) {

		case 'search':
		if (!$user_id) $sql = "SELECT autocomplete FROM results WHERE status = 'global' " . $like_sql . " ORDER BY CHAR_LENGTH(autocomplete) LIMIT " . $max_results;
		else $sql = "SELECT autocomplete FROM results WHERE ((status = 'private' AND id_user = $user_id) OR status = 'global') " . $like_sql . " ORDER BY CHAR_LENGTH(autocomplete) LIMIT " . $max_results;
		break;

		// id_from_user = id from that guy you're searching things from
		case 'from_user':
		$sql = "SELECT autocomplete FROM results WHERE (id_user = $id_from_user) ". $like_sql . " ORDER BY CHAR_LENGTH(autocomplete) LIMIT " . $max_results;
		break;	
		// -> This one searched without global entries
		//$sql = "SELECT autocomplete FROM results WHERE (status = 'private' AND id_user = $id_from_user) ". $like_sql . " ORDER BY CHAR_LENGTH(autocomplete) LIMIT " . $max_results;
		
		// -> This one search with global entries

		case 'alias':
		if (!$user_id) $sql = "SELECT autocomplete FROM results WHERE status <> 'off' AND status <> 'private' " . $like_sql . " ORDER BY CHAR_LENGTH(autocomplete) LIMIT " .  $max_results;
		else $sql = $sql = "SELECT autocomplete FROM results WHERE (status <> 'off' AND status <> 'private') OR (status = 'private' AND id_user = $user_id) " . $like_sql . " ORDER BY CHAR_LENGTH(autocomplete) LIMIT " .  $max_results;
		break;

	}

	$query = $this->db->query($sql);
	$data['nb_results'] = $query->num_rows();
	$data['results'] = $query->result_array();



	return $data;

	}
}