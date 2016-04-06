/**
 * Setting Controller
 *
 * Manage settings 
 *
 * @author		Jeremie Ges
 * @copyright   2013
 * @category	Controller
 *
 */
STRIKE.Controller('settings',{

	/**
	 * Prefs list
	 *
	 * All prefs to manage
	 */
	prefs_list: ['prefs_default_result_enabled', 'prefs_smart_domains_enabled', 'prefs_clever_returns_enabled'],

	/**
	 * Construct of controller
	 *
	 * @access	public
	 * @return	void
	 */
	init: function () {

		this.load.library('cookie');
		this.load.helper('url');

	},	

	/**
	 * Check settings
	 *
	 * When page is loading, we will check settings and change color of buttons
	 *
	 * @access	public
	 * @return	void
	 */
	check_settings: function() {

		var count_prefs = this.prefs_list.length;

		for (var i = 0; i < count_prefs; i++) {

			var get_cookie = this.cookie.get(this.prefs_list[i]);
			var id = this.prefs_list[i].split('_enabled').join('');

			//console.log(get_cookie);

			if (get_cookie == '0' || get_cookie == 'null') {

				$('button[data-setting="' + id + '"]').removeClass('btn-blue').addClass('btn-danger');

			} else {

				$('button[data-setting="' + id + '"]').removeClass('btn-danger').addClass('btn-blue');

			}

		}


	},

	/**
	 * Set settings
	 *
	 * @access	public
	 * @param	object	Selector jQuery object
	 * @return	void
	 */
	set_settings: function(selector) {

		// Load helper array to use in_array function
		this.load.helper('array');

		// Get selectors
		var selector_data = selector.data('setting');
		var concat_enabled  = selector_data + '_enabled';

		// Check if setting exists in array prefs_list
		if (this.in_array(concat_enabled, this.prefs_list)) {

			// Get cookie
			var get_cookie = this.cookie.get(concat_enabled);

			if (get_cookie == '0' || get_cookie == 'null') {

				// Set to 1
				var new_cookie = 1;

				this.cookie.set(concat_enabled, new_cookie);

				$(selector).removeClass('btn-danger').addClass('btn-blue');


			} else {

				// Set to 0
				var new_cookie = 0;

				this.cookie.set(concat_enabled, new_cookie);

				$(selector).removeClass('btn-blue').addClass('btn-danger');

			}


			// Data persistent 
			var request = this.site_url('api/prefs/set/' + concat_enabled + '/' + new_cookie);

			$.ajax({
				url: request,
				type: 'GET',
			});	


		}


	}

	


});
