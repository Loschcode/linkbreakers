<?php
/**
 * Date Helper Extends
 *
 * @package		Linkbreakers
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Jeremie Ges
 */

// ------------------------------------------------------------------------

/**
 * Elapsed time
 *
 * @access	public
 * @param	int 	The timestamp of date (ex : 55445478725)
 * @return	string
 */

if (! function_exists('elapsed_time')) {

	function elapsed_time($date) {

		$old_time = $date;
		$time = time();

		$seconds = round($time - $old_time);
		$minutes = round($seconds/60);
		$hours = round($minutes/60);
		
		if ($hours >= 72) return 'Le ' . date('d/m/Y', $old_time);
		elseif ($hours >= 48 && $hours < 72) return 'Avant Hier';
		elseif ($hours >= 24 && $hours < 48) return 'Hier';
		elseif ($hours >= 1) return 'Il y a ' . $hours . ' h';
		elseif ($minutes >= 1) return 'Il y a ' . $minutes . ' mn';
		elseif ($seconds == 0) return 'A l\'instant';
		else return 'Il y a ' .  $seconds . ' s';

	}


}