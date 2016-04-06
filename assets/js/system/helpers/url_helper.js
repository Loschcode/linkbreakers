/**
 * Url Helper
 *
 * All functions about url
 *
 * @author		Jeremie Ges
 * @copyright   2013
 * @category	Helper
 * @version		0.0.3
 */

 // ------------------------------------------------------------------------

 /**
 * History of request helper
 *
 *
 * @version 0.0.3
 * Clean code
 *
 * @version 0.0.2
 * Added site_url()
 * Deleted base_url() because create name conflict
 *
 * @version 0.0.1
 * Added redirect() method
 * Added base_url() method
 */


 // ------------------------------------------------------------------------

STRIKE.Helper("url",{

	/**
	 * Redirect
	 *
	 * Redirect navigator to another url
	 *
	 * @access		public
	 * @param	    string  Url to redirect
	 * @param		string	Set "http" to make redirect by protocol
	 * @return		void
	 */
	redirect: function (where,options) {

		if (options == 'http') {

			window.location.replace(where);

		} else {

			window.location.href = where;

		}

		// Exit
		return false;
		
	},

	/**
	 * Site Url
	 *
	 * Return base url with uri
	 *
	 * @access		private
	 * @param	    string  Concat uri to base url
	 * @return		void
	 */
	site_url: function (uri) {

		if (uri) {

			return STRIKE.base_url + uri;

		}

		return STRIKE.base_url;

	}
});
