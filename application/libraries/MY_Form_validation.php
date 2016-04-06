<?php

class MY_Form_validation extends CI_Form_validation {

	public function __construct() {

		parent::__construct();

		// Remove <p> and </p> delimiters
		$this->set_error_delimiters('', '');

	}

	public static function set_post($post, $value) {

		$_POST[$post] = $value;

	}

	public static function set_get($get, $value) {

		$_GET[$get] = $value;

	}

	public function set_value($field = '', $default = '') {

		if (count($_POST) == 0){
			return $default;
		}

		if (!isset($this->_field_data[$field])) {

			$this->set_rules($field, '', '');

			if ($this->_field_data[$field]['is_array']) {

				$keys = $this->_field_data[$field]['keys'];
				$value = $this->_traverse_array($_POST, $keys);

			} else {
				$value = isset($_POST[$field])? $_POST[$field] : FALSE;
			}

			if ($value === FALSE) {

				return $default;

			} else {

				$this->_field_data[$field]['postdata'] = form_prep($value, $field);

			}
		}

		return parent::set_value($field, $default);
	}

	public function _traverse_array($array, $keys) {

		foreach($keys as $key) {

			if (!isset($array[$key])) return FALSE;
			$array = $array[$key];

		}

		return $array;
	}


    public function run($module = NULL, $group = '') {        

    	if (is_object($module)) $this->CI =& $module;
        return parent::run($group);
        
    }


	// --------------------------------------------------------------------
    
    /**
     * Validate URL
     *
     * @access    public
     * @param    string
     * @return    string
     */
    public static function valid_url($url) {
    	
        $pattern = "/^([a-z][a-z0-9\*\-\.]*):\/\/(?:(?:(?:[\w\.\-\+!$&'\(\)*\+,;=]|%[0-9a-f]{2})+:)*(?:[\w\.\-\+%!$&'\(\)*\+,;=]|%[0-9a-f]{2})+@)?(?:(?:[a-z0-9\-\.]|%[0-9a-f]{2})+|(?:\[(?:[0-9a-f]{0,4}:)*(?:[0-9a-f]{0,4})\]))(?::[0-9]+)?(?:[\/|\?](?:[\w#!:\.\?\+=&@!$'~*,;\/\(\)\{\}\[\]\-]|%[0-9a-f]{2})*)?$/xiS";
        
        if (!preg_match($pattern, $url)) return FALSE;

        return TRUE;
    }
    

}

?>