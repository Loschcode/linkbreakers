<?php

/**
 * Lyza class
 *
 * Everything about the audio understanding and voice system
 *
 * NOTE : This section was fucking aborted because Ges is an awesome guy.
 * It also means everything is deprecated.
 *
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Class
 *
 */

class Lyza {

	public function __construct() {

	}

	public function audio_string($string) {

		$ignore_chars = array('/');
		$count = count($ignore_chars);
		$string = strip_tags($string);

		for ($i=0; $i!= $count; $i++) {
			$string = str_replace($ignore_chars[$i], ' ', $string);
		}

		return $string;


	}


}

?>