<?php
/*
Plugin Name: Auto Select Result
Plugin URI: 
Description: Will go directly to the matching page if you search the exact post title.
Version: 0.0.1
Author: Laurent Schaffner
Author URI: http://www.laurentschaffner.com
Text Domain: auto-select-result
*/

// If WordPress was correctly loaded
if (defined('ABSPATH')) {

	//  Init header anti-conflict
	add_action('init', 'clean_output_buffer');

	// We enqueue the script
	add_action('wp_enqueue_scripts', 'search_redirect', 1);

}

/**
 * The main plugin system
 * - Check if there's a search query
 * - Then check if there are results
 * - Select the first result and redirect to its permalink
 *
 * @access	public
 * @return	void
 */
function search_redirect() {

	// If something was searched
	if (get_search_query()) {

		$research = esc_attr(get_search_query());

		// If there are posts as result
		while (have_posts()) {

			// If the article title matchs with the research
			if (get_the_title() === $research) {

				// Clean redirection
				wp_redirect(get_permalink());

			}

			break;

		}

	}

	return;

}

/**
 * This will avoid header conflict
 * 
 * I created it because of the wp_redirect we must do for the plugin to be effective
 *
 * @access	public
 * @return	void
 */
function clean_output_buffer() {

	// If ob_strart isn't intialized, we start it
	if (!ob_get_status()) ob_start();


}