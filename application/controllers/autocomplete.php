<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Autocomplete controller
 *
 * Everything linked with the input autocomplete side of Linkbreakers
 *
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Controller
 *
 */

class Autocomplete extends CI_Controller {

	public function __construct() {

		parent::__Construct();

		// Load models
		$this->load->model('autocomplete_model');

		// Set page
		$this->template->set('page', 'autocomplete');

	}

	/**
	 * Disable access to the /autocomplete/ simple route
	 *
	 * @access	public
	 * @return	void
	 */
	public function index() {

		exit();

	}

	/**
	 * Search matches troughout our database and return it in JSON
	 *
	 * @access	public
	 * @param string $type (e.g 'search', 'alias', 'from_user') which kind of autocomplete are we looking for ?
	 * @param integer $opt if it's from_user we should find an id
	 * @return void
	 */
	public function search($type=FALSE, $opt=FALSE) {

		if ($type === FALSE) $type = 'search';

		// Micro-config
		$min_chars = 1;
		$max_results = 9;
		$prefix = NULL; // Prefix we'll add to our results (originally made for '@')

		// Chars treatment
		$search_string = $this->_autocomplete_treatments($this->input->get('q'));

		// We set conditions to execute everything, depending of our type specifications
		if ($type === 'search') {

			if (!$search_string) return;
			if (strlen($search_string) < $min_chars) return;
			if ($search_string === '[') return;
			if ($search_string === ']') return;

		} elseif ($type === 'alias') {

			if (!$search_string) return;
			if (strlen($search_string) < $min_chars) return;
			if (substr($search_string, 0, 1) != '@') return;
			if ($search_string === '@[') return;
			if ($search_string === '@]') return;

			$prefix = '@';

		} elseif ($type === 'from_user') {

			if (!$search_string) return;
			if (strlen($search_string) < $min_chars) return;
			if ($search_string === '[') return;
			if ($search_string === ']') return;

		}

		$get_specifications = array(
			'search_string' => $search_string,
			'type_autocomplete' => $type,
			'max_results' => $max_results,
			'id_from_user' => $opt
		);

		// Get SQL result
		$array_sql = $this->autocomplete_model->get_autocomplete($get_specifications);

		// Init tab
		$return_datas = array();

		// Display everything (raw)
		foreach ($array_sql as $row) {

			$end_result = $row['autocomplete'];
			$return_datas[] = "$prefix$end_result";

		}

		echo json_encode($return_datas);

	}

	/**
	 * We treat the search string before checking it
	 *
	 * @access	public
	 * @param string $string_raw the raw string to check
	 * @return string
	 */
	protected function _autocomplete_treatments($string_raw) {

		$string = str_replace('  ', ' ', $string_raw);
		$string = trim($string);

		$string = utf8_decode($string);
		$string = addslashes($string);

		return $string;

	}

}