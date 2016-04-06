<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Internal Linkbreakers APIs
 *
 * Here we launch our Linkreakers APIs
 *
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Controller
 *
 */

class Api extends CI_Controller {

	public function __construct() {

		parent::__Construct();

	}

	/**
	 * Entry point of the API, it returns a formatted error (JSON)
	 *
	 * @access	public
	 * @return	json
	 */
	public function index() {

		$arr = array('error' => 'what are you doing dude ?!');
		echo json_encode($arr);
		return TRUE;

	}

	/**
	 * Research API - Get last research details in JSON
	 * Useful for the JavaScript framework to communicate with PHP (ex. Clever returns)
	 *
	 * @access	public
	 * @version 0.0.1
	 * @return json
	 */
	public function research() {

		$api_result['_success'] = TRUE;

		// If there's a data in our session we want to put
		if (isset($_SESSION['research'])) $api_result['research'] = $_SESSION['research'];
		else $api_result['_success'] = FALSE;

		// We destroy our last research information after we got it
		//$this->pikachu->destroy('research');
		// -> We don't destroy it, because it's used in many things now

		echo json_encode($api_result);

		return TRUE;

	}

	/**
	 * Prefs API - Get user settings in JSON
	 * Everytime we want to create a new (AJAX) setting we need to add it to the $prefs_list (setter control / getter sending)
	 *
	 * @access	public
	 * @param string $action do you want to get or set in the API
	 * @param string $detail which prefs do you want to set or get datas from ? (ex. prefs_default_result_enabled)
	 * @param string $content in case we want to set it, which data do you want to put ? (usually 0, 1 depending our securities)
	 * @version 0.0.1
	 * @return json
	 */
	public function prefs($action='', $detail=NULL, $content=NULL) {

		//$this->load->library('volt/mustache');

		$api_result = NULL;
		$prefs_list = array('prefs_default_result_enabled', 'prefs_smart_domains_enabled', 'prefs_clever_returns_enabled');

		if ($userid = $this->pikachu->show('userid')) {

				$this->load->model('log_model');

			if ($action === 'get') {

				$api_result['_success'] = TRUE;

				$api_result = $this->log_model->get_user_detail('prefs', $userid, $prefs_list);

			} elseif ($action === 'set') {

				$prefs_detail_array = $prefs_list;
				$prefs_content_array = array('0', '1');

				if ((in_array($detail, $prefs_detail_array)) && (in_array($content, $prefs_content_array))) {

					$this->log_model->set_user_detail('prefs', $userid, $detail, $content);

					$api_result['_success'] = TRUE;

				} else $api_result['_success'] = FALSE;

			}

		} else {

			$api_result['_success'] = FALSE;

		}

		echo json_encode($api_result);

		return TRUE;

	}

	/**
	 * Mustache API - Get LBL documentation in JSON
	 *
	 * Here's some examples :
	 *
	 * http://www.linkbreakers.com/api/mustache/all/function/
	 * http://www.linkbreakers.com/api/mustache/all/strongtype/
	 * http://www.linkbreakers.com/api/mustache/all/function/order_by/type/
	 * http://www.linkbreakers.com/api/mustache/all/function/order_by/stability/
	 * http://www.linkbreakers.com/api/mustache/all/strongtype/order_by/genre/
	 *
	 * @access	public
	 * @param string $syst what we want to get from the API
	 * @param string $type strong-type / function
	 * @param string $focus peculiar entity (e.g 'lburl' function) to get or a special ask (e.g. 'order_by')
	 * @param string $opt_detail only used if $focus is converted to an option (special ask) l.153~
	 * @version 0.0.2
	 * @return JSON
	 */
	public function mustache($syst='all', $type=NULL, $focus=NULL, $opt_detail=NULL) {

		$opt = NULL;

		$this->load->library('volt/mustache');

		// We check if there's a special ask within the $focus
		if ($focus === 'order_by') {

			$opt = $focus;
			$focus = NULL;

		}

		if ($syst === 'all') {

			if ($type === NULL) {

				$result = $this->mustache->get_datas();

			} else {

				if ($focus === NULL) {

					if ($opt === NULL) $result = $this->mustache->get_datas($type);
					else $result = $this->mustache->get_datas($type, $opt, $opt_detail);

				} else $result = $this->mustache->focus_datas($type, $focus);

			}

			echo $result;

		}

		return TRUE;

	}

}