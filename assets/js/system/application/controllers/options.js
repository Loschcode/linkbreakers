/**
 * Options Controller
 *
 * Manage options such as 'Guess my entry'
 *
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Controller
 *
 */
STRIKE.Controller('options',{

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
	 *
	 * Expired : the time the last research couldn't be a Guess my entry worth value (in seconds)
	 *
	 */	
	 expired: 200,
	 research_type: '',
	 research_time: '',
	 research: '',

	/**
	 * Check options
	 *
	 * When page is loading, we will check some options and change color of our button
	 *
	 * @access	public
	 * @return	void
	 */
	check_guess_my_entry: function() {

			// Load different helpers
			this.load.helper('request', 'url', 'array');

			// Send request ajax with url below
			var url = this.site_url('api/research');
			var datas = this.ajax_get(url, false);

			// Ajax request treatment ok
			if (datas['_success_request']) {

				// Get result of api
				var result = $.parseJSON(datas['datas']);

				// Success api
				if (result._success) {

					this.load.helper('date')

					// Get details about the last research
					this.research_type = result.research.result_type;
					this.research_time = result.research.result_time;
					this.research = result.research.search_text;

					var calculate = this.time() - this.research_time;

						// This mean the time the last research was executed isn't far
						// And there were no result so it has to be added
						if ((calculate < this.expired) && (this.research_type === 'default_result')) {

							$('button[data-option="guess_my_entry"]').removeClass('btn-danger disabled').addClass('btn-blue');

						}

				}

			}


	},

	/**
	 * Put options
	 *
	 * @access	public
	 * @param	object	Selector jQuery object
	 * @return	void
	 */
	put_guess_my_entry: function(selector) {

		//this.load.helper('key');

		$('#string').val(this.research);
		$('#url').focus();

		/*e = jQuery.Event("keydown");        
		e.which = 118;
		e.ctrlKey = true;
		$("#url").trigger(e);*/

		//console.log($.ui.keyCode.ENTER);

	}

	


});
