<?php

/**
 * Devlog class
 *
 * Everything about the devlog for the Sandbox system and the admin devlog
 *
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Class
 *
 */

abstract class Devlog {

	public static $devlog_arr = array();
	public static $user_status = '';
	public static $last_microtime = 0;

	public static function initialize() {

		self::$last_microtime = microtime();

		if (!isset($_SESSION['session']['userstatus'])) self::$user_status = 'unknown';
		self::$user_status = $_SESSION['session']['userstatus'];

		// Be careful with this, 'START' add an element within the devlog view foreach
		self::add('Connection with the server established at UNIX '.self::$last_microtime, 'debug', 'START');

	}


	/**
 	*
 	* Inside the add to devlog system
 	*
 	* @access public
 	* @param string $log_msg our content
 	* @param string $type of our content
 	* @return void
 	*
 	*/
 	private static function _intern_add($log_msg, $type, $color, $time, $userstatus) {

 		if ($time === NULL) {

 			// We calculate the microtime and reset the last microtime
 			$time = microtime() - self::$last_microtime;
 			//self::$last_microtime = $time;

 			// We humanize it a little
 			$time = '+'. $time;

 		}

 		self::$devlog_arr[$type][] = array(

			'time' => $time,
			'log_msg' => htmlentities($log_msg),
			'status' => $userstatus,
			'color' => $color

			);

 		return TRUE;

 	}

	/**
 	*
 	* Add an entry to our devlog
 	*
 	* @access public
 	* @param string $log_msg our content
 	* @param string $type of our content
 	* @return void
 	*
 	*/
 	public static function add($log_msg='', $type='debug', $time=NULL) {

 		self::_intern_add($log_msg, $type, 'default', $time, 'user');

 	}

	/**
 	*
 	* Add an admin entry to our devlog
 	*
 	* @access public
 	* @param string $log_msg our content
 	* @param string $type of our content
 	* @return void
 	*
 	*/
 	public static function add_admin($log_msg='', $type='debug', $time=NULL) {

 		if (self::$user_status === 'admin') {

 			self::_intern_add($log_msg, $type, 'blue', $time, 'admin');

 		}

 	}

 	public static function add_admin_green($log_msg='', $type='debug', $time=NULL) {

 		if (self::$user_status === 'admin') {

 			self::_intern_add($log_msg, $type, 'green', $time, 'admin');

 		}

 	}

 	public static function add_admin_yellow($log_msg='', $type='debug', $time=NULL) {

 		if (self::$user_status === 'admin') {

 			self::_intern_add($log_msg, $type, 'yellow', $time, 'admin');

 		}

 	}

 	public static function add_admin_red($log_msg='', $type='debug', $time=NULL) {

 		if (self::$user_status === 'admin') {

 			self::_intern_add($log_msg, $type, 'red', $time, 'admin');

 		}

 	}

	/**
 	*
 	* Show our entire devlog or a part of it
 	*
 	* @access public
 	* @param string $type to show
 	* @return array
 	*
 	*/
 	public static function show($type=FALSE) {

 		if ($type) return self::$devlog_arr[$type];
 		else return self::$devlog_arr;

 	}

	/**
 	*
 	* Show our entire devlog or a part of it
 	*
 	* @access public
 	* @return array
 	*
 	*/
 	public static function show_stored() {

 		if (isset($_SESSION['session']['devlog'])) return $_SESSION['session']['devlog'];
 		else return array();

 	}

	/**
 	*
 	* Save the Devlog into a session
 	* If there are datas already sessioned, it merge the array, to cancel this reaction
 	* Just remove the condition and save the else case
 	* But the devlog sometimes needs to save double connections (such as userspace redirections)
 	*
 	* @access public
 	* @return void
 	*
 	*/
 	public static function save() {

 		self::add('Connection with the server closed');

 		if (isset($_SESSION['session']['devlog']) && (isset($_SESSION['session']['devlog_options']['multi_logs']))) {

 			$_SESSION['session']['devlog'] = array_merge_recursive(self::$devlog_arr, $_SESSION['session']['devlog']);

 		} else $_SESSION['session']['devlog'] = self::$devlog_arr;


 		return TRUE;

 	}

	/**
 	*
 	* Clean your devlog
 	*
 	* @access public
 	* @return void
 	*
 	*/
 	public static function clean() {

 		$_SESSION['session']['devlog'] = array();

 	}

	/**
 	*
 	* Change the devlog mode (Multi logs / Unique log)
 	*
 	* @access public
 	* @return void
 	*
 	*/
 	public static function logs_mode($opt=TRUE) {

 		if ($opt === 'multi') $_SESSION['session']['devlog_options']['multi_logs'] = TRUE;
 		elseif ($opt === 'unique') {

 			//session_unregister($_SESSION['session']['devlog_options']['multi_logs']);
 			unset($_SESSION['session']['devlog_options']['multi_logs']);

 		}

 	}


 }