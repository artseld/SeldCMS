<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Userdata_model extends CI_Model {

    protected $_user_data;
    protected $_modules_privileges;

    public function __construct()
    {
        parent::__construct();
    }

    public function get_user()
    {
        // get user and group data
        $this->_user_data = $this->db->query('
            select
                u.id as id,
                u.auth_login as auth_login,
                u.first_name as first_name,
                u.middle_name as middle_name,
                u.last_name as last_name,
                u.private_message as private_message,
                u.time_registration as time_registration,
                u.flag_verification as flag_verification,
                u.flag_access as flag_access,
                u.id_group as id_group,
                ug.title as user_group,
                ug.flag_frontside_access as flag_frontside_access,
                ug.flag_backside_access as flag_backside_access
            from
                ' . $this->db->dbprefix('users') . ' as u,
                ' . $this->db->dbprefix('users_groups') . ' as ug
            where
                u.id=' . $this->session->userdata('logged_as') . ' and
                u.id_group=ug.id
            limit 1
        ')->row_array();

        // return user data to controller
        return $this->_user_data;
    }

    public function get_modules_privileges()
    {
        // prepare user data if it not exist
        if (is_null($this->_user_data)) $this->get_user();

        // get user modules privileges
        $user_modules_privileges = $this->db->query('
            select
                ugfp.id_module as id_module,
                m.alias as alias,
                ugfp.flag_access as flag_access
            from
                ' . $this->db->dbprefix('users_groups_frontside_privileges') . ' as ugfp,
                ' . $this->db->dbprefix('modules') . ' as m
            where
                ugfp.id_group=' . $this->_user_data['id_group'] . ' and
                ugfp.id_module=m.id
        ');

        // convert user modules privileges object
        foreach ($user_modules_privileges->result() as $row)
        {
            $this->_modules_privileges[$row->alias] = ($row->flag_access) ? TRUE : FALSE;
        }

        // return modules privileges to controller
        return $this->_modules_privileges;
    }
}

/* End of file userdata_model.php */
/* Location: ./application/models/userdata_model.php */