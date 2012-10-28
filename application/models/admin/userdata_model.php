<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Userdata_model extends CI_Model {

    protected $_user_data;
    protected $_components_privileges;
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
                ug.flag_backside_access as flag_backside_access,
                "" as user_components_privileges,
                "" as user_modules_privileges
            from
                ' . $this->db->dbprefix('users') . ' as u,
                ' . $this->db->dbprefix('users_groups') . ' as ug
            where
                u.id=' . $this->session->userdata('logged_as') . ' and
                u.id_group=ug.id
            limit 1
        ')->row();

        // get user components privileges
        $this->_user_data->user_components_privileges = $this->db->query('
            select
                ugbp.id_element as id_component,
                c.alias as alias,
                ugbp.flag_view_access as flag_view_access,
                ugbp.flag_edit_access as flag_edit_access,
                ugbp.flag_add_access as flag_add_access,
                ugbp.flag_delete_access as flag_delete_access
            from
                ' . $this->db->dbprefix('users_groups_backside_privileges') . ' as ugbp,
                ' . $this->db->dbprefix('components') . ' as c
            where
                ugbp.id_group=' . $this->_user_data->id_group . ' and
                ugbp.type_element=\'component\' and
                ugbp.id_element=c.id
        ');

        // get user modules privileges
        $this->_user_data->user_modules_privileges = $this->db->query('
            select
                ugbp.id_element as id_module,
                m.alias as alias,
                ugbp.flag_view_access as flag_view_access,
                ugbp.flag_edit_access as flag_edit_access,
                ugbp.flag_add_access as flag_add_access,
                ugbp.flag_delete_access as flag_delete_access
            from
                ' . $this->db->dbprefix('users_groups_backside_privileges') . ' as ugbp,
                ' . $this->db->dbprefix('modules') . ' as m
            where
                ugbp.id_group=' . $this->_user_data->id_group . ' and
                ugbp.type_element=\'module\' and
                ugbp.id_element=m.id
        ');

        // root status fix
        if ($this->_user_data->id_group == 0) $this->_user_data->user_group = $this->lang->line('FIELD_ROOT_STATUS');

        // return user data to controller
        return $this->_user_data;
    }

    public function get_components_privileges()
    {
        // prepare user data if it not exist
        if (is_null($this->_user_data)) $this->get_user();

        // convert user components privileges object
        foreach ($this->_user_data->user_components_privileges->result() as $row)
        {
            $this->_components_privileges->{$row->alias}->view = ($row->flag_view_access) ? TRUE : FALSE;
            $this->_components_privileges->{$row->alias}->edit = ($row->flag_edit_access) ? TRUE : FALSE;
            $this->_components_privileges->{$row->alias}->add = ($row->flag_add_access) ? TRUE : FALSE;
            $this->_components_privileges->{$row->alias}->delete = ($row->flag_delete_access) ? TRUE : FALSE;
        }

        // Set FilesAccess flag into session if file browsing resolved
        if ($this->_components_privileges->files->view && $this->_components_privileges->files->edit
            && $this->_components_privileges->files->add && $this->_components_privileges->files->delete) {
            $_SESSION['FilesAccess'] = 'yep!';
        } elseif (isset($_SESSION['FilesAccess'])) unset($_SESSION['FilesAccess']);

        // return components privileges to controller
        return $this->_components_privileges;
    }
	
    public function get_modules_privileges()
    {
        // prepare user data if it not exist
        if (is_null($this->_user_data)) $this->get_user();

        // convert user modules privileges object
        foreach ($this->_user_data->user_modules_privileges->result() as $row)
        {
            $this->_modules_privileges->{$row->alias}->view = ($row->flag_view_access) ? TRUE : FALSE;
            $this->_modules_privileges->{$row->alias}->edit = ($row->flag_edit_access) ? TRUE : FALSE;
            $this->_modules_privileges->{$row->alias}->add = ($row->flag_add_access) ? TRUE : FALSE;
            $this->_modules_privileges->{$row->alias}->delete = ($row->flag_delete_access) ? TRUE : FALSE;
        }

        // return modules privileges to controller
        return $this->_modules_privileges;
    }
}

/* End of file userdata_model.php */
/* Location: ./application/models/admin/userdata_model.php */