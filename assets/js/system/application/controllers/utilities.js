/**
 * Utilities Controller
 *
 * All utilities function for application
 *
 * @author		Jeremie Ges
 * @copyright   2013
 * @category	Controller
 *
 */

 STRIKE.Controller('utilities',{

	/**
	 * Construct of controller
	 *
	 * @access	public
	 * @return	void
	 */
	 init: function () {

	 },

	/**
	 * Search focus
	 *
	 * Give the focus to search
	 *
	 * @access	public
	 * @return	void
	 */
	 search_focus: function() {


		// Element exist ?
		if ($('#search').length > 0) {

			// Set focus (problem with jQuery way, so fuck you)
			document.getElementById('search').focus();

		}

	},

	/**
	 * Add tag focus
	 *
	 * @access	public
	 * @return	void
	 */
	 add_tag_focus: function() {

	 	if ($('#string').length > 0 && $('#url').length > 0) {

			// Empty first field
			if ($('#string').val() == '') {

				$('#string').focus();

			} else {

				if ($('#url').val() == '') {

					$('#url').focus();

				}

			}

		}

	},

	/**
	 * Forgot your password focus
	 *
	 * Give the focus to the email input
	 *
	 * @access	public
	 * @return	void
	 */
	 forgot_password_focus: function() {


		// Element exist ?
		if ($('#useremail').length > 0) {

			// Set focus (problem with jQuery way, so fuck you)
			document.getElementById('useremail').focus();

		}

	},

	/**
	 * Forgot your password recover focus
	 *
	 * Give the focus to the recovering key
	 *
	 * @access	public
	 * @return	void
	 */
	 forgot_password_recover_focus: function() {


		// Element exist ?
		if (($('#recoverkey').length > 0) && ($('#newpassword').length > 0)) {

			if ($('#recoverkey').val() == '') {

			// Set focus (problem with jQuery way, so fuck you)
			document.getElementById('recoverkey').focus();

			} else {

			document.getElementById('newpassword').focus();

			}

		}

	},

	/**
	 * Input focus
	 *
	 * @access	public
	 * @param	string  The first field of form
	 * @param	string 	The second field of form
	 * @return	void
	 */
	input_focus: function(selector, selector2) {

		// Check if selectors exists
		if ($(selector).length > 0 && $(selector2).length > 0) {

			// Empty first field
			if ($(selector).val() == '') {

				$(selector).focus();

			} else {

				if ($(selector2).val() == '') {

					$(selector2).focus();

				}

			}

		}

	},

	/**
	 * Autosize textarea
	 *
	 * @access	public
	 * @return	void
	 */
	 autosize_textarea: function() {

	 	$('textarea').autosize();
	 	$('textarea').addClass('textarea-transition');

	 },

	/**
	 * Permalink click
	 *
	 * System permalink on profile creations
	 *
	 * @access	public
	 * @param	object	Selector jQuery
	 * @return	void
	 */
	 permalink_click: function(selector) {

	 	var permalink = selector.data('share');
	 	var id = selector.data('id');

		// Load view and inject datas
		var html = this.load.view('utilities/permalink/input', {link: permalink, id: id});
		
		selector.hide();
		selector.after(html);
		$('#focus-' + id).focus();

	},

	/**
	 * Permalink out
	 *
	 * System permalink on profile creations
	 *
	 * @access	public
	 * @param	object	Selector jQuery
	 * @return	void
	 */
	 permalink_out: function(selector) {

	 	var id = selector.data('id');
	 	$('#inject-' + id).hide();
	 	$('#inject-' + id).removeClass('form-same-line-without-width');
	 	$('#share-' + id).show();


	 }



	});
