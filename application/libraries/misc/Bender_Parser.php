<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Bender Parser
|--------------------------------------------------------------------------
|
| @author Jeremie Ges
| @date 02/06/2013
| @version 0.1
| @require DOM 
|
| A powerful library to parse HTML & XML files like a boss
| 
| 
| To Do :
| Xml system (with loadXML)
| Load local ressouces
| Add flexibility with name attribue "LIKE system"
*/
class Bender_Parser {

	private $url = ''; // Url to parse
	private $selector = ''; // Pattern selector
	private $errors = ''; // When you suck
	private $results_raw = array(); // Results of parsing (Node List Object)
	private $results; // Final results
	private $output = 'array';
	private $html_datas = ''; // Load html string

	public function __construct() {

		// Check if you can use this beautiful library
		if (! $this->_can_load()) {

			throw new Exception("To use Bender Parser you must load DOM & CURL in your php config");

		}

	}


	/*
	|--------------------------------------------------------------------------
	| Initialize
	|--------------------------------------------------------------------------
	|
	| Initialize preferences
	| 
	| @params
	| <array>
	| url - The url to parse
	| selector - Selector to use when you parse datas
	*/
	public function initialize($params=array()) {

		if (count($params) > 0) {

			foreach ($params as $key => $value) {

				if (isset($this->$key)) {

					$this->$key = $value;

				}

			}

		}

	}

	/*
	|--------------------------------------------------------------------------
	| Set Url
	|--------------------------------------------------------------------------
	|
	| Another way to initialize preferences with chaining system
	| 
	| @params
	| url - The url to parse
	*/
	public function set_url($url) {

		$this->url = $url;
		return $this;

	}

	/*
	|--------------------------------------------------------------------------
	| Set Selector 
	|--------------------------------------------------------------------------
	|
	| Another way to initialize preferences with chaining system
	| 
	| @params
	| selector_pattern - Selector to use when you parse datas
	*/
	public function set_selector($selector_pattern) {

		$this->selector = $selector_pattern;
		return $this;

	}

	/*
	|--------------------------------------------------------------------------
	| Set Html
	|--------------------------------------------------------------------------
	|
	| With this method you can load "local" html datas
	| 
	| @params
	| selector_pattern - Selector to use when you parse datas
	*/
	public function set_html($html_datas) {

		$this->html_datas = $html_datas;
		return $this;

	}

	/*
	|--------------------------------------------------------------------------
	| Output
	|--------------------------------------------------------------------------
	|
	| Choose ouput way
	| 
	| @params
	| output - json - default value : array
	*/
	public function output($output) {

		if ($output == 'json') {

			$this->ouput = 'json';

		}

		return $this;

	}

	/*
	|--------------------------------------------------------------------------
	| _Load datas
	|--------------------------------------------------------------------------
	|
	| Manage local/remote datas
	| 	
	*/
	private function _load_datas() {

		if (!empty($this->html_datas)) {

			return $this->html_datas;

		}

		if (!empty($this->url)) {

			if ($load_url = $this->_file_get_contents($this->url)) {

				return $load_url;

			}

			$this->_add_error('Unable to request url : ' . $this->url);

			return false;

		}

		$this->_add_error('No datas to parse');
		return false;
	}

	/*
	|--------------------------------------------------------------------------
	| Run 
	|--------------------------------------------------------------------------
	|
	| It's here where the magic happens
	| 
	*/
	public function run() {
		
		if (! empty($this->selector)) {

			$html_datas = $this->_load_datas();


			// Require dom and load html datas
			$dom = new DomDocument();

			// Fuck you syntax errors
			@$dom->loadHTML($html_datas);

			// Hello xpath
			$xpath = new DomXpath($dom);

			$query = $this->_selector_to_xpath($this->selector);

			// Silent mode dude, i manage error just after
			@$datas = $xpath->query($query);

			if ($datas) {

				// Stock object node list
				$this->results_raw = $datas;


			} else {

				$this->_add_error('The xpath expression is wrong');

			}

		} else {

			$this->_add_error('You must config selector pattern');

		}

		// Chaining
		return $this;

	}

	public function results($type='node_value') {

		$out = array();

		foreach ($this->results_raw as $result) {

			if ($type == 'node_value') {

				// The node value
				$out[] = $result->nodeValue;

			} elseif ($type == 'html') {

				// The html
				$out[] = htmlentities($result->c14n());

			} else {

				// By attribute
				$out[] = $result->getAttribute($type);

			}

		}

		// Affect to results
		$this->results = $out;

		// Send object for chaining
		return $this;

	}

	public function find($type='all') {

		// No results dude -> false !
		if (! isset($this->results[0])) return false;

		switch ($type) {

			case 'first':
			return $this->results[0];
			break;

			case 'all':

			if ($this->output == 'json') {

				return json_encode($this->results);

			}

			return $this->results;

			break;

			case 'last':

			// -1 it's a little hack
			return $this->results[count($this->results)-1];
			break;


		}

		// Your last chance !
		if (is_numeric($type)) {

			if (isset($this->results[$type])) {

				return $this->results[$type];

			}

		}

		// Read the fucking manual
		return false;

	}

	/*
	|--------------------------------------------------------------------------
	| _Selector to xpath
	|--------------------------------------------------------------------------
	|
	| Convert home made selector "language" to xpath
	| 
	| @params
	| selector - The selector to convert
	|
	| @memento xpath
	| //a -> Get all links
	| //a[@href] -> Get all links with href attributes
	| //a[@href:'minimal.html'] -> Get all links where href attribute = minimal.html
	| 
	| @memento bender language
	| a -> get all links
	| a[href] -> get all links with href attributes
	| a[href=minimal.html] -> Get all links where href attribute = minimal.html
	|
	| @infos about development 
	| 3 months ago i created a similar function for linkbreakers (the first instant search engine), i used
	| native functions php, with substr() & cie. Now i will use REGEX, because it's a powerful language 
	| to detect and replace occurences.
	| -
	| It was a beautiful idea, i reduce my old code (~90 lines to detect & replace)
	*/
	private function _selector_to_xpath($selector) {

		// Start pattern xpath (we are dropping 2 chars of pattern query !, yeah it's awesome ...)
		$out = '//';

		//------- Step 1 ------- 

		// Detect '[]' without '=' char
		$first_regex = '#\[[0-9A-Za-z]*={0}\]#';

		// Add '@' just after '[' char
		// I use anonymous function because it's more pretty than create_function()
		$first_replacement = function ($matches) { 

			return  '[@' . trim(strtr($matches[0], '[]', '  ')) . ']';

		};

		$selector = preg_replace_callback($first_regex, $first_replacement, $selector);

		//------- Step 2 ------- 

		$second_regex = '#\[[0-9A-Za-z]*=[a-zA-Z0-9._\-@ ]*\]#';

		$second_remplacement = function($matches) {
			
			// Replace '[' char with '[@'
			$value = '[@' . ltrim($matches[0], '[');

			// Replace first occurence of '=' char with '=\''
			$pos = strpos($value, '=');

			$value = substr_replace($value, '=\'', $pos, 1);

			// Replace the end ']' char with '\']'
			$value = rtrim($value, ']') . '\']';
			return $value;


		};

		$selector = preg_replace_callback($second_regex, $second_remplacement, $selector);

		//------- The end of cool story ------- 

		// Now we concat, it's finish, i will forget this with beer :p
		$out .= $selector;

		return $out;

	}

	/*
	|--------------------------------------------------------------------------
	| _Add error
	|--------------------------------------------------------------------------
	|
	| Simple function to add error 
	| 
	| @params
	| msg - Message to add
	*/
	private function _add_error($msg) {

		$this->errors[] = $msg;

	}

	/*
	|--------------------------------------------------------------------------
	| Display errors
	|--------------------------------------------------------------------------
	|
	| With this function you can display errors
	| 
	*/
	public function display_errors($return_array=false) {

		$out = '';

		if (!empty($this->errors)) {

			foreach ($this->errors as $error) {

				$out .= '<p>' . $error . '</p>';

			}

		}

		return $out;

	}



	/*
	|--------------------------------------------------------------------------
	| _File Get Contents
	|--------------------------------------------------------------------------
	|
	| It's a "fork" of file_get_contents php function with Curl library
	| It's more faster than the native function
	|
	| @params
	| selector_pattern - Selector to use when you parse datas
	*/
	private function _file_get_contents($url) {

		// Load the Curl session 
		$load = curl_init();

		// Quick fix https problem
		curl_setopt($load, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($load, CURLOPT_REFERER, $url);

		// Set curl to return the data instead of printing it to the browser. 
		curl_setopt($load, CURLOPT_RETURNTRANSFER, true); 

		// Follow if redirect
		curl_setopt($load, CURLOPT_FOLLOWLOCATION, true);

		// Set the URL 
		curl_setopt($load, CURLOPT_URL, $url); 


		// Execute baby
		$datas = curl_exec($load); 

		// Close the connection 
		curl_close($load);

		if (!$datas) return false;

		return $datas;

	}


	/*
	|--------------------------------------------------------------------------
	| _Can Load
	|--------------------------------------------------------------------------
	|
	| When library is loading this method check if you can use Bender Parser
	| 
	*/
	private function _can_load() {

		if (extension_loaded('dom') && extension_loaded('curl')) return true;

		return false;
	}

}

