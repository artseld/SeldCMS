<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resources extends MY_Controller {

    public function __construct()
    {
        parent::MY_Controller();

        if (!$this->session->userdata('logged_as')) redirect('/admin/auth');

        $this->set('title', $this->lang->line('NAV_TITLE_RESOURCES'));

        $this->load->model('admin/file_model');
    }
	
    // controller page
    public function index($page = 0)
    {
        // if privileges
        if ($this->get('components_privileges')->resources->view)
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

	// add resource
	public function add_resource()
	{
		// if privileges
		if ($this->get('components_privileges')->resources->add)
		{
			if ($this->input->post())
			{
				$this->load->library('form_validation');

				$this->form_validation->set_rules('url', 'lang:FIELD_URL', 'trim|required|max_length[50]|alpha_dash|xss_clean');
				$this->form_validation->set_rules('title', 'lang:FIELD_TITLE', 'trim|required|max_length[250]|xss_clean');
				$this->form_validation->set_rules('comments', 'lang:FIELD_COMMENTS', 'trim|max_length[500]|xss_clean');

				// if validation return error
				if ($this->form_validation->run() == FALSE)
				{
					$this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_ADD_RESOURCE'));

					$this->_action_error = TRUE;
				}
				// if successfully verified
				else
				{
					// insert data
					$this->db->query('
						insert into
							' . $this->db->dbprefix('resources') . '
						values (
							0,
							' . $this->db->escape($this->input->post('url')) . ',
							' . $this->db->escape($this->input->post('title')) . ',
							' . $this->db->escape($this->input->post('comments')) . '
						)
					');

					// if query ok
					if (!$this->db->_error_number() && $this->db->affected_rows())
					{
						// additional queries
						$this->db->query('
							insert into
								' . $this->db->dbprefix('resources_settings') . '
							values (
								(select max(id) from ' . $this->db->dbprefix('resources') . '),
								\'\', \'\', \'\', \'\', \'\', \'\', \'\'
							)
						');

						// additional actions
						$this->file_model->rewrite_resources_file();

						$this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_ADD_RESOURCE'));
					}
					// if query failed
					elseif (!$this->db->affected_rows())
					{
						$this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_ADD_RESOURCE'));

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
					'url' => $this->input->post('url', TRUE),
					'title' => $this->input->post('title', TRUE),
					'comments' => $this->input->post('comments', TRUE)
				));

				redirect('/admin/resources#add_resource');
			}
			// if action ok
			else
			{
				redirect('/admin/resources');
			}
		}

		// no privileges
		else
		{
			$this->_backside_load_tpl_no_privileges();
		}
	}

	// edit resource
	public function edit_resource($id_resource = 0)
	{
		// if privileges
		if ($this->get('components_privileges')->resources->edit)
		{
			if (is_numeric($id_resource) && $id_resource > 0)
			{
				if ($this->input->post())
				{
					$this->load->library('form_validation');

					$this->form_validation->set_rules('url', 'lang:FIELD_URL', 'trim|required|max_length[50]|alpha_dash|xss_clean');
					$this->form_validation->set_rules('title', 'lang:FIELD_TITLE', 'trim|required|max_length[250]|xss_clean');
					$this->form_validation->set_rules('comments', 'lang:FIELD_COMMENTS', 'trim|max_length[500]|xss_clean');

					// if validation return error
					if ($this->form_validation->run() == FALSE)
					{
						$this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_EDIT_RESOURCE'));

						$this->_action_error = TRUE;
					}
					// if successfully verified
					else
					{
						// update data
						$this->db->query('
							update
								' . $this->db->dbprefix('resources') . ' as r
							set
								r.url=' . $this->db->escape($this->input->post('url')) . ',
								r.title=' . $this->db->escape($this->input->post('title')) . ',
								r.comments=' . $this->db->escape($this->input->post('comments')) . '
							where
								r.id=' . $this->db->escape($id_resource) . '
							limit 1
						');

						// if query ok
						if (!$this->db->_error_number())
						{
							// additional queries
							// NOTHING

							// additional actions
							$this->file_model->rewrite_resources_file();

							$this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_EDIT_RESOURCE'));
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
								'url' => $this->input->post('url', TRUE),
								'title' => $this->input->post('title', TRUE),
								'comments' => $this->input->post('comments', TRUE)
							)
						));
					}

					redirect('/admin/resources/edit_resource/' . $id_resource . '#edit_resource');
				}

				// get resource
				$this->set('resource', $this->db->query('
					select
						*
					from
						' . $this->db->dbprefix('resources') . '
					where
						id=' . $this->db->escape($id_resource) . '
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

	// delete resource
	public function delete_resource($id_resource = 0)
	{
		// if privileges
		if ($this->get('components_privileges')->resources->delete)
		{
			if (is_numeric($id_resource) && $id_resource > 0)
			{
				// delete data
				$this->db->query('
					delete
						r, rs
					from
						' . $this->db->dbprefix('resources') . ' as r
					join
						((select count(1) as how_many from ' . $this->db->dbprefix('resources') . ') as rc,
						(select id_resource_default as default_resource from ' . $this->db->dbprefix('settings') . ') as rd,
						' . $this->db->dbprefix('resources_settings') . ' as rs)
					where
						r.id=' . $this->db->escape($id_resource) . ' and
						rs.id_resource=r.id and
						rd.default_resource!=' . $this->db->escape($id_resource) . ' and
						rc.how_many > 1
				');

				// if query ok
				if (!$this->db->_error_number() && $this->db->affected_rows())
				{
					// additional queries
					$this->db->query('
						delete
							s
						from
							' . $this->db->dbprefix('structure') . ' as s
						where
							s.id_resource=' . $this->db->escape($id_resource) . '
					');
					$this->db->query('
						delete
							c
						from
							' . $this->db->dbprefix('containers') . ' as c
						where
							c.id_resource=' . $this->db->escape($id_resource) . '
					');

					// additional actions
					$this->file_model->rewrite_resources_file();

					$this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_DELETE_RESOURCE'));
				}
				// if query failed
				elseif (!$this->db->affected_rows())
				{
					$this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_DELETE_RESOURCE'));
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

			redirect('/admin/resources');
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
				r.id
			from
				' . $this->db->dbprefix('resources') . ' as r
		')->num_rows();
		$config['base_url']	= '/admin/resources/page/';

		$this->pagination->initialize($config);
		$this->set('pages_line', $this->pagination->create_links());

		$this->set('resources_position_number', $config['cur_page']);

		// get resources per page
		$this->set('resources', $this->db->query('
			select
				*
			from
				' . $this->db->dbprefix('resources') . ' as r
			order by
				r.title asc
			limit
				' . $config['cur_page'] . ', ' . $this->pagination->per_page . '
		'));
	}
}

/* End of file admin/resources.php */
/* Location: ./application/controllers/admin/resources.php */