<?php
/*
Plugin Name: LB Crawler
Plugin URI: http://www.linkbreakers.com/docs
Description: Replace all the Linkbreakers post linked with Mustache API
Version: 0.0.1
Author: Laurent Schaffner
Author URI: http://www.laurentschaffner.com
Text Domain: lb-crawler
*/

// We require the API-WP functions
require('api.php');

// If WP was correctly loaded
if (defined('ABSPATH')) {

	// We apply the filter the the_content() ; everything will be replaced by the correct datas if it needs to
	add_filter('the_content', 'lb_crawler_the_content', 100);
	add_filter('the_excerpt', 'lb_crawler_the_excerpt', 100);

}

// This function will do all the work and replace properly the content
function lb_crawler_the_content($the_content) {

	/* Here's the content of our page depending on the Mustache API */
	$infos_permalink = infos_permalink(get_permalink());

	if ($infos_permalink) {

		$the_content = automatic_api($infos_permalink, $the_content);

	}

	return $the_content;

}

function lb_crawler_the_excerpt($the_excerpt) {

	/* We remove all the %system% */
	$the_excerpt = preg_replace('#\%(.*)\%#isU', '', $the_excerpt);

	return $the_excerpt;

}