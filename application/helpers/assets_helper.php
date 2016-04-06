<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('css_url')) {
	function css_url($nom) {
		return base_url() . 'assets/css/' . str_replace('.css', '', $nom) . '.css';
	}
}
if ( ! function_exists('js_url')) {
	function js_url($nom) {
		return base_url() . 'assets/js/' . $nom . '.js';
	}
}
if ( ! function_exists('img_url')) {
	function img_url($nom) {
		return base_url() . 'assets/img/' . $nom;
	}
}
if ( ! function_exists('img')) {
	function img($nom, $alt = '') {
		return '<img src="' . img_url($nom) . '" alt="' . $alt . '" />';
	}
}

if (! function_exists('assets_url')) {

	function assets_url($path) {

		return base_url('assets/' . ltrim($path, '/'));

	}

}

if (! function_exists('libs_url')) {
	function libs_url($path='') {

		return base_url('assets/libs/' . ltrim($path, '/'));

	}
}

if ( ! function_exists('img_absolute')) {
	function img_absolute($nom, $alt = '') {
		return '<img src="' . $nom . '" alt="' . $alt . '" />';
	}
}

/*
|--------------------------------------------------------------------------
| Nav Active
|--------------------------------------------------------------------------
|
| Simple helper to know if you must add "active" css class
| 
*/
if (! function_exists('nav_active')) {

	function nav_active($value, $must_be) {

		if ($value == $must_be) return 'active';

	}

}