<?php

/**
 * LBL HTML Shaping class
 *
 * LBL HTML shaping functions
 *
 * @package 	LBL / HTML Shaping
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Class
 *
 */

class Lbl_html_shaping extends Genius {

	public function __construct() {

		parent::__Construct();

		Devlog::add_admin_yellow('LBL-Library '.__CLASS__.' has been initialized');

	}

	// Audio player (HTML5)
	public function _AUDIO($url='') {

		/*if ($this->panda->is_correct_strong_type($url, 'url'))*/ return '<audio controls><source src="'.$url.'" type="audio"></audio>';
		//else return '';

	}

	// Gist (Github)
	public static function _GIST($code=0) {

		$code = (int) $code;
		return '<script src="https://gist.github.com/'.$code.'.js"></script>';
	}

	// Same as below
	public static function _PARSE_URL($url, $get, $select_argument=FALSE) {

		$first_split = parse_url($url);

		if ($get === 'SCHEME') return $first_split['scheme'];
		if ($get === 'HOST') return $first_split['host'];
		if ($get === 'PATH') return $first_split['path'];
		if ($get === 'QUERY') return $first_split['query'];

		if ($get === 'VARIABLES') {

			parse_str($first_split['query'], $result);

			if (isset($result[$select_argument])) return $result[$select_argument];
			else return FALSE;

		}

		return FALSE;

	}

	// We should move this function somewhere else after :
	// -> Youtube video id extractor
	public function _GET_YOUTUBE_ID($youtube_url) {

		if (strpos($youtube_url, 'http://www.youtube.') !== FALSE) return $this->_PARSE_URL($youtube_url, 'VARIABLES', 'v');
		elseif (strpos($youtube_url, '://youtu.be') !== FALSE) return substr($this->_PARSE_URL($youtube_url, 'PATH'), 1);

		// http://www.youtube.com/watch?v=Umx60zKRg0A
		// http://youtu.be/Umx60zKRg0A?t=17s

	}

	// Youtube embed
	public function _YOUTUBE($youtube_url='', $width=560, $height=315, $autoplay=FALSE) {

		if ($width === 'DEFAULT') $width = 560;
		if ($height === 'DEFAULT') $height = 315;

		if ($autoplay === 'AUTOPLAY') $autoplay = 1; // Autoplay Youtube format

		$youtube_code = $this->_GET_YOUTUBE_ID($youtube_url);

		return '<iframe width="'.$width.'" height="'.$height.'" src="//www.youtube.com/embed/'.$youtube_code.'?autoplay='.$autoplay.'" frameborder="0" allowfullscreen></iframe>';

	}

	// Vimeo
	// <iframe src="http://player.vimeo.com/video/55742745?title=0&amp;byline=0&amp;portrait=0" frameborder="0" width="640" height="340"></iframe>
	// <iframe src="http://player.vimeo.com/video/VIDEO_ID" width="WIDTH" height="HEIGHT" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
	public static function _VIMEO($code=0,$width=640,$height=340,$frameborder=0) {

		$code = (int) $code;
		$width = (int) $width;
		$height = (int) $height;

		$frameborder = (int) $frameborder;

		return '<iframe src="http://player.vimeo.com/video/'.$code.'" width="'.$width.'" height="'.$height.'" frameborder="'.$frameborder.'" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';

	}

	public function _LINK($name, $url) {

		return '<a href="'.$url.'">'.$name.'</a>';

	}

	// HTML Picture return
	public function _IMG($url='', $width=0, $height=0) {

		$opt = '';

		if ($width > 0) {

			$width = (int) $width;
			$opt .= 'width="'.$width.'"';

		}

		if ($height > 0) {

			$height = (int) $height;
			$opt .= 'height="'.$height.'"';

		}

		if ($this->panda->is_correct_strong_type($url, 'url')) return '<img src="'.$url.'" '.$opt.'>';
		else return '';

	}

	// Call a javascript script
	public function _JS($js='') {

		$args = func_get_args();

		// To be understandable
		$js = $this->understandable($js);

		$js_loader = '<script src="'.base_url('assets/js/scripts/'.$js.'.js').'"></script>';

		if ($js === 'timer') {

			// New way ...
			$init_time = exists($args[1], 10);
			$init_type = exists($args[2], 'MSG');
			$init_return = exists($args[3], '');

			//$js_script = '<script>timer('.$init_time.', '.$init_type.', '.$init_return.');</script>
			$js_script = '<script>window.onLoad=timer('.$init_time.', \''.$init_type.'\', \''.$init_return.'\')</script>'; // init_time, init_type, init_return

		} elseif ($js === 'chrono') {

			if (isset($args[1])) $init_chrono = (int) $args[1]; else $init_chrono = 0;

			$js_script = '<script>window.onLoad=chrono('.$init_chrono.')</script>'; // init_chrono

		}

		if (isset($js_script)) return '<p id="js_function"></p>'. $js_loader . ' ' . $js_script ;
		else return FALSE;

	}

	public function _CHRONO() { $args = func_get_args(); array_unshift($args, 'CHRONO'); return call_user_func_array(array($this, "_JS"), $args); }
	public function _TIMER() { $args = func_get_args(); array_unshift($args, 'TIMER'); return call_user_func_array(array($this, "_JS"), $args); }

	// HTML New line
	public static function _N($num=1) {

		$result = '';

		for ($int=0;$int<$num;$int++) $result .= '<br />';

			return $result;
	}

}