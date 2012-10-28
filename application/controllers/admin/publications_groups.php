<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Publications_groups extends MY_Controller {

    public function __construct()
    {
        parent::MY_Controller();

        if (!$this->session->userdata('logged_as')) redirect('/admin/auth');

        $this->lang->load('publications', 'english');

        $this->set('title', $this->lang->line('MODULE_NAME') . ' : ' . $this->lang->line('CONTROLLER_TITLE_GROUPS'));
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

	// add group
	public function add_group()
	{
		// if privileges
		if ($this->get('modules_privileges')->publications->add)
		{
			if ($this->input->post())
			{
				$this->load->library('form_validation');

                $this->form_validation->set_rules('priority', 'lang:FIELD_PRIORITY', 'trim|required|is_natural|xss_clean');
				$this->form_validation->set_rules('title', 'lang:FIELD_TITLE', 'trim|required|max_length[250]|xss_clean');
				$this->form_validation->set_rules('comments', 'lang:FIELD_COMMENTS', 'trim|max_length[500]|xss_clean');
				$this->form_validation->set_rules('flag_is_default', 'lang:FIELD_FLAG_IS_DEFAULT', 'trim|trigger|xss_clean');
				$this->form_validation->set_rules('for_all_resources', 'lang:FIELD_FOR_ALL_RESOURCES', 'trim|trigger|xss_clean');

				// if validation return error
				if ($this->form_validation->run() == FALSE)
				{
					$this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_ADD_GROUP'));

					$this->_action_error = TRUE;
				}
				// if successfully verified
				else
				{
					// insert data
					$this->db->query('
						insert into
							' . $this->db->dbprefix('publications_groups') . '
						select
							0,
							' . ($this->input->post('for_all_resources') ? '0' : 'r.id') . ',
							' . $this->db->escape($this->input->post('priority')) . ',
							' . $this->db->escape($this->input->post('title')) . ',
							' . $this->db->escape($this->input->post('comments')) . ',
							' . $this->db->escape($this->input->post('flag_is_default')) . '
						from
							' . $this->db->dbprefix('resources') . ' as r
						where
							' . ($this->input->post('for_all_resources') ? '1' : 'r.id=' . $this->db->escape($this->session->userdata('id_resource_current'))) . '
						limit 1
					');

					// if query ok
					if (!$this->db->_error_number() && $this->db->affected_rows())
					{
						// additional queries
						if ($this->input->post('flag_is_default'))
						{
							$this->db->query('
								update
									' . $this->db->dbprefix('publications_groups') . ' as pg
								set
									pg.flag_is_default=0
								where
									pg.id!=' . $this->db->insert_id() . ' and
									pg.id_resource=' . ($this->input->post('for_all_resources') ? '0' : $this->db->escape($this->session->userdata('id_resource_current'))) . '
							');
						}

						// additional actions
						// NOTHING

						$this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_ADD_GROUP'));
					}
					// if query failed
					elseif (!$this->db->affected_rows())
					{
						$this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_ADD_GROUP'));

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
                    'priority' => $this->input->post('priority', TRUE),
					'title' => $this->input->post('title', TRUE),
					'comments' => $this->input->post('comments', TRUE),
					'flag_is_default' => ($this->input->post('flag_is_default') ? TRUE : FALSE),
					'for_all_resources' => ($this->input->post('for_all_resources') ? TRUE : FALSE)
				));

				redirect('/admin/publications_groups#add_group');
			}
			// if action ok
			else
			{
				redirect('/admin/publications_groups');
			}
		}

		// no privileges
		else
		{
			$this->_backside_load_tpl_no_privileges();
		}
	}

	// edit group
	public function edit_group($id_group = 0)
	{
		// if privileges
		if ($this->get('modules_privileges')->publications->edit)
		{
			if (is_numeric($id_group) && $id_group > 0)
			{
				if ($this->input->post()) {

					$this->load->library('form_validation');

                    $this->form_validation->set_rules('priority', 'lang:FIELD_PRIORITY', 'trim|required|is_natural|xss_clean');
					$this->form_validation->set_rules('title', 'lang:FIELD_TITLE', 'trim|required|max_length[250]|xss_clean');
					$this->form_validation->set_rules('comments', 'lang:FIELD_COMMENTS', 'trim|max_length[500]|xss_clean');
					$this->form_validation->set_rules('flag_is_default', 'lang:FIELD_FLAG_IS_DEFAULT', 'trim|trigger|xss_clean');
					$this->form_validation->set_rules('for_all_resources', 'lang:FIELD_FOR_ALL_RESOURCES', 'trim|trigger|xss_clean');

					// if validation return error
					if ($this->form_validation->run() == FALSE)
					{
						$this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_EDIT_GROUP'));

						$this->_action_error = TRUE;
					}
					// if successfully verified
					else
					{
						// update data
						$this->db->query('
							update
								' . $this->db->dbprefix('publications_groups') . ' as pg,
								' . $this->db->dbprefix('resources') . ' as r
							set
								pg.id_resource=' . ($this->input->post('for_all_resources') ? '0' : 'r.id') . ',
								pg.priority=' . $this->db->escape($this->input->post('priority')) . ',
								pg.title=' . $this->db->escape($this->input->post('title')) . ',
								pg.comments=' . $this->db->escape($this->input->post('comments')) . ',
								pg.flag_is_default=' . $this->db->escape($this->input->post('flag_is_default')) . '
							where
								(pg.id_resource=0 or pg.id_resource=r.id) and
								r.id=' . $this->db->escape($this->session->userdata('id_resource_current')) . ' and
								pg.id=' . $this->db->escape($id_group) . '
						');

						// if query ok
						if (!$this->db->_error_number())
						{
							// additional queries
							if ($this->input->post('flag_is_default'))
							{
								$this->db->query('
									update
										' . $this->db->dbprefix('publications_groups') . ' as pg
									set
										pg.flag_is_default=0
									where
										pg.id!=' . $this->db->escape($id_group) . ' and
										pg.id_resource=' . ($this->input->post('for_all_resources') ? '0' : $this->db->escape($this->session->userdata('id_resource_current'))) . '
								');
							}

							// additional actions
							// NOTHING

							$this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_EDIT_GROUP'));
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
                                'priority' => $this->input->post('priority', TRUE),
								'title' => $this->input->post('title', TRUE),
								'comments' => $this->input->post('comments', TRUE),
								'flag_is_default' => ($this->input->post('flag_is_default') ? TRUE : FALSE),
								'for_all_resources' => ($this->input->post('for_all_resources') ? TRUE : FALSE)
							)
						));
					}

					redirect('/admin/publications_groups/edit_group/' . $id_group . '#edit_group');
				}

				// get group
				$this->set('group', $this->db->query('
					select
						*
					from
						' . $this->db->dbprefix('publications_groups') . '
					where
						id=' . $this->db->escape($id_group) . ' and
						(id_resource=0 or id_resource=' . $this->db->escape($this->session->userdata('id_resource_current')) . ')
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

	// delete group
	public function delete_group($id_group = 0)
	{
		// if privileges
		if ($this->get('modules_privileges')->publications->delete)
		{
			if (is_numeric($id_group) && $id_group > 0)
			{
				// delete data
				$this->db->query('
					delete
						pg
					from
						' . $this->db->dbprefix('publications_groups') . ' as pg
					where
						pg.id=' . $this->db->escape($id_group) . ' and
						(pg.id_resource=' . $this->db->escape($this->session->userdata('id_resource_current')) . ' or
						pg.id_resource=0)
				');

				// if query ok
				if (!$this->db->_error_number() && $this->db->affected_rows())
				{
					// additional queries
					$this->db->query('
						delete
							pit
						from
							' . $this->db->dbprefix('publications_items') . ' as pit
						where
							id_group=' . $this->db->escape($id_group) . '
					');

					// additional actions
					// NOTHING

					$this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_DELETE_GROUP'));
				}
				// if query failed
				elseif (!$this->db->affected_rows())
				{
					$this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_DELETE_GROUP'));
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

			redirect('/admin/publications_groups');
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
		// set form data
		$this->set('formdata', $this->session->userdata('formdata'));

		// pagination
		$this->load->library('pagination');

		$config['cur_page']	= ($page != "" && is_numeric($page)) ? $page : 0;
		$config['total_rows']	= $this->db->query('
			select
				id
			from
				' . $this->db->dbprefix('publications_groups') . '
			where
				id_resource=' . $this->db->escape($this->session->userdata('id_resource_current')) . ' or
				id_resource=0
		')->num_rows();
		$config['base_url']	= '/admin/publications_groups/page/';

		$this->pagination->initialize($config);
		$this->set('pages_line', $this->pagination->create_links());

		$this->set('groups_position_number', $config['cur_page']);

		// get groups per page
		$this->set('publications_groups', $this->db->query('
			select
				*
			from
				' . $this->db->dbprefix('publications_groups') . ' as pg
			where
				pg.id_resource=' . $this->db->escape($this->session->userdata('id_resource_current')) . ' or
				pg.id_resource=0
			order by
			    pg.priority asc,
				pg.title asc
			limit
				' . $config['cur_page'] . ', ' . $this->pagination->per_page . '
		'));

	}
}

/* End of file admin/publications_groups.php */
/* Location: ./application/controllers/admin/publications_groups.php */