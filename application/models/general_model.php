<?php

class General_model extends CI_Model {

	public $strong_type_exceptions = array();

	function __construct() {

		parent::__construct();

		// We get correct language
		$this->sql_lang = Language::pick('short');
	}

	/**
	 * Smart domains system
	 * @param  string $string the cleaned research
	 * @param  bool/integer $by_specific_user we will look through this user private entries (from database user id)
	 * @return string the url to get or !bool
	 */
	public function find_auto_url($string, $by_specific_user=FALSE) {

		$string = trim($string); // We never know, a space could appear everywhere man !
		$string = clean($string);
		$string = preg_quote($string, '/'); // Escape for regular expression usage
		$string = trim($this->db->escape($string), '\''); // We need to trim the '' that CodeIgniter add to escape because we don't need it within our REGEX

		// We add conditions depending whether the user is loggedin or not
		if (!$iduser = $this->pikachu->show('userid')) $loggeduser_entries_get = "((status <> 'off') AND (status <> 'private'))";
		else $loggeduser_entries_get = "((status <> 'off') AND (status <> 'private')) OR ((status = 'private') AND (id_user = ".$iduser."))";

		// If we're using a specific search engine, we should be able to retrieve user's websites entries and check it
		if ($by_specific_user) $loggeduser_entries_get .= " OR ((status = 'private') AND (id_user = ".$by_specific_user."))";

		// We surround it (NOTE - in this case, it's very important)
		$loggeduser_entries_get = '('.$loggeduser_entries_get.')';

		// % exceptions
		$string = str_replace('%', '', $string);

		$sql_request = "SELECT url FROM ".DB_RESULTS_TABLE." WHERE 

		(

		url REGEXP '^https?\:\/\/(a-z)?\.?".$string."\.[a-z]{2,3}\/(.*)$'
		
		) AND ".$loggeduser_entries_get." LIMIT 1;";

/*

'^(https?://[a-Z]\.".$string."\.[a-Z]{2,3}/)$'

		url LIKE ".$this->db->escape('http://'.$string.'.__/%')." 
		OR url LIKE ".$this->db->escape('http://www.'.$string.'.__/%')."

		OR url LIKE ".$this->db->escape('http://'.$string.'.___/%')."
		OR url LIKE ".$this->db->escape('http://www.'.$string.'.___/%')."

*/
		
		//var_dump($sql_request);

		$query = $this->db->query($sql_request);
		$array_sql = $query->result_array();

		if (!isset($array_sql[0])) return FALSE;
		else return $array_sql[0]['url'];

	}

	/**
 	*
 	* Match all the research doubles
 	*
 	* @param string $string cleaned research
 	* @return bool is there a double or not ?
 	*
 	*/
	public function find_double($string) {

		$string = clean($string, TRUE);
		$string = trim($string);

		// String separation, each word
		$string_array = explode(" ", $string);
		$mask_array = explode(" ", $string);

		$where = '';
		$int = 0;
		$num = count($string_array);

		while ($int < $num) {

			if ($string_array[$int][0] === '$') {

				$mask_array[$int] = '%'; // Mask construction
				$where .= "(dyn = 1 AND position = ".($int+1)." AND position_max = ".($num).") OR ";

			} else {

				$mask_array[$int] = trim($mask_array[$int]);
				$where .= "(tag_clean = ".$this->db->escape($string_array[$int])." AND position = ".($int+1)." AND position_max = ".($num)." ) OR ";

			}

			++$int;

		}

		$mask_string = trim(implode(" ", $mask_array));
		$where = substr($where, 0, -4);

		$sql_request = "SELECT COUNT(id_string), id_string, status FROM ".DB_TAGS_TABLE." WHERE (".$where.") AND (mask = ".$this->db->escape($mask_string).") AND (status <> 'private') ORDER BY COUNT(id_string) DESC LIMIT 1;";
		$query = $this->db->query($sql_request);

		if ($sql_result = $query->result_array()) {

			$actual_id = $sql_result[0]['id_string'];

			// We get id_string so just find into 'results' correct id_string and check last_search (LIMIT 1) -> delete or not
			$query = $this->db->get_where(DB_RESULTS_TABLE, array('id' => $actual_id), 1);

			if ($sql_check_expiration_result = $query->result_array()) {

			// We will check if it's more than X months than this function is unused
				if (($sql_result[0]['status'] != 'global') && (($sql_check_expiration_result[0]['last_search'] < time()-LINKBREAKERS_LINK_EXPIRATION))) {

					// We delete it depending on linkbreakers_link_expiration specifications (seconds)
					$this->delete_entry($sql_check_expiration_result[0]['id']);
					return FALSE;

				}

			}

			$match_num = $sql_result[0]["COUNT(id_string)"];

			// Final match = if everything match correctly with a result, it's a double and we need to cancel it
			if ($match_num >= $num) return TRUE;

		}

		return FALSE;

	}

	/**
 	*
 	* Short find matching the one word researches
 	*
 	* @param string $string the cleaned research
 	* @return array the results
 	*
 	*/
	public function short_find($string) {

		// Clean it
		$string = clean($string, TRUE);
		$string = trim($string);

		if (!$iduser = $this->pikachu->show('userid')) $loggeduser_entries_get = "((status <> 'off') AND (status <> 'private') AND (status <> 'alpha'))";
		else $loggeduser_entries_get = "(((status <> 'off') AND (status <> 'private') AND (status <> 'alpha')) OR ((status = 'private') AND (id_user = ".$iduser.")))";

		$sql_request =

		"SELECT id, id_user, url, string_clean, strong_type, edit, status, lang, last_search, ip
		FROM ".DB_RESULTS_TABLE." WHERE (string_clean = ".$this->db->escape($string).") AND ".$loggeduser_entries_get." ORDER BY id DESC LIMIT 1";

		$query = $this->db->query($sql_request);

		if ($sql_result = $query->result_array()) return $this->find_final_transition($string, $sql_result, 0); // SQL array and 0 as result number

	}

	/**
 	*
 	* Find a result for a research (VERY IMPORTANT SYSTEM)
 	*
 	* @param string $string the cleaned research
 	* @param bool/integer $specific_user are we looking through an user search engine ?
 	* @return array the results
 	*
 	*/
	public function find($string, $specific_user=FALSE) {

		// In case we'll look for specific user_id entries
		if ($specific_user) $and_specific_user = "AND (lb_results.id_user = ".$specific_user.")";
		else $and_specific_user = "";

		// Clean it
		$string = clean($string, TRUE);
		$string = trim($string);

		if (!$iduser = $this->pikachu->show('userid')) $loggeduser_entries_get = "((lb_tags.status <> 'off') AND (lb_tags.status <> 'private'))";
		else $loggeduser_entries_get = "(((lb_tags.status <> 'off') AND (lb_tags.status <> 'private')) OR ((lb_tags.status = 'private') AND (lb_tags.id_user = ".$iduser.")))";

		if ($specific_user) $loggeduser_entries_get = "(lb_tags.status <> 'off')";

		// String separation, each word
		$string_array = explode(" ", $string);

		$int = 0;
		$num = count($string_array);

		// Find count system
		static $find_count = -1; // Cannot be reset -> it'll increase
		$find_count++;

		// Find_count verification -> If it's >0 it means we already did a SQL-request and we should use our "cache"
		if (($find_count > 0) && ($find_count < 6)) { // If it's a while, let's avoid this SQL request and use our cache

			return $this->find_final_transition($this->find_string, $this->find_result, $find_count);

		} else {

		// If we checked precisely these IDs before, we should ignore them -> look at strong type specifications
		$id_exceptions = $this->strong_type_exceptions;
		$current_exceptions = '';

		foreach ($id_exceptions as $row) $current_exceptions .= 'AND lb_tags.id_string != ' . $row . ' ';

		$where = '';
		
		// We'll check each possible result and take the best with a point-system (COUNT(*))
		while ($int < $num) {

			$where .= "((lb_tags.tag_clean = ".$this->db->escape($string_array[$int])." OR lb_tags.dyn = 1) ".$current_exceptions."AND lb_tags.position <= ".($int+1)." ) OR ";
			++$int;

		}

		$where = substr($where, 0, -4);

		// This is a mothafucka SQL-request.
		$sql_request =

		"SELECT COUNT(lb_tags.id), lb_tags.id_string, lb_tags.position_max, MAX(lb_tags.dyn),
		lb_results.id, lb_results.id_user, lb_results.url, lb_results.string_clean, lb_results.strong_type, lb_results.edit, lb_results.status, lb_results.lang, lb_results.last_search, lb_results.ip
		FROM ".DB_TAGS_TABLE." 
		LEFT JOIN ".DB_RESULTS_TABLE." ON lb_tags.id_string = lb_results.id
		WHERE (".$where.") 
		".$and_specific_user."
		AND (lb_tags.position_max <= ".($num+1)." 
		AND ".$loggeduser_entries_get."
		AND ".$this->db->escape($string)." LIKE lb_tags.mask) 
		GROUP BY lb_tags.id_string 
		ORDER BY lb_tags.position_max-COUNT(lb_tags.id) ASC, lb_tags.position_max DESC, MAX(lb_tags.dyn) ASC, MAX(lb_tags.highest_type) DESC, MAX(lb_tags.num_highest_type) DESC, FIELD(lb_tags.status, 'private', 'global', 'trans', 'beta'), lb_tags.id DESC 
		LIMIT 0,5"; // dyn DESC if 'facebook [search] > facebook messages', dyn ASC otherwise

		$query = $this->db->query($sql_request);

		if ($sql_result = $query->result_array()) {

			// We'll set these globals in case of strong-type negative return (while system)
			$this->find_string = $string;
			$this->find_result = $sql_result;

			//var_dump($sql_result); die();

			// We check and do the ultimate things
			return $this->find_final_transition($string, $sql_result, 0); // SQL array and 0 as result number

		}

	}

			return FALSE;

	}

	/**
	 * Finds final transition
	 * This will check/update some things when an entry was found searching the database
	 * 
	 * @param  string $string the cleaned research
	 * @param  string $sql_result the result we found at the end
	 * @param  integer $result_num the number of matching results found
	 * @return bool/array return the final result
	 */
	protected function find_final_transition($string, $sql_result, $result_num) {

		if (!isset($sql_result[$result_num])) return FALSE; // This function could be optimized (created on 23/12/12) -> We should try to avoid this unuseful 0,5 while before entering into this function, and go trough google directly after it

		$ultimate_mask = $this->panda->mask_gen($sql_result[$result_num]['string_clean']);

		// We check if the sentence is right to the SQL favorite result
		if (!$this->panda->like($string, $ultimate_mask)) return FALSE;

			// We will check if it's more than X months than this function is unused
			if (($sql_result[0]['status'] != 'global') && ($sql_result[0]['status'] != 'private') && ($sql_result[$result_num]['last_search'] < time()-LINKBREAKERS_LINK_EXPIRATION)) { // Fail about "traduire machin en bidule en truc" which doesn't match and check this anyway

				// We delete it depending on linkbreakers_link_expiration specifications (seconds)
				$this->delete_entry($sql_result[$result_num]['id']);
				return FALSE;

			}

				// Then we update last_search if it's ok
				$data = array(
				'last_search' => time()
				);

			$this->db->where('id', $sql_result[$result_num]['id']);
			$this->db->update(DB_RESULTS_TABLE, $data);

			// We increment the hits
			$this->hit_up($sql_result[$result_num]['id']);

			return $sql_result[$result_num];

	}

	/**
	 * Change the status from an entry
	 * @param  integer $id     entry id
	 * @param  integer $iduser the iduser from the creator of this entry
	 * @param  string $status the new status
	 * @return bool TRUE because nothing can fail, because i'm the powerful god of the Internet.
	 */
	public function switch_status($id, $iduser, $status) {

		$data = array(
			'status' => $status
			);

		$this->db->where('id', $id);
		$this->db->where('id_user', $iduser);
		$this->db->update(DB_RESULTS_TABLE, $data);

		$this->db->where('id_string', $id);
		$this->db->where('id_user', $iduser);
		$this->db->update(DB_TAGS_TABLE, $data);

		return TRUE;
	}

	public function update_creation(&$post, $id, $iduser) {

		Devlog::add('The entry #'.$id.' was found in the database and will be updated');

		$id = (string) $id;

 		/**
 		 *
 		 * If it's an unknown user, we should delete his last entry
 		 * Nothing else, depending on the user IP
 		 * 
 		 */
		if ($iduser === NULL) {

			$user_ip = $_SERVER['REMOTE_ADDR'];

			// Delete properly the last one from this IP
			$query = $this->db
			->select('*')
			->where('ip', $user_ip)
			->where('id', $id)
			->order_by('date', 'DESC')
			->limit(1)
			->get(DB_RESULTS_TABLE);

			$old_entry = $query->row();

		/**
		 *
		 * If the user is logged in
		 * It's a different case :
		 * Delete_entry should be for one entry and nothing else (no linkedurl)
		 * 
		 */
		} else {

			// Delete properly this one
			$query = $this->db
			->select('*')
			->where('id_user', $iduser)
			->where('id', $id)
			->limit(1)
			->get(DB_RESULTS_TABLE);

			$old_entry = $query->row();

		}

		// If we didn't found anything as old entry
		if (!isset($old_entry->id)) return FALSE;

		$this->delete_entry($id, FALSE);

		/**
		 * Now after everything was done properly
		 * We will recreate the entry artificially with the dates and everything that was in the old entry
		 */
		
		$edition = array(

			'date' => $old_entry->date,
			'id' => $old_entry->id,
			'hits' => $old_entry->hits

			);

		return $this->general_model->insert($post, LINKBREAKERS_INSERTION_STATUS, $edition);

	}

	/**
	 * 
	 * Deleting the creation
	 * First, we check the mode (linkedURL to remove)
	 * Then, we will use the delete_entry to clean properly the creation
	 * 
	 * WARNING : On 06/01/2014 the system has been changed, you MUST be logged-in
	 * To remove a creation otherwise it will BUG (normally everything is checked before the function is called)
	 * 
	 * @param  integer  $id         entry id
	 * @param  integer  $iduser     the user database id
	 * @return bool did it work ?
	 */
	public function remove_creation($id, $iduser) {

		Devlog::add('The entry #'.$id.' was found in the database and will be deleted');

		$id = (string) $id;

		// Delete properly this one
		$query = $this->db
		->select('id')
		->where('id_user', $iduser)
		->where('id', $id)
		->limit(1)
		->get(DB_RESULTS_TABLE);

		$array_sql = $query->row();

		if (!isset($array_sql->id)) return FALSE;

		// We will use the primary function to delete it with the option to do not erase linkedurl
		if ($array_sql->id === $id) return $this->delete_entry($id, TRUE);

		return FALSE;

	}

	/**
	 * Deleting properly an entry
	 * @param  integer  $id          entry to remove
	 * @param  boolean $remove_linkedurl should we remove the linkedurl with it ?
	 * @return bool TRUE
	 */
	public function delete_entry($id, $remove_linkedurl=TRUE) {

		// Delete properly an entry
		$this->db
		->where('id', $id) // Normal reference
		->delete(DB_RESULTS_TABLE); // Results table

		$this->db
		->where('id_string', $id) // Normal reference
		->delete(DB_TAGS_TABLE); // Tags table

		if ($remove_linkedurl === TRUE) {

			// Delete all LinkedURL with this entry (if it exists)
			$linkedurl = '@'.$id;

			$query = $this->db
			->select('id')
			->where('url', $linkedurl)
			->get(DB_RESULTS_TABLE);

			$array_sql = $query->result_array();

			// So there are @linkedurl with this entry
			foreach ($array_sql as $row) {

				$linkedurl_id = $row['id'];

				$this->db
				->where('id', $linkedurl_id)
				->delete(DB_RESULTS_TABLE); // Results table

				$this->db
				->where('id_string', $linkedurl_id) // LinkedURL reference
				->delete(DB_TAGS_TABLE); // Tags table

			}

		}

	return TRUE;

	}

	/**
	 * Find an entry id from its autocomplete (this will be useful for the @linkedurls)
	 * @param  string  $autocomplete the autocomplete to look on
	 * @param  integer $iduser       the id of the user wanting to link the autocomplete
	 * @return integer the entry id
	 */
	public function find_id_by_autocomplete($autocomplete, $iduser=0) {

		$autocomplete = trim($autocomplete); // We never know, a space could appear everywhere man !
		$iduser = (int) $iduser;

		if ($iduser === 0)

		$sql_request = "

		SELECT id FROM ".DB_RESULTS_TABLE." WHERE 
		(
		(autocomplete = ".$this->db->escape($autocomplete).") AND (status <> 'private') AND (status <> 'off') AND (status <> 'alpha')
		) ORDER BY id DESC LIMIT 1

		";

		else

		$sql_request = "

		SELECT id FROM ".DB_RESULTS_TABLE." WHERE 
		(

		(autocomplete = ".$this->db->escape($autocomplete).") 
		AND (
			((id_user = '".$iduser."') AND (status = 'private')) OR (status <> 'private')
			)
		AND (
			(status <> 'off') AND (status <> 'alpha')
			)

		) ORDER BY id DESC LIMIT 1

		";

		$query = $this->db->query($sql_request);
		$array_sql = $query->result_array();

		if (!isset($array_sql[0])) return 0;
		else return $array_sql[0]['id'];

	}

	/**
	 * Find an autocomplete from the entry id
	 * @param  integer $id of this entry
	 * @return string the autocomplete of this entry
	 */
	public function find_autocomplete_by_id($id) {

		$id = (int) $id; // We never know, a space could appear everywhere man !

		$query = $this->db
		->select('autocomplete')
		->where('id', $id)
		->limit(1)
		->get(DB_RESULTS_TABLE);

		$array_sql = $query->result_array();

		if (!isset($array_sql[0])) return 0;
		else return $array_sql[0]['autocomplete'];

	}

	/**
	 * Get the linkedURL of an entry (@'final_url') from its id
	 * @param  integer  $search_id   the id of the entry
	 * @param  integer $remove_linkedid should we remove the linkedURL if the link couldn't be found ?
	 * @return string the url of the entry
	 */
	public function find_linkedurl_by_id($search_id, $remove_linkedid=0) {

		$search_id = (int) $search_id;
		if ($search_id === 0) return FALSE;

		$query = $this->db
		->select('url')
		->where('id', $search_id)
		->limit(1)
		->get(DB_RESULTS_TABLE);

		$array_sql = $query->result_array();

		/**
		 * This is an OLD SCHOOL system which can be understood as a bug
		 * Someone will try to go to a linkedurl-entry which match @0 or such and it will go back to the homepage
		 * It should be different : this should redirect to all the auto-redirect system
		 */

		// We should set an auto-delete system here
		if (!isset($array_sql[0])) {

			$this->delete_entry($remove_linkedid);
			return base_url(); // We redirect to linkbreakers.com root -> this is an excpetion, normally a linkedurl everytime gets its URL
		
		} else return $array_sql[0]['url'];

	}

	/**
	 * We increase the hit
	 * @param  integer $resultid the id to hit up
	 * @return void
	 */
	public function hit_up($resultid) {

		$this->db->where('id', $resultid);
		$this->db->set('hits', 'hits+1', FALSE);
		$this->db->update(DB_RESULTS_TABLE);

	}

	/**
	 * Count an amount from the database depending on the user creations
	 * @param  integer $userid the user id
	 * @param  string $target the data which's targetted
	 * @return integer the result
	 */
	public function counter_from_user($userid, $target) {

		if ($target === 'hits') {

			$query = $this->db
			->select_sum('hits')
			->where('id_user', $userid)
			->get(DB_RESULTS_TABLE);

			$array_sql = $query->row();
			if (isset($array_sql->hits)) return $array_sql->hits;

		}

	}

	/**
	 * Entry insertion system
	 * @param  array $post the post-datas that will be inserted within the database
	 * @param  string $default_status the default status to set for the entry
	 * @param  array/bool $edition_module are we in an edition mode ? if we are editing the entry, some datas will be kept (depending on the array)
	 * @return integer the id of the new entry which will be used during the process afterwards
	 */
	public function insert(&$post, $default_status, $edition_module=FALSE) { // string, url and method already defined

		if (empty($post['url'])) return FALSE;
		if (empty($post['string'])) return FALSE;
		if (empty($post['method'])) return FALSE;

		if (!$iduser = $this->pikachu->show('userid')) $iduser = 0;

		// We need to check if URL isn't a @linkedurl
		if ($post['url'][0] === '@') {

			Devlog::add('The (URL) "'.$post['url'].'" was recognized as LinkedURL');

			// We find the id from this "correct" autocomplete (theorically, there's a verification before this)

			$post['url'] = '@' . $this->general_model->find_id_by_autocomplete(substr($post['url'], 1), $iduser);

			Devlog::add_admin('It was transformed to "'.$post['url'].'" to check the database');

			// We will check until it's not linked with a linkedurl (because it's a constructor loop after)

			$int = 0; // There will be a loop
			$temporary_post[$int] = $post['url']; // We set it first

			while ($temporary_post[$int+1] = $this->general_model->find_linkedurl_by_id(substr($temporary_post[$int], 1))) {

				Devlog::add_admin('Linkbreakers is looping and got "'.$temporary_post[$int+1].'" as temporary LinkedURL response');
				++$int;

			}

			$post['url'] = $temporary_post[$int-1]; // The two right before the last (no-linkedurl then) will be set as URL

			Devlog::add('This LinkedURL matchs with "'.$temporary_post[$int].'")');

		}

		// We clean our string (just a little, we will finish the process after the strongtype system)
		$post['string_clean'] = trim(delete_double_spaces($post['string']));

		Devlog::add_admin('(STRING) half-cleaned "'.$post['string_clean'].'"');

		// Strongtype anti conflict encode in base64 (think about [regex:example])

		$encode_strongtype_args = explode(' ', $post['string_clean']);
		$strongtype_array = array();

		foreach ($encode_strongtype_args as $key => $word) {

			// preg_match [regex:bidule][another:test] with multi strong-type
			if (preg_match("#\\$[a-zA-Z]*\[regex:(.+)\]\[#is", $word, $strongtype_array));
			elseif (preg_match("#\\$[a-zA-Z]*\[regex:(.+)\]#is", $word, $strongtype_array));

			if ($strongtype_array) {

				// We encode our result (which is a regex strongtype)
				$encoded_regex = encode($strongtype_array[1]);

				// We put it in a new word
				$encoded_word = str_replace('[regex:'.$strongtype_array[1].']', '[regex:'.$encoded_regex.']', $word);

				Devlog::add_admin('Strongtype [regex] detected and encoded as "'.$encoded_word.'"');

				// We replace the original strongtype
				$encode_strongtype_args[$key] = $encoded_word;

			}

		}

		$post['string_clean'] = implode(' ', $encode_strongtype_args);

		// First insertion (into 'results')
		$results['url'] = $post['url'];
		$results['method'] = $post['method'];
		$results['string_clean'] = clean($post['string_clean'], TRUE); // We will finish our big clean system

		Devlog::add_admin('(STRING) was completely cleaned and became "'.$results['string_clean'].'"');

		$results['lang'] = $this->sql_lang;

		// User informations
		$results['date'] = time();
		$results['last_update'] = time();

		$results['last_search'] = time();
		$results['ip'] = $_SERVER['REMOTE_ADDR'];

		// Strong type system
		$arr_strong_type = $this->panda->get_strong_type($results['string_clean']); // We put it in an array
		$results['string_clean'] = $this->panda->clean_strong_type($results['string_clean']); // We clean it from string_clean

		$results['strong_type'] = json_encode($arr_strong_type); // We encode it to put is into our database

		Devlog::add_admin('Strongtypes encoded in JSON ('.$results['strong_type'].')');

		// We need to keep all the language signs such as éèà etc. (this is here because of the strong type)
		$results['autocomplete'] = $this->panda->var_to_autocomplete($this->panda->clean_strong_type($post['string']));

		// Edit mode, we must JSON this entry (data-memory)
		$arr_edit = array(

			'string' => $post['string'], // Why $post ? Because we want the root-data and not the cleaned one
			'url' => $post['url']

			);

		Devlog::add_admin('Displayed (STRING) and (URL) saved');

		/*
		 *
		 * Now that the URL display is safe, we can convert the true LBL URL to be understood
		 * By the system, it includes comments deleting, inline conversion ...
		 *
		 */
		$results['url'] = $this->panda->prepare_lbl_url_to_be_understood($results['url']);

		$results['edit'] = json_encode($arr_edit);

		if ($this->pikachu->show('userid')) {

			$results['id_user'] = $this->pikachu->show('userid');
			$default_status = 'private'; // This mean it won't be "alpha" but "private" if it's a logged user

		} else $results['id_user'] = 0;

		$results['status'] = $default_status;

		// Conflict system (0 - 10)
		$this->load->model('tank_model');
		$results['conflict'] = $this->tank_model->get_conflict_rate($results['string_clean'], $results['url']);

		Devlog::add_admin('Linkbreakers set the conflict rate at '.$results['conflict'].' for this entry');

		// We will need this in our foreach database insertion -> "One does not simply change these lines"
		$string_array = explode(" ", $results['string_clean']);
		$position = 1;

		/**
		 * If it's an edition there are some data that we'll keep from the old entry
		 * Merging the arrays keeping the edition module in priority
		 */
		if (is_array($edition_module)) {

			$results = array_merge($results, $edition_module);

		}

		// Database insertion
		$this->db
		->insert(DB_RESULTS_TABLE, $results);

		// Get corresponding 'id'
		$tags_id_string = $this->db->insert_id();

		Devlog::add('This entry was successfully added to the database (#'.$tags_id_string.')');

		// Mask system protection-detection
		$mask = $this->panda->mask_gen($results['string_clean']);

		foreach ($string_array as $row) {

			// Insertion
			if ($row[0] === '$') $insert_database[$position]['dyn'] = 1;
			else $insert_database[$position]['dyn'] = 0;

			$insert_database[$position]['id_string'] = $tags_id_string;
			$insert_database[$position]['tag_clean'] = trim($row);
			$insert_database[$position]['mask']	= $mask;
			$insert_database[$position]['position'] = $position;
			$insert_database[$position]['status'] = $default_status;

			//if (isset($arr_strong_type[$position])) $insert_database[$position]['type'] = $arr_strong_type[$position];
			//else $insert_database[$position]['type'] = $this->config->item('linkbreakers_default_strong_type'); // Default strong type

			$position++;

		}

		$position_max = ($position-1);

		// Strong-type anti-conflict system (for tags -> be sure you perfectly understand what you're doing before editing this it's fucking complicated)
		$arr_anti_conflict = $this->panda->get_strong_type_anti_conflict_array($arr_strong_type);

		$highest_type = $arr_anti_conflict['highest_type'];
		$num_highest_type = $arr_anti_conflict['num_highest_type'];

		foreach ($insert_database as $row) {

			$tags = array(

				'dyn' => $row['dyn'],
				'id_string' => $row['id_string'],
				'id_user' => $results['id_user'],
				'tag_clean' => $row['tag_clean'],
				'mask' => $row['mask'],

				'position' => $row['position'],
				'position_max' => $position_max,

				'highest_type' => $highest_type,
				'num_highest_type' => $num_highest_type,

				'status' => $row['status'],
				'lang' => $this->sql_lang

				);

			// Database insertion
			$this->db
			->insert(DB_TAGS_TABLE, $tags);

		}

		return $tags_id_string; // We return the unique ID from the insertion (it's also TRUE)

	}

}