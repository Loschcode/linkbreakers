/**
 * Clever Controller
 *
 * Manage the clever returns option and system
 *
 * @author		Laurent Schaffner
 * @copyright   2013
 * @category	Controller
 *
 */

STRIKE.Controller('clever',{


	/**
	 * Construct of controller
	 *
	 * @access	public
	 * @return	void
	 */
	init: function () {
	},	

	/**
	 * Forbidden values
	 *
	 * The render function call one api which return the type result search
	 * here you can block the action to redirect for this values.
	 */
	forbidden_values: ['default_result', 'form_error'],

	/**
	 * Expired
	 * 
	 * How much time we need to set the redirection timeout (seconds)
	 */
	expired: 30,

	/**
	 * If the clever returns is enabled
	 *
	 * Check cookie to know if the user accept clever returns
	 *
	 * @access	public
	 * @return	bool
	 */
	clever_returns_is_enabled: function() {

		this.load.library('cookie');

		// We get the cookie
		var clever_returns_is_enabled = this.cookie.get('prefs_clever_returns_enabled');

		// If it's undefined then the clever returns should be set to '0'
		if (clever_returns_is_enabled == 'null') clever_returns_is_enabled = '0';

		// We convert the string system and return it as boolean
		if (clever_returns_is_enabled === '1') return true;
		else return false;

	},


	/**
	 * Research API getter
	 *
	 * In this function we'll get the research API actual datas
	 * And return it as an object
	 *
	 * @access	public
	 * @return	obj/bool
	 */
	get_details_from_research_api: function() {

		// Send the AJAX request with the url below
		var url = this.site_url('api/research');
		var datas = this.ajax_get(url, false);

		// AJAX request appears ok
		if (datas['_success_request']) {

			// Get the result of the API
			return $.parseJSON(datas['datas']);

		} else return false

	},

	/**
	 * Check the clever returns expiration
	 *
	 * We check if the clever returns hasn't expired and return a boolean
	 *
	 * @access	public
	 * @param   string research_time the time the research was done
	 * @return	bool
	 */
	clever_returns_has_expired: function(research_time) {

		this.load.helper('date');

		if (this.time() - research_time < this.expired) return false;
		else return true;

	},

	/**
	 * Slow redirection
	 *
	 * We redirect the user slowly for him to save the actual page and potentialy come back
	 *
	 * @access	public
	 * @param   string url the url to load
	 * @return	bool
	 */
	slow_redirection: function(url) {

		var _this = this;

		setTimeout(function() {

			_this.load.helper('url');
			_this.redirect(url);

		}, 10);

	},

	/**
	 * Clever pushback
	 *
	 * We are in the pushback system, we will redirect the user
	 * depending on the clever returns state
	 *
	 * @access	public
	 * @param   object
	 * @return	void
	 */
	 clever_pushback: function(selector) {

	 	var search = sessionStorage.getItem('search');
	 	var url_redirect = sessionStorage.getItem('url_redirect');
	 	var clever_returns_was_pushed = sessionStorage.getItem('clever_returns_was_pushed');

	 	// First, we check if the page was "pushed" before it appeared (this is a string, bool doesn't work)
	 	if (clever_returns_was_pushed == 'false') {

			this.load.helper('request', 'url', 'array');

			// Send request ajax with url below
			var url = this.site_url('api/research');
			var datas = this.ajax_get(url, false);

			// Get the research API details
			var research_api = this.get_details_from_research_api();

			// And we need to know if the connection was successful
			if (research_api._success) {

				// Check out the convert_research_api() PHP method, the result is similar
				var research_type = research_api.research.result_type;
				var research_time = research_api.research.result_time;

				// We check if the type isn't within the forbidden values
				if (!this.in_array(research_type, this.forbidden_values)) {

					// We calculate if the the clever returns hasn't expired
					if (this.clever_returns_has_expired(research_time)) {

						// Redirection to the user historic
						history.go(-1);
						return;

						//this.slow_redirection(url_redirect);

					} else {

					// We need to redirect to a search engine, we come back and force a "no_result"
					this.redirect(this.site_url('/search/?request='+ search +'&opt=no_result'));
					return;
					
					}

				}

			}

			// If the user didn't respect the condition (normaly it wouldn't happen)
			history.go(-1);
			return;

		} else {

	 		sessionStorage.setItem('clever_returns_was_pushed', false);
	 		this.slow_redirection(url_redirect);

		}

	},

	/**
	 * Clever push
	 *
	 * We push the datas within sessions and redirect to the pushback system
	 *
	 * @access	public
	 * @param   object
	 * @param   string search the research from the user
	 * @param   string url_redirect the URL the user should be redirected to
	 * @return	void
	 */
	 clever_push: function(selector, search, url_redirect) {

	 	if (this.clever_returns_is_enabled()) {

			// If there's no value, we should exit the system
			if (search == null && url_redirect == null) return false;

			// Load different helpers
			this.load.helper('request', 'url', 'array');

			// We store within the "sessionStorage" which is one tab memory
			sessionStorage.setItem('search', search);
			sessionStorage.setItem('url_redirect', url_redirect);
			sessionStorage.setItem('clever_returns_was_pushed', true);

			this.redirect(this.site_url('redirect'));

		}


	}


});
