<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends MY_Controller {

    public function __construct()
    {
        parent::MY_Controller();
    }

    public function index()
    {
        if ($this->session->userdata('logged_as') && $this->get('userdata')->flag_backside_access) redirect('/admin/main');
        else redirect('/admin/auth');
    }
}

/* End of file admin/index.php */
/* Location: ./application/controllers/admin/index.php */