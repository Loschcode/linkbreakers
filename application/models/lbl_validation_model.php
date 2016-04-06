<?php

/**
 * Lbl_Validation library
 *
 * Every LBL rules for form_validation (input control)
 *
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Class
 *
 */

class LBL_Validation_Model extends MY_Model {

	public function __construct() {

		parent::__Construct();

	}

	public function validate_lbl($arr) {

		$this->load->library('form_validation');

		foreach ($arr as $field => $rules) {

			/**
			 *
			 * rules[0] is the label (such as 'space description')
			 * rules[1] contains the rules
			 *
			 */
			$this->form_validation->set_rules($field, $rules[0], $rules[1]);

		}

		return $this->form_validation->run($this);

	}


	/**
	 *
	 * INSERTION MULTICHECK FUNCTIONS : STRING/URL FIELD SECURITIES
	 * ------
	 * 
	 * - General check
	 * - Tools/Functions/Variables format
	 * - Database control
	 *
	 */

	///////////////////
	// GENERAL CHECK //
	///////////////////

	/**
	 * Check if there're forbidden char in the URL, such as < or > or any invisible things
	 *
	 * @access	public
	 * @param string the $str we are looking through
	 * @return bool
	 */
	public function secure_dyn_url($str) {

		if (!$this->panda->secure_dyn_url($str)) {

			$this->form_validation->set_message('secure_dyn_url', 'This %s entry contains forbidden characters.');
			return FALSE;

		} else {

			return TRUE;

		}

	}

	/**
	 * Check if there're forbidden char in the STRING, such as double-quotes (big fail in our system)
	 *
	 * @access	public
	 * @param string the $str we are looking through
	 * @return bool
	 */
	public function secure_string($str) {

		if (strpos($str, '"')) {

			$this->form_validation->set_message('secure_string', 'This %s entry contains forbidden characters (").');
			return FALSE;

		} else {

			return TRUE;

		}

	}

	/**
	 * Check double spaces
	 *
	 * @access	public
	 * @param string the $str we are looking through
	 * @return bool
	 */
	public function double_spaces($str) {

		if (strpos($str, "  ")) {

			$this->form_validation->set_message('double_spaces', 'The %s field cannot contain double spaces between words');
			return FALSE;

		} else {

			return TRUE;

		}

	}

	/**
	 * Check if there are only vars and no static datas
	 *
	 * @access	public
	 * @param string the $str we are looking through
	 * @return bool
	 */
	public function var_restriction($str) {

		$str = trim($str);
		$str_array = explode(" ", $str);

		$num_words = count($str_array);
		$num_dyn_words = 0;

		foreach ($str_array as $row) {

			if ($row[0] === '$') $num_dyn_words++;

		}

		if ($num_dyn_words >= $num_words) {

			$this->form_validation->set_message('var_restriction', 'The %s field cannot contain only dynamic datas');
			return FALSE;

		} else {

			return TRUE;

		}


	}

	//////////////////////////////////////
	// TOOLS/FUNCTIONS/VARIABLES FORMAT //
	//////////////////////////////////////

	/**
	 * Check if there's an unknown tool trying to be added
	 *
	 * @access	public
	 * @param string the $str we are looking through
	 * @return bool
	 */
	public function tools_check($str) {

		if (is_intern_url($str)) {

			$first_word = substr($str, 1, (strpos($str, ' ')-1));
			$first_word_with_return = substr($str, 1, (strpos($str, "\n")-1));

			if ($this->panda->existing_tool($first_word)) return TRUE;
			elseif ($this->panda->existing_tool($first_word_with_return)) return TRUE;
			else {

				$this->form_validation->set_message('tools_check', 'The %s field contains unknown tool (#'.$first_word.')');
				return FALSE;

			}

		} else return TRUE;

	}

	/**
	 * Check the $variable format in STRING field
	 *
	 * @access	public
	 * @param string the $str we are looking through
	 * @return bool
	 */
	public function var_string_format($str) {

		$str = trim($str);

		$int = 0;
		$str_length = strlen($str);

		$this->form_validation->set_message('var_string_format', 'The %s field contains wrong variable format - note: $variable[type]');

		while ($int < $str_length) {

			if ($str[$int] === '$') {

				$small_name_check = '';
				++$int;

				if (!isset($str[$int])) return FALSE;

				while ($str[$int] != ' ') {

					$small_name_check .= $str[$int];

					++$int;

					if ($int >= $str_length) break;

				}

				// To edit if regex strong-type added
				//if (!$this->panda->is_alphanumeric($small_name_check, array('[', ']', ':'))) return FALSE; // Exceptions: strong type []


			}

			++$int;
		}

		return TRUE;

	}

	/**
	 * Check the $variable(separator) format in URL field
	 *
	 * @access	public
	 * @param string the $str we are looking through
	 * @return bool
	 */
	public function var_url_format($str) {

		$str = trim($str);

		// If there's a $var(format)
		$int = 0;
		$str_length = strlen($str);

		$this->form_validation->set_message('var_url_format', 'The %s field contains wrong variable format - note: $variable(format)');

		while ($int < $str_length) {

			if (($str[$int] === '$') && (!check_backslash_exception($str, $int))) {

				$small_name_check = '';
				++$int;

				while ($str[$int] !== '(') {

					// Small '$NAME(' check
					$small_name_check .= $str[$int];

					++$int;
					if ($int >= $str_length) return FALSE;

				}

				if (!$this->panda->is_alphanumeric($small_name_check)) return FALSE;

				while ($str[$int] != ')') {

					++$int;
					if ($int >= $str_length) return FALSE;

				}


			}

			++$int;

		}

		return TRUE;


	}

	/**
	 * Check comments format in URL field
	 *
	 * @access	public
	 * @param string the $str we are looking through
	 * @return bool
	 */
	public function comments_format($str) {

		$start_comment = substr_count($str, LBL_LEFT_COMMENT);
		$stop_comment = substr_count($str, LBL_RIGHT_COMMENT);

		if ($start_comment === $stop_comment) {

			return TRUE;

		} else {

			$this->form_validation->set_message('comments_format', 'The %s field contains invalid comments structure ('.LBL_LEFT_COMMENT.' example '.LBL_RIGHT_COMMENT.')');
			return FALSE;

		}

	}

	/**
	 * Check {FUNCTION} format in URL field
	 *
	 * @access	public
	 * @param string the $str we are looking through
	 * @return bool
	 */
	public function brackets_format($str) {

		$start_bracket = substr_count($str, '{');
		$stop_bracket = substr_count($str, '}');

		if ($start_bracket === $stop_bracket) {

			return TRUE;

		} else {

			$this->form_validation->set_message('brackets_format', 'The %s field contains invalid {FUNCTION} format');
			return FALSE;

		}

	}

	/**
	 * Check if the {FUNCTION} exists in LBL
	 *
	 * @access	public
	 * @param string the $str we are looking through
	 * @return bool
	 */
	public function lbl_function_existence($str) {

		return TRUE; /* TEMPORARY AUTO VALIDATION FOR DEBUGGING AND STUFF LIKE THIS */

		$this->load->library('volt/mustache');

		$functions_list = $this->panda->pick_functions($str);

		foreach ($functions_list as $function) {

			$result = $this->mustache->array_global('function', strtolower($function));

			if (isset($result['error'])) {

				$this->form_validation->set_message('lbl_function_existence', 'The %s field contains an unknown function ('.$function.')');
				return FALSE;

			}

		}

			return TRUE;

	}


	/**
	 * Check if the conditions system structure is valid
	 *
	 * @access public
	 * @param string the $str we are looking through
	 * @return bool
	 */
	public function check_conditions_structure($str) {

		$error = FALSE;
		$validation_message = 'The %s field contains an invalid conditionnal structure ';

		/*
		* A valid structure looks like :

		{IF:} if is ok

		{ELSEIF:} elseif is ok

		{ELSEIF:lol} another elseif is ok

		{ELSE} else is ok

		{ENDIF}

		*/

		$functions_list = $this->panda->pick_functions($str);

		if (in_array('IF', $functions_list)) {

			$dot_condition_pos = strpos($str, '{IF');

			// {IF:} structure check (':')
			if ($dot_condition_pos !== FALSE) {

				if ($str[$dot_condition_pos+3] != ':') {

				$validation_message_detail = 'missing ":"" at your {IF} structure';
				$error = TRUE;					

				}

			}

			// Endif check
			if (!in_array('ENDIF', $functions_list)) {

				$validation_message_detail = 'missing {ENDIF} at the end of your structure';
				$error = TRUE;

			} else {

				// Else has to be right before the endif
				if (in_array('ELSE', $functions_list)) {

					$else_position = (int) array_search('ELSE', $functions_list);
					$if_position = (int) array_search('IF', $functions_list);
					$possible_elseif_position = (int) array_search('ELSEIF', $functions_list);
					$endif_position = (int) array_search('ENDIF', $functions_list);

					if (($else_position > $endif_position) || ($else_position < $possible_elseif_position) || ($else_position < $if_position)) {

						$validation_message_detail = 'the {ELSE} function isn\'t at the right place';
						$error = TRUE;

					}

				}

			}

			// Elseif has to be in the fucking middle yo
			if (in_array('ELSEIF', $functions_list)) {

				$dot_condition_pos = strpos($str, '{ELSEIF');

				// {ELSEIF:} structure check (':')
				if ($dot_condition_pos !== FALSE) {

					if ($str[$dot_condition_pos+7] != ':') {

						$validation_message_detail = 'missing ":"" at your {ELSEIF} structure';
						$error = TRUE;					

					}

				}

				$if_position = (int) array_search('IF', $functions_list);
				$elseif_position = (int) array_search('ELSEIF', $functions_list);

				if ($if_position > $elseif_position) {

					$validation_message_detail = 'the {ELSEIF} function isn\'t at the right place';
					$error = TRUE;			

				}

			}

		}

		if ($error) {

			$this->form_validation->set_message('check_conditions_structure', $validation_message.' ('.$validation_message_detail.')');
			return FALSE;

		} else return TRUE;

	}

	/**
	 * Check if a variable doesn't get its double between STRING/URL $var/$var()
	 *
	 * @access	public
	 * @param string the $str we are looking through
	 * @return bool
	 */
	public function var_alone($str2) {

		// STRING post set
		$str = delete_double_spaces($this->input->post('string'));

		if (empty($str2)) return TRUE; // If the URL field is empty, we exit the check (because it causes some bugs)

		$str = trim($str);
		$str2 = trim($str2);

		$couple = array();

		// We will clean the strong type detection first
		$str = $this->panda->clean_strong_type($str);

		// STRING VARS CHECK AND VERIFICATION
		$str_array = explode(" ", $str);

		foreach ($str_array as $row) {

			if ($row[0] == '$') {

				$name = trim(substr($row, 1)); // convert $thing into 'thing'
				if (substr($row, -1, 1) == ')') substr($row, 0, strlen($row)-strpos($row, '('));  // if there's a $thing(separator), we need to trim the end () as well

				if (!isset($couple[$name])) $couple[$name] = array(); // Avoid 'scalar' error
				$couple[$name]['string'] = TRUE;

			}

		}

		// URL VARS CHECK AND VERIFICATION
		$int = 0;
		$num = strlen($str2);
		$name = '';

		if (empty($str2)) return FALSE; // If it's empty, it's not a good format

		// LINKEDURL EXCEPTIONS
		if ($str2[0] === '@') {

			while ($int < $num) {

				if ($str2[$int] == '[') {

					++$int;

					while ($str2[$int] != ']') {

						$name .= $str2[$int];
						++$int;

						if ($int >= $num) break;

					}

					if (!isset($couple[$name])) $couple[$name] = array(); // Avoid 'scalar' error
					$couple[$name]['url'] = TRUE;


					$name = '';

				}

				++$int;

			}

		} else { // NORMAL LINKS

			while ($int < $num) {

				if (($str2[$int] === '$') && (!check_backslash_exception($str2, $int))) {

					++$int;

					while ($str2[$int] != '(') {

						$name .= $str2[$int];
						++$int;

						if ($int >= $num) break;

					}

					if (!isset($couple[$name])) $couple[$name] = array(); // Avoid 'scalar' error
					$couple[$name]['url'] = TRUE;


					$name = '';

				}

				++$int;

			}

		}

		// We will check if every string/url gets its url/string
		$check = 0;

		foreach ($couple as $row) {

			if (isset($row['url'])) if (!isset($row['string'])) $check++;
			if (isset($row['string'])) if (!isset($row['url'])) $check++;

		}

		$this->form_validation->set_message('var_alone', 'The %s field contains single variables or miss some of them, check your variables names ('.$check.' are missing)');

		// If not each var is with another, they stayed into $couple and count is > 0
		if ($check > 0) {

			return FALSE;

		} else {

			return TRUE;

		}

	}

	//////////////////////
	// DATABASE CONTROL //
	//////////////////////

	/**
	 * Check if the LinkedURL isn't fake
	 *
	 * @access	public
	 * @param string the $str we are looking through
	 * @return bool
	 */
	public function check_linkedurl($str) {

		if ($str[0] !== '@') return TRUE;

		$autocomplete = substr($str, 1); // We split the @

		if (!$iduser = $this->pikachu->show('userid')) $iduser = 0;

		if ($this->general_model->find_id_by_autocomplete($autocomplete, $iduser) === 0) {

			$this->form_validation->set_message('check_linkedurl', 'This linked-%s entry didn\'t find its link, impossible to validate this entry.');
			return FALSE;

		} else {

			return TRUE;

		}

	}



}