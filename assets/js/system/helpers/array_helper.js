/**
 * Array Helper
 *
 * All functions about array
 *
 * @author		Jeremie Ges
 * @copyright   2013
 * @category	Helper
 * @version		0.0.2
 */

 // ------------------------------------------------------------------------

 /**
 * History of array helper
 *
 * @version 0.0.2
 * Updated in_array function, now you can know the position of element 
 *
 * @version 0.0.1
 * Added in_array function
 */


 // ------------------------------------------------------------------------

STRIKE.Helper('array',{

	/**
	 * In array
	 *
	 * Check if value exists in array
	 *
	 * @access		public
	 * @param	    string	What do you search
	 * @param		array	Where you must search
	 * @param 		bool 	Return the position
	 * @return		bool
	 */
	in_array: function (needle,haystack, pos) {

		// Check pos param
		if (typeof pos == 'undefined')
			pos = false;
		else
			pos = true;


		// Count array
		var count = haystack.length;

		// Loop array
		for(var i = 0; i < count; i++) {


			if(haystack[i] == needle) {

				// We want the position
				if (pos)
					return i;
				// We want know if value exists
				else
					return true

			}
			
		}

		// -1 represents "no exists"
		if (pos)
			return '-1';
		else
			return false;

	}

});
