<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logs extends MY_Controller {

    public function __construct()
    {
        parent::MY_Controller();

        if (!$this->session->userdata('logged_as')) redirect('/admin/auth');

        $this->set('title', $this->lang->line('NAV_TITLE_LOGS'));
    }

    // controller page
    public function index()
    {
        // if privileges
        if ($this->get('components_privileges')->logs->view)
        {
            $this->_backside_load_tpl('in_dev');
        }

        // no privileges
        else
        {
            $this->_backside_load_tpl_no_privileges();
        }
    }
}

/* End of file admin/logs.php */
/* Location: ./application/controllers/admin/logs.php */