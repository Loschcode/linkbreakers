<?php

/**
 * Panda class
 *
 * Raw datas treatments
 * - Function directly linked with models
 * - Useful small function that cannot be removed as helpers
 *
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Class
 *
 */

class Panda extends CI_Context {

	public function __construct() {

		parent::__Construct();

	}

	/**
 	*
 	* Set a cache-file
 	*
 	* @param page to cache
 	* @param content of this page
 	* @param mode subfolder to put in
 	* @return bool
 	*
 	*/
 	public static function set_memory($page, $content, $mode='misc') {

 		$page = str_replace('/', '_', $page);
 		$path = 'memory/'.$mode.'/'.$page.'.cache';

 		$fd = fopen($path, "w");

 		if ($fd) {

 			fwrite($fd, $content);
 			fclose($fd);

 		} else return FALSE;

 		return TRUE;

 	}

	/**
 	*
 	* Get a cache-file
 	*
 	* @param page to cache
 	* @param mode subfolder to put in
 	* @param timeout in seconds
 	* @return bool
 	*
 	*/
 	public static function get_memory($page, $mode='misc', $timeout=3600) {

 		$page = str_replace('/', '_', $page);
 		$path = 'memory/'.$mode.'/'.$page.'.cache';

 		if (@filemtime($path) < (time() - $timeout)) {

 			$fd = fopen($path, "r");
 			$content = fread($fd, filesize($path));

 		} else return FALSE;

 		return $content;

 	}

	/**
 	*
 	* Erase a cache-file
 	*
 	* @param page to cache
 	* @param mode subfolder to put in
 	* @return bool
 	*
 	*/
 	public static function erase_memory($page, $mode) {

 		$page = str_replace('/', '_', $page);
 		$path = 'memory/'.$mode.'/'.$page.'.cache';

 		return unlink($path);

 	}

	/**
 	*
 	* str_replace with associative array
 	*
 	* @param array $replace to use
 	* @param subject string to replace
 	* @return string with replaced areas
 	*
 	*/
	public static function str_replace_assoc(array $replace, $subject) {

  		return str_replace(array_keys($replace), array_values($replace), $subject);

	}

	/**
 	*
 	* Display tools format
 	*
 	* @param string $string to format
 	* @return string formatted string
 	*
 	*/
	public static function display_tool_format($string) {

		$length = strlen(strip_tags($string));

		if ($length < 50) $string = '<h1>'.$string.'</h1>';
		elseif ($length < 200) $string = '<h2>'.$string.'</h2>';
		else $string = '<h3>'.$string.'</h3>';

		return $string;

	}

	/**
 	*
 	* Recovering encryption
 	*
 	* @param string $string to encrypt
 	* @return string encrypted string
 	*
 	*/
	public static function make_recovery_key($string) {

		return strtoupper(sha1(md5('linkbreakers-'.uniqid().'-'.$string)));

	}

	/**
 	*
 	* Simple email checker
 	*
 	* @param string $string to check
 	* @return bool
 	*
 	*/
	public static function is_email($string) {

		if (filter_var($string, FILTER_VALIDATE_EMAIL)) return TRUE;

	}

	/**
 	*
 	* Generate tinyURL
 	*
 	* @param string $url to convert
 	* @return string/bool generated url
 	*
 	*/
	public static function generate_tinyurl($url) {

		if (!$fget = @file_get_contents('http://tinyurl.com/api-create.php?url=' . $url)) $fget = base_url();
		
		if ($fget === 'Error') return FALSE;
		else return $fget;

	}

	/**
 	*
 	* Get Gravatar picture
 	*
 	* @param string $email matching with the gravatr
 	* @return string picture
 	*
 	*/
	public static function get_gravatar($email, $size=160) {

		$default_picture = DEFAULT_PROFILE_PICTURE;

		$gravatar_hash = md5(strtolower(trim($email)));
		return 'http://www.gravatar.com/avatar/'.$gravatar_hash.'.jpg?size='.$size.'&default='.$default_picture;

	}

	/**
 	*
 	* Check if this tool exists within LB system
 	*
 	* @param string $string tool to check (without #)
 	* @return bool
 	*
 	*/
 	public function existing_tool($string) {

 		if ($string === 'tools/text') return TRUE;
 		if ($string === 'tools/raw') return TRUE;
 		if ($string === 'tools/sandbox') return TRUE;
 		if ($string === 'tools/stick') return TRUE;

 		return FALSE;

 	}


	/**
 	*
 	* Get the function type corresponding to the LBL function and return it as a string
 	*
 	* @param string $lbl_function such as 'str_up'
 	* @return bool / string (e.g. 'semantic' or 'web parsing')
 	*
 	*/
 	public function get_type_from_lbl_function($lbl_function) {

 		$lbl_function = strtolower($lbl_function);

 		$this->load->library('volt/mustache');
 		$lbl_function_details = $this->mustache->array_global('function', $lbl_function); // From the Mustache API

 		if (isset($lbl_function_details['type'])) return $lbl_function_details['type'];
 		else return FALSE;

 	}

	/**
 	*
 	* Get the kind of library corresponding to the LBL function and return it as a string
 	* Be careful : this is a normed area, it means the - are replaced by '_' and also the spaces
 	* It will return a string which will be used to load a LBL library, it must works.
 	*
 	* @param string $lbl_function such as 'str_up'
 	* @return bool / string (e.g. 'semantic' or 'web_parsing')
 	*
 	*/
 	public function get_library_type_from_lbl_function($lbl_function) {

 		if ($type = $this->get_type_from_lbl_function($lbl_function)) return $this->rename_as_file($type);
 		else return FALSE;

 	}

	/**
 	*
 	* Rename a string to be used as file name (useful to load LBL libraries from now)
 	*
 	* @param string $string_to_convert it's like le Port-Salut
 	* @return string normalized
 	*
 	*/
 	public static function rename_as_file($string_to_convert) {


 		$final_string = str_replace("-", "_", $string_to_convert);
 		$final_string = str_replace(" ", "_", $final_string);

 		return $final_string;

 	}

	/**
 	*
 	* Remove the LBL comments from a string
 	*
 	* @param string $string_with_comments
 	* @return string without LBL comments
 	*
 	*/
 	public static function remove_lbl_comments($string_with_comments) {

		Devlog::add_admin('Comments deleted in (URL) "'.$string_with_comments.'"');

 		return preg_replace('#/\*(.*)\*\/#is', '', $string_with_comments);

 	}

	/**
 	*
 	* Prepare LBL URL to be understood
 	* This is useful in many fields such as add_tag system or redirections with LBL inside
 	* It will make the LBL URL understandable by our system
 	*
 	* @param string $lbl_url to convert
 	* @return string understandable LBL URL
 	*
 	*/
 	public function prepare_lbl_url_to_be_understood($lbl_url) {

		/*
		 *
		 * COMMENTS SYSTEM (/ * * /)
		 * We need to delete all our comments datas before anything will be put in the database
		 *
		 * NOTE : We kept it right before our comments for the displayed URL
		 *
		 */
		$lbl_url = $this->remove_lbl_comments($lbl_url);

		/*
		 *
		 * #tools/example\n conversion
		 *
		 */
		$lbl_url = $this->inline_intern_url($lbl_url);

		/*
		 *
		 * Lines (\r, \n) removing
		 *
		 */
		$lbl_url = $this->trim_lines($lbl_url);

		return $lbl_url;

 	}

	/**
 	*
 	* Inline intern URL
 	*
 	* It will detect and convert an intern_url such as
 	* #tools/text\nhello to #tools/text hello
 	*
 	* This system was created for the LBL to be more clear for the user, he could make new lines
 	* Without destroying the sensitive LBL system
 	*
 	* @param string $possible_intern_url
 	* @return string with a normed intern url
 	*
 	*/
 	public function inline_intern_url($possible_intern_url) {

 		// First we trim it in case there are some spaces
 		$possible_intern_url = trim($possible_intern_url);

 		// First we check if there's an intern url
		if (is_intern_url($possible_intern_url)) {

			Devlog::add('(URL) recognized as intern URL');

			// We check if there isn't a already normed intern_url
			$first_word = trim(substr($possible_intern_url, 1, strpos($possible_intern_url, ' ')));

			// If this tool doesn't exist maybe it's a #tools/example\n tool (we don't use a space separation but a line return separation)
			if (!$this->existing_tool($first_word)) {

				$first_word_with_return = trim(substr($possible_intern_url, 1, strpos($possible_intern_url, "\n")));

				// Why is there a die ? Because we should've checked the tool existence before that
				if (!$this->existing_tool($first_word_with_return)) die('Failure : there\'s a weird tool trying to be added.'); // If it doesn't work there's a problem in our system

				//$first_word_with_return = str_replace("\n", " ", $first_word_with_return);

				// We will delete the first \n we will find -> we are sure this is at the end of the intern url detection
				// because we should've checked it before
				$final_intern_url = str_replace_once("\n", " ", $possible_intern_url);

				return $final_intern_url;

			}
			

		}

		return $possible_intern_url;

 	}

	/**
 	*
 	* Understand and resolve intern URL
 	*
 	* @param string $raw_search_text tool to check (without #)
 	* @return bool
 	*
 	*/
 	public function understand_intern_url($final_url, array $research_details) { //$raw_search_text, &$by_specific_user_name=FALSE, &$by_specific_user=FALSE) {

		// It contains the search_text, by_specific_user, by_specific_user_name, ...
		extract($research_details);

 		$intern_url = trim(substr($final_url, 1, strpos($final_url, ' ')));	
 		
		if ($this->existing_tool($intern_url)) { // Check to avoid hack

			// We set correct intern PikachuSession variables -> content we analyzed functions before
			$this->pikachu->set($intern_url, substr($final_url, strlen($intern_url)+1));

			// Let's get the context ('laurent' or '17' or 'search') to set it afterwards (controller/tools)
			if ($by_specific_user_name) {

				$tools_real_page = $by_specific_user_name; // The real page
				$tools_id_userspace =  $this->log_model->find_id_by_username($by_specific_user_name);// user space id (equivalent to host user id)
				$tools_from_space = TRUE; // Is this tool called from a user space ?

			} elseif ($by_specific_user) {

				$tools_real_page = $by_specific_user;
				$tools_id_userspace = $by_specific_user;
				$tools_from_space = TRUE;

			} else {

				$tools_real_page = uri_string();
				$tools_from_space = FALSE;

			}
			// -> We set the context that can be used for every tools

			/*
			 * TOOLS DATAS INJECTION
			 *
			 * Here we will add every details we could need within intern url requests
			 * Example : the user space linked with our research to show the correct page.
			 *
			*/
			if ($intern_url === 'tools/stick') {

			$this->pikachu->set('tools/_userspace', $tools_real_page); // We get the actual (real)page

			} elseif ($intern_url === 'tools/text') {

			if ($tools_from_space === TRUE) $this->pikachu->set('tools/_id_userspace', $tools_id_userspace);
			$this->pikachu->set('tools/_call_from_userspace', $tools_from_space); // We define if it has been called from a user space or not

			}

			// Set Permalink
			if (!empty($raw_search_text)) {

			if ($by_specific_user_name) $this->pikachu->set('permalink', base_url($by_specific_user_name.'/'.rawurlencode($raw_search_text)));
			elseif ($by_specific_user) $this->pikachu->set('permalink', base_url($by_specific_user.'/'.rawurlencode($raw_search_text)));
			else $this->pikachu->set('permalink', base_url('search/?request='.rawurlencode($raw_search_text)));

			Devlog::add_admin_green('Permalink was set "'.$this->pikachu->show('permalink').'"');

			}

		}

		return $intern_url;

	}

	/**
 	*
 	* Check strong type one by one
 	*
  	* @param string $string to check
 	* @param string $type to look at
 	* @param string $arg optional argument
 	* @return bool
 	*
 	*/
 	public function is_correct_strong_type($string, $type, $arg='') {

 		///////////////////////////////////////////////////////////////////////////////////////////
 		// DON'T FORGET TO CHECK THE FUNCTION RIGHT BELOW BEFORE YOU ADD A STRONG-TYPE HERE DUDE //
 		///////////////////////////////////////////////////////////////////////////////////////////

 		Devlog::add('Strongtype ['.$type.'] is checking "'.$string.'" ('.$arg.')');

 		/////////////////////////
 		// ORDER : SPECIAL (0) // -> The bottom of the strong-type check.
 		/////////////////////////

 		if ($type === 'string') return TRUE; // The ever-true strong type

 		elseif ($type === 'sentence') return TRUE; // Sentence system (treatment has been done before.)

 		elseif ($type === 'regex') {

 			$arg = (string) $arg;

 			if (preg_match("#".$arg."#", $string)) return TRUE;

 		}

 		///////////////////////////
 		// ORDER : DATA-TYPE (1) // -> Is it an int ? a float ? or whatever is (computer basics understanding).
 		///////////////////////////

 		// Is it a integer ?
 		elseif ($type === 'int') {

 			if (ctype_digit($string)) return TRUE;

 		}


  		elseif ($type === 'not_int') {

 			if (!ctype_digit($string)) return TRUE; // Think about [word][not_int] "un mot et que ce ne soit pas un chiffre non plus"

 		}

 		// NUMBER DETECTION //
 		elseif ($type === 'numeric') {

 			if (is_numeric($string)) return TRUE;

 		}

 		// NUMBER DETECTION //
 		elseif ($type === 'not_numeric') {

 			if (!is_numeric($string)) return TRUE;

 		}

 		///////////////////////////
 		// ORDER : PURE SIZE (2) // -> How long ? Purely, not by word (computer size understanding)
 		///////////////////////////

 		elseif ($type === 'max_size') { // man_size:2

 			$arg = (int) $arg; // arg must be set
 			if (!isset($string{$arg+1})) return TRUE;

 		}

 		elseif ($type === 'min_size') { // min_size:2

 			$arg = (int) $arg; // arg must be set
 			if (isset($string{$arg})) return TRUE;

 		}

  		elseif ($type === 'exact_size') { // exact_size:2

 			$arg = (string) $arg; // arg must be set
 			if (strlen($arg) == $string) return TRUE;

 		}

 		///////////////////////////
 		// ORDER : WORD SIZE (3) // -> How long ? Words specifications and shits (linkbreakers size understanding)
 		///////////////////////////

 		// Is it a word or a sentence ?
 		elseif ($type === 'word') {

 			if (!strpos($string, ' ')) return TRUE;

 		}

 		elseif ($type === 'not_word') {

 			if (strpos($string, ' ')) return TRUE;

 		}

 		elseif ($type === 'min_word') {

 			$arg = (int) $arg;

 			$words = explode(' ', $string);
 			if (isset($words[$arg-1])) return TRUE; // +1 because offset starts at 0 which isn't usually understandable.

 		}

 		elseif ($type === 'max_word') {

 			$arg = (int) $arg;

 			$words = explode(' ', $string);
 			if (!isset($words[$arg])) return TRUE; // Changed 06/04/2013 -> -1 instead of +2

 		}

 		elseif ($type === 'exact_word') {

 			$arg = (int) $arg;

 			$num_words = count(explode(' ', $string));
 			if ($num_words === $arg) return TRUE;

 		}

 		//////////////////////////
 		// ORDER : SEMANTIC (4) // -> The top of the specifications (semantic and high-semantic understanding)
 		//////////////////////////

 		//////////
 		// FAST //
 		//////////

 		elseif ($type === 'ip') {

 			if (filter_var($string, FILTER_VALIDATE_IP)) return TRUE;

 		}

 		elseif ($type === 'url') {

 			if (filter_var($string, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED)) return TRUE;

 		}

 		elseif ($type === 'domain') {

 			if (filter_var('http://'.$string, FILTER_VALIDATE_URL)) if (strpos($string, '.')) return TRUE;

 		}

 		elseif ($type === 'email') {

 			if (filter_var($string, FILTER_VALIDATE_EMAIL)) return TRUE;

 		}

 		// Regex control //
 		/*elseif ($type === 'regex') {

 			$arg = (string) $arg;

 			// We replace everything correctly
 			$arg = str_replace('\[', '[', $arg);
 			$arg = str_replace('\]', ']', $arg);

 			echo $arg; die();

 			if (preg_match($arg, $string)) return TRUE;

 		}*/

 		//////////
 		// SLOW //
 		//////////

 		elseif ($type === 'date_relative') {

 			if ($this->lbl_semantic->_DATE_RELATIVE($string)) return TRUE;

 		}

 		elseif ($type === 'language') {

 			if ($this->lbl_semantic->_LANGUAGE_TO_SIGN($string)) return TRUE;

 		}

 		elseif ($type === 'calc') {

 			if ($this->lbl_semantic->_CALC($string)) return TRUE;

 		}


 		return FALSE;

 	}

	/**
 	*
 	* Get importance and number of it for SQL insertion and ORDER (very important)
 	*
 	* @param array $arr with all the strong-types of this entry, ordered by variable
 	* @return array (int) highest_type, (int) num_highest_type
 	*
 	*/
 	public static function get_strong_type_anti_conflict_array($arr) {

 		if (empty($arr)) return array('highest_type' => 0, 'num_highest_type' => 0);

 		$special_list = array( // 0
 			'string',
 			'sentence',
 			'regex'
 			);

 		$data_type_list = array( // 1
 			'int',
 			'not_int',
 			'number'
 			);

 		$pure_size_list = array( // 2
 			'max_size',
 			'min_size'
 			);

 		$word_size_list = array( // 3
 			'word'
 			);

 		$semantic_list = array( // 4
 			'ip',
 			'url',
 			'domain',
 			'date_relative',
 			'language',
 			'calc'
 			);

 		$special_num = 0;
 		$data_type_num = 0;
 		$pure_size_num = 0;
 		$word_size_num = 0;
 		$semantic_num = 0;

 		// Let's dissect this big array
		foreach ($arr as $row) {

 			foreach ($row as $variable => $value) {

 				if (in_array($variable, $special_list)) $special_num++;
 				if (in_array($variable, $data_type_list)) $data_type_num++;
 				if (in_array($variable, $pure_size_list)) $pure_size_num++;
 				if (in_array($variable, $word_size_list)) $word_size_num++;
 				if (in_array($variable, $semantic_list)) $semantic_num++;

 			}

 		}

 		// Order of this elseif isn't random, don't touch it if you don't know.
 		if ($semantic_num > 0) $final_array = array(4, $semantic_num);
 		elseif ($word_size_num > 0) $final_array = array(3, $word_size_num);
 		elseif ($pure_size_num > 0) $final_array = array(2, $pure_size_num);
 		elseif ($data_type_num > 0) $final_array = array(1, $data_type_num);
 		elseif ($special_num > 0) $final_array = array(0, $special_num);
 		else $final_array = array('', '');

 		return array(
 			'highest_type' => $final_array[0],
 			'num_highest_type' => $final_array[1]
 			);

 	}

	/**
 	*
 	* Check strong type per needle
 	*
 	* @param array $var_array text to check ordered by variable name
 	* @param array with strong type by variable name
 	* @return bool
 	*
 	*/
 	public function check_strong_type($var_array, $strong_types) {

 		if ($strong_types === NULL) return TRUE; // If there isn't any strong type to check

 		foreach ($strong_types as $variable => $value) {

 			//if (!isset($strong_types[$variable])) return FALSE; // Added 27/03/2013 (quite new) -> if the strong type doesn't exist we set FALSE

 			foreach ((array) $strong_types[$variable] as $condition => $argument) {

 				/*var_dump($var_array[$variable]);
 				var_dump($condition);
 				var_dump($argument);*/

 				if (!$this->is_correct_strong_type($var_array[$variable], $condition, $argument)) return FALSE;

 			}

 		}

 		return TRUE;

 	}

	/**
 	*
 	* Get strong type from a string (such as $hello[string] -> [position] = 'string')
 	*
 	* @param string $string to analyze
 	* @return array with strong type by position
 	*
 	*/
 	public static function get_strong_type($string) {

 		$string = trim($string); // We trim otherwise it would produce some bugs

 		$arr_string = explode(" ", $string);
 		$final_array = array();

 		// Let's search trough this string to set our array
 		foreach ($arr_string as $row) {

 			// It's a dynamic word
 			if ($row[0] === '$') {

 				// There's a strong type definition to get
 				if ((strpos($row, '[')) && (strpos($row, ']'))) {

 					$name_var = substr(strstr($row, '[', TRUE), 1);

 					while (!empty($row)) {

 						$label = find($row, '[', ']'); // We set the label

 						if (strpos($label, ':')) { // The label is word_min:2 it means there are args, we should reset the label

 							$label_pre = $label;
 							$label = strstr($label, ':', TRUE);
 							$arg = substr(strstr($label_pre, ':'), 1); // We trim ':' and 'arg' will stay

 							/*
 							 *
 							 * SPECIAL CASE : If it's a regex strongtype
 							 * Wwe must decode (with our home encoding system) before insert it in your database (get_strong_type will be mainly used for this)
 							 *
 							 */

 							if ($label === 'regex') $arg = decode($arg);

 						} else $arg = '';

 						$final_array[$name_var][$label] = $arg;
 						$row = substr($row, (strpos($row, ']')+1)); // Multiple strong-type case (we delete the one which's already done)


 					}

 				}

 			}


 		}

 		return $final_array;

 	}

	/**
 	*
 	* Clean strong type from a string (such as $hello[string] -> $hello)
 	*
 	* @param string $string contains LBL code
 	* @return array cleaned function listing
 	*
 	*/
 	public static function pick_functions($string) {

 		$functions_output = array();

		while ($possible_function = find($string, '{', '}', 0)) {

			// We remove our function as a function
			$string = str_replace_once('{', '', $string);
			$string = str_replace_once('}', '', $string);

			// If there are arguments in the function, we should cut it again to be clean
			if (strpos($possible_function, ':')) $possible_function = strstr($possible_function, ':', TRUE);

			// It's a clean function we get now
			$functions_output[] = $possible_function;

		}

		return $functions_output;

	}

	/**
 	*
 	* Clean strong type from a string (such as $hello[string] -> $hello)
 	*
 	* @param string $string to clean
 	* @return string cleaned
 	*
 	*/
 	public static function clean_strong_type($string) {

 		// Be careful and NEVER use $this->panda->clean inside this function (because of modeling autocomplete)

 		// Manage \[\] exceptions
 		//$string = $this->encrypt('[\\', $this->encrypt('\\]', $string));

 		$string = trim($string); // We trim the string otherwise it could make some bugs with the explode

 		$arr_string = explode(" ", $string);
 		$new_string = '';

 		// Let's search trough this string
 		foreach ($arr_string as $row) {

 			// Bug case
 			if (!isset($row[0])) $row[0] = '';

 			// It's a dynamic word
 			if ($row[0] === '$') {

 				// There's a strong type definition to clean
 				if ((strpos($row, '[')) && (strpos($row, '['))) $new_string .= strstr($row, '[', TRUE) . ' ';
 				else $new_string .= $row . ' ';

 			} else {

 				$new_string .= $row . ' ';

 			}

 		}

 		// Manage \[\] exceptions
 		//$new_string = $this->decrypt('[\\', $this->decrypt('\\]', $new_string));

 		return trim($new_string);

 	}

	/**
 	*
 	* Transform 'google $search' into 'google [search]' to be ready for autocomplete system
 	*
 	* @param string $char The string to convert
 	* @return string converted
 	*
 	*/
	public static function var_to_autocomplete($string) {

		$int = 0;
		$string_length = strlen($string);
		$new_string = '';

		while ($int < $string_length) {

			if ($string[$int] == '$') {

				$new_string .= '[';
				++$int;

				if (!isset($string[$int+1])) $string[$int+1] = ' ';

				while ($string[$int+1] != ' ') {

					$new_string .= $string[$int];

					++$int;
					if (!isset($string[$int+1])) break;

				}

				$new_string .= $string[$int];
				++$int;

				$new_string .= ']';

			}

			if (!isset($string[$int])) break;
			$new_string .= $string[$int];
			++$int;

		}

		return $new_string;

	}

	/**
 	*
 	* Convert an array (full of vars and functions) into a valid URL
 	*
 	* @param array $var_array contains variable into an array
 	* @param string $url url with variables and functions
 	* @param bool $urlencode if we need to urlencode our result
 	* @return string cleaned url ready to be launched
 	*
 	*/
	public function array_to_url($var_array, $url, $urlencode=TRUE) {

		// $var(sep) - We need to understand what the user wants
		$new_url = $this->understand_variables($var_array, $url, $urlencode);

		/*$this->benchmark = new CI_Benchmark();
		$this->benchmark->mark('start');

		$int = 0;
		while ($int < 10000) {
			$this->understand_functions($new_url);
			++$int;
		}

		$this->benchmark->mark('middle');

		echo 'Function process speed : ' . $this->benchmark->elapsed_time('start', 'middle') . '<br />';*/

		//echo $new_url; die();

		// {fun:arg,arg} - We need to take datas from our internal functions (depending on variables)
		$new_url = $this->understand_functions($new_url);

		return $new_url;

	}

	/**
 	*
 	* To understand $variable(+) structure
 	*
 	* @param array $array contains variables to understand
 	* @param string $string which contains variables to replace
 	* @param bool $urlencode if we need to urlencode our result
 	* @return string cleaned url ready for function to understand
 	*
 	*/
	private static function understand_variables($array, $string, $urlencode=TRUE) {

		// First of all we'll look into $vars(separator)
		$int = 0;
		$string_length = strlen($string);
		$new_string = '';

		while ($int < $string_length) {

			// There's a var and it's not a \$ exception
			if (($string[$int] == '$') && (!check_backslash_exception($string, $int))) {

				$actual_var = '';
				++$int;

				while ($string[$int] != '(') {

					$actual_var .= $string[$int];
					++$int;

					if ($int > $string_length) break;

				}

				// split '('
				++$int;

				if (isset($array[$actual_var])) {

					$actual_separator = '';

					while ($string[$int] != ')') {

						$actual_separator .= $string[$int];
						++$int;

						if ($int > $string_length) break;

					}

					// Prepare to supposed $urlencode, avoid encoding " " failure that disable dynamic separators ...
					$before_encode = $array[$actual_var];

					// Complex system to avoid "1&1" failure in URL
					if ($urlencode) {
						$before_encode = encrypt(" ", $before_encode);
						$before_encode = rawurlencode($before_encode);
						$before_encode = decrypt(" ", $before_encode);
					}

					$before_encode = str_replace(" ", $actual_separator, $before_encode);

					// We add it to the futur URL
					$new_string .= $before_encode;

				}

			} else $new_string .= $string[$int];

			++$int;

		}

		// We cut \$ exception manually before continuing through our system
		$new_string = str_replace('\\$', '$', $new_string);

		return $new_string;

	}

	/**
 	*
 	* Make from {IF:}{ELSE}{ENDIF} a {IF:}{THEN:}{ELSE}{THEN:}{ENDIF} 
 	* Which will be understood by our system
 	*
 	* @param string $content the source we'll check everything from
 	* @param string $condition contains the condition we are looking for
 	* @return string with all the {THEN} stuff
 	*
 	*/
 	public static function add_then_to_condition($source, $condition) {

 		/** 
 		*
 		* Why this shit ?
 		* -----
 		* IF/ELSE/ELSEIF regex detection has to be done once and it go through the entire source request
 		* so we set it to TRUE if the regex has been applied once and it doesn't have to be called twice
 		* otherwise there will be a motherfucker bug and multi {THEN:} everywhere.
 		*
 		* It also respects the core way of understanding the functions, it's not a random order i took
 		* Be careful editing this.
 		* -----
 		*
 		* I also use str_replace and sometimes preg_replace :
 		* str_replace runs faster so we should use it whenever it's possible (in some case below, it's quite possible)
 		*
 		*/
 		static $if = FALSE;
 		static $else = FALSE;
 		static $elseif = FALSE;

 		if (($condition === 'IF') && (!$if)) {

 		// The first replace to do
 		$source = preg_replace('#{IF:(.*)}#isU', '{IF:$1}{THEN:', $source);
 		$source = str_replace('{ENDIF}', '}{ENDIF}', $source); //$source = preg_replace('#{ENDIF}#isU', '}{ENDIF}', $source);

 		// We need to end the bracket of our {THEN:} in every case
 		$source = str_replace('{ELSEIF:', '}{ELSEIF:', $source); //$source = preg_replace('#{ELSEIF:#isU', '}{ELSEIF:', $source);
 		$source = str_replace('{ELSE}', '}{ELSE}', $source); //$source = preg_replace('#{ELSE}#isU', '}{ELSE}', $source);

 		$if = TRUE;

 		} elseif (($condition === 'ELSEIF') && (!$elseif)) {

 		// The first logical replace
 		$source = preg_replace('#{ELSEIF:(.*)}#isU', '{ELSEIF:$1}{THEN:', $source);

 		$elseif = TRUE;

 		} elseif (($condition === 'ELSE') && (!$else)) {

 		// The ending (ENDIF doesn't count because it has been replaced in our IF above)
 		$source = str_replace('{ELSE}', '{ELSE}{THEN:', $source); //$source = preg_replace('#{ELSE}#isU', '{ELSE}{THEN:', $source);

 		$else = TRUE;

 		}

 		return $source;

 	}

	/**
 	*
 	* Make from {IF:}{THEN:}{ELSE}{THEN:}{ENDIF} a {IF:}{THEN:}{ENDIF} 
 	* Execute this function only if you're sure the {THEN:} is the correct one to be executed
 	*
 	* @param string $content the source we'll check everything from
 	* @return string trimed string
 	*
 	*/
 	public static function skip_conditions_after_then($source) {

 		/** 
 		* We erase the condition system because we don't need anything else after the {THEN} call
 		* It includes all the functions that could be executed inside the others {THEN}
 		*/
 		$source = preg_replace('#{THEN:(.*)}(.*){ENDIF}#isU', '$1', $source);

 		return $source;

 	}


	/**
 	*
 	* To understand {FUNCTION:ARG,ARG} structure
 	*
 	* @param string $string contains functions to understand
 	* @param string $left start function delimiter
 	* @param string $right end function delimiter
 	* @return string cleaned url ready to be launched
 	*
 	*/
 	public function understand_functions($string, $left='{', $right='}') {

 		$actual_condition = NULL; // actual "undercondition" case
 		$validated_condition = NULL; // last validated condition

 		$default_phoenix_library = 'phoenix';

		// We'll search each {CONSTANT} possible in this system

 		while ($possible_function = find($string, $left, $right, 0)) {

 			$final_constant = '';
 			$original_raw_function = $possible_function;
 			$str_replace_already_done = FALSE;

			// Possible constants: {IP_ADDR} or {STR_LOW:this_is_a_string}
 			$actual_constant = $left.$possible_function.$right;

			// If there's an argument (this_is_a_string) then we return it in $argument
 			if (strpos($possible_function, ':')) {

				// Argument(s)
				$argument_raw = rawurldecode(substr($possible_function, (strpos($possible_function, ':')+1))); // This are arguments 'bidule, much, machin'

				// If there are deep similar function {URL:{IP_ADDR}} we need to check it now
				if (find($argument_raw, $left, $right, 1)) $argument_raw = $this->understand_functions($argument_raw);

				$argument_raw = encrypt('\,', $argument_raw); // \' exception

				// We delete all accents and all this shit that our server doesn't understand because it's a fucking scumbag.
				$arguments = explode(",", trim($argument_raw));
				$arguments = decrypt('\,', $arguments, ',');
				$arguments = array_map('trim', $arguments);

				$possible_function = trim(substr($possible_function, 0, strpos($possible_function, ':'))); // We need to change VAR:bidule to 'VAR'

			} else $arguments = array();

			$real_possible_function = '_'.$possible_function; // Added 14/01/2012 virtual understanding changed (avoid conflict)

			$conditions = array('IF', 'ELSE', 'ELSEIF');

			if (in_array($possible_function, $conditions)) {

			// The condition class to preload and launch
			if ($this->lbl_condition === NULL) $this->load->library('lbl/lbl_condition');

			Devlog::add('Conditional structure {'.$possible_function.'} in use.');

			// Make the {THEN:} system automatically with our algorithms

			$string = $this->add_then_to_condition($string, $possible_function);

			$actual_condition = $possible_function;

			if ($validated_condition === NULL) if (call_user_func_array(array($this->lbl_condition, $real_possible_function), $arguments)) $validated_condition = $possible_function;
			
			} elseif ($possible_function === 'ENDIF') { // We cancel every under-condition case

				$actual_condition = NULL;
				$validated_condition = NULL;

			} else {

				if ($actual_condition === $validated_condition) { // Condition system check

					/**
					*
					* Multi LBL libraries
					* On october 2013 we changed the Phoenix system and replaced it by a multi-libraries system
					* Each function has a type which matchs with a library to load
					* So we have to detect the correct library and load it
					*
					*/

					if ($type_from_lbl_function = $this->get_library_type_from_lbl_function($possible_function)) {

						$phoenix_library_name = 'lbl_'.$type_from_lbl_function;

						// If it fails here it means we didn't create all the classes for multi LBL libraries
						// Be careful with Mustache, don't declare a function type if it doesn't exist as library
						if ($this->$phoenix_library_name === NULL) $this->load->library('lbl/'. $phoenix_library_name);

					} else {

						Devlog::add_admin_red("The server can't recognize the LBL-library to load for the function {$possible_function}");
						$phoenix_library_name = $default_phoenix_library;

					}

					/**
					*
					* If this library exists we set it, otherwise we use the phoenix instance
					* In most of the case it will fail to launch the method, we could exit the system here
					* This system is useful if we want to test some LBL functions within phoenix before transfering within a classified library
					*
					*/

					if ($this->$phoenix_library_name !== NULL) $phoenix_instance = $phoenix_library_name;
					else $phoenix_instance = $default_phoenix_library;

					/**
					*
					* CONDITION CORE
					* If the function is a conditional function, we will add {THEN} to the first conditional structure
					* We will test everything and execute if we need it
					*
					*/

					if (method_exists($this->$phoenix_instance, $real_possible_function)) {


						// $phoenix_instance is the equivalent to the old $this->phoenix which was replaced by multi LBL libraries
						$final_constant = call_user_func_array(array($this->$phoenix_instance, $real_possible_function), $arguments);


						if ($possible_function === 'THEN') {

							/**
							 * If {THEN} is called, we don't need the rest of our condition structure
							 * We can regex-replace from {THEN} to the {ENDIF}
							 * -> This is only an optimization
							 */
							$string = $this->skip_conditions_after_then($string);
							Devlog::add_admin('The condition structure has been skipped from now');
							
						} else {

							/**
							 * LOGICAL REPLACE
							 *
							 * If we replace a function by its value effectively, we can replace all occurence.
							 * We also don't need to replace at the end of this function
							 * -> This is only an optimization, if it doesn't work, put str_replace_once at the end and that's it
							 *
							 */
							$string = str_replace($actual_constant, encrypt('{', encrypt('}', $final_constant)), $string);
							
						}

						$str_replace_already_done = TRUE;

						Devlog::add('Function {'.$original_raw_function.'} was called. It returned "'.$final_constant.'"');

					} else Devlog::add('Trying to call unknown function '.$possible_function.' there is no reply');


				}

			}
			
			if (!$str_replace_already_done) $string = str_replace_once($actual_constant, encrypt('{', encrypt('}', $final_constant)), $string);

			}

		// Clean/Recompose brackets were created to secure returned elements such as WHOIS() from {} annoying interpretation
		//$functions_return = decrypt('=', decrypt('{', decrypt('}', $string)));
		$functions_return = decrypt('{', decrypt('}', $string));

		return $functions_return;


	}

	/**
 	*
 	* To understand variables from $search (from database) and replaced by $string (what people taped) datas
 	*
 	* @param string $string contains cleaned search string (ex. hello @google)
 	* @param string $search contains variable string (ex. $search @google)
 	* @param string $var_sign variable start sign to detect
 	* @param string $str_separator words separator
 	* @return array which contains variable[label] = 'value'
 	*
 	*/
	public static function string_to_array($string, $search, $strong_types=array(), $var_sign='$', $str_separator=' ') {

		// THIS FUNCTION ISN'T TOTALLY DONE, WE SHOUDL REPLACE ALL THE ' ' PER $str_separator to be FLEXIBLE !

		/////// LEFT RIGHT SYSTEM /////////
		// -> To check '!traduire $bidule' and erase '!traduire' to avoid misunderstood after
		// -> It will return faster than the complete reader if there's no variable

		if ($search[0] !== $var_sign) { // If it doesn't start with a dynamic entry ($test !bidule)

			$pos = 0;
			$max = strlen($search);

			while ($pos < $max) {

				// If it's a variable
				if ($search[$pos] === $var_sign) {

					$search = substr($search, $pos);
					$string = substr($string, $pos); // We delete this shit from every variable, we're here to get variable no static datas
					break;

				}

				$pos++;

			}

		if (!isset($search[1])) return array(); // If there's nothing left, we should return directly.

		}

		/////// RIGHT LEFT SYSTEM /////////

		$string_array = explode(" ", $string);
		$search_array = explode(" ", $search);

 		$pos = count($search_array)-1;
		$pos2 = count($string_array)-1;
		$num_string = 0;
		$num_search = 0;;
		$var_array = array();
		$array_reverse = array();

		while ($pos >= $num_search) {

			// If it's a variable
			if ($search_array[$pos][0] === $var_sign) {

				$var_label = substr($search_array[$pos], 1);
				$var_array[$var_label] = '';
				$pos--;

				// If isn't set
				if (!isset($search_array[$pos])) $search_array[$pos] = '';
				if (!isset($string_array[$pos2])) $string_array[$pos2] = '';

				while (clean($string_array[$pos2]) != $search_array[$pos]) {

					$var_array[$var_label] = $string_array[$pos2] . ' ' . $var_array[$var_label];
					$pos2--;

					if (isset($search_array[$pos][0])) {

						if ($search_array[$pos][0] === $var_sign) {

							// Cool reversing system to set
							$array_reverse['to'][] = $var_label; // Var
							$array_reverse['from'][] = substr($search_array[$pos], 1); // Another var

							++$pos;
							++$pos2;
							break;

						} // If next word is $var, it's only one word duration, let's break it

					}

						if ($pos2 < $num_string) break;

				}

				$var_array[$var_label] = trim($var_array[$var_label]); //trim(htmlentities($var_array[$var_label]));

			}

			$pos2--;
			$pos--;

		}


/*
		$pos = 0;
		$pos2 = 0;
		$num_string = count($string_array);
		$num_search = count($search_array);
		$var_array = array();

		while ($pos < $num_search) {

			// If it's a variable
			if ($search_array[$pos][0] === $var_sign) {

				$var_label = substr($search_array[$pos], 1);
				$var_array[$var_label] = '';
				$pos++;

				// If isn't set
				if (!isset($search_array[$pos])) $search_array[$pos] = '';
				if (!isset($string_array[$pos2])) $string_array[$pos2] = '';

				while (clean($string_array[$pos2]) != $search_array[$pos]) {

					$var_array[$var_label] .= $string_array[$pos2] . $str_separator;
					$pos2++;

					if (isset($search_array[$pos][0])) if ($search_array[$pos][0] === $var_sign) { --$pos; --$pos2; break; } // If next word is $var, it's only one word duration, let's break it

					if ($pos2 >= $num_string) break;

				}

				$var_array[$var_label] = trim($var_array[$var_label]); //trim(htmlentities($var_array[$var_label]));

			}

			$pos2++;
			$pos++;

		}
*/
		// In case there are multiple variable we need to inject/empty some arrays (reversing length of everything, very sensitive)
		if (isset($array_reverse['to'])) {

			$num_reverse = count($array_reverse['from']) - 1;

			while ($num_reverse >= 0) {

				$label_from = $array_reverse['from'][$num_reverse];
				$label_to = $array_reverse['to'][$num_reverse];

				if ($pos_splitting = strpos($var_array[$label_from], ' ')) {

					//if ($label_from !== 'mot') { <---- this could have been our sentence system bug strong_types is really easy to use
					if (!isset($strong_types[$label_from]['sentence'])) { // [sentence] system test

						$var_array[$label_to] = trim(substr($var_array[$label_from], $pos_splitting) . ' ' . $var_array[$label_to]); // We add the corresponding words to the other vae
						$var_array[$label_from] = trim(substr($var_array[$label_from], 0, $pos_splitting)); // We trim into one word

					}


				}

				$num_reverse--;

			}

		}

		//echo ('<pre>'); var_dump($array_reverse); echo ('</pre>');
		//echo ('<pre>'); var_dump($var_array); echo ('</pre>');

		// Array which contains every var with label and each value
		return $var_array;


	}

	/**
 	*
 	* Like mask generator (you put 'word $var other_word' and it returns 'word % other_word')
 	*
 	* @param string $string to convert
 	* @return string mask generated
 	*
 	*/
	public static function mask_gen($string) {

		$tag_array = explode(" ", $string);

		$final_mask = '';

		foreach ($tag_array as $row) {

			$row = trim($row); // Clean deleted 13/12/12

			if ($row[0] === '$') $final_mask .= '% ';
			else $final_mask .= $row . ' ';

		}

		$final_mask = trim($final_mask);

		// Final split
		$final_mask = str_replace("%%", "%", $final_mask);

		return $final_mask;

	}

	/**
 	*
 	* Check if it's a valid like ($input is compared with a SQL Like alike set as $pattern)
 	*
 	* @param string $input string to check
 	* @param string $pattern mask string (SQL LIKE)
 	* @param string escape case
 	* @return bool
 	*
 	*/
	public static function like($input, $pattern, $escape = '\\') {

		if ($pattern === FALSE) return TRUE;

    	// Split the pattern into special sequences and the rest
		$expr = '/((?:'.preg_quote($escape, '/').')?(?:'.preg_quote($escape, '/').'|%|_))/';
		$parts = preg_split($expr, $pattern, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

	    // Loop the split parts and convert/escape as necessary to build regex
		$expr = '/^';
		$lastWasPercent = FALSE;
		foreach ($parts as $part) {
			switch ($part) {
				case $escape.$escape:
				$expr .= preg_quote($escape, '/');
				break;
				case $escape.'%':
				$expr .= '%';
				break;
				case $escape.'_':
				$expr .= '_';
				break;
				case '%':
				if (!$lastWasPercent) {
					$expr .= '.*?';
				}
				break;
				case '_':
				$expr .= '.';
				break;
				default:
				$expr .= preg_quote($part, '/');
				break;
			}
			$lastWasPercent = $part == '%';
		}
		$expr .= '$/i';

   		 // Look for a match and return bool
		return (bool) preg_match($expr, $input);

	}

	/**
 	*
 	* Check if a string is alphanumeric (with exceptions)
 	*
 	* @param string $str string to check
 	* @return bool
 	*
 	*/
	public static function is_alphanumeric($str, $exceptions=array()) {

		$default_exceptions = array('-', '_');

		$final_exceptions = array_merge(

			$default_exceptions,
			$exceptions

			);

		// To add excptions, fill this '-' '_' array -> higly optimized to be as fast as possible
		if (ctype_alnum(str_replace($final_exceptions, '', $str))) return TRUE;
		else return FALSE;
	}

	/**
 	*
 	* URL Security by ASCII code (mainly to detect <> or any \r \n, invisible characters)
 	*
 	* @param string $url string to detect
 	* @return bool
 	*
 	*/
 	public static function secure_dyn_url($url) {

 		$int = 0;
 		$num = strlen($url);

 		while ($int < $num) {

 			$ascii = ord($url[$int]);

 			if (($ascii < 31) && ($ascii !== 10)) return FALSE; // 10 = \r\n
			//if ($ascii === 60) return FALSE; // <
			//if ($ascii === 62) return FALSE; // >
			//if ($ascii === 92) return FALSE; // \
			if ($ascii === 127) return FALSE; // DEL

			++$int;

		}

		return TRUE;

	}

	/**
 	*
 	* Trim \r \n
 	*
 	* @param string $string to trim
 	* @return string trimed
 	*
 	*/
	public static function trim_lines($string) {

		$string = str_replace("\r", "", $string);
		$string = str_replace("\n", "", $string);
		return $string;

	}

	/**
 	*
 	* Make the raw username pretty (to register a new user for example)
 	* - If we get something like iDrink we keep it this way
 	* - If we get something like bonjour we make a Bonjour which is prettier
 	*
 	* @param string $raw_username the raw username
 	* @return string pretty username
 	*
 	*/
	public function make_username_pretty($raw_username) {

		if ($this->is_part_uppercase($raw_username)) $pretty_username = $raw_username;
		else $pretty_username = ucfirst($raw_username);

		return $pretty_username;

	}

	/**
	 * Delete the edition mode from sessions
	 *
	 * @access	public
	 * @return	void
	 */
	public function delete_edition_mode() {

		$this->pikachu->multi_delete(

			array('canEdit', 'edit_string', 'edit_url', 'edit_id')

		);

	}

	/**
 	*
 	* Originally when we add a tag, this check an url and correct it to fit within a redirect()
 	* Example : www.example.com will become http://www.example.com
 	*
 	* @param string $url the url to check/change
 	* @return string with the valid url
 	*
 	*/
	public static function check_and_correct_url($url) {

		if (strpos($url, 'www.') === 0) $url = 'http://' . $url;
		elseif (strpos($url, 'ftp.') === 0) $url = 'ftp://' . $url;
		elseif (strpos($url, 'mail.') === 0) $url = 'smtp://' . $url;

		return $url;

	}

	/**
 	*
 	* Check if there's any part of the string which contains upper-cased characters
 	*
 	* @param string $string to check
 	* @return bool
 	*
 	*/
	public static function is_part_uppercase($string) {

		return strtolower($string) !== $string;
	
	}

}

?>