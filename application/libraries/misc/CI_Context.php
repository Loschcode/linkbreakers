<?php
/*
|--------------------------------------------------------------------------
| CI_Context library
|--------------------------------------------------------------------------
|
| Once upon a time this class came to life to get your CI context.
|
| @author Jeremie Ges / pimped by Laurent Schaffner
| @date 25/02/2013
| @version 0.1
|
*/
class CI_Context {

	protected $CI;

	public function __construct() {

		// We load the CodeIgniter instance
		$this->CI =& get_instance();

	}

	/*
	 *
	 * Auto-loads
	 *
	 */
	public function __get($key) {

		// If it exists we return it
		if (isset($this->CI->$key)) return $this->CI->$key;

		// Otherwise we check for a model
		elseif (strpos($key, '_model') !== FALSE) $this->CI->load->model($key);

		// It's a LBL library (be careful, don't move it before _model, it cans bug our system)
		elseif (strpos($key, 'lbl_') !== FALSE) {

			$this->CI->load->library('lbl/'.$key);

		}

		// If it doesn't match with anything, it's certainly a library
		else $this->CI->load->library($key);

		// We return it at the end
		return $this->CI->$key;

	}

}

?>