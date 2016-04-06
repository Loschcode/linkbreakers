/**
 * Effects Controller
 *
 * Manage all design effects
 *
 * @author		Jeremie Ges
 * @copyright   2013
 * @category	Controller
 *
 */

STRIKE.Controller('effects',{

	/**
	 * Construct of controller
	 *
	 * @access	public
	 * @return	void
	 */
	init: function () {

	},	

	/**
	 * Search Typing
	 *
	 * Display / Hide many elements when you are searching
	 *
	 * @access	public
	 * @param 	object	jQuery selector (from router)
	 * @param	object	The event with many informations
	 * @return 	void
	 */
	search_typing: function(selector, event) {

		// Little config

		// Speed of transitions
		var speed_transition_show = 500;
		var speed_transition_hide = 100;

		// What do you want hide ?
		var selectors_hidden = '#block-example, #what-btn';

		// What happen if no text/spacers into the input
		if (selector.val() == '') {

			if ($(selectors_hidden).css('opacity') == 0) {

				$(selectors_hidden).show();

				// Show
				$(selectors_hidden).animate({

					opacity: 1

				}, speed_transition_show);
			}

		} else {

			// Text into input search

			if ($(selectors_hidden).css('opacity') == 1) {

				// Go to opacity 0 with speed transition
				$(selectors_hidden).animate({

					opacity: 0

				}, speed_transition_hide);

				// Hide completely the block (the block take no space in the page now)
				setTimeout(function() {

					$(selectors_hidden).hide();

				}, speed_transition_hide)

			}

		}

	},

	/**
	 * Intro
	 *
	 * Pretty effect on logo / punchline when you "load" the page
	 *
	 * @access	public
	 * @return 	void
	 */
	intro: function() {

		$('#logo, #punchline').hide();

		$('#logo').fadeIn(1000);
		$('#punchline').fadeIn(1500);

	},


	/**
	 * Change pages
	 *
	 * Pretty transition effect when you "follow" a link
	 *
	 * @access	public
	 * @param 	object	jQuery selector (from router)
	 * @return	void
	 */
	change_pages: function(selector) {

		// Load url helper
		this.load.helper('url');

		var speed = 200;
		var _strike = this;

		var data_href = selector.attr('data-transition-href');
		
		console.log(data_href);

		if (typeof data_href != 'undefined') {

			console.log('redirect');

			$('div[id^=block-]').fadeOut(speed, function() {
				
				console.log('redirect 2');
				
				_strike.redirect(data_href);

			});

		}


	}


});
