<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends MY_Controller {

    public function __construct()
    {
        parent::MY_Controller();
    }
	
    public function index()
    {
        // remaped...
    }
	
    public function _remap()
    {
        // default method
        if ($this->uri->segment($this->get('uri.segment')) == '') {
            $this->_frontside_load_tpl();
        }
        // custom method
        else {
            $this->_frontside_load_tpl($this->uri->segment($this->get('uri.segment')));
        }
    }

}

/* End of file index.php */
/* Location: ./application/controllers/index.php */