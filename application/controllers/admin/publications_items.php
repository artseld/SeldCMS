<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Publications_items extends MY_Controller {

    public function __construct()
    {
        parent::MY_Controller();

        if (!$this->session->userdata('logged_as')) redirect('/admin/auth');

        $this->lang->load('publications', 'english');

        $this->set('title', $this->lang->line('MODULE_NAME') . ' : ' . $this->lang->line('CONTROLLER_TITLE_ITEMS'));

        // search pre-actions
        $this->set('keywords', array( 'group' => '', 'text' => '' ));
        if ($this->session->flashdata('keywords_pi'))
        {
                $this->set('keywords', $this->session->flashdata('keywords_pi'));
                $this->session->keep_flashdata('keywords_pi');
        }
        else $this->session->flashdata('keywords_pi', array(
                'group' => '',
                'text' => ''
        ));

        $this->set('global_flag_use_rte', $this->get('global.flag_use_rte'));
    }
	
    // controller page
    public function index($page = 0)
    {
        // if privileges
        if ($this->get('modules_privileges')->publications->view)
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
        if ($this->get('modules_privileges')->publications->view && $this->input->post())
        {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('group', 'lang:FIELD_GROUP', 'trim|max_length[250]|xss_clean');
            $this->form_validation->set_rules('text', 'lang:FIELD_KEYWORDS', 'trim|max_length[250]|xss_clean');

            // if validation return error
            if ($this->form_validation->run() != FALSE)
            {
                $this->session->set_flashdata('keywords_pi', array(
                    'group' => $this->input->post('group', TRUE),
                    'text' => $this->input->post('text', TRUE)
                ));
            }

            redirect('/admin/publications_items');
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
        if ($this->get('modules_privileges')->publications->view)
        {
            $this->session->set_flashdata('keywords_pi', array(
                'group' => '',
                'text' => ''
            ));

            redirect('/admin/publications_items');
        }

        // no privileges
        else
        {
            $this->_backside_load_tpl_no_privileges();
        }
    }

    // add item
    public function add_item()
    {
        // if privileges
        if ($this->get('modules_privileges')->publications->add)
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

                $this->form_validation->set_rules('id_group', 'lang:FIELD_GROUP', 'trim|required|is_natural_no_zero|xss_clean');
                $this->form_validation->set_rules('url', 'lang:FIELD_URL', 'trim|required|max_length[250]|alpha_dash|xss_clean');
                $this->form_validation->set_rules('keywords', 'lang:FIELD_META_KEYWORDS', 'trim|max_length[250]|xss_clean');
                $this->form_validation->set_rules('description', 'lang:FIELD_META_DESCRIPTION', 'trim|max_length[250]|xss_clean');
                $this->form_validation->set_rules('title_browser', 'lang:FIELD_TITLE_BROWSER', 'trim|max_length[250]|xss_clean');
                $this->form_validation->set_rules('title_page', 'lang:FIELD_TITLE_PAGE', 'trim|max_length[250]|xss_clean');
                $this->form_validation->set_rules('title_menu', 'lang:FIELD_TITLE_MENU', 'trim|max_length[250]|xss_clean');
                $this->form_validation->set_rules('announce', 'lang:FIELD_ANNOUNCE', 'trim');
                $this->form_validation->set_rules('body', 'lang:FIELD_BODY', 'trim');
                $this->form_validation->set_rules('time_publication', 'lang:FIELD_DATETIME_PUBLICATION', 'trim|max_length[19]|matches_pattern[####-##-## ##:##:##]|xss_clean');
                $this->form_validation->set_rules('time_expiration', 'lang:FIELD_DATETIME_EXPIRATION', 'trim|max_length[19]|matches_pattern[####-##-## ##:##:##]|xss_clean');
                $this->form_validation->set_rules('flag_publication', 'lang:FIELD_PUBLICATION', 'trim|trigger|xss_clean');

                // if validation return error
                if ($this->form_validation->run() == FALSE)
                {
                    $this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_ADD_ITEM'));

                    $this->_action_error = TRUE;
                }
                // if successfully verified
                else
                {
                    // insert data
                    $this->db->query('
                        insert into
                            ' . $this->db->dbprefix('publications_items') . '
                        select
                            0,
                            pg.id,
                            u.id,
                            ' . $this->db->escape($this->input->post('url')) . ',
                            ' . $this->db->escape($this->input->post('keywords')) . ',
                            ' . $this->db->escape($this->input->post('description')) . ',
                            ' . $this->db->escape($this->input->post('title_browser')) . ',
                            ' . $this->db->escape($this->input->post('title_page')) . ',
                            ' . $this->db->escape($this->input->post('title_menu')) . ',
                            ' . $this->db->escape($this->input->post('announce')) . ',
                            ' . $this->db->escape($this->input->post('body')) . ',
                            now(),
                            ' . ($this->input->post('time_publication') ? $this->db->escape($this->input->post('time_publication')) : 'now()') . ',
                            ' . ($this->input->post('time_expiration') ? $this->db->escape($this->input->post('time_expiration')) : 'NULL') . ',
                            ' . $this->db->escape($this->input->post('flag_publication')) . '
                        from
                            ' . $this->db->dbprefix('users') . ' as u,
                            ' . $this->db->dbprefix('publications_groups') . ' as pg
                        where
                            pg.id=' . $this->db->escape($this->input->post('id_group')) . ' and
                            u.id=' . $this->get('userdata')->id . '
                        limit 1
                    ');

                    // if query ok
                    if (!$this->db->_error_number() && $this->db->affected_rows())
                    {
                        // additional queries
                        // NOTHING

                        // additional actions
                        // NOTHING

                        $this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_ADD_ITEM'));
                    }
                    // if query failed
                    elseif (!$this->db->affected_rows())
                    {
                        $this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_ADD_ITEM'));

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
                    'url' => $this->input->post('url', TRUE),
                    'keywords' => $this->input->post('keywords', TRUE),
                    'description' => $this->input->post('description', TRUE),
                    'title_browser' => $this->input->post('title_browser', TRUE),
                    'title_page' => $this->input->post('title_page', TRUE),
                    'title_menu' => $this->input->post('title_menu', TRUE),
                    'announce' => $this->input->post('announce', TRUE),
                    'body' => $this->input->post('body', TRUE),
                    'time_publication' => $this->input->post('time_publication', TRUE),
                    'time_expiration' => $this->input->post('time_expiration', TRUE),
                    'flag_publication' => ($this->input->post('flag_publication') ? TRUE : FALSE)
                ));

                redirect('/admin/publications_items#add_item');
            }
            // if action ok
            else
            {
                redirect('/admin/publications_items');
            }
        }

        // no privileges
        else
        {
            $this->_backside_load_tpl_no_privileges();
        }
    }

    // edit item
    public function edit_item($id_item = 0)
    {
        // if privileges
        if ($this->get('modules_privileges')->publications->edit)
        {
            if (is_numeric($id_item) && $id_item > 0)
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

                    $this->form_validation->set_rules('id_group', 'lang:FIELD_GROUP', 'trim|required|is_natural_no_zero|xss_clean');
                    $this->form_validation->set_rules('url', 'lang:FIELD_URL', 'trim|required|max_length[250]|alpha_dash|xss_clean');
                    $this->form_validation->set_rules('keywords', 'lang:FIELD_META_KEYWORDS', 'trim|max_length[250]|xss_clean');
                    $this->form_validation->set_rules('description', 'lang:FIELD_META_DESCRIPTION', 'trim|max_length[250]|xss_clean');
                    $this->form_validation->set_rules('title_browser', 'lang:FIELD_TITLE_BROWSER', 'trim|max_length[250]|xss_clean');
                    $this->form_validation->set_rules('title_page', 'lang:FIELD_TITLE_PAGE', 'trim|max_length[250]|xss_clean');
                    $this->form_validation->set_rules('title_menu', 'lang:FIELD_TITLE_MENU', 'trim|max_length[250]|xss_clean');
                    $this->form_validation->set_rules('announce', 'lang:FIELD_ANNOUNCE', 'trim');
                    $this->form_validation->set_rules('body', 'lang:FIELD_BODY', 'trim');
                    $this->form_validation->set_rules('time_publication', 'lang:FIELD_DATETIME_PUBLICATION', 'trim|required|max_length[19]|matches_pattern[####-##-## ##:##:##]|xss_clean');
                    $this->form_validation->set_rules('time_expiration', 'lang:FIELD_DATETIME_EXPIRATION', 'trim|max_length[19]|matches_pattern[####-##-## ##:##:##]|xss_clean');
                    $this->form_validation->set_rules('flag_publication', 'lang:FIELD_PUBLICATION', 'trim|trigger|xss_clean');

                    // if validation return error
                    if ($this->form_validation->run() == FALSE)
                    {
                        $this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_EDIT_ITEM'));

                        $this->_action_error = TRUE;
                    }
                    // if successfully verified
                    else
                    {
                        // update data
                        $this->db->query('
                            update
                                ' . $this->db->dbprefix('publications_items') . ' as pit,
                                ' . $this->db->dbprefix('publications_groups') . ' as pg
                            set
                                pit.id_group=' . $this->db->escape($this->input->post('id_group')) . ',
                                pit.url=' . $this->db->escape($this->input->post('url')) . ',
                                pit.meta_keywords=' . $this->db->escape($this->input->post('keywords')) . ',
                                pit.meta_description=' . $this->db->escape($this->input->post('description')) . ',
                                pit.title_browser=' . $this->db->escape($this->input->post('title_browser')) . ',
                                pit.title_page=' . $this->db->escape($this->input->post('title_page')) . ',
                                pit.title_menu=' . $this->db->escape($this->input->post('title_menu')) . ',
                                pit.announce=' . $this->db->escape($this->input->post('announce')) . ',
                                pit.body=' . $this->db->escape($this->input->post('body')) . ',
                                pit.time_modification=now(),
                                pit.time_publication=' . $this->db->escape($this->input->post('time_publication')) . ',
                                pit.time_expiration=' . ($this->input->post('time_expiration') ? $this->db->escape($this->input->post('time_expiration')) : 'NULL')  . ',
                                pit.flag_publication=' . $this->db->escape($this->input->post('flag_publication')) . '
                            where
                                pit.id=' . $this->db->escape($id_item) . ' and
                                pit.id_group=pg.id and
                                pg.id=' . $this->db->escape($this->input->post('id_group')) . '
                        ');

                        // if query ok
                        if (!$this->db->_error_number())
                        {
                            // additional queries
                            // NOTHING

                            // additional actions
                            // NOTHING

                            $this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_EDIT_ITEM'));
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
                                'url' => $this->input->post('url', TRUE),
                                'keywords' => $this->input->post('keywords', TRUE),
                                'description' => $this->input->post('description', TRUE),
                                'title_browser' => $this->input->post('title_browser', TRUE),
                                'title_page' => $this->input->post('title_page', TRUE),
                                'title_menu' => $this->input->post('title_menu', TRUE),
                                'announce' => $this->input->post('announce', TRUE),
                                'body' => $this->input->post('body', TRUE),
                                'time_publication' => $this->input->post('time_publication', TRUE),
                                'time_expiration' => $this->input->post('time_expiration', TRUE),
                                'flag_publication' => ($this->input->post('flag_publication') ? TRUE : FALSE)
                            )
                        ));
                    }

                    redirect('/admin/publications_items/edit_item/' . $id_item . '#edit_item');
                }

                // get item
                $this->set('item', $this->db->query('
                    select
                        pit.*,
                        u.auth_login,
                        u.first_name,
                        u.last_name
                    from
                        ' . $this->db->dbprefix('publications_groups') . ' as pg,
                        ' . $this->db->dbprefix('publications_items') . ' as pit
                    left join
                        ' . $this->db->dbprefix('users') . ' as u
                    on
                        u.id=pit.id_user_author
                    where
                        pit.id=' . $this->db->escape($id_item) . ' and
                        pit.id_group=pg.id and
                        (pg.id_resource=0 or pg.id_resource=' . $this->db->escape($this->session->userdata('id_resource_current')) . ')
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

    // delete item
    public function delete_item($id_item = 0)
    {
        // if privileges
        if ($this->get('modules_privileges')->publications->delete)
        {
            if (is_numeric($id_item) && $id_item > 0)
            {
                // delete data
                $this->db->query('
                    delete
                        pit
                    from
                        ' . $this->db->dbprefix('publications_items') . ' as pit
                    where
                        pit.id=' . $this->db->escape($id_item) . '
                ');

                // if query ok
                if (!$this->db->_error_number() && $this->db->affected_rows())
                {
                    // additional queries
                    // NOTHING

                    // additional actions
                    // NOTHING

                    $this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_DELETE_ITEM'));
                }
                // if query failed
                elseif (!$this->db->affected_rows())
                {
                    $this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_DELETE_ITEM'));
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

            redirect('/admin/publications_items');
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

        $config['cur_page']	= ($page != "" && is_numeric($page)) ? $page : 0;
        $config['total_rows']	= $this->db->query('
            select
                pit.id
            from
                ' . $this->db->dbprefix('publications_groups') . ' as pg,
                ' . $this->db->dbprefix('publications_items') . ' as pit
            left join
                ' . $this->db->dbprefix('users') . ' as u
            on
                pit.id_user_author=u.id
            where
                pit.id_group=pg.id and
                (pg.id_resource=' . $this->db->escape($this->session->userdata('id_resource_current')) . ' or
                pg.id_resource=0)
                ' . ($keywords ? 'and (
                ' . ($keywords['group'] ? 'pg.id=' . $this->db->escape($keywords['group']) . ' and ' : '') . '
                ' . (!empty($keywords['text']) ? '(
                        pit.title_browser like \'%' . $this->db->escape_like_str($keywords['text']) . '%\' or
                        pit.title_page like \'%' . $this->db->escape_like_str($keywords['text']) . '%\' or
                        pit.title_menu like \'%' . $this->db->escape_like_str($keywords['text']) . '%\' or
                        pit.time_publication like \'%' . $this->db->escape_like_str($keywords['text']) . '%\' or
                        u.auth_login like \'%' . $this->db->escape_like_str($keywords['text']) . '%\' or
                        u.first_name like \'%' . $this->db->escape_like_str($keywords['text']) . '%\' or
                        u.last_name like \'%' . $this->db->escape_like_str($keywords['text']) . '%\'
                )' : '1') . '
                )' : '') . '
        ')->num_rows();
        $config['base_url']	= '/admin/publications_items/page/';

        $this->pagination->initialize($config);
        $this->set('pages_line', $this->pagination->create_links());

        //$this->set('items_position_number', $config['cur_page']);
        $this->set('items_position_number', ($config['total_rows'] - $config['cur_page']));

        // get items per page
        $this->set('publications_items', $this->db->query('
            select
                pit.*,
                u.auth_login,
                u.first_name,
                u.last_name,
                pg.id_resource as group_resource,
                pg.title as group_title,
                pg.flag_is_default as group_is_default
            from
                ' . $this->db->dbprefix('publications_groups') . ' as pg,
                ' . $this->db->dbprefix('publications_items') . ' as pit
            left join
                ' . $this->db->dbprefix('users') . ' as u
            on
                pit.id_user_author=u.id
            where
                pit.id_group=pg.id and
                (pg.id_resource=' . $this->db->escape($this->session->userdata('id_resource_current')) . ' or
                pg.id_resource=0)
                ' . ($keywords ? 'and (
                ' . ($keywords['group'] ? 'pg.id=' . $this->db->escape($keywords['group']) . ' and ' : '') . '
                ' . (!empty($keywords['text']) ? '(
                        pit.title_browser like \'%' . $this->db->escape_like_str($keywords['text']) . '%\' or
                        pit.title_page like \'%' . $this->db->escape_like_str($keywords['text']) . '%\' or
                        pit.title_menu like \'%' . $this->db->escape_like_str($keywords['text']) . '%\' or
                        pit.time_publication like \'%' . $this->db->escape_like_str($keywords['text']) . '%\' or
                        u.auth_login like \'%' . $this->db->escape_like_str($keywords['text']) . '%\' or
                        u.first_name like \'%' . $this->db->escape_like_str($keywords['text']) . '%\' or
                        u.last_name like \'%' . $this->db->escape_like_str($keywords['text']) . '%\'
                )' : '1') . '
                )' : '') . '
            order by
                pit.time_publication desc,
                pit.id desc,
                pg.title asc
            limit
                ' . $config['cur_page'] . ', ' . $this->pagination->per_page . '
        '));

        // get publications groups
        $this->set('publications_groups', $this->db->query('
            select
                *
            from
                ' . $this->db->dbprefix('publications_groups') . ' as pg
            where
                pg.id_resource=' . $this->db->escape($this->session->userdata('id_resource_current')) . ' or
                pg.id_resource=0
            order by
                pg.title asc
        '));

    }
}

/* End of file admin/publications_items.php */
/* Location: ./application/controllers/admin/publications_items.php */