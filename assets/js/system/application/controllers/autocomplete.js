/**
 * Aucomplete Controller
 *
 * Manage all autocompletes system (search, search from user, alias)
 *
 * @author		Jeremie Ges / Laurent Schaffner (because you're a fagoot Ges.)
 * @copyright   2013
 * @category	Controller
 *
 */

STRIKE.Controller('autocomplete',{

	// Current focus result in autocomplete results
	current_focus_result : 0,

	// The selector whish contain the string search
	selector : '#search',

	// What's the selector to inject results
	block_inject_autocomplete : '#inject-autocomplete',


	/**
	 * Construct of controller
	 *
	 * @access	public
	 * @return	void
	 */
	init: function () {

		// Load helper key used by search typing method
		this.load.helper('key');
	},	

	/**
	 * Run
	 *
	 * Exec system autocomplete
	 *
	 * @access	public
	 * @return	void
	 */
	run: function() {

		var search = $(this.selector).val();

		if (search != '') {

			//console.log('search : ' + search);

			// The system regenerate results of autocomplete
			// so we must reset the current focus result
			this.current_focus_result = 0;
			this.autocomplete(search);

		} else {

			//console.log('hide autocomplete');

			// The block inject autocomplete is visible ?
			// Little hack
			if ($(this.block_inject_autocomplete).is(':visible'))
				this.hide_autocomplete();

		}

	},


	/**
	 * Search typing
	 *
	 * Business code when user typing in autocomplete input
	 *
	 * @access	public
	 * @param	object	Stock event object of keydown/keypress/keydown
	 * @return	void
	 */
	search_typing: function(event) {


		var key = event.keyCode;

		// Find code of arrow_down and arrow_up
		var key_arrow_down = this.convert_key('arrow_down');
		var key_arrow_up = this.convert_key('arrow_up');

		// We will check if the key is an arrow 
		switch (key) {

			case key_arrow_up:
			this.result_up();

			// Stop method it's not a letter
			return;
			break;

			case key_arrow_down:
			this.result_down();

			// Stop method it's not a letter
			return;
			break;

		}

		// At this instant we know the user typing letter
		// So we will run the system
		this.run();

	},

	/**
	 * Result down
	 *
	 * Down action on result's autocomplete
	 *
	 * @access	public
	 * @return	void
	 */
	result_down: function() {

		var max_elements = $(this.block_inject_autocomplete + 'a').length;

		if (this.current_focus_result < max_elements-1) {
			this.current_focus_result += 1;
			this.focus_render();
		}
	},

	/**
	 * Result up
	 *
	 * Up action on result's autocomplete
	 *
	 * @access	public
	 * @return	void
	 */
	result_up: function() {
		
		if (this.current_focus_result > 0) {
			this.current_focus_result -= 1;
			this.focus_render();
		}

	},

	/**
	 * Focus render
	 *
	 * Give the focus on the result field
	 *
	 * @access	public
	 * @return	void
	 */
	focus_render: function() {
		console.log('heere');

		var eq = this.current_focus_result;

		if (eq < 0)
			eq = 0;

		var target_element = $(this.block_inject_autocomplete + 'a:eq(' + (eq) + ')').hover();

		if (target_element.length > 0) {

			target_element.focus();

		}	


	},

	/**
	 * Autocomplete
	 *
	 * Run api
	 *
	 * @access	public
	 * @param	string	Your search
	 * @return	void
	 */
	autocomplete: function(search) {

		var _strike = this;

		$.ajax({
			type: "POST",
			url: 'http://www.linkbreakers.com/autocomplete/search/search?q=' + search,
			success: function(datas) {
				_strike.display_results(datas);
			}
		});


	},

	/**
	 * Display results
	 *
	 * Display results of autocomplete
	 *
	 * @access	public
	 * @param	array	Results of autocomplete
	 * @return	void
	 */
	display_results: function(datas) {

		// Ajax is async method, so maybe the input at this time is blank
		if ($(this.selector).val() != '') {

			$(this.block_inject_autocomplete).show();

			var render = '';

			// Json string with no results
			if (datas != '[]') {

				datas = $.parseJSON(datas);
				var count = datas.length;


				for (var i = 0; i < count; i++) {

					render += '<a id="autocomplete-entry-'+ i +'" href="#" class="">' + datas[i] + '</a><br/>';

				}

				$(this.block_inject_autocomplete).html(render);

			} else {

				$(this.block_inject_autocomplete).html('No Result');

			}

		}

	},

	/**
	 * Hide autocomplete
	 *
	 * Hide the autocomplete system
	 *
	 * @access	public
	 * @return	void
	 */
	hide_autocomplete: function() {

		$(this.block_inject_autocomplete).hide();

	},

	/**
	 * Click result
	 *
	 * Called when you click on result
	 *
	 * @access	public
	 * @param	object	selector jquery
	 * @return	void
	 */
	click_result: function(selector) {

		$(this.selector).val(selector.text());
		$(this.selector).focus();
		this.run();

	}


});
