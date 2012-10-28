<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Containers extends MY_Controller {

    public function __construct()
    {
        parent::MY_Controller();

        if (!$this->session->userdata('logged_as')) redirect('/admin/auth');

        $this->set('title', $this->lang->line('NAV_TITLE_CONTAINERS'));

        // search pre-actions
        $this->set('keywords', array(
            'group' => '',
            'text' => ''
        ));
        if ($this->session->flashdata('keywords_c'))
        {
            $this->set('keywords', $this->session->flashdata('keywords_c'));
            $this->session->keep_flashdata('keywords_c');
        }
        else $this->session->flashdata('keywords_c', array(
            'group' => '',
            'text' => ''
        ));
    }

    // controller page
    public function index($page = 0)
    {
        // if privileges
        if ($this->get('components_privileges')->containers->view)
        {
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
        if ($this->get('components_privileges')->containers->view && $this->input->post())
        {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('group', 'lang:FIELD_GROUP', 'trim|max_length[250]|xss_clean');
            $this->form_validation->set_rules('text', 'lang:FIELD_KEYWORDS', 'trim|max_length[250]|xss_clean');

            // if validation return error
            if ($this->form_validation->run() != FALSE)
            {
                $this->session->set_flashdata('keywords_c', array(
                    'group' => $this->input->post('group', TRUE),
                    'text' => $this->input->post('text', TRUE)
                ));
            }

            redirect('/admin/containers');
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
        if ($this->get('components_privileges')->containers->view)
        {
            $this->session->set_flashdata('keywords_c', array(
                'group' => '',
                'text' => ''
            ));

            redirect('/admin/containers');
        }

        // no privileges
        else
        {
            $this->_backside_load_tpl_no_privileges();
        }
    }

    // add container
    public function add_container()
    {
        // if privileges
        if ($this->get('components_privileges')->containers->add)
        {
            if ($this->input->post())
            {
                $this->load->library('form_validation');

                $this->form_validation->set_rules('id_group', 'lang:FIELD_GROUP', 'trim|required|is_natural|xss_clean');
                $this->form_validation->set_rules('id_document', 'lang:FIELD_DOCUMENT', 'trim|required|is_natural|xss_clean');
                $this->form_validation->set_rules('id_type', 'lang:FIELD_TYPE', 'trim|required|is_natural|xss_clean');
                $this->form_validation->set_rules('priority', 'lang:FIELD_PRIORITY', 'trim|required|is_natural|xss_clean');
                $this->form_validation->set_rules('alias', 'lang:FIELD_ALIAS', 'trim|required|max_length[50]|alpha_dash|xss_clean');
                $this->form_validation->set_rules('title', 'lang:FIELD_TITLE', 'trim|required|max_length[250]|xss_clean');
                $this->form_validation->set_rules('body', 'lang:FIELD_BODY', 'trim|required');
                $this->form_validation->set_rules('flag_free_access', 'lang:FIELD_FREE_ACCESS', 'trim|trigger|xss_clean');
                $this->form_validation->set_rules('comments', 'lang:FIELD_COMMENTS', 'trim|max_length[500]|xss_clean');

                // if validation return error
                if ($this->form_validation->run() == FALSE)
                {
                    $this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_ADD_CONTAINER'));

                    $this->_action_error = TRUE;
                }
                // if successfully verified
                else
                {
                    // insert data
                    $this->db->query('
                        insert into
                            ' . $this->db->dbprefix('containers') . '
                        select
                            0,
                            r.id,
                            cg.id,
                            ' . ($this->input->post('id_document') ? '(select
                                    id
                            from
                                    ' . $this->db->dbprefix('structure') . '
                            where
                                    id=' . $this->db->escape($this->input->post('id_document')) . ' and
                                    id_resource=' . $this->db->escape($this->session->userdata('id_resource_current')) . '
                            limit 1)' : '0') . ' as id_document,
                            ' . $this->db->escape($this->input->post('id_type')) . ',
                            ' . $this->db->escape($this->input->post('priority')) . ',
                            ' . $this->db->escape($this->input->post('alias')) . ',
                            ' . $this->db->escape($this->input->post('title')) . ',
                            ' . $this->db->escape($this->input->post('body')) . ',
                            ' . $this->db->escape($this->input->post('flag_free_access')) . ',
                            ' . $this->db->escape($this->input->post('comments')) . '
                        from
                            ' . $this->db->dbprefix('containers_groups') . ' as cg,
                            ' . $this->db->dbprefix('resources') . ' as r
                        where
                            cg.id=' . $this->db->escape($this->input->post('id_group')) . ' and
                            r.id=' . $this->db->escape($this->session->userdata('id_resource_current')) . '
                    ');

                    // if query ok
                    if (!$this->db->_error_number() && $this->db->affected_rows())
                    {
                        // additional queries
                        // NOTHING

                        // additional actions
                        // NOTHING

                        $this->_backside_set_message(self::STATUS_DONE, $this->lang->line('MESSAGE_SC_ADD_CONTAINER'));
                    }
                    // if query failed
                    elseif (!$this->db->affected_rows())
                    {
                        $this->_backside_set_message(self::STATUS_ERROR, $this->lang->line('MESSAGE_UNSC_ADD_CONTAINER'));

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
                $this->session->set_userdata('formdata', array(
                    'id_group' => $this->input->post('id_group', TRUE),
                    'id_document' => $this->input->post('id_document', TRUE),
                    'id_type' => $this->input->post('id_type', TRUE),
                    'priority' => $this->input->post('priority', TRUE),
                    'alias' => strtolower($this->input->post('alias', TRUE)),
                    'title' => $this->input->post('title', TRUE),
                    'body' => $this->input->post('body'),
                    'flag_free_access' => ($this->input->post('flag_free_access') ? TRUE : FALSE),
                    'comments' => $this->input->post('comments', TRUE)
                ));

                redirect('/admin/containers#add_container');
            }
            // if action ok
            else
            {
                redirect('/admin/containers');
            }
        }

        // no privileges
        else
        {
            $this->_backside_load_tpl_no_privileges();
        }
    }

    // edit container
    public function edit_container($id_container = 0)
    {
        // if privileges
        if ($this->get('components_privileges')->containers->edit)
        {
            if (is_numeric($id_container) && $id_container > 0)
            {
                if ($this->input->post()) {

                    $this->load->library('form_validation');

                    $this->form_validation->set_rules('id_group', 'lang:FIELD_GROUP', 'trim|required|integer|xss_clean');
                    $this->form_validation->set_rules('id_document', 'lang:FIELD_DOCUMENT', 'trim|required|is_natural|xss_clean');
                    $this->form_validation->set_rules('id_type', 'lang:FIELD_TYPE', 'trim|required|is_natural|xss_clean');
                    $this->form_validation->set_rules('priority', 'lang:FIELD_PRIORITY', 'trim|required|is_natural|xss_clean');
                    $this->form_validation->set_rules('alias', 'lang:FIELD_ALIAS', 'trim|required|max_length[50]|alpha_dash|xss_clean');
                    $this->form_validation->set_rules('title', 'lang:FIELD_TITLE', 'trim|required|max_length[250]|xss_clean');
                    $this->form_validation->set_rules('body', 'lang:FIELD_BODY', 'trim|required');
                    $this->form_validation->set_rules('flag_free_access', 'lang:FIELD_FREE_ACCESS', 'trim|trigger|xss_clean');
                    $this->form_validation->set_rules('comments', 'lang:FIELD_COMMENTS', 'trim|max_length[500]|xss_clean');

                    // if validation return error
                    if ($this->form_validation->run() == FALSE)
                    {
                        $this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_EDIT_CONTAINER'));

                        $this->_action_error = TRUE;
                    }
                    // if successfully verified
                    else
                    {
                        // update data
                        $this->db->query('
                            update
                                ' . $this->db->dbprefix('containers') . ' as c
                            set
                                c.id_group=(
                                    select
                                        id
                                    from
                                        ' . $this->db->dbprefix('containers_groups') . '
                                    where
                                        id=' . $this->db->escape($this->input->post('id_group')) . '
                                    limit 1
                                ),
                                c.id_document=' . ($this->input->post('id_document') ? '(select
                                    id
                                from
                                    ' . $this->db->dbprefix('structure') . '
                                where
                                    id=' . $this->db->escape($this->input->post('id_document')) . ' and
                                    id_resource=' . $this->db->escape($this->session->userdata('id_resource_current')) . '
                                limit 1)' : '0') . ',
                                c.id_type=' . $this->db->escape($this->input->post('id_type')) . ',
                                c.priority=' . $this->db->escape($this->input->post('priority')) . ',
                                c.alias=' . $this->db->escape($this->input->post('alias')) . ',
                                c.title=' . $this->db->escape($this->input->post('title')) . ',
                                c.body=' . $this->db->escape($this->input->post('body')) . ',
                                c.flag_free_access=' . $this->db->escape($this->input->post('flag_free_access')) . ',
                                c.comments=' . $this->db->escape($this->input->post('comments')) . '
                            where
                                c.id=' . $this->db->escape($id_container) . ' and
                                c.id_resource=' . $this->db->escape($this->session->userdata('id_resource_current')) . '
                            limit 1
                        ');

                        // if query ok
                        if (!$this->db->_error_number())
                        {
                            // additional queries
                            // NOTHING

                            // additional actions
                            // NOTHING

                            $this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_EDIT_CONTAINER'));
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
                        $this->session->set_userdata('formdata', array(
                            'edit' => array(
                                'id_group' => $this->input->post('id_group', TRUE),
                                'id_document' => $this->input->post('id_document', TRUE),
                                'id_type' => $this->input->post('id_type', TRUE),
                                'priority' => $this->input->post('priority', TRUE),
                                'alias' => strtolower($this->input->post('alias', TRUE)),
                                'title' => $this->input->post('title', TRUE),
                                'body' => $this->input->post('body'),
                                'flag_free_access' => ($this->input->post('flag_free_access') ? TRUE : FALSE),
                                'comments' => $this->input->post('comments', TRUE)
                            )
                        ));
                    }

                    redirect('/admin/containers/edit_container/' . $id_container . '#edit_container');
                }

                // get container
                $this->set('container', $this->db->query('
                    select
                        *
                    from
                        ' . $this->db->dbprefix('containers') . '
                    where
                        id=' . $this->db->escape($id_container) . '
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

    // delete container
    public function delete_container($id_container = 0)
    {
        // if privileges
        if ($this->get('components_privileges')->containers->delete)
        {
            if (is_numeric($id_container) && $id_container > 0)
            {
                // delete data
                $this->db->query('
                    delete
                        c
                    from
                        ' . $this->db->dbprefix('containers') . ' as c
                    where
                        c.id=' . $this->db->escape($id_container) . ' and
                        c.id_resource=' . $this->db->escape($this->session->userdata('id_resource_current')) . '
                ');

                // if query ok
                if (!$this->db->_error_number() && $this->db->affected_rows())
                {
                    // additional queries
                    // NOTHING

                    // additional actions
                    // NOTHING

                    $this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_DELETE_CONTAINER'));
                }
                // if query failed
                elseif (!$this->db->affected_rows())
                {
                    $this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_DELETE_CONTAINER'));
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

            redirect('/admin/containers');
        }

        // no privileges
        else
        {
            $this->_backside_load_tpl_no_privileges();
        }
    }

    // initialize index/list
    protected function _init_list($page = 0)
    {
        $keywords = $this->get('keywords');

        // set form data
        $this->set('formdata', $this->session->userdata('formdata'));

        // pagination
        $this->load->library('pagination');

        $config['cur_page']     = ($page != "" && is_numeric($page)) ? $page : 0;
        $config['total_rows']   = $this->db->query('
            select
                c.id
            from
                ' . $this->db->dbprefix('containers') . ' as c,
                ' . $this->db->dbprefix('containers_groups') . ' as cg
            where
                c.id_group=cg.id and
                c.id_resource=(select id_resource_default from ' . $this->db->dbprefix('settings') . ' limit 1)
                ' . ($keywords ? 'and (
                ' . ($keywords['group'] ? 'cg.id=' . $this->db->escape($keywords['group']) . ' and ' : '') . '
                ' . (!empty($keywords['text']) ? '(
                    c.alias like \'%' . $this->db->escape_like_str($keywords['text']) . '%\' or
                    c.title like \'%' . $this->db->escape_like_str($keywords['text']) . '%\' or
                    c.comments like \'%' . $this->db->escape_like_str($keywords['text']) . '%\'
                )' : '1') . '
                )' : '') . '
        ')->num_rows();
        $config['base_url']     = '/admin/containers/page/';

        $this->pagination->initialize($config);
        $this->set('pages_line', $this->pagination->create_links());

        $this->set('containers_position_number', $config['cur_page']);

        // get containers per page
        $this->set('containers', $this->db->query('
            select
                c.*,
                cg.id as id_group,
                cg.priority as priority_group,
                cg.title as title_group
            from
                ' . $this->db->dbprefix('containers') . ' as c,
                ' . $this->db->dbprefix('containers_groups') . ' as cg
            where
                c.id_group=cg.id and
                c.id_resource=(select id_resource_default from ' . $this->db->dbprefix('settings') . ' limit 1)
                ' . ($keywords ? 'and (
                ' . ($keywords['group'] ? 'cg.id=' . $this->db->escape($keywords['group']) . ' and ' : '') . '
                ' . (!empty($keywords['text']) ? '(
                    c.alias like \'%' . $this->db->escape_like_str($keywords['text']) . '%\' or
                    c.title like \'%' . $this->db->escape_like_str($keywords['text']) . '%\' or
                    c.comments like \'%' . $this->db->escape_like_str($keywords['text']) . '%\'
                )' : '1') . '
                )' : '') . '
            order by
                cg.priority asc,
                cg.title asc,
                c.priority asc,
                c.title asc
            limit
                ' . $config['cur_page'] . ', ' . $this->pagination->per_page . '
        '));
        // get containers groups
        $this->set('containers_groups', $this->db->query('
            select
                *
            from
                ' . $this->db->dbprefix('containers_groups') . '
            order by
                title asc,
                id asc
        '));
        // get documents tree
        $this->load->model('admin/tree_model');
        $this->tree_model->make_tree($this->get('components_privileges')->containers, 0);
        $this->set('documents_list', $this->tree_model->get_tree_list());
    }
}

/* End of file admin/containers.php */
/* Location: ./application/controllers/admin/containers.php */