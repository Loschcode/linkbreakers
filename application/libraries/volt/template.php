<?php

/**
 * Template class
 *
 * Manage view and datas
 *
 * @author      Jeremie Ges
 * @copyright   2013
 * @category    Class
 *
 */

class Template extends CI_Context {


    public $special_lang_load = array();
    public $data = array();


    /**
    *
    * Construct
    *
    * Get CI context / Very important
    *
    */
	public function __construct() {

        parent::__Construct();
        
	}

    /**
    * Set
    *
    * Inject datas in "data" var
    *
    * @access  public
    * @param   string  Label of var
    * @param   string  Value of var
    * @return  bool
    *
    */
    public function set($label, $value) {

        if ($this->data[$label] = $value) 
            return TRUE;
        
        return FALSE;

    }

    /**
    * Get
    *
    * Get value of var
    *
    * @access  public
    * @param   string  Label of var
    * @return  mixed
    *
    */
    public function get($label) {

        return $this->data[$label];

    }

    /**
    * Set lang
    *
    * Set file or files lang
    *
    * @access  public
    * @param   string  Name file to load
    * @return  void
    *
    */
    public function set_lang($file) {

        if (is_array($file)) {

            foreach ($file as $value) {

                $this->special_lang_load[] = $value;

            }

        }

        $this->special_lang_load[] = $file;

    }

    /**
    * Launch admin
    *
    * Run admin templates
    *
    * @access  public
    * @param   string  View to load
    * @param   array   Datas to inject
    * @return  void
    *
    */
    public function launch_admin($template='', $data=array()) {

        // Set the subpage if there isn't one defined
        if (!isset($this->data['subpage'])) $this->set('subpage', '');
        if (!isset($this->data['title'])) $this->set('title', 'Dashboard');

        $data = array_merge($data, $this->data, $this->pikachu->show_all());

        $content = $this->load->view('_struct/header_admin', $data, TRUE);
        $content .= $this->load->view('_repeat/nav_admin', $data, TRUE);
        $content .= $this->load->view('admin/'.$template, $data, TRUE);
        $content .= $this->load->view('_struct/footer_admin', $data, TRUE);

        echo $content;
        exit();

    }

    /**
    * Launch
    *
    * Run template
    *
    * @access  public
    * @param   string  View to load
    * @param   array   Datas to inject
    * @return  void
    *
    */
    public function launch($template='', $data=array()) {

        /**
        *
        * Lang part
        *
        */
        $lang_small = Language::pick('short');
        $lang_large = Language::pick();

        $this->data['lang_small'] = $lang_small;

        // Set the subpage if there isn't one defined
        if (!isset($this->data['subpage'])) $this->set('subpage', '');
        if (!isset($this->data['title'])) $this->set('title', 'Beta');

        // Load struct lang (header, footer, nav and current view)
        $this->launch_languages($lang_large);

        // Add other lang file ?
        if (! empty($this->special_lang_load)) {

            $this->lang->load($this->special_lang_load, $lang_large);

        }

        /**
        *
        * Permalink part
        *
        */
        $instant_permalink = $this->pikachu->show('permalink');

        // TinyURL generation (only if URL is an inside part)
        if (!empty($instant_permalink)) $instant_permalink = $this->panda->generate_tinyurl($instant_permalink);

        // Set auto Permalink
        $this->set('permalink', $instant_permalink);

        // Delete value stored in session
        $this->pikachu->delete('permalink');

        /**
        *
        * Tools part
        *
        */
        if ($this->data['page'] === 'tools') {

            // Voice system
            $this->set('voice', $this->pikachu->show('voice'));
            $this->pikachu->delete('voice');

        }

        /**
        *
        * General
        *
        */
        if (!is_array($data)) {

            $data = (array) $data;
        
        }

        $repeat_data = $this->_repeat_data();

        // Merge all data
        $data = array_merge($this->pikachu->show_all(), $this->lang->language, $this->data, $repeat_data, $data);

        // Very big bug with strong-type and controller loops within search engine on 06/04/2013 so we use $content and display it manually
        $content = $this->load->view('_struct/header', $data, TRUE);
        $content .= $this->load->view($template, $data, TRUE);
        $content .= $this->load->view('_struct/footer', $data, TRUE);

        echo $content;
        exit();

    }

    /**
    * Here we launch the languages matching with the templates to load
    *
    * @access  private
    * @return  void
    *
    */
    private function launch_languages($lang_large) {

        $this->lang->load('header', $lang_large);
        $this->lang->load('footer', $lang_large);
        $this->lang->load('nav', $lang_large);
        $this->lang->load($this->data['page'], $lang_large); 

    }

    /**
    * Repeat data
    *
    * Set file or files lang
    *
    * @access  private
    * @param   string  Name file to load
    * @return  void
    *
    */
    private function _repeat_data() {

        $repeat = array();

        // Connected ?
        if ($userid = $this->pikachu->show('userid')) {

            // How many creations for this user ?
            $repeat['num_creations'] = $this->log_model->get_num_creations($userid, array('global', 'trans', 'beta', 'private'));

        }

        $repeat['userstatus'] = $this->pikachu->show('userstatus');
        $repeat['userid'] = $this->pikachu->show('userid');
        $repeat['usercreations'] = $this->pikachu->show('usercreations');
        $repeat['username'] = $this->pikachu->show('username');
        $repeat['userspace'] = $this->pikachu->show('userspace');

        $repeat['canEdit'] = $this->pikachu->show('canEdit');
        $repeat['onSandbox'] = $this->pikachu->show('onSandbox');

        

        return $repeat;


    }

}

?>