<?php

class MY_Controller extends CI_Controller {

	private $imported;
	private $imported_functions;

	public function __construct() {

		parent::__Construct();
		
		$this->imported	= array();
		$this->imported_functions = array();

	}

	/* Not currently in use in Linkbreakers */
	protected function imports($object) {
		
		// the new object to import
		$new_import = new $object();

		// the name of the new object (class name)
		$import_name = get_class($new_import);

		// the new functions to import
		$import_functions = get_class_methods($new_import);

		// add the object to the registry
		array_push($this->imported, array($import_name, $new_import));

		// add the methods to the registry
		foreach($import_functions as $key => $function_name) {

			$this->imported_functions[$function_name] = &$new_import;

		}
		
	}

	public function __call($method, $args) {

		// make sure the function exists
		if(array_key_exists($method, $this->imported_functions)) {

			$args[] = $this;
			// invoke the function
			return call_user_func_array(array($this->imported_functions[$method], $method), $args);
		
		}

		throw new Exception ('Call to undefined method/class function: ' . $method);
	}

}