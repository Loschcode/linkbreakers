<?php

/**
 * Flower class
 *
 * Everything about the pictures management
 *
 * @author		Laurent SCHAFFNER
 * @copyright   2013
 * @category	Class
 *
 */

class Flower extends CI_Context {

	public function __construct() {
		
		parent::__Construct();

	}

	/**
 	*
 	* Upload a picture
 	*
 	* @access public
 	* @param array $config what do we need to set to upload it ?
 	* @return mixed (???)
 	*
 	* NOTE :
 	*
 	* $config['name_file'] - Key index $_FILES
 	* $config['destination'] - Destination
 	*
 	*/
	public function upload_picture($config) {

		$name_file = '';
		$type = '';
		$destination = '';
		$min_height = '';
		$min_width = '';

		if (isset($config['name_file'])) $name_file = $config['name_file'];
		if (isset($config['type'])) $type = $config['type'];
		if (isset($config['destination'])) $destination = $config['destination'];

		// Protect
		unset($config);

		$this->load->library('upload');

		$config['upload_path'] = 'assets/uploads/' . ltrim($destination, '/') . '/';

		$config['allowed_types'] = 'gif|jpg|png|bmp';
		$config['max_size'] = 1000;
		$config['max_width'] = 1200;				
		$config['max_height'] = 900;

		$config['file_name'] = uniqid();
		
		$this->upload->initialize($config);

		$resume = array();

		if ($this->upload->do_upload($name_file)) {

			$resume['success_upload'] = TRUE;
			$resume['about_upload'] = $this->upload->data();


		} else {

			$resume['success_upload'] = FALSE;
			$resume['about_errors'] = $this->upload->display_errors();

		}


		return $resume;

	}

	/**
 	*
 	* Resize a picture (check the CI's documentation for further information)
 	*
 	* @access public
 	* @param string $src the path of your picture
 	* @param integer $width the size we will set the picture
 	* @param integer $height as above
 	* @param string $master_dim which way will you use to resize properly this picture ? (cf. CI's doc)
 	* @return void
 	*
 	*/
	public function resize_picture($src, $width, $height, $master_dim='auto') {

		$config['image_library'] = 'gd2';
		$config['source_image']	= $src;
		$config['maintain_ratio'] = TRUE;
		$config['master_dim'] = $master_dim;
		$config['width'] = $width;
		$config['height'] = $height;

		$this->load->library('image_lib', $config); 

		$this->image_lib->resize();

	}

}