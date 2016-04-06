<?php

/**
 * Hermes class
 *
 * Manage all the email and shortcut system
 *
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Class
 *
 */

class Hermes extends CI_Context {

	public function __construct() {

		parent::__Construct();

	}

	public $from_name = 'Linkbreakers';
	public $from_email = 'laurent@linkbreakers.com';
	public $archive_email = 'archives@linkbreakers.com';

	/**
	 * Linkbreakers emails sender
	 *
	 * @access public
	 * @param string $from_email the sender
	 * @param string $from_name the sender name
	 * @param string $to_email the getter
	 * @param string $subject the topic
	 * @param string $message the content
	 * @return bool
	 *
	 */
	public function send_email($from_email, $from_name, $to_email, $subject, $message) {

     	$headers  = 'MIME-Version: 1.0' . "\r\n";
     	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
     	$headers .= 'From: '. $from_name .' <'. $from_email .'>' . "\r\n";

		return mail($to_email, $subject, $message, $headers);

	}
	/**
	 * Linkbreakers email template integration
	 *
	 * @access public
	 * @param string $email the getter
	 * @param string $template the corresponding template (e.g. 'forgot_password')
	 * @param string $subject the topic
	 * @param array $content the variables that will be (smartly) put to the template
	 * @return bool
	 *
	 */
	public function send_template_email($email, $template, $subject, array $content) {

		$template_header = $this->load->view('emails/_struct/email_header', $content, TRUE);
		$template_content = $this->load->view('emails/'.$template, $content, TRUE);
		$template_footer = $this->load->view('emails/_struct/email_footer', $content, TRUE);

		$template_complete = $template_header . $template_content . $template_footer;

		return $this->send_email('laurent@linkbreakers.com', 'Linkbreakers', $email, $subject, $template_complete);

	}


}