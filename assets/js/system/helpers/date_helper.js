/**
 * Date Helper
 *
 * All functions about date
 *
 * @author		Jeremie Ges
 * @copyright   2013
 * @category	Helper
 * @version		0.0.1
 */

 // ------------------------------------------------------------------------

 /**
 * History of date helper
 *
 * @version 0.0.1
 * Added time() function
 */


 // ------------------------------------------------------------------------

STRIKE.Helper('date',{

	/**
	 * Time
	 *
	 * Like time() php function, return the actual timestamp
	 *
	 * @access		public
	 * @return		int
	 */
	time: function () {

		return Math.floor(new Date().getTime() / 1000);

	}

});

