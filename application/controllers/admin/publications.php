<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Publications extends MY_Controller {

    public function __construct()
    {
        parent::MY_Controller();

        if (!$this->session->userdata('logged_as')) redirect('/admin/auth');

        $this->lang->load('publications', 'english');

        $this->set('title', $this->lang->line('MODULE_NAME'));
    }
	
    // controller page
    public function index()
    {
        // if privileges
        if ($this->get('modules_privileges')->publications->view)
        {
            $this->_backside_load_tpl(__CLASS__);
        }

        // no privileges
        else
        {
            $this->_backside_load_tpl_no_privileges();
        }
    }
}

/* End of file admin/publications.php */
/* Location: ./application/controllers/admin/publications.php */