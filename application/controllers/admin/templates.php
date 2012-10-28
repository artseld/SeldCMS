<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Templates extends MY_Controller {

    public function __construct()
    {
        parent::MY_Controller();

        if (!$this->session->userdata('logged_as')) redirect('/admin/auth');

        $this->set('title', $this->lang->line('NAV_TITLE_TEMPLATES'));

        $this->load->model('admin/file_model');

        // search pre-actions
        $this->set('keywords', array(
            'group' => '',
            'text' => ''
        ));
        if ($this->session->flashdata('keywords_t'))
        {
            $this->set('keywords', $this->session->flashdata('keywords_t'));
            $this->session->keep_flashdata('keywords_t');
        }
        else $this->session->flashdata('keywords_t', array(
            'group' => '',
            'text' => ''
        ));
    }
	
    // controller page
    public function index($page = 0)
    {
        // if privileges
        if ($this->get('components_privileges')->templates->view)
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
                $this->session->set_flashdata('keywords_t', array(
                    'group' => $this->input->post('group', TRUE),
                    'text' => $this->input->post('text', TRUE)
                ));
            }

            redirect('/admin/templates');
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
            $this->session->set_flashdata('keywords_t', array(
                'group' => '',
                'text' => ''
            ));

            redirect('/admin/templates');
        }

        // no privileges
        else
        {
            $this->_backside_load_tpl_no_privileges();
        }
    }

	// add template
	public function add_template()
	{
		// if privileges
		if ($this->get('components_privileges')->templates->add)
		{
			if ($this->input->post())
			{
				$this->load->library('form_validation');

				$this->form_validation->set_rules('id_group', 'lang:FIELD_GROUP', 'trim|required|integer|xss_clean');
				$this->form_validation->set_rules('alias', 'lang:FIELD_ALIAS', 'trim|required|max_length[50]|alpha_dash|xss_clean');
				$this->form_validation->set_rules('title', 'lang:FIELD_TITLE', 'trim|required|max_length[250]|xss_clean');
				$this->form_validation->set_rules('body', 'lang:FIELD_BODY', 'trim|required');
				$this->form_validation->set_rules('comments', 'lang:FIELD_COMMENTS', 'trim|max_length[500]|xss_clean');

				// if validation return error
				if ($this->form_validation->run() == FALSE)
				{
					$this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_ADD_TEMPLATE'));

					$this->_action_error = TRUE;
				}
				// if successfully verified
				else
				{
					// insert data
					$this->db->query('
						insert into
							' . $this->db->dbprefix('templates') . '
						select
							0,
							tg.id,
							' . $this->db->escape($this->input->post('alias')) . ',
							' . $this->db->escape($this->input->post('title')) . ',
							' . $this->db->escape($this->input->post('comments')) . '
						from
							' . $this->db->dbprefix('templates_groups') . ' as tg
						where
							tg.id=' . $this->db->escape($this->input->post('id_group')) . '
					');

					// if query ok
					if (!$this->db->_error_number() && $this->db->affected_rows())
					{
						// additional queries
						// NOTHING

						// additional actions
						$this->file_model->rewrite_template_file(strtolower($this->input->post('alias', TRUE)));

						$this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_ADD_TEMPLATE'));
					}
					// if query failed
					elseif (!$this->db->affected_rows())
					{
						$this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_ADD_TEMPLATE'));

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
					'alias' => strtolower($this->input->post('alias', TRUE)),
					'title' => $this->input->post('title', TRUE),
					'body' => $this->input->post('body'),
					'comments' => $this->input->post('comments', TRUE)
				));

				redirect('/admin/templates#add_template');
			}
			// if action ok
			else
			{
				redirect('/admin/templates');
			}
		}

		// no privileges
		else
		{
			$this->_backside_load_tpl_no_privileges();
		}
	}

	// edit template
	public function edit_template($id_template = 0)
	{
		// if privileges
		if ($this->get('components_privileges')->templates->edit)
		{
			if (is_numeric($id_template) && $id_template > 0)
			{
				if ($this->input->post())
				{
					$this->load->library('form_validation');

					$this->form_validation->set_rules('id_group', 'lang:FIELD_GROUP', 'trim|required|integer|xss_clean');
					$this->form_validation->set_rules('title', 'lang:FIELD_TITLE', 'trim|required|max_length[250]|xss_clean');
					$this->form_validation->set_rules('body', 'lang:FIELD_BODY', 'trim|required');
					$this->form_validation->set_rules('comments', 'lang:FIELD_COMMENTS', 'trim|max_length[500]|xss_clean');

					// if validation return error
					if ($this->form_validation->run() == FALSE)
					{
						$this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_EDIT_TEMPLATE'));

						$this->_action_error = TRUE;
					}
					// if successfully verified
					else
					{
						// update data
						$this->db->query('
							update
								' . $this->db->dbprefix('templates') . ' as t
							set
								t.id_group=(
									select
										id
									from
										' . $this->db->dbprefix('templates_groups') . '
									where
										id=' . $this->db->escape($this->input->post('id_group')) . '
									limit 1
								),
								t.title=' . $this->db->escape($this->input->post('title')) . ',
								t.comments=' . $this->db->escape($this->input->post('comments')) . '
							where
								t.id=' . $this->db->escape($id_template) . '
							limit 1
						');

						// if query ok
						if (!$this->db->_error_number())
						{
							// additional queries
							$template_alias = $this->db->query('
								select
									alias
								from
									' . $this->db->dbprefix('templates') . '
								where
									id=' . $this->db->escape($id_template) . '
								limit 1
							')->row()->alias;

							// additional actions
							$this->file_model->rewrite_template_file($template_alias);

							$this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_EDIT_TEMPLATE'));
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
								'title' => $this->input->post('title', TRUE),
								'body' => $this->input->post('body'),
								'comments' => $this->input->post('comments', TRUE)
							)
						));
					}

					redirect('/admin/templates/edit_template/' . $id_template . '#edit_template');
				}

				// get template
				$this->set('template', $this->db->query('
					select
						*
					from
						' . $this->db->dbprefix('templates') . '
					where
						id=' . $this->db->escape($id_template) . '
					limit 1
				')->row());

				// get file contents
				if (file_exists(APPPATH . 'views/' . $this->container['template']->alias . '_tpl.php'))
				{
					$this->container['template']->body = htmlspecialchars_decode(file_get_contents(APPPATH . 'views/' . $this->container['template']->alias . '_tpl.php'));
				}
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

	// delete template
	public function delete_template($id_template = 0)
	{
		// if privileges
		if ($this->get('components_privileges')->templates->delete)
		{
			if (is_numeric($id_template) && $id_template > 0)
			{
				// get alias
				$template_alias = $this->db->query('
					select
						alias
					from
						' . $this->db->dbprefix('templates') . '
					where
						id=' . $this->db->escape($id_template) . '
					limit 1
				')->row()->alias;

				// delete data
				$this->db->query('
					delete
						t
					from
						' . $this->db->dbprefix('templates') . ' as t
					join
						(select count(1) as how_many from ' . $this->db->dbprefix('templates') . ') as tc
					where
						t.id=' . $this->db->escape($id_template) . ' and
						tc.how_many > 1
				');

				// if query ok
				if (!$this->db->_error_number() && $this->db->affected_rows())
				{
					// additional queries
					// NOTHING

					// additional actions
					$this->file_model->rewrite_template_file($template_alias, TRUE);

					$this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_DELETE_TEMPLATE'));
				}
				// if query failed
				elseif (!$this->db->affected_rows())
				{
					$this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_DELETE_TEMPLATE'));
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

			redirect('/admin/templates');
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
                            t.id
			from
                            ' . $this->db->dbprefix('templates') . ' as t,
                            ' . $this->db->dbprefix('templates_groups') . ' as tg
                        where
                            t.id_group=tg.id
                            ' . ($keywords ? 'and (
                            ' . ($keywords['group'] ? 'tg.id=' . $this->db->escape($keywords['group']) . ' and ' : '') . '
                            ' . (!empty($keywords['text']) ? '(
                                t.alias like \'%' . $this->db->escape_like_str($keywords['text']) . '%\' or
                                t.title like \'%' . $this->db->escape_like_str($keywords['text']) . '%\' or
                                t.comments like \'%' . $this->db->escape_like_str($keywords['text']) . '%\'
                            )' : '1') . '
                            )' : '') . '
		')->num_rows();
		$config['base_url']	= '/admin/templates/page/';

		$this->pagination->initialize($config);
		$this->set('pages_line', $this->pagination->create_links());

		$this->set('templates_position_number', $config['cur_page']);

		// get templates per page
		$this->set('templates', $this->db->query('
                    select
                        t.*,
                        tg.id as id_group,
                        tg.title as title_group
                    from
                        ' . $this->db->dbprefix('templates') . ' as t,
                        ' . $this->db->dbprefix('templates_groups') . ' as tg
                    where
                        t.id_group=tg.id
                        ' . ($keywords ? 'and (
                        ' . ($keywords['group'] ? 'tg.id=' . $this->db->escape($keywords['group']) . ' and ' : '') . '
                        ' . (!empty($keywords['text']) ? '(
                            t.alias like \'%' . $this->db->escape_like_str($keywords['text']) . '%\' or
                            t.title like \'%' . $this->db->escape_like_str($keywords['text']) . '%\' or
                            t.comments like \'%' . $this->db->escape_like_str($keywords['text']) . '%\'
                        )' : '1') . '
                        )' : '') . '
                    order by
                        tg.title asc,
                        t.title asc
                    limit
                        ' . $config['cur_page'] . ', ' . $this->pagination->per_page . '
		'));
		// get templates groups
		$this->set('templates_groups', $this->db->query('
                    select
                        *
                    from
                        ' . $this->db->dbprefix('templates_groups') . '
                    order by
                        title asc,
                        id asc
		'));
	}
}

/* End of file admin/templates.php */
/* Location: ./application/controllers/admin/templates.php */