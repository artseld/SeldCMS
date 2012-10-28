<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends MY_Controller {

    protected $_error = FALSE;

    public function __construct()
    {
        parent::MY_Controller();
    }
	
    public function index()
    {
        if ($this->session->userdata('logged_as') && $this->get('userdata')->flag_backside_access) redirect('/admin/main');

        $this->_load_tpl();
    }

    public function enter()
    {
        if ($this->session->userdata('logged_as') && $this->get('userdata')->flag_backside_access) redirect('/admin/main');

        $this->load->library('form_validation');
			
        $this->form_validation->set_rules('user_login', 'lang:FIELD_USER_LOGIN', 'trim|required|max_length[25]|xss_clean');
        $this->form_validation->set_rules('user_password', 'lang:FIELD_USER_PASSWORD', 'trim|required|max_length[25]');
		
        if ($this->input->post('user_login') && $this->input->post('user_password'))
        {
            $query = $this->db->query('
                select
                    u.id as id
                from
                    '.$this->db->dbprefix('users').' as u,
                    '.$this->db->dbprefix('users_groups').' as ug
                where
                    u.auth_login='.$this->db->escape($this->input->post('user_login')).' and
                    u.auth_password=OLD_PASSWORD('.$this->db->escape($this->input->post('user_password')).') and
                    u.flag_verification=1 and
                    u.flag_access=1 and
                    u.id_group=ug.id and
                    ug.flag_backside_access=1
                limit 1
            ');
            if ($query->num_rows() > 0)
            {
                $row = $query->row();
                $logged_as = $row->id;
                $query = TRUE;
            }
            else
            {
                $query = FALSE;
            }
        }
        else
        {
            $query = FALSE;
        }

        // fields validation error
        if ($this->form_validation->run() == FALSE)
        {
            $this->_error = TRUE;
        }
        // user not found
        elseif (!$query)
        {
            $this->ipblock_model->block_ip();
            $this->_error = TRUE;
        }
        // all ok, redirect to management area
        else
        {
            $this->session->set_userdata('logged_as', $logged_as);
            redirect('/admin/main');
        }

        $this->_load_tpl();
    }

    public function quit()
    {
        if ($this->session->userdata('logged_as') && !$this->get('userdata')->flag_backside_access) redirect('/admin/auth');
        $this->session->unset_userdata('logged_as');
        redirect('/admin/auth');
    }

    protected function _load_tpl()
    {
        $this->set('lang', $this->lang);
        $this->set('error', $this->_error);
        $this->load->view('admin/header_tpl', $this->get());
        $this->load->view('admin/auth_tpl', $this->get());
        $this->load->view('admin/footer_tpl', $this->get());
    }
}

/* End of file admin/auth.php */
/* Location: ./application/controllers/admin/auth.php */