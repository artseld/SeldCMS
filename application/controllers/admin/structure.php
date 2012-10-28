<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Structure extends MY_Controller {

    public function __construct()
    {
        parent::MY_Controller();

        if (!$this->session->userdata('logged_as')) redirect('/admin/auth');

        $this->set('title', $this->lang->line('NAV_TITLE_STRUCTURE'));

        $this->set('global_flag_use_rte', $this->get('global.flag_use_rte'));
    }
	
    // controller page
    public function index()
    {
        // if privileges
        if ($this->get('components_privileges')->structure->view)
        {
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

    // add document
    public function add_document()
    {
        // if privileges
        if ($this->get('components_privileges')->structure->add)
        {
            if ($this->input->post())
            {
                // auto-url
                if ($this->input->post('url') == '')
                {
                    $this->load->model('func_model');
                    if ($this->input->post('title_page') != '') $_POST['url'] = strtolower($this->func_model->transliterate_str($this->input->post('title_page', TRUE), 'ru', '_'));
                    elseif ($this->input->post('title_browser') != '') $_POST['url'] = strtolower($this->func_model->transliterate_str($this->input->post('title_browser', TRUE), 'ru', '_'));
                    elseif ($this->input->post('title_menu') != '') $_POST['url'] = strtolower($this->func_model->transliterate_str($this->input->post('title_menu', TRUE), 'ru', '_'));
                }

                $this->load->library('form_validation');

                $this->form_validation->set_rules('document_type', 'lang:FIELD_DOCUMENT_TYPE', 'trim|required|integer|xss_clean');
                $this->form_validation->set_rules('id_parent', 'lang:FIELD_PARENT', 'trim|required|integer|xss_clean');
                $this->form_validation->set_rules('id_template', 'lang:FIELD_TEMPLATE', 'trim|required|integer|xss_clean');
                $this->form_validation->set_rules('priority', 'lang:FIELD_PRIORITY', 'trim|is_natural|max_length[3]|xss_clean');
                $this->form_validation->set_rules('url', 'lang:FIELD_URL', 'trim|required|max_length[250]|alpha_dash|xss_clean');
                $this->form_validation->set_rules('keywords', 'lang:FIELD_META_KEYWORDS', 'trim|max_length[250]|xss_clean');
                $this->form_validation->set_rules('description', 'lang:FIELD_META_DESCRIPTION', 'trim|max_length[250]|xss_clean');
                $this->form_validation->set_rules('title_browser', 'lang:FIELD_TITLE_BROWSER', 'trim|max_length[250]|xss_clean');
                $this->form_validation->set_rules('title_page', 'lang:FIELD_TITLE_PAGE', 'trim|max_length[250]|xss_clean');
                $this->form_validation->set_rules('title_menu', 'lang:FIELD_TITLE_MENU', 'trim|max_length[250]|xss_clean');
                $this->form_validation->set_rules('body', 'lang:FIELD_BODY', 'trim');
                $this->form_validation->set_rules('flag_use_rte', 'lang:FIELD_USE_RTE', 'trim|trigger|xss_clean');
                $this->form_validation->set_rules('flag_display_in_menu', 'lang:FIELD_DISPLAY_IN_MENU', 'trim|trigger|xss_clean');
                $this->form_validation->set_rules('flag_free_access', 'lang:FIELD_FREE_ACCESS', 'trim|trigger|xss_clean');
                $this->form_validation->set_rules('flag_caching', 'lang:FIELD_CACHING', 'trim|trigger|xss_clean');
                $this->form_validation->set_rules('flag_publication', 'lang:FIELD_PUBLICATION', 'trim|trigger|xss_clean');
                $this->form_validation->set_rules('flag_is_mainpage', 'lang:FIELD_IS_MAINPAGE', 'trim|trigger|xss_clean');

                // if validation return error
                if ($this->form_validation->run() == FALSE)
                {
                    $this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_ADD_DOCUMENT'));

                    $this->_action_error = TRUE;
                }
                // if successfully verified
                else
                {
                    // insert data
                    $this->db->query('
                        insert into
                            ' . $this->db->dbprefix('structure') . '
                        select
                            0,
                            r.id,
                            ' . ($this->input->post('flag_is_mainpage') ? '0' : 'if (
                                    (select count(1) from ' . $this->db->dbprefix('structure') . ' where id=' . $this->db->escape($this->input->post('id_parent')) . ')>0,
                                    ' . $this->db->escape($this->input->post('id_parent')) . ',
                                    0
                            )') . ',
                            t.id,
                            ' . (!$this->input->post('document_type') ? '0' : 'if (
                                    (select count(1) from ' . $this->db->dbprefix('modules') . ' where id=' . $this->db->escape($this->input->post('document_type')) . ')>0,
                                    ' . $this->db->escape($this->input->post('document_type')) . ',
                                    0
                            )') . ',
                            ' . $this->db->escape($this->input->post('priority')) . ',
                            ' . $this->db->escape($this->input->post('url')) . ',
                            ' . $this->db->escape($this->input->post('keywords')) . ',
                            ' . $this->db->escape($this->input->post('description')) . ',
                            ' . $this->db->escape($this->input->post('title_browser')) . ',
                            ' . $this->db->escape($this->input->post('title_page')) . ',
                            ' . $this->db->escape($this->input->post('title_menu')) . ',
                            ' . $this->db->escape($this->input->post('body')) . ',
                            now(),
                            now(),
                            ' . $this->db->escape($this->input->post('flag_use_rte')) . ',
                            ' . $this->db->escape($this->input->post('flag_display_in_menu')) . ',
                            ' . $this->db->escape($this->input->post('flag_free_access')) . ',
                            ' . $this->db->escape($this->input->post('flag_caching')) . ',
                            ' . $this->db->escape($this->input->post('flag_publication')) . ',
                            ' . $this->db->escape($this->input->post('flag_is_mainpage')) . '
                        from
                            ' . $this->db->dbprefix('resources') . ' as r,
                            ' . $this->db->dbprefix('templates') . ' as t
                        where
                            r.id=' . $this->db->escape($this->session->userdata('id_resource_current')) . ' and
                            t.id=' . $this->db->escape($this->input->post('id_template')) . '
                    ');

                    // if query ok
                    if (!$this->db->_error_number() && $this->db->affected_rows())
                    {
                        // additional queries
                        if ($this->input->post('flag_is_mainpage'))
                        {
                            $this->db->query('
                                update
                                    ' . $this->db->dbprefix('structure') . ' as s,
                                    (select
                                        max(id) as id_max
                                    from
                                        ' . $this->db->dbprefix('structure') . '
                                    where
                                        id_resource=' . $this->db->escape($this->session->userdata('id_resource_current')) . ') as sm
                                set
                                    s.flag_is_mainpage=0
                                where
                                    s.id!=sm.id_max
                            ');
                        }

                        // additional actions
                        // NOTHING

                        $this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_ADD_DOCUMENT'));
                    }
                    // if query failed
                    elseif (!$this->db->affected_rows())
                    {
                        $this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_ADD_DOCUMENT'));

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
                    'id_parent' => $this->input->post('id_parent', TRUE),
                    'id_template' => $this->input->post('id_template', TRUE),
                    'document_type' => $this->input->post('document_type', TRUE),
                    'priority' => $this->input->post('priority', TRUE),
                    'url' => $this->input->post('url', TRUE),
                    'keywords' => $this->input->post('keywords', TRUE),
                    'description' => $this->input->post('description', TRUE),
                    'title_browser' => $this->input->post('title_browser', TRUE),
                    'title_page' => $this->input->post('title_page', TRUE),
                    'title_menu' => $this->input->post('title_menu', TRUE),
                    'body' => $this->input->post('body'),
                    'flag_use_rte' => ($this->input->post('flag_use_rte') ? TRUE : FALSE),
                    'flag_display_in_menu' => ($this->input->post('flag_display_in_menu') ? TRUE : FALSE),
                    'flag_free_access' => ($this->input->post('flag_free_access') ? TRUE : FALSE),
                    'flag_caching' => ($this->input->post('flag_caching') ? TRUE : FALSE),
                    'flag_publication' => ($this->input->post('flag_publication') ? TRUE : FALSE),
                    'flag_is_mainpage' => ($this->input->post('flag_is_mainpage') ? TRUE : FALSE)
                ));

                redirect('/admin/structure#add_document');
            }
            // if action ok
            else
            {
                redirect('/admin/structure');
            }
        }

        // no privileges
        else
        {
            $this->_backside_load_tpl_no_privileges();
        }
    }

    // edit document
    public function edit_document($id_document = 0)
    {
        // if privileges
        if ($this->get('components_privileges')->structure->edit)
        {
            if (is_numeric($id_document) && $id_document > 0)
            {
                if ($this->input->post())
                {
                    // auto-url
                    if ($this->input->post('url') == '')
                    {
                        $this->load->model('func_model');
                        if ($this->input->post('title_page') != '') $_POST['url'] = strtolower($this->func_model->transliterate_str($this->input->post('title_page', TRUE), 'ru', '_'));
                        elseif ($this->input->post('title_browser') != '') $_POST['url'] = strtolower($this->func_model->transliterate_str($this->input->post('title_browser', TRUE), 'ru', '_'));
                        elseif ($this->input->post('title_menu') != '') $_POST['url'] = strtolower($this->func_model->transliterate_str($this->input->post('title_menu', TRUE), 'ru', '_'));
                    }

                    $this->load->library('form_validation');

                    $this->form_validation->set_rules('document_type', 'lang:FIELD_DOCUMENT_TYPE', 'trim|required|integer|xss_clean');
                    $this->form_validation->set_rules('id_parent', 'lang:FIELD_PARENT', 'trim|required|integer|xss_clean');
                    $this->form_validation->set_rules('id_template', 'lang:FIELD_TEMPLATE', 'trim|required|integer|xss_clean');
                    $this->form_validation->set_rules('priority', 'lang:FIELD_PRIORITY', 'trim|is_natural|max_length[3]|xss_clean');
                    $this->form_validation->set_rules('url', 'lang:FIELD_URL', 'trim|required|max_length[250]|alpha_dash|xss_clean');
                    $this->form_validation->set_rules('keywords', 'lang:FIELD_META_KEYWORDS', 'trim|max_length[250]|xss_clean');
                    $this->form_validation->set_rules('description', 'lang:FIELD_META_DESCRIPTION', 'trim|max_length[250]|xss_clean');
                    $this->form_validation->set_rules('title_browser', 'lang:FIELD_TITLE_BROWSER', 'trim|max_length[250]|xss_clean');
                    $this->form_validation->set_rules('title_page', 'lang:FIELD_TITLE_PAGE', 'trim|max_length[250]|xss_clean');
                    $this->form_validation->set_rules('title_menu', 'lang:FIELD_TITLE_MENU', 'trim|max_length[250]|xss_clean');
                    $this->form_validation->set_rules('body', 'lang:FIELD_BODY', 'trim');
                    $this->form_validation->set_rules('flag_use_rte', 'lang:FIELD_USE_RTE', 'trim|trigger|xss_clean');
                    $this->form_validation->set_rules('flag_display_in_menu', 'lang:FIELD_DISPLAY_IN_MENU', 'trim|trigger|xss_clean');
                    $this->form_validation->set_rules('flag_free_access', 'lang:FIELD_FREE_ACCESS', 'trim|trigger|xss_clean');
                    $this->form_validation->set_rules('flag_caching', 'lang:FIELD_CACHING', 'trim|trigger|xss_clean');
                    $this->form_validation->set_rules('flag_publication', 'lang:FIELD_PUBLICATION', 'trim|trigger|xss_clean');
                    $this->form_validation->set_rules('flag_is_mainpage', 'lang:FIELD_IS_MAINPAGE', 'trim|trigger|xss_clean');

                    // if validation return error
                    if ($this->form_validation->run() == FALSE)
                    {
                        $this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_EDIT_DOCUMENT'));

                        $this->_action_error = TRUE;
                    }
                    // if successfully verified
                    else
                    {
                        // update data
                        $this->db->query('
                            update
                                ' . $this->db->dbprefix('structure') . ' as s,
                                ' . $this->db->dbprefix('structure') . ' as sa
                            set
                                s.id_parent=' . ($this->input->post('flag_is_mainpage') ? '0' : '(select x.id from (select if (
                                    (select count(1) from ' . $this->db->dbprefix('structure') . ' where id=' . $this->db->escape($this->input->post('id_parent')) . ')>0,
                                    ' . $this->db->escape($this->input->post('id_parent')) . ',
                                    0
                                ) as id) as x)') . ',
                                s.id_template=(
                                    select
                                        id
                                    from
                                        ' . $this->db->dbprefix('templates') . '
                                    where
                                        id=' . $this->db->escape($this->input->post('id_template')) . '
                                    limit 1
                                ),
                                s.id_module=' . (!$this->input->post('document_type') ? '0' : 'if (
                                    (select count(1) from ' . $this->db->dbprefix('modules') . ' where id=' . $this->db->escape($this->input->post('document_type')) . ')>0,
                                    ' . $this->db->escape($this->input->post('document_type')) . ',
                                    0
                                )') . ',
                                s.priority=' . $this->db->escape($this->input->post('priority')) . ',
                                s.url=' . $this->db->escape($this->input->post('url')) . ',
                                s.meta_keywords=' . $this->db->escape($this->input->post('keywords')) . ',
                                s.meta_description=' . $this->db->escape($this->input->post('description')) . ',
                                s.title_browser=' . $this->db->escape($this->input->post('title_browser')) . ',
                                s.title_page=' . $this->db->escape($this->input->post('title_page')) . ',
                                s.title_menu=' . $this->db->escape($this->input->post('title_menu')) . ',
                                s.body=' . $this->db->escape($this->input->post('body')) . ',
                                s.time_modification=now(),
                                s.flag_use_rte=' . $this->db->escape($this->input->post('flag_use_rte')) . ',
                                s.flag_display_in_menu=' . $this->db->escape($this->input->post('flag_display_in_menu')) . ',
                                s.flag_free_access=' . $this->db->escape($this->input->post('flag_free_access')) . ',
                                s.flag_caching=' . $this->db->escape($this->input->post('flag_caching')) . ',
                                s.flag_publication=' . $this->db->escape($this->input->post('flag_publication')) . ',
                                s.flag_is_mainpage=' . $this->db->escape($this->input->post('flag_is_mainpage')) . ',
                                sa.flag_is_mainpage=' . ($this->input->post('flag_is_mainpage') ? '0' : 'sa.flag_is_mainpage') . '
                            where
                                s.id=' . $this->db->escape($id_document) . ' and
                                s.id_resource=' . $this->db->escape($this->session->userdata('id_resource_current')) . ' and
                                sa.id!=' . $this->db->escape($id_document) . '
                        ');

                        // if query ok
                        if (!$this->db->_error_number())
                        {
                            // additional queries
                            // NOTHING

                            // additional actions
                            // NOTHING

                            $this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_EDIT_DOCUMENT'));
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
                                'id_parent' => $this->input->post('id_parent', TRUE),
                                'id_template' => $this->input->post('id_template', TRUE),
                                'document_type' => $this->input->post('document_type', TRUE),
                                'priority' => $this->input->post('priority', TRUE),
                                'url' => $this->input->post('url', TRUE),
                                'keywords' => $this->input->post('keywords', TRUE),
                                'description' => $this->input->post('description', TRUE),
                                'title_browser' => $this->input->post('title_browser', TRUE),
                                'title_page' => $this->input->post('title_page', TRUE),
                                'title_menu' => $this->input->post('title_menu', TRUE),
                                'body' => $this->input->post('body'),
                                'flag_use_rte' => ($this->input->post('flag_use_rte') ? TRUE : FALSE),
                                'flag_display_in_menu' => ($this->input->post('flag_display_in_menu') ? TRUE : FALSE),
                                'flag_free_access' => ($this->input->post('flag_free_access') ? TRUE : FALSE),
                                'flag_caching' => ($this->input->post('flag_caching') ? TRUE : FALSE),
                                'flag_publication' => ($this->input->post('flag_publication') ? TRUE : FALSE),
                                'flag_is_mainpage' => ($this->input->post('flag_is_mainpage') ? TRUE : FALSE)
                            )
                        ));
                    }

                    redirect('/admin/structure/edit_document/' . $id_document . '#edit_document');
                }

                // get document
                $this->set('document', $this->db->query('
                    select
                        *
                    from
                        ' . $this->db->dbprefix('structure') . '
                    where
                        id=' . $this->db->escape($id_document) . ' and
                        id_resource=' . $this->db->escape($this->session->userdata('id_resource_current')) . '
                    limit 1
                ')->row());
            }
            else
            {
                $this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_INCORRECT_ID'));
                $id_document = 0;
            }

            // init list
            $this->_init_list($id_document);

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

    // delete document
    public function delete_document($id_document = 0)
    {
        // if privileges
        if ($this->get('components_privileges')->structure->delete)
        {
            if (is_numeric($id_document) && $id_document > 0)
            {
                // delete data
                $this->db->query('
                    delete
                        s
                    from
                        ' . $this->db->dbprefix('structure') . ' as s
                    where
                        s.id=' . $this->db->escape($id_document) . ' and
                        s.id_resource=' . $this->db->escape($this->session->userdata('id_resource_current')) . '
                ');

                // if query ok
                if (!$this->db->_error_number() && $this->db->affected_rows())
                {
                    // additional queries
                    $records_count_start = 0; while (TRUE)
                    {
                        $records_count_end = $this->db->query('
                            select
                                count(1) as records_count
                            from
                                ' . $this->db->dbprefix('structure') . ' as s
                            where
                                s.id_resource=' . $this->db->escape($this->session->userdata('id_resource_current')) . '
                        ')->row()->records_count;

                        if ($records_count_start == $records_count_end) break;
                        $records_count_start = $records_count_end;

                        $this->db->query('
                            delete
                                s
                            from
                                ' . $this->db->dbprefix('structure') . ' as s
                            where
                                s.id_resource=' . $this->db->escape($this->session->userdata('id_resource_current')) . ' and
                                s.id_parent not in (
                                    select
                                        sa.id
                                    from (
                                        select
                                            id
                                        from
                                            ' . $this->db->dbprefix('structure') . '
                                        where
                                            id_resource=' . $this->db->escape($this->session->userdata('id_resource_current')) . '
                                    ) sa
                                ) and
                                s.id_parent!=0
                        ');
                    }
                    $this->db->query('
                        update
                            ' . $this->db->dbprefix('containers') . '
                        set
                            id_document=0
                        where
                            id_document=' . $this->db->escape($id_document) . ' and
                            id_resource=' . $this->db->escape($this->session->userdata('id_resource_current')) . '
                    ');

                    // additional actions
                    // NOTHING

                    $this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_DELETE_DOCUMENT'));
                }
                // if query failed
                elseif (!$this->db->affected_rows())
                {
                    $this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_DELETE_DOCUMENT'));
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

            redirect('/admin/structure');
        }

        // no privileges
        else
        {
            $this->_backside_load_tpl_no_privileges();
        }
    }

    // initialize index/list
    protected function _init_list($id_document = 0)
    {
        // get tree
        $this->load->model('admin/tree_model');
        $this->tree_model->make_tree($this->get('components_privileges')->structure, $id_document);
        $this->set('tree', $this->tree_model->get_tree());
        $this->set('parents_list', $this->tree_model->get_tree_list());
        $this->set('parents_list_edit', $this->tree_model->get_tree_list_edit());
        $this->set('modules', $this->db->query('
            select
                *
            from
                ' . $this->db->dbprefix('modules') . '
            order by
                title asc
        '));
        $this->set('templates', $this->db->query('
            select
                tg.id as id_group,
                tg.title as title_group,
                t.id as id_template,
                t.title as title_template
            from
                ' . $this->db->dbprefix('templates_groups') . ' as tg,
                ' . $this->db->dbprefix('templates') . ' as t
            where
                t.id_group=tg.id
            order by
                tg.title asc,
                t.title asc
        '));

        // set form data
        $this->set('formdata', $this->session->userdata('formdata'));
    }
}

/* End of file admin/structure.php */
/* Location: ./application/controllers/admin/structure.php */