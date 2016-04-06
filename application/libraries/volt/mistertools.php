<?php

/**
 * MisterTools class
 *
 * Handle Linkbreakers Tools from any controller
 *
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Class
 *
 */

class Mistertools extends CI_Context {

	public function __construct() {

		parent::__Construct();

		// Set page -> normally in controllers, but it's a special library
		$this->template->set('page', 'tools');

	}

	/**
	 * INTRODUCTION
	 *
	 * In this page, each tool is a function
	 * Each argument has to get a default set
	 * Be carefull building something.
	 *
	 */

	/**
	 * TOOLS/STICK
	 *
	 * It redirects the user on the home/userspace page
	 * And adds a "stick" (a formatted text) below our search engine
	 *
	 */
	public function stick($stick='') {

		$stick = (string) $stick;

		if ($this->pikachu->show('tools/stick')) {

			$stick_page = $this->pikachu->show('tools/_userspace');

			// If it's from the home page, the page was set as 'search' and we need to redirect to the home page
			// Otherwise we will redirect to the correct user space
			if ($stick_page === 'search') direct(base_url());
			else direct(base_url($stick_page)); // Redirect -> linked with controller/search line ~240

			/*$data['stick_content'] = $this->pikachu->show('tools/stick');

			// If it from a user context
			if ($stick_page) $this->template->launch('search_from_user', $data);
			else $this->template->launch('search', $data);*/

		//} elseif (!empty($stick)) direct(base_url('/search/?request='.$stick));
		} else direct(base_url());
		// -> I didn't remember why this elseif existed, so i canceled it
		// It was originally for something but everything works with PikachuSession, i canceled the system

	}

	/**
	 * TOOLS/RAW
	 *
	 * It redirects the user on a raw page (without HTML)
	 * And adds what was set
	 *
	 */
	public function raw($raw='') {

		$raw = (string) $raw;

		if ($this->pikachu->show('tools/raw')) {

			$raw_content = $this->pikachu->show('tools/raw');
			$this->pikachu->delete('tools/raw');

		/*} elseif (!empty($raw)) { direct(base_url('/search/?request='.$raw));*/
		} else direct(base_url());

		echo $raw_content;

	}

	/**
	 * TOOLS/TEXT
	 *
	 * It redirects the user on a similar page to home/userspace
	 * And adds a (formatted)text on it
	 *
	 */
	public function text($text='') {

		$text = (string) $text;

		// If there's a pikachu session inside, it will be the content
		if ($this->pikachu->show('tools/text')) {

			/*$bidule = $this->pikachu->show('tools/_userspace');
			var_dump($bidule);*/

			$content = $this->pikachu->show('tools/text');
			$this->pikachu->delete('tools/text');

		/*} elseif (!empty($text)) { direct(base_url('/search/?request='.$text));*/
		} else direct(base_url());

		//////////////////////
		// Final treatments //
		//////////////////////

		// HTML Conversions to set a correct display
		$content = $this->panda->display_tool_format($content);

		// \n to <br />
		//$content = str_replace("\n", "<br />", $content); -> deleted on 08/04/2013

		// We set the correct variable now
		$data['content'] = $content;

		// Is here a voice system enabled ?
		if ($this->pikachu->show('voice')) {

			// We load the library manually
			$this->load->library('volt/lyza');

			// We treat our string
			$data['voice_content'] = $this->lyza->audio_string($content);

		 }

		// We launch the final template (depending on userspace or not)
		$call_from_userspace = $this->pikachu->show('tools/_call_from_userspace');

		if ($call_from_userspace === TRUE) {

			$id_userspace = $this->pikachu->show('tools/_id_userspace');

			// We set the location/subpage
			$this->template->set('subpage', 'tools/text');

			$this->template->set('space_user', $this->log_model->get_user_detail('log', $id_userspace, 'username'));
			$this->template->set('space_avatar', $this->log_model->get_user_detail('space', $id_userspace, 'space_avatar'));
			
			$this->template->launch('tools/text_from_user', $data);

		} else $this->template->launch('tools/text', $data);

	}

	/**
	 * TOOLS/SANDBOX
	 *
	 * It redirects the user on the sandbox page
	 * And adds the LBL-code you want to understand
	 *
	 * WARNING : This tool is used by the sandbox itself, don't change it without testing the sandbox afterwards.
	 *
	 */
	public function sandbox($sandbox='') {

		// Multiargs and '/' failure solution (we set one arg with all the args and set '/' between them)
		if ($sandbox !== '') {

			$arr_strings = func_get_args();
			$sandbox = implode('/', $arr_strings);

		}

		// Ergonomic system -> Sandbox button will appear after we use it (seems legit)
		$this->pikachu->set('onSandbox', TRUE);

		// We get our datas from pikachu
		if ($this->pikachu->show('tools/sandbox')) {

			$sandbox = $this->pikachu->show('tools/sandbox');
			$this->pikachu->delete('tools/sandbox');
		
		} elseif (empty($sandbox)) $sandbox = 'Welcome in your Sandbox !';

		$request = rawurldecode(trim($sandbox));

		// Last treatment
		$result = $this->panda->understand_functions($request);
		$request = htmlentities($request);

		// We set
		$data['request'] = $request;
		$data['result'] = $result;

		// We launch the sandbox
		$this->template->launch('tools/sandbox', $data);

	}

}