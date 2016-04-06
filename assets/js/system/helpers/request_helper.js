/**
 * Request Helper
 *
 * Simplify syntax of jQuery ajax requests
 *
 * @author		Jeremie Ges
 * @copyright   2013
 * @category	Helper
 * @version		0.0.1
 */

 // ------------------------------------------------------------------------

 /**
 * History of request helper
 *
 * @version 0.0.1
 * Added ajax_get() method
 * Added _execute_request() method
 */


 // ------------------------------------------------------------------------

STRIKE.Helper("request",{

	/**
	 * Ajax get
	 *
	 * Send ajax request with GET method
	 *
	 * @access		public
	 * @param	    string	Url to request
	 * @param		bool	Async or not ?
	 * @return		array
	 */
	ajax_get: function (url, async) {

		return this._execute_request(url, async, 'GET');


	},

	/**
	 * Execute request
	 *
	 * Execute Ajax request with jQuery
	 *
	 * @access		private
	 * @param	    string	Url to request
	 * @param		bool	Async or not ?
	 * @param		string	Method to use (Get/Post)
	 * @return		array
	 */
	_execute_request: function(url, async, method) {

		var datas = [];

		var request = $.ajax({
			type: method,
			url: url,
			async: async,
			success: function(msg) {
				datas['_success_request'] = true;
				datas['datas'] = msg; 
			}, 
			error: function(msg) {
				datas['_success_request'] = false;
				datas['datas'] = msg;
			}
		});

		return datas;

	}


});
