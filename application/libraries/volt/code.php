<?php

/**
 * Code class
 *
 * The LBL code coloration and shaping
 *
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Class
 *
 */

class Code extends CI_Context {

	public function __construct() {

		parent::__Construct();

	}


	/**
	 * Global STRING structuration scope
	 * This is the entry point of the code system
	 *
	 * @access public
	 * @param string $raw_code LBL code
	 * @return void
	 */
	public function make_string_shaping($raw_code) {

		// The content we will transfert through the template
		$content = array();

		// We put the raw code
		$transform_code = $raw_code;

		/**
		 *
		 * Code coloration system
		 *
		 */

 		// The $variable
  		$transform_code = preg_replace('#\$(.*)([[:blank:]]|$)#isU', '<span class="code_string_variable">\$$1</span>$2', $transform_code);

 		// The [strongtype:XXX]<span>
  		$transform_code = preg_replace('#\[(.*)\]</span>#isU', '<span class="code_string_variable_strongtype">[$1]</span>', $transform_code);

 		// We put the raw code
		$content['string'] = $transform_code;

		// We load the URL template
		$final_url_shape = $this->load->view('code/string_shape', $content, TRUE);

		// We return the CSS/HTML
		return $final_url_shape;

	}

	/**
	 * Global URL structuration scope
	 * This is the entry point of the code system
	 *
	 * @access public
	 * @param string $raw_code LBL code
	 * @return void
	 */
	public function make_url_shaping($raw_code) {

		// The content we will transfert through the template
		$content = array();

		// We put the raw code
		$transform_code = $raw_code;

		/**
		 *
		 * Code coloration system
		 *
		 */

		// The @linkedurl
		$transform_code = preg_replace('#^(@.*)$#isU', '<span class="code_url_linkedurl">$1</span>', $transform_code);

		// The #tools/XXX
		$transform_code = preg_replace('#(\#tools\/.*)([[:blank:]]|[\n])#isU', '<span class="code_url_tools">$1</span>$2', $transform_code);

		// The {FUN}
		// Must also be uppercased
 		$transform_code = preg_replace('#{([^:][A-Z0-9_]*)}#isU', '<span class="code_url_function">{$1}</span>', $transform_code);

		// The {FUN:XXX}
		// Must be uppercased (in two parts because the recursive system doesn't work well with regex)
 		$transform_code = preg_replace('#{([A-Z0-9_]*):#isU', '<span class="code_url_function">{$1:</span>', $transform_code);
 		$transform_code = preg_replace('#([^\\\])}#', '$1<span class="code_url_function">}</span>', $transform_code);

 		// The $var(XXX)
 		// \$ exception
  		$transform_code = preg_replace('#([^\\\])\$(.*)\((.*)\)#isU', '$1<span class="code_url_variable">\$$2(<span class="code_url_variable_inner_brackets">$3</span>)</span>', $transform_code);

		// The /* comments */
		$transform_code = preg_replace('#\/\*(.*)\*\/#isU', '<span class="code_url_comments">/*$1*/</span>', $transform_code);

  		/**
  		 *
  		 * Format system
  		 * NOTE : I'm not sure i should put it here, it will replace the \r\n by <br /> and things like that
  		 *
  		 */

  		// \r\n by HTML new lines
  		//$transform_code = str_replace("\n", '<div class="code_new_line">&nbsp;</div>', $transform_code);
  		$transform_code = str_replace("\n", '<br />', $transform_code);

 		// We put the raw code
		$content['url'] = $transform_code;

		// We load the URL template
		$final_url_shape = $this->load->view('code/url_shape', $content, TRUE);

		// We return the CSS/HTML
		return $final_url_shape;

	}

}