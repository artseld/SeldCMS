<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Error extends MY_Controller {
 
    //public function __construct()
    //{
    //	@session_start();
    //}

    public function index()
    {
        // remapped...
        //$this->error_404();
    }

    public function error_404()
    {
        // remapped...
        //$this->output->set_status_header('404');
        //$this->_frontside_load_tpl($this->get('resource.url_error_404'));
        //echo 'Error 404 : Page not found. Resource Id : ' . $this->get('resource.id');
    }

    public function _remap()
    {
        // backside error
        if ($this->uri->segment($this->get('uri.segment')) == 'admin') {
            redirect('/admin/main');
        }
        // frontside empty method
        elseif ($this->uri->segment($this->get('uri.segment')) == '') {
            $this->output->set_status_header('404');
            $this->_frontside_load_tpl($this->get('resource.url_error_404'));
        }
        // custom method
        else {
            $this->output->set_status_header('404');
            $this->_frontside_load_tpl($this->get('resource.url_error_404'));
        }
    }
}

/* End of file error.php */
/* Location: ./application/controllers/error.php */