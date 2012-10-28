<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends MY_Controller {

    public function __construct()
    {
        parent::MY_Controller();

        if (!$this->session->userdata('logged_as')) redirect('/admin/auth');

        $this->set('title', $this->lang->line('NAV_TITLE_USERS'));

        // search pre-actions
        $this->set('keywords', array( 'group' => '', 'text' => '' ));
        if ($this->session->flashdata('keywords_u'))
        {
            $this->set('keywords', $this->session->flashdata('keywords_u'));
            $this->session->keep_flashdata('keywords_u');
        }
        else $this->session->flashdata('keywords_u', array( 'group' => '', 'text' => '' ));
    }

    // controller page
    public function index($page = 0)
    {
        // if privileges
        if ($this->get('components_privileges')->users->view)
        {
            $this->set('user_contacts', $this->_get_user_contacts());

            // init list
            $this->_init_list($page);

            $this->_backside_load_tpl(__CLASS__);

            // delete temporary session data
            $this->_backside_delete_tmp_session_data();
        }

        // no privileges
        else
        {
            $this->_backside_load_tpl_no_privileges();
        }
    }

    // selected page
    public function page($page = 0)
    {
        $this->index($page);
    }

    // set keywords
    public function set_keywords()
    {
        if ($this->get('components_privileges')->users->view && $this->input->post())
        {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('group', 'lang:FIELD_GROUP', 'trim|max_length[250]|xss_clean');
            $this->form_validation->set_rules('text', 'lang:FIELD_KEYWORDS', 'trim|max_length[250]|xss_clean');

            // if validation return error
            if ($this->form_validation->run() != FALSE)
            {
                $this->session->set_flashdata('keywords_u', array(
                    'group' => $this->input->post('group', TRUE),
                    'text' => $this->input->post('text', TRUE)
                ));
            }

            redirect('/admin/users');
        }

        // no privileges
        else
        {
            $this->_backside_load_tpl_no_privileges();
        }
    }

    // clear keywords
    public function clear_keywords()
    {
        if ($this->get('components_privileges')->users->view)
        {
            $this->session->set_flashdata('keywords_u', array(
                'group' => '',
                'text' => ''
            ));

            redirect('/admin/users');
        }

        // no privileges
        else
        {
            $this->_backside_load_tpl_no_privileges();
        }
    }

    // add user
    public function add_user()
    {
        // if privileges
        if ($this->get('components_privileges')->users->add)
        {
            $this->set('user_contacts', $this->_get_user_contacts());

            if ($this->input->post())
            {
                $this->load->library('form_validation');

                $this->form_validation->set_rules('id_group', 'lang:FIELD_GROUP', 'trim|required|is_natural|xss_clean');
                $this->form_validation->set_rules('auth_login', 'lang:FIELD_USER_LOGIN', 'trim|required|max_length[25]|alpha_dash|xss_clean');
                $this->form_validation->set_rules('auth_password', 'lang:FIELD_USER_PASSWORD', 'trim|required|min_length[6]|max_length[25]');
                $this->form_validation->set_rules('auth_passwordv', 'lang:FIELD_USER_PASSWORD_VERIFY', 'trim|required|min_length[6]|max_length[25]|matches[auth_password]');
                $this->form_validation->set_rules('first_name', 'lang:FIELD_USER_FIRST_NAME', 'trim|required|max_length[50]|xss_clean');
                $this->form_validation->set_rules('middle_name', 'lang:FIELD_USER_MIDDLE_NAME', 'trim|max_length[50]|xss_clean');
                $this->form_validation->set_rules('last_name', 'lang:FIELD_USER_LAST_NAME', 'trim|max_length[50]|xss_clean');
                $this->form_validation->set_rules('birthday', 'lang:FIELD_USER_BIRTHDAY', 'trim|max_length[10]|matches_pattern[####-##-##]|xss_clean');
                $this->form_validation->set_rules('gender', 'lang:FIELD_USER_GENDER', 'trim|required|min_length[4]|max_length[6]|xss_clean');
                $this->form_validation->set_rules('country', 'lang:FIELD_USER_COUNTRY', 'trim|max_length[100]|xss_clean');
                $this->form_validation->set_rules('city', 'lang:FIELD_USER_CITY', 'trim|max_length[50]|xss_clean');
                $this->form_validation->set_rules('address', 'lang:FIELD_USER_ADDRESS', 'trim|max_length[250]|xss_clean');
                $this->form_validation->set_rules('email', 'lang:FIELD_USER_EMAIL', 'trim|required|max_length[50]|valid_email|xss_clean');
                $this->form_validation->set_rules('additional', 'lang:FIELD_USER_ADDITIONAL', 'trim|xss_clean');
                $this->form_validation->set_rules('visibility_type', 'lang:FIELD_USER_VISIBILITY', 'trim|required|min_length[3]|max_length[7]|xss_clean');
                $this->form_validation->set_rules('rating', 'lang:FIELD_USER_RATING', 'trim|is_numeric|xss_clean');
                $this->form_validation->set_rules('flag_verification', 'lang:FIELD_USER_VERIFICATION', 'trim|trigger|xss_clean');
                $this->form_validation->set_rules('flag_access', 'lang:FIELD_USER_ACCESS', 'trim|trigger|xss_clean');
                $this->form_validation->set_rules('datetime_registration', 'lang:FIELD_DATETIME_REGISTRATION', 'trim|max_length[19]|matches_pattern[####-##-## ##:##:##]|xss_clean');

                $this->_set_user_contacts_validation( $this->get('user_contacts') );

                // if validation return error
                if ($this->form_validation->run() == FALSE)
                {
                    $this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_ADD_USER'));

                    $this->_action_error = TRUE;
                }
                // if successfully verified
                else
                {
                    // upload files
                    if (is_uploaded_file($_FILES['avatar']['tmp_name'])) {
                        $this->load->model('file_model');
                        $upload_result = $this->file_model->do_upload(File_model::UPLOAD_AVATAR, 'avatar', true);
                        if ($upload_result['status']) {
                            $avatar = array(
                                'filename' => $upload_result['config']['upload_prefix'] . $upload_result['data']['file_name'],
                                'mimetype' => $upload_result['data']['file_type']
                            );
                        }
                    }

                    // insert data
                    $this->db->query('
                        insert into
                            ' . $this->db->dbprefix('users') . '
                        select
                            0,
                            ug.id,
                            ' . $this->db->escape($this->input->post('auth_login')) . ',
                            OLD_PASSWORD(' . $this->db->escape($this->input->post('auth_password')) . '),
                            ' . $this->db->escape($this->input->post('first_name')) . ',
                            ' . $this->db->escape($this->input->post('middle_name')) . ',
                            ' . $this->db->escape($this->input->post('last_name')) . ',
                            ' . (isset($avatar) ? $this->db->escape($avatar['filename']) . ', '
                        . $this->db->escape($avatar['mimetype']) . ', ' : 'NULL, NULL, ') . '
                            ' . $this->db->escape($this->input->post('birthday')) . ',
                            ' . $this->db->escape($this->input->post('gender')) . ',
                            ' . $this->db->escape($this->input->post('country')) . ',
                            ' . $this->db->escape($this->input->post('city')) . ',
                            ' . $this->db->escape($this->input->post('address')) . ',
                            ' . $this->db->escape($this->input->post('email')) . ',
                            ' . $this->db->escape($this->input->post('additional')) . ',
                            \'\',
                            ' . ($this->input->post('datetime_registration') == '' ? 'now()' : $this->db->escape($this->input->post('datetime_registration'))) . ',
                            ' . $this->db->escape($this->input->post('visibility_type')) . ',
                            ' . $this->db->escape($this->input->post('rating')) . ',
                            NULL,
                            ' . $this->db->escape($this->input->post('flag_verification')) . ',
                            ' . $this->db->escape($this->input->post('flag_access')) . '
                        from
                            ' . $this->db->dbprefix('users_groups') . ' as ug
                        where
                            ug.id=' . $this->db->escape($this->input->post('id_group')) . '
                            ' . ($this->get('userdata')->id_group ? 'and ug.id!=0' : '') . '
                    ');

                    // if query ok
                    if (!$this->db->_error_number() && $this->db->affected_rows())
                    {
                        // additional queries
                        $values = array();
                        foreach ($this->get('user_contacts')->result() as $row) {
                            $values[] = '(' . $this->db->insert_id() . ', ' . $row->id
                                . ', ' . $this->db->escape($this->input->post('user_contacts_value_id_' . $row->id))
                                . ', ' . $this->db->escape($this->input->post('user_contacts_visibility_id_' . $row->id)) . ')';
                        }
                        $this->db->query('
                            insert into
                                ' . $this->db->dbprefix('users_contacts') . '
                            values
                                ' . implode(',', $values) . '
                            where
                                id_user=' . $this->db->insert_id() . '
                        ');

                        // additional actions
                        // NOTHING

                        // if file upload error
                        if (isset($upload_result) && !$upload_result['status']) {
                            $this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_ADD_USER_WITHOUT_AVA'));
                        } else {
                            $this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_ADD_USER'));
                        }
                    }
                    // if query failed
                    elseif (!$this->db->affected_rows())
                    {
                        $this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_ADD_USER'));

                        $this->_action_error = TRUE;
                    }
                    // database error
                    else
                    {
                        $this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_DB_ERROR') . ' [' . $this->db->_error_number() . '] ' . $this->db->_error_message());

                        $this->_action_error = TRUE;
                    }
                }
            }
            else
            {
                $this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_NO_POSTDATA'));
            }

            // if action error
            if ($this->_action_error)
            {
                // set form data
                $this->session->set_userdata('formdata', array_merge(array(
                    'id_group' => $this->input->post('id_group', TRUE),
                    'auth_login' => $this->input->post('auth_login', TRUE),
                    'first_name' => $this->input->post('first_name', TRUE),
                    'middle_name' => $this->input->post('middle_name', TRUE),
                    'last_name' => $this->input->post('last_name', TRUE),
                    'birthday' => $this->input->post('birthday', TRUE),
                    'gender' => $this->input->post('gender', TRUE),
                    'country' => $this->input->post('country', TRUE),
                    'city' => $this->input->post('city', TRUE),
                    'address' => $this->input->post('address', TRUE),
                    'email' => $this->input->post('email', TRUE),
                    'additional' => $this->input->post('additional', TRUE),
                    'datetime_registration' => $this->input->post('datetime_registration', TRUE),
                    'visibility_type' => $this->input->post('visibility_type', TRUE),
                    'rating' => $this->input->post('rating', TRUE),
                    'flag_verification' => ($this->input->post('flag_verification') ? TRUE : FALSE),
                    'flag_access' => ($this->input->post('flag_access') ? TRUE : FALSE)
                ), $this->_get_user_contacts_formdata($this->get('user_contacts'))));

                redirect('/admin/users#add_user');
            }
            // if action ok
            else
            {
                redirect('/admin/users');
            }
        }

        // no privileges
        else
        {
            $this->_backside_load_tpl_no_privileges();
        }
    }

    // edit user
    public function edit_user($id_user = 0)
    {
        // if privileges
        if ($this->get('components_privileges')->users->edit)
        {
            if (is_numeric($id_user) && $id_user > 0)
            {
                $this->set('user_contacts', $this->_get_user_contacts());
                $this->set('user_contacts_edit', $this->_get_user_contacts( $id_user ));

                if ($this->input->post())
                {
                    $this->load->library('form_validation');

                    $this->form_validation->set_rules('id_group', 'lang:FIELD_GROUP', 'trim|required|is_natural|xss_clean');
                    $this->form_validation->set_rules('auth_login', 'lang:FIELD_USER_LOGIN', 'trim|required|max_length[25]|alpha_dash|xss_clean');
                    $this->form_validation->set_rules('auth_password', 'lang:FIELD_USER_PASSWORD', 'trim|min_length[6]|max_length[50]');
                    $this->form_validation->set_rules('auth_passwordv', 'lang:FIELD_USER_PASSWORD_VERIFY', 'trim|min_length[6]|max_length[50]|matches[auth_password]');
                    $this->form_validation->set_rules('first_name', 'lang:FIELD_USER_FIRST_NAME', 'trim|required|max_length[50]|xss_clean');
                    $this->form_validation->set_rules('middle_name', 'lang:FIELD_USER_MIDDLE_NAME', 'trim|max_length[50]|xss_clean');
                    $this->form_validation->set_rules('last_name', 'lang:FIELD_USER_LAST_NAME', 'trim|max_length[50]|xss_clean');
                    $this->form_validation->set_rules('birthday', 'lang:FIELD_USER_BIRTHDAY', 'trim|max_length[10]|matches_pattern[####-##-##]|xss_clean');
                    $this->form_validation->set_rules('gender', 'lang:FIELD_USER_GENDER', 'trim|required|min_length[4]|max_length[6]|xss_clean');
                    $this->form_validation->set_rules('country', 'lang:FIELD_USER_COUNTRY', 'trim|max_length[100]|xss_clean');
                    $this->form_validation->set_rules('city', 'lang:FIELD_USER_CITY', 'trim|max_length[50]|xss_clean');
                    $this->form_validation->set_rules('address', 'lang:FIELD_USER_ADDRESS', 'trim|max_length[250]|xss_clean');
                    $this->form_validation->set_rules('email', 'lang:FIELD_USER_EMAIL', 'trim|required|max_length[50]|valid_email|xss_clean');
                    $this->form_validation->set_rules('additional', 'lang:FIELD_USER_ADDITIONAL', 'trim|xss_clean');
                    $this->form_validation->set_rules('visibility_type', 'lang:FIELD_USER_VISIBILITY', 'trim|required|min_length[3]|max_length[7]|xss_clean');
                    $this->form_validation->set_rules('rating', 'lang:FIELD_USER_RATING', 'trim|is_numeric|xss_clean');
                    $this->form_validation->set_rules('flag_verification', 'lang:FIELD_USER_VERIFICATION', 'trim|trigger|xss_clean');
                    $this->form_validation->set_rules('flag_access', 'lang:FIELD_USER_ACCESS', 'trim|trigger|xss_clean');
                    $this->form_validation->set_rules('datetime_registration', 'lang:FIELD_DATETIME_REGISTRATION', 'trim|required|max_length[19]|matches_pattern[####-##-## ##:##:##]|xss_clean');

                    $this->_set_user_contacts_validation( $this->get('user_contacts_edit') );

                    // if validation return error
                    if ($this->form_validation->run() == FALSE)
                    {
                        $this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_EDIT_USER'));

                        $this->_action_error = TRUE;
                    }
                    // if successfully verified
                    else
                    {
                        // select files
                        $files = $this->db->query('
                            select avatar_filename from ' . $this->db->dbprefix('users') . ' where id=' . $this->db->escape($id_user) . ' limit 1
                        ');

                        // upload files
                        if (is_uploaded_file($_FILES['avatar']['tmp_name'])) {
                            $this->load->model('file_model');
                            $upload_result = $this->file_model->do_upload(File_model::UPLOAD_AVATAR, 'avatar', true);
                            if ($upload_result['status']) {
                                $avatar = array(
                                    'filename' => $upload_result['config']['upload_prefix'] . $upload_result['data']['file_name'],
                                    'mimetype' => $upload_result['data']['file_type']
                                );
                            }
                        }

                        // update data
                        $this->db->query('
                            update
                                ' . $this->db->dbprefix('users') . ' as u,
                                ' . $this->db->dbprefix('users_groups') . ' as ug
                            set
                                u.id_group=ug.id,
                                u.auth_login=' . $this->db->escape($this->input->post('auth_login')) . ',
                                ' . (($this->input->post('auth_password') != '') ? 'u.auth_password=OLD_PASSWORD(' . $this->db->escape($this->input->post('auth_password')) . '),' : '') . '
                                u.first_name=' . $this->db->escape($this->input->post('first_name')) . ',
                                u.middle_name=' . $this->db->escape($this->input->post('middle_name')) . ',
                                u.last_name=' . $this->db->escape($this->input->post('last_name')) . ',
                                ' . (isset($avatar) ? 'u.avatar_filename=' . $this->db->escape($avatar['filename']) . ', '
                            . 'u.avatar_mimetype=' . $this->db->escape($avatar['mimetype']) . ', ' : '') . '
                                u.birthday=' . $this->db->escape($this->input->post('birthday')) . ',
                                u.gender=' . $this->db->escape($this->input->post('gender')) . ',
                                u.country=' . $this->db->escape($this->input->post('country')) . ',
                                u.city=' . $this->db->escape($this->input->post('city')) . ',
                                u.address=' . $this->db->escape($this->input->post('address')) . ',
                                u.email=' . $this->db->escape($this->input->post('email')) . ',
                                u.additional=' . $this->db->escape($this->input->post('additional')) . ',
                                u.time_registration=' . $this->db->escape($this->input->post('datetime_registration')) . ',
                                u.visibility_type=' . $this->db->escape($this->input->post('visibility_type')) . ',
                                u.rating=' . $this->db->escape($this->input->post('rating')) . ',
                                u.flag_verification=' . $this->db->escape($this->input->post('flag_verification')) . ',
                                u.flag_access=' . $this->db->escape($this->input->post('flag_access')) . '
                            where
                                u.id=' . $this->db->escape($id_user) . ' and
                                u.id_group=ug.id
                                ' . ($this->get('userdata')->id_group ? 'and u.id_group!=0' : '') . '
                        ');

                        // if query ok
                        if (!$this->db->_error_number())
                        {
                            // additional queries
                            $tables = array();
                            $values = array();
                            $conditions = array();
                            foreach ($this->get('user_contacts_edit')->result() as $row) {
                                $tables[] = $this->db->dbprefix('users_contacts') . ' as t_' . $row->id;
                                $values[] .= 't_' . $row->id . '.value=' . $this->db->escape($this->input->post('user_contacts_value_id_' . $row->id))
                                    . ', t_' . $row->id . '.flag_visibility=' . $this->db->escape($this->input->post('user_contacts_visibility_id_' . $row->id));
                                $conditions[] = 't_' . $row->id . '.id_user=' . (int) $id_user . ' and t_'
                                    . $row->id . '.id_group=' . $row->id;
                            }
                            $this->db->query('
                                update
                                    ' . implode(',', $tables) . '
                                set
                                    ' . implode(',', $values) . '
                                where
                                    ' . implode(' and ', $conditions) . '
                            ');

                            // additional actions
                            if (isset($avatar) && $files->row()->avatar_filename && file_exists(APPPATH . '../uploads/users/' . $files->row()->avatar_filename)) {
                                unlink(APPPATH . '../uploads/users/' . $files->row()->avatar_filename);
                            }

                            // if file upload error
                            if (isset($upload_result) && !$upload_result['status']) {
                                $this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_EDIT_USER_WITHOUT_AVA'));
                            } else {
                                $this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_EDIT_USER'));
                            }
                        }
                        // database error
                        else
                        {
                            $this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_DB_ERROR') . ' [' . $this->db->_error_number() . '] ' . $this->db->_error_message());

                            $this->_action_error = TRUE;
                        }
                    }

                    // if action error
                    if ($this->_action_error)
                    {
                        // set form data
                        $this->session->set_userdata('formdata', array_merge(array(
                            'edit' => array(
                                'id_group' => $this->input->post('id_group', TRUE),
                                'auth_login' => $this->input->post('auth_login', TRUE),
                                'first_name' => $this->input->post('first_name', TRUE),
                                'middle_name' => $this->input->post('middle_name', TRUE),
                                'last_name' => $this->input->post('last_name', TRUE),
                                'birthday' => $this->input->post('birthday', TRUE),
                                'gender' => $this->input->post('gender', TRUE),
                                'country' => $this->input->post('country', TRUE),
                                'city' => $this->input->post('city', TRUE),
                                'address' => $this->input->post('address', TRUE),
                                'email' => $this->input->post('email', TRUE),
                                'additional' => $this->input->post('additional', TRUE),
                                'datetime_registration' => $this->input->post('datetime_registration', TRUE),
                                'visibility_type' => $this->input->post('visibility_type', TRUE),
                                'rating' => $this->input->post('rating', TRUE),
                                'flag_verification' => ($this->input->post('flag_verification') ? TRUE : FALSE),
                                'flag_access' => ($this->input->post('flag_access') ? TRUE : FALSE)
                            )
                        ), $this->_get_user_contacts_formdata($this->get('user_contacts_edit'))));
                    }

                    redirect('/admin/users/edit_user/' . $id_user . '#edit_user');
                }

                // get user
                $this->set('user', $this->db->query('
                    select
                        *
                    from
                        ' . $this->db->dbprefix('users') . '
                    where
                        id=' . $this->db->escape($id_user) . '
                        ' . ($this->get('userdata')->id_group ? 'and id_group!=0' : '') . '
                    limit 1
                ')->row());
            }
            else
            {
                $this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_INCORRECT_ID'));
            }

            // init list
            $this->_init_list();

            $this->_backside_load_tpl(__CLASS__);

            // delete temporary session data
            $this->_backside_delete_tmp_session_data();
        }

        // no privileges
        else
        {
            $this->_backside_load_tpl_no_privileges();
        }
    }

    // delete user
    public function delete_user($id_user = 0)
    {
        // if privileges
        if ($this->get('components_privileges')->users->delete)
        {
            if (is_numeric($id_user) && $id_user > 0)
            {
                // select files
                $files = $this->db->query('
                    select avatar_filename from ' . $this->db->dbprefix('users') . ' where id=' . $this->db->escape($id_user) . ' limit 1
                ');

                // delete data
                $this->db->query('
                    delete
                        u
                    from
                        ' . $this->db->dbprefix('users') . ' as u
                    where
                        u.id=' . $this->db->escape($id_user) . ' and
                        u.id!=' . $this->db->escape($this->session->userdata('logged_as')) . '
                ');

                // if query ok
                if (!$this->db->_error_number() && $this->db->affected_rows())
                {
                    // additional queries
                    $this->db->query('
                    delete
                        uc
                    from
                        ' . $this->db->dbprefix('users_contacts') . ' as uc
                    where
                        uc.id_user=' . $this->db->escape($id_user) . ' and
                        uc.id_user!=' . $this->db->escape($this->session->userdata('logged_as')) . '
                    ');

                    // additional actions
                    if ($files->row()->avatar_filename && file_exists(APPPATH . '../uploads/users/' . $files->row()->avatar_filename)) {
                        unlink(APPPATH . '../uploads/users/' . $files->row()->avatar_filename);
                    }

                    $this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_DELETE_USER'));
                }
                // if query failed
                elseif (!$this->db->affected_rows())
                {
                    $this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_DELETE_USER'));
                }
                // database error
                else
                {
                    $this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_DB_ERROR') . ' [' . $this->db->_error_number() . '] ' . $this->db->_error_message());

                    $this->_action_error = TRUE;
                }
            }
            else
            {
                $this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_INCORRECT_ID'));
            }

            redirect('/admin/users');
        }

        // no privileges
        else
        {
            $this->_backside_load_tpl_no_privileges();
        }
    }

    // get user contacts query result
    protected function _get_user_contacts( $id_user = null ) {
        if ($id_user !== null) return $this->db->query('
            select
                ucg.id as id,
                ucg.title as title,
                ucg.type as type,
                uc.value as value,
                uc.flag_visibility as visibility
            from
                ' . $this->db->dbprefix('users_contacts') . ' as uc,
                ' . $this->db->dbprefix('users_contacts_groups') . ' as ucg
            where
                uc.id_group=ucg.id and
                uc.id_user=' . (int) $id_user . ' and
                ucg.id_resource=' . $this->db->escape($this->session->userdata('id_resource_current')) . '
            order by
                ucg.title asc,
                ucg.id asc
        ');
        else return $this->db->query('
            select
                ucg.id as id,
                ucg.title as title,
                ucg.type as type,
                \'\' as value,
                \'0\' as visibility
            from
                ' . $this->db->dbprefix('users_contacts_groups') . ' as ucg
            where
                ucg.id_resource=' . $this->db->escape($this->session->userdata('id_resource_current')) . '
            order by
                ucg.title asc,
                ucg.id asc
        ');
    }

    // set user contacts fields validation rules
    protected function _set_user_contacts_validation( $user_contacts ) {
        if (isset($user_contacts) && $user_contacts !== null) {
            foreach ($user_contacts->result() as $row) {
                switch ($row->type) {
                    case 'phone':   $validator = '|is_phone'; break;
                    case 'email':   $validator = '|valid_email'; break;
                    case 'number':  $validator = '|is_numeric'; break;
                    case 'text':    $validator = ''; break;
                    case 'url':     $validator = '|is_url'; break;
                    default:        $validator = '';
                }
                $this->form_validation->set_rules('user_contacts_value_id_' . $row->id, $row->title, 'trim|max_length[250]' . $validator . '|xss_clean');
            }
        }
    }

    // get user contacts fields formdata array
    protected function _get_user_contacts_formdata( $user_contacts ) {
        if (isset($user_contacts) && $user_contacts !== null) {
            $result = array();
            foreach ($user_contacts->result() as $row) {
                $result['user_contacts_value_id_' . $row->id] = $this->input->post('user_contacts_value_id_' . $row->id, TRUE);
                $result['user_contacts_visibility_id_' . $row->id] = ($this->input->post('user_contacts_visibility_id_' . $row->id) ? TRUE : FALSE);
            }
            return $result;
        }
        return false;
    }

    // initialize index/list
    protected function _init_list($page = 0)
    {
        $keywords = $this->get('keywords');

        // set form data
        $this->set('formdata', $this->session->userdata('formdata'));

        // pagination
        $this->load->library('pagination');

        $config['cur_page']	= ($page != "" && is_numeric($page)) ? $page : 0;
        $config['total_rows']	= $this->db->query('
            select
                u.id
            from
                ' . $this->db->dbprefix('users') . ' as u,
                ' . $this->db->dbprefix('users_groups') . ' as ug
            where
                u.id_group=ug.id
		' . ($this->get('userdata')->id_group ? 'and u.id_group!=0' : '') . '
                ' . ($keywords ? 'and (
                    ' . (isset($keywords['group']) && is_numeric($keywords['group']) ? 'ug.id=' . $this->db->escape($keywords['group']) . ' and ' : '') . '
                    ' . (!empty($keywords['text']) ? '(
                        u.first_name like \'%' . $this->db->escape_like_str($keywords['text']) . '%\' or
                        u.last_name like \'%' . $this->db->escape_like_str($keywords['text']) . '%\' or
                        u.auth_login like \'%' . $this->db->escape_like_str($keywords['text']) . '%\'
                    )' : '1') . '
                )' : '') . '
        ')->num_rows();
        $config['base_url']	= '/admin/users/page/';

        $this->pagination->initialize($config);
        $this->set('pages_line', $this->pagination->create_links());

        $this->set('users_position_number', $config['cur_page']);

        // get users per page
        $this->set('users', $this->db->query('
            select
                u.*,
                ug.id as id_group,
                ug.title as title_group
            from
                ' . $this->db->dbprefix('users') . ' as u,
                ' . $this->db->dbprefix('users_groups') . ' as ug
            where
                u.id_group=ug.id
                ' . ($this->get('userdata')->id_group ? 'and u.id_group!=0' : '') . '
                ' . ($keywords ? 'and (
                    ' . (isset($keywords['group']) && is_numeric($keywords['group']) ? 'ug.id=' . $this->db->escape($keywords['group']) . ' and ' : '') . '
                    ' . (!empty($keywords['text']) ? '(
                        u.first_name like \'%' . $this->db->escape_like_str($keywords['text']) . '%\' or
                        u.last_name like \'%' . $this->db->escape_like_str($keywords['text']) . '%\' or
                        u.auth_login like \'%' . $this->db->escape_like_str($keywords['text']) . '%\'
                    )' : '1') . '
                )' : '') . '
            order by
                u.auth_login asc,
                ug.title asc,
                u.first_name asc,
                u.last_name asc
            limit
                ' . $config['cur_page'] . ', ' . $this->pagination->per_page . '
        '));

        // get users groups
        $this->set('users_groups', $this->db->query('
            select
                *
            from
                ' . $this->db->dbprefix('users_groups') . ' as u
                ' . ($this->get('userdata')->id_group ? 'where u.id_group!=0' : '') . '
            order by
                u.title asc,
                u.id asc
        '));

        // get countries
        $this->set('countries', $this->db->query('
            select
                c.*
            from
                ' . $this->db->dbprefix('countries') . ' as c,
                ' . $this->db->dbprefix('settings') . ' as s
            where
                c.group_alias = s.countries_selector_alias and
                s.flag_countries_selector=1
            order by
                title asc,
                alias asc
        '));
    }
}

/* End of file admin/users.php */
/* Location: ./application/controllers/admin/users.php */