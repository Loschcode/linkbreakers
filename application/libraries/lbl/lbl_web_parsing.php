<?php

/**
 * LBL Web Parsing class
 *
 * LBL web parsing functions
 *
 * @package 	LBL / Web parsing
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Class
 *
 */

class Lbl_web_parsing extends Genius {

	public function __construct() {

		parent::__Construct();

		Devlog::add_admin_yellow('LBL-Library '.__CLASS__.' has been initialized');

	}

	/*
	|--------------------------------------------------------------------------
	| Parser
	|--------------------------------------------------------------------------
	|
	| @author Jeremie Ges
	| @date 03/06/2013
	| @version 0.1
	| @require misc/Bender_Parser
	|
	| Function to parse HTML & XML datas
	| 
	| @params
	| $url - Url to load
	| $selector - The pattern for select datas
	| $attribute_retrieve - The thing do you want retrieve
	| $how_many - How many elements to retrieve ? (first/last/all/numeric_position)
	|
	| @todos
	| 1. System Cache URL
	*/
	public function _PARSER($url='', $selector='', $attribute_retrieve='', $how_many='') {

		// Little security to start the function
		if (!empty($url) && !empty($selector)) {

			// Load Bender Parser
			$this->load->library('misc/Bender_Parser');

			// Construct base query
			$query = $this->bender_parser
			->set_url($url)
			->set_selector($selector)
			->run();

			// Reset attribute retrieve
			if (empty($attribute_retrieve)) {

				$attribute_retrieve = 'node_value';

			}

			// Reset how_many
			if (empty($how_many)) {

				$how_many = 'first';

			}

			// Get results of parsing
			$results = $query->results($attribute_retrieve)->find($how_many);

			// Enjoy !
			return $results;

		}

		// Read the fucking manual bro
		return false;

	}

	public function _HTML($url='', $pattern='', $take_attribute='value', $limit=1) {
		if (empty($url) OR empty($pattern)) return false;

		$url = str_replace(' ', '+', $url);

		$len = strlen($pattern);
		$chaining[] = '';
		$in_bracket = false;
		$last_tmp_pos = 0; 
		$i = 0;
		$segment_current = 0;

		while ($i != $len) {
			if ($pattern[$i] == '[') $in_bracket = true;
			if ($pattern[$i] == ']') $in_bracket = false;
			if ($pattern[$i] == '.' && !$in_bracket) {
				$substr = substr($pattern, $last_tmp_pos, $i-$last_tmp_pos);
				$substr = ltrim($substr, '.');

				$chaining[$segment_current] = $substr;

				$last_tmp_pos = $i;
				$segment_current++;
			}

			if ($i == $len-1 && !$in_bracket) {
				$substr = substr($pattern, $last_tmp_pos, $i+1);
				$substr = ltrim($substr, '.');

				$chaining[$segment_current] = $substr;
			}
			++$i;
		}
		if (empty($chaining[0])) return false;

		// Cache system
		if ($html = $this->get_cache(__METHOD__, $url));
		else {

			// We initialize CURL session
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			$html = $this->set_cache(__METHOD__, $url, curl_exec($curl));
			curl_close($curl);

		}

		$dom = new DomDocument();
		@$dom->loadHTML($html);

		$count_chain = count($chaining);
		$i = 0;

		// Init tag, attribute & value
		$tag = '';
		$attribute = '';
		$attribute_value = '';
		$type = '';

		// Init xpath
		$xpath_query = '/';

		while ($i != $count_chain) {

			if (!strpos($chaining[$i], '[')) {
				// Just tag (no attribute & no value)
				$tag = $chaining[$i];
				$type = 'tag_alone';

			}  elseif (strpos($chaining[$i], '[]')) {
				$tag = str_replace('[]', '', $chaining[$i]);
				$type = 'tag_alone';

			} elseif (strpos($chaining[$i], ':') === false) {
				// Tag with attribute (no value)
				$values = explode('[', rtrim($chaining[$i], ']'));
				if (!isset($values[0]) OR !isset($values[1])) return false;
				$tag = $values[0];
				$attribute = $values[1];
				$type = 'tag_attribute';

		} elseif (strpos($chaining[$i], ':')+2 == strlen($chaining[$i])) { // +2 to eat chars :]
			// Tag with attribute (no value)
			$values = explode('[', rtrim($chaining[$i], ']:'));
			if (!isset($values[0]) OR !isset($values[1])) return false;
			$tag = $values[0];
			$attribute = $values[1];
			$type = 'tag_attribute';

		} else {
			// Tag with attribute & value
			$values = explode('[', $chaining[$i]);
			if (!isset($values[0]) OR !isset($values[1])) return false;
			$tag = $values[0];
			$sub_chain = $values[1];
			$attribute = substr($sub_chain, 0, strpos($sub_chain, ':'));
			$attribute_value = substr($sub_chain, strpos($sub_chain, ':')+1, strlen($sub_chain)); // +1 to eat char
			$attribute_value = trim($attribute_value);
			$attribute_value = rtrim($attribute_value, ']');
			$type = 'tag_attribute_value';
		}

		
		/* End parse the "language" */


		/* Go create xpath */
		if ($type == 'tag_alone') $xpath_query .= '/' . $tag;
		elseif ($type == 'tag_attribute') $xpath_query .= '/' . $tag . '[' . $attribute . ']';
		elseif ($type == 'tag_attribute_value') $xpath_query .= '/' . $tag . '[@' . $attribute . '=\'' . $attribute_value . '\']';

		++$i;
	}

	$xpath = new DOMXPath($dom);
	$entries = @$xpath->query($xpath_query);
	if(!$entries) return false;
	$the_attribute = '';
	$the_attribute_val = '';

	$take_attribute = trim($take_attribute);
	$pos_take_attribute = strpos($take_attribute, ' ');

	if ($pos_take_attribute !== false) {
		$the_attribute = substr($take_attribute, 0, $pos_take_attribute);
		$the_attribute_val = substr($take_attribute, $pos_take_attribute, strlen($take_attribute));
		$the_attribute_val = trim($the_attribute_val);
		$the_attribute_val = trim($the_attribute_val, '%');
		$the_attribute_val = '%' . $the_attribute_val . '%';
	} else {
		$the_attribute = $take_attribute;
	}

	$found = 0;
	$key = 1;
	foreach ($entries as $entry) {


		// Loop for just the_attribute with no the_attribute_val
		if (empty($the_attribute_val)) {
			if ($key == $limit) {
				if ($the_attribute == 'value') return $entry->nodeValue;
				else return $entry->getAttribute($the_attribute);
			}
		} else {

			if ($the_attribute == 'value') {
				if($this->panda->like($entry->nodeValue, $the_attribute_val)) {
					if ($found == $limit) {
						return $entry->nodeValue;
					}

					$found++;
				}
			} else {
				if($this->panda->like($entry->getAttribute($the_attribute), $the_attribute_val)) {
					if ($found == $limit) {
						return $entry->getAttribute($the_attribute);
					}

					$found++;
				}
			}

		}
		++$key;
	}

}


/*
	// Parse any file (no proxy)
public function _HTML($url, $target='a[href]', $position=1, $get='getAttribute', $mask=FALSE) {

		// Modification idéale: title.class[test]->a.href[/?watch]

	$url = (string) $url;
	$position = (int) $position;

		// Mask check
	if ($mask) {
		$mask = '%'.$mask.'%';
		str_replace($mask, '%%', '%');
	}

		// We set the 'a[href]' -> href as attribute
	if ($attribute = find($target, '[', ']')) {

			// How long is 'a' in 'a[href]' (for example)
			$target_length = (strlen($target) - (strlen($attribute)+2)); // +2 = []

			// We get the 'a' as target
			$target = substr($target, 0, $target_length);

		} else {

			$attribute = FALSE;
			$get = 'nodeValue'; // NE CAREFUL WITH THIS : if it's a simple 'a' and no 'a[href]' we will systematically look for nodeValue (<a>ThisIsNodeValue</a>)

		}

		// Cache system
		if ($html = $this->get_cache(__METHOD__, $url));
		else {

			// We initialize CURL session
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 2);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			$html = $this->set_cache(__METHOD__, $url, curl_exec($curl));
			curl_close($curl);

		}

		// We initialize DOM session

		$doc = new DOMDocument();
		@$doc->loadHTML($html); // @ because this function sucks most of the time

		// We get by target
		$tags = $doc->getElementsByTagName($target);

		$int = 1;
		foreach ($tags as $tag) {

			if ($getAttribute = $tag->getAttribute($attribute));
			else $getAttribute = $tag->nodeValue;

			if ($this->panda->like($getAttribute, $mask)) {

				if ($int === $position) {

					if ($get === 'getAttribute') return $tag->getAttribute($attribute);
					elseif ($get === 'nodeValue') return $tag->nodeValue;

				}

				++$int;

			}

		}


	}

*/

}