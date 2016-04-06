<?php

class Log_model extends MY_Model {

	// Should be in MY_Model
	protected $id_creator_from_table = array(
		
		'log'        => 'id',
		'results'    => 'id_user',
		'prefs'      => 'prefs_id_creator',
		'space'      => 'space_id_creator',
		'recoveries' => 'recovery_id_creator'

		);

	protected $erreur = FALSE;

	function __construct() {

		parent::__construct();

	}
 
	/**
	 * We remove a recovery (it cans delete multiple recoveries)
	 * @param  integer $userid the userid
	 * @param  string $type   which kind of recovery ?
	 * @return void
	 */
	public function remove_recovery($userid, $type) {

		$this->db
		->where('recovery_id_creator', $userid)
		->where('recovery_type', $type)
		->delete(DB_RECOVERIES_TABLE);

	}

	// Should be within another table (recoveries_model or something like that)
	public function add_recovery($userid, $type, $key, $check_unique=TRUE) {

		// If a recovery needs to be unique, we remove all the doublons before
		if ($check_unique) $this->remove_recovery($userid, $type);

		// Recovery insertion
		$new_recovery_array = array(

			'recovery_id_creator' => $userid,
			'recovery_type'       => $type,
			'recovery_key'        => $key,
			'recovery_date'       => time()

			);

		$this->db->insert(DB_RECOVERIES_TABLE, $new_recovery_array);

	}

	// Insert a new user
	public function insert_new_user($user, $user_pretty, $password) {

		$user_pretty = $this->panda->make_username_pretty($user_pretty);

		$password = strong_encrypt($password);
	$status = 'user';
		$date_subscribe = time();
		$ip = $_SERVER['REMOTE_ADDR'];

		$insert_new_user = array(
			
			'username'        => $user,
			'username_pretty' => $user_pretty,
			'password'        => $password,
			'status'          => $status,
			'email'           => '',
			'date_subscribe'  => $date_subscribe,
			'date_login'      => $date_subscribe,
			'ip'              => $ip,

			);

		// Base user simple insertion
		$this->db->insert(DB_LOG_TABLE, $insert_new_user);

		// We get our new id
		$new_user_id = $this->db->insert_id();

		// Space user simple insertion
		$insert_new_space_user = array(

			'space_id_creator' => $new_user_id

			);

		$this->db->insert(DB_SPACE_TABLE, $insert_new_space_user);

		// Prefs user simple insertion
		$insert_new_prefs_user = array(

			'prefs_id_creator'              => $new_user_id,
			'prefs_language'                => Language::pick('short'),
			'prefs_default_result_url'      => LINKBREAKERS_NO_RESULT_REDIRECTION,
			'prefs_default_result_url_edit' => json_encode(LINKBREAKERS_NO_RESULT_REDIRECTION)

			);

		$this->db->insert(DB_PREFS_TABLE, $insert_new_prefs_user);

		return $new_user_id;

	}

	/**
 	*
 	* We check if this user exists
 	*
 	* @param string $user the username
 	* @param bool $auth_from_email the username could be an email
 	* @return bool
 	*
 	*/
	public function user_exists($user, $auth_from_email=FALSE) {

		if ($auth_from_email) {

			$query = $this->db
			->select('password')
			->where('username', $user)
			->or_where('email', $user)
			->limit(1)
			->get(DB_LOG_TABLE);


		} else {

			$query = $this->db
			->select('password')
			->where('username', $user)
			->limit(1)
			->get(DB_LOG_TABLE);

		}

		$num_rows = $query->num_rows();

		if ($num_rows > 0) return TRUE;
		else return FALSE;

	}

	// We check if this email exists in our database
	public function email_exists($email) {

		$query = $this->db
		->select('id, email')
		->where('email', $email)
		->limit(1)
		->get(DB_LOG_TABLE);

		if ($query->num_rows() > 0) return $query->first_row()->id;
		else return FALSE;

	}

	// We get the username from the original email
	public function username_from_email($email) {

		$query = $this->db
		->select('username, email')
		->where('email', $email)
		->limit(1)
		->get(DB_LOG_TABLE);

		if ($query->num_rows() > 0) return $query->first_row()->username;
		else return FALSE;

	}

	public function change_password_from_recover_key($recoverkey, $newpassword) {

		if ($userid = $this->recover_key_exists($recoverkey)) {

			$new_password_encrypted = strong_encrypt($newpassword);
			$this->set_user_detail('log', $userid, 'password', $new_password_encrypted);	

		}

	}

	public function recover_key_exists($recoverkey) {

		$query = $this->db
		->select('id')
		->where('recovery_type', 'email_recover')
		->where('recovery_key', $recoverkey)
		->limit(1)
		->get(DB_RECOVERIES_TABLE);

		if ($query->num_rows() > 0) return $query->first_row()->id;
		else return FALSE;

	}

	public function email_valid_exists($id, $key) {

		$query = $this->db
		->select('id')
		->where('recovery_id_creator', $id)
		->where('recovery_type', 'email_valid')
		->where('recovery_key', $key)
		->limit(1)
		->get(DB_RECOVERIES_TABLE);

		if ($query->num_rows() > 0) return $query->first_row()->id;
		else return FALSE;

	}

	/**
 	*
 	* We auth the user
 	*
 	* @param string $user the username
 	* @param string $password the linked password
 	* @param bool $auth_from_email the username could be an email
 	* @return bool
 	*
 	*/
	public function auth_user($user, $password, $auth_from_email=FALSE) {

		// We encrypt before our check
		$password = strong_encrypt($password);

		if ($auth_from_email) {

			$user = $this->db->escape($user);
			$password = $this->db->escape($password);

			$query = $this->db
			->select('id')
			->where("(email = $user OR username = $user) AND password = $password")
			->limit(1)
			->get(DB_LOG_TABLE);

		} else {

			$query = $this->db
			->select('id')
			->where('username', $user)
			->where('password', $password)
			->limit(1)
			->get(DB_LOG_TABLE);

		}

		$array_sql = $query->result_array();

		// On retourne l'ID
		foreach ($array_sql as $row) return $row['id'];
		return FALSE;

	}

	// Change password
	public function change_password($id, $old_password, $new_password) {

		$username = $this->get_user_detail('log', $id, 'username');

		// We check if the informations are corrects
		if ($this->auth_user($username, $old_password)) {

			$new_password_encrypted = strong_encrypt($new_password);

			$this->set_user_detail('log', $id, 'password', $new_password_encrypted, TRUE, 'userpassword');

			return TRUE;

		}

		return FALSE;

	}

	// Get number of creations
	public function get_num_creations($id, $status=array()) {

		// <!> Active record sucks so i created the sql query manually
		// <!> In effect you can't use "group where clause" 

		// Force value to be an array
		$status = (array) $status;

		$nb_elements = count($status);

		// Base query
		$query = "SELECT `id` FROM (`" . DB_RESULTS_TABLE . "`) WHERE `id_user` = '" . $id . "'";

		// Loop if status have got values
		if ($nb_elements > 0) {

			// Manage where clause
			foreach ($status as $key => $value) {

				if ($key == 0) {

					$query .= " AND (`status` = '" . $this->db->escape_str($value) . "'";

				} else {

					$query .= " OR `status` = '" . $this->db->escape_str($value) . "'";

				}

			} 



			$query .= ' )';

		}

		$query = $this->db->query($query);

		return $query->num_rows();

	}

	/**
	 * Get the user detail
	 *
	 * We will get the user detail from any table matching with some user datas
	 *
	 * @access	public
	 * @param string $table match with a table within the database
	 * @param string $id the userid
	 * @param array $detail the datas to get
	 */
	public function get_user_detail($table, $id, $detail) {

		$infos = array(

			'checked_id' => $this->id_creator_from_table[$table],
			'get_table' => $table

			);

		return $this->get_detail($infos, $id, $detail);

	}

	/**
	 * Set the user detail
	 *
	 * We will set the user detail (it cans be located in any table matching with a user data)
	 * Optionaly we can auto-reset the session (pikachu) with the same or a different label
	 *
	 * @access	public
	 * @param string $table match with a table within the database
	 * @param string $id the userid
	 * @param string $detail the field to change within the database
	 * @param string $content the new content to set
	 * @param bool $pikachu is there a pikachu-session to change ?
	 * @param bool/string $pikachu_special_label is there a special label to put for the pikachu-session ?
	 * @return bool BE CAREFUL : if we set a data with the same value as previous data, it will return a negative result even if it theorically "works"
	 */
	public function set_user_detail($table, $id, $detail, $content, $pikachu=FALSE, $pikachu_special_label=NULL) {

		$data = array(

			$detail => $content

			);

		if ($pikachu) {

			if ($pikachu_special_label === NULL) $this->pikachu->set($detail, $content);
			else $this->pikachu->set($pikachu_special_label, $content);
			
		}

		/*
		 * Where works with a protected variable on the top of the file matching
		 * the id creator from the selected table
		 */
		$this->db->where($this->id_creator_from_table[$table], $id);
		return $this->db->update(DB_TABLE_PREFIX.$table, $data);

	}

	/*public function check_and_insert_user($arr) {

		// Si l'user existe déjà, on va checker si le mot de passe correspond
		if ($this->user_exists($arr['user'])) {

			if ($id_user_auth = $this->auth_user($arr['user'], $arr['password'])) return $id_user_auth;
			else return FALSE;

		} else {

		// Création compte utilisateur -> récupération de l'ID-user -> puis AUTH
			return $this->insert_new_user($arr['user'], $arr['password']);

		}


	}*/

	// Auto an user (session mode)
	public function session_auth($arr=array('username' => '', 'password' => '')) {

		$user = $arr['username'];
		$password = $arr['password'];

		/*
		 *
		 * In case there's a username_pretty within the array to register a new user
		 * Otherwise we set it as a auto-pretty username
		 *
		 */
		if (isset($arr['username_pretty'])) $user_pretty = $arr['username_pretty'];
		else $user_pretty = $this->panda->make_username_pretty($user);

		if (!$this->user_exists($user)) $userid = $this->insert_new_user($user, $user_pretty, $password);
		else $userid = $this->auth_user($user, $password);

		// Synchronization session var is 'on' because the both were synchronized
		$this->pikachu->set('sync', 'TRUE');

		// Ultimate security (if the user cannot login, there's a big problem)
		if (!$userid) return FALSE;

		// Get user details (if there's only one detail, it's not an array then we set the direct var)
		$user_detail_status = $this->get_user_detail('log', $userid, 'status');
		$user_detail_email = $this->get_user_detail('log', $userid, 'email');

		$user_detail_email_valid = $this->get_user_detail('log', $userid, 'email_valid');
		if ($user_detail_email_valid) $user_detail_email_valid = TRUE;
		else $user_detail_email_valid = FALSE;

		$space_user_details = $this->get_user_detail('space', $userid, array('space_avatar', 'space', 'space_home', 'space_description_edit', 'space_redirection_edit'));
		$prefs_user_details = $this->get_user_detail('prefs', $userid, array('prefs_language', 'prefs_default_result_url', 'prefs_default_result_url_edit', 'prefs_default_result_enabled', 'prefs_smart_domains_enabled', 'prefs_clever_returns_enabled'));

		// Set variables
		$userstatus = $user_detail_status;

		$space_user = $space_user_details['space'];
		$space_home = $space_user_details['space_home'];
		$space_avatar = $space_user_details['space_avatar'];
		$space_description_edit =  $space_user_details['space_description_edit'];
		$space_redirection_edit = $space_user_details['space_redirection_edit'];

		$prefs_default_result_url = $prefs_user_details['prefs_default_result_url'];
		$prefs_default_result_url_edit = $prefs_user_details['prefs_default_result_url_edit'];
		$prefs_default_result_enabled = $prefs_user_details['prefs_default_result_enabled'];
		$prefs_smart_domains_enabled = $prefs_user_details['prefs_smart_domains_enabled'];
		$prefs_clever_returns_enabled = $prefs_user_details['prefs_clever_returns_enabled'];
		$prefs_language = $prefs_user_details['prefs_language'];

		// We refresh the language in the system (FALSE = no database affection)
		$this->language->change_language($prefs_language, FALSE);

		$usercreations = $this->get_num_creations($userid);

		/*
		 *
		 * We set the $userpretty and get it from the database
		 * -----
		 * /!\ Be careful : this variable exists right above as $user_pretty but doesn't correspond to the same system
		 * I had to use almost the same name to norm it.
		 * -----
		 *
		 */
		$userpretty = $this->get_user_detail('log', $userid, 'username_pretty');

		// It's a success, we will log-in this user and put a correct status
		$this->pikachu->multi_set(

			array(

			'userid'          => $userid,
			'username'        => $user,
			'username_pretty' => $userpretty, 
			'userpassword'    => $password,
			'userstatus'      => $userstatus,
			'useremail'       => $user_detail_email,
			'useremailvalid'  => $user_detail_email_valid,
			'usercreations'   => $usercreations,
			'userspace'       => $space_user

			)

		);



		// Spaces
		$this->pikachu->set('space_description_edit', $space_description_edit);
		$this->pikachu->set('space_redirection_edit', $space_redirection_edit);
		$this->pikachu->set('space_avatar', $space_avatar);
		$this->pikachu->set('space_home', $space_home);

		// Prefs (cookies)
		$this->pikachu->cooking(

			array(

				'prefs_default_result_url'      => $prefs_default_result_url,
				'prefs_default_result_url_edit' => $prefs_default_result_url_edit,
				'prefs_default_result_enabled'  => $prefs_default_result_enabled,
				'prefs_smart_domains_enabled'   => $prefs_smart_domains_enabled,
				'prefs_clever_returns_enabled'  => $prefs_clever_returns_enabled

				)

			);

		// User base details (cookies)
		$this->pikachu->cooking(

			array(

				'userid'        => $userid,
				'username'      => $user,
				'userpassword'  => $password,
				'userstatus'    => $userstatus,
				'usercreations' => $usercreations,
				)

			);

		// We delete the possible email_recover that could've been set (security point)
		$this->remove_recovery($userid, 'email_recover');

		return TRUE;

	}

	public function find_id_by_username($username) {

		$query = $this->db
		->select('id')
		->where('username', $username)
		->limit(1)
		->get(DB_LOG_TABLE);

		$arr = $query->row_array();

		if ($arr) return $arr['id'];
		else return FALSE;

	}

	public function find_by_user($id, $limit=FALSE, $status='private', $search='') {

		// Status 
		if (is_array($status)) {

			$status_end = "";

			foreach ($status as $row) {

				$status_end .= "status = '$row' OR ";

			}

			$status_end = substr($status_end, 0, (strlen($status_end)-4));

		} else {

			$status_end = "status = '$status'";

		}

		// Where clause
		$where = "id_user = '$id' AND ($status_end)";


		// Base query
		$query = $this->db->where($where)->order_by('last_update', 'DESC');


		// More conditions (limit)
		if ($limit !== FALSE) {

			$query = $query->limit($limit);

		}

		// More conditions (search)
		if (!empty($search)) {

			$search = trim($search);
			$args = explode(' ', $search);

			foreach ($args as $arg) {

				$query = $query->like('string_clean', $arg);

			} 

		}

		// Run query
		$query = $query->get(DB_RESULTS_TABLE);

		// Get results 
		$arr = $query->result_array();

		if ($arr) return $arr;
		
		return FALSE;

	}

	public function recompose_by_id($id, $iduser) {

		$query = $this->db
		->where('id', $id)
		->where('id_user', $iduser)
		->limit(1)
		->get(DB_RESULTS_TABLE);

		$arr = $query->result_array();

		if (isset($arr[0]['edit'])) $recomposition = json_decode($arr[0]['edit'], TRUE);
		else $recomposition = FALSE;

		return $recomposition;

	}


}