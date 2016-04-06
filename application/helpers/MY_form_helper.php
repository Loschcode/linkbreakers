<?php
/**
 * Form Helper Extends
 *
 * @package		Linkbreakers
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Jeremie Ges
 */

// ------------------------------------------------------------------------

/**
 * Error css
 *
 * @access	public
 * @param	string  The value to check
 * @param	string	Class css to add if we found an error
 * @return	mixed
 */
if (! function_exists('error_css')) {

	function error_css($check_value, $css_class='') {

		if (empty($css_class)) 
			$css_class = 'error-field';

		$form_error = form_error($check_value);

		// Detect error
		if (!empty($form_error)) {

			return $css_class;

		}

		// No error
		return false;

	}

}