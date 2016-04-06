/**
 * Linkbreakers
 *
 * The very first instant search engine
 *
 * @author		Linkbreakers Team
 * @copyright	2013
 * @link		http://www.linkbreakers.com/
 */

// ------------------------------------------------------------------------

/**
 * Router.js
 *
 * Attach events to controller / method
 *
 * @category	Linkbreakers Front
 * @author		Jeremie Ges / Laurent SCHAFFNER
 * @version		0.0.1
 */

/*
|--------------------------------------------------------------------------
| What happen when the document is loaded
|--------------------------------------------------------------------------
*/
$(document).ready(function() {

	$('body').css('visibility', 'visible');

	// Here you can preload controllers
	STRIKE.preload_controllers(['settings', 'effects', 'utilities']);

	/*
	|--------------------------------------------------------------------------
	| All pages
	|--------------------------------------------------------------------------
	*/

	// Autosize textarea
	STRIKE.dispatch('utilities', 'autosize_textarea');

	// Intro effects
	STRIKE.dispatch('effects', 'intro');

	// Check settings
	STRIKE.dispatch('settings', 'check_settings');

	// Clever system -> better to call from all the pages 
	STRIKE.dispatch('clever', 'clever_push');

	/*
	|--------------------------------------------------------------------------
	| Add tag page
	|--------------------------------------------------------------------------
	*/
	if (STRIKE.route('/tag/add')) {

		// Check options
		STRIKE.dispatch('options', 'check_guess_my_entry');

		// Give the focus of add tag form
		STRIKE.dispatch('utilities', 'input_focus', '#string', '#url');

	}

	/*
	|--------------------------------------------------------------------------
	| Search page (Home)
	|--------------------------------------------------------------------------
	*/

	if (STRIKE.route('/') || STRIKE.route('/:any')) {
				
		// Give the focus of search input
		STRIKE.dispatch('utilities', 'search_focus');
	}

	/*
	|--------------------------------------------------------------------------
	| Forgot password
	|--------------------------------------------------------------------------
	*/

	if (STRIKE.route('/log/forgot')) { 

		// Give the focus of email input
		STRIKE.dispatch('utilities', 'forgot_password_focus');

		// Give it to the recoverkey
		STRIKE.dispatch('utilities', 'forgot_password_recover_focus');

	}


	/*
	|--------------------------------------------------------------------------
	| Log in section
	|--------------------------------------------------------------------------
	*/
	if (STRIKE.route('/log')) {

		STRIKE.dispatch('utilities', 'input_focus', '#username', '#password');

	}



});

/*
|--------------------------------------------------------------------------
| Binding Events
|--------------------------------------------------------------------------
*/
$(document).ready(function() {

	/*
	|--------------------------------------------------------------------------
	| Settings
	|--------------------------------------------------------------------------
	*/
	$('button[data-setting]').click(function() {

		STRIKE.dispatch('settings', 'set_settings', $(this));

	});

	/*
	|--------------------------------------------------------------------------
	| Guess my entry system
	|--------------------------------------------------------------------------
	*/
	$('button[data-option="guess_my_entry"]').click(function() {

		STRIKE.dispatch('options', 'put_guess_my_entry', $(this));

	});

	/*
	|--------------------------------------------------------------------------
	| Clever returns system
	|--------------------------------------------------------------------------
	*/
	$('#form-search').submit(function (e) {

		e.preventDefault();

		this.submit();
	
	});

	/*
	|--------------------------------------------------------------------------
	| Search system / Autocomplete
	|--------------------------------------------------------------------------
	*/
	$('#search').keyup(function(event) {
		
		STRIKE.dispatch('effects', 'search_typing', $(this), event);
		STRIKE.dispatch('autocomplete', 'search_typing', event);

	});

	$(document).on('keyup', '#inject-autocomplete a', function(event) {

		STRIKE.dispatch('autocomplete', 'search_typing', $(this), event);

	});

	$(document).on('click', '#inject-autocomplete a', function() {

		STRIKE.dispatch('autocomplete', 'click_result', $(this));

	});


	/*
	|--------------------------------------------------------------------------
	| Pages (change blocks)
	|--------------------------------------------------------------------------
	*/
	$('a').click(function() {	

		STRIKE.dispatch('effects', 'change_pages', $(this));

	});


	$('a[data-share]').click(function() {

		STRIKE.dispatch('utilities', 'permalink_click', $(this));

	});

	$(document).on('blur', 'input[id^="focus-"]', function() {

		STRIKE.dispatch('utilities', 'permalink_out', $(this));

	});






});