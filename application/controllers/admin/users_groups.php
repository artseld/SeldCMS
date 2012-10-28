<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_groups extends MY_Controller {

	public function __construct()
	{
		parent::MY_Controller();

		if (!$this->session->userdata('logged_as')) redirect('/admin/auth');

		$this->set('title', $this->lang->line('NAV_TITLE_USERS_GROUPS'));
	}
	
	// controller page
	public function index($page = 0)
	{
		// if privileges
		if ($this->get('components_privileges')->users_groups->view)
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
		if ($this->get('components_privileges')->users_groups->add)
		{
			if ($this->input->post())
			{
				$this->load->library('form_validation');

				$this->form_validation->set_rules('title', 'lang:FIELD_TITLE', 'trim|required|max_length[250]|xss_clean');
				$this->form_validation->set_rules('comments', 'lang:FIELD_COMMENTS', 'trim|max_length[500]|xss_clean');

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
							' . $this->db->dbprefix('users_groups') . '
						values (
							0,
							' . $this->db->escape($this->input->post('title')) . ',
							0,
							0,
							' . $this->db->escape($this->input->post('comments')) . '
						)
					');

					// if query ok
					if (!$this->db->_error_number() && $this->db->affected_rows())
					{
						// additional queries
						$id_group = $this->db->query('
							select
								max(id) as id
							from
								' . $this->db->dbprefix('users_groups') . '
							limit 1
						')->row()->id;
						// set backside privileges
						$this->db->query('
							insert into
								' . $this->db->dbprefix('users_groups_backside_privileges') . '
							select
								*
							from (
								(select
									' . $id_group . ',
									c.id,
									\'component\',
									0 as flag_view_access,
									0 as flag_edit_access,
									0 as flag_add_access,
									0 as flag_delete_access
								from
									' . $this->db->dbprefix('components') . ' as c)
								union
								(select
									' . $id_group . ',
									m.id,
									\'module\',
									0 as flag_view_access,
									0 as flag_edit_access,
									0 as flag_add_access,
									0 as flag_delete_access
								from
									' . $this->db->dbprefix('modules') . ' as m)
							) uq
						');
						// set frontside privileges
						$this->db->query('
							insert into
								' . $this->db->dbprefix('users_groups_frontside_privileges') . '
							select
								' . $id_group . ',
								m.id,
								0 as flag_access
							from
								' . $this->db->dbprefix('modules') . ' as m
						');

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
					'title' => $this->input->post('title', TRUE),
					'comments' => $this->input->post('comments', TRUE)
				));

				redirect('/admin/users_groups#add_group');
			}
			// if action ok
			else
			{
				redirect('/admin/users_groups');
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
		if ($this->get('components_privileges')->users_groups->edit)
		{
			if (is_numeric($id_group) && $id_group > 0)
			{
				if ($this->input->post())
				{
					$this->load->library('form_validation');

					$this->form_validation->set_rules('title', 'lang:FIELD_TITLE', 'trim|required|max_length[250]|xss_clean');
					$this->form_validation->set_rules('comments', 'lang:FIELD_COMMENTS', 'trim|max_length[500]|xss_clean');

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
								' . $this->db->dbprefix('users_groups') . '
							set
								title=' . $this->db->escape($this->input->post('title')) . ',
								comments=' . $this->db->escape($this->input->post('comments')) . '
							where
								id=' . $this->db->escape($id_group) . ' and
								id!=0
							limit 1
						');

						// if query ok
						if (!$this->db->_error_number())
						{
							// additional queries
							// NOTHING

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
								'title' => $this->input->post('title', TRUE),
								'comments' => $this->input->post('comments', TRUE)
							)
						));
					}

					redirect('/admin/users_groups/edit_group/' . $id_group . '#edit_group');
				}

				// get group
				$this->set('group', $this->db->query('
					select
						*
					from
						' . $this->db->dbprefix('users_groups') . '
					where
						id=' . $this->db->escape($id_group) . '
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
		if ($this->get('components_privileges')->users_groups->delete)
		{
			if (is_numeric($id_group) && $id_group > 0)
			{
				// delete data
				$this->db->query('
					delete
						ug
					from
						' . $this->db->dbprefix('users_groups') . ' as ug
					join
						(select count(1) as how_many from ' . $this->db->dbprefix('users_groups') . ') as ug
					where
						ug.id=' . $this->db->escape($id_group) . ' and
						ug.id!=' . $this->db->escape($this->get('userdata')->id_group) . ' and
						ug.id!=0 and
						ug.how_many > 2
				');

				// if query ok
				if (!$this->db->_error_number() && $this->db->affected_rows())
				{
					// additional queries
					$this->db->query('
						delete
							ugbp
						from
							' . $this->db->dbprefix('users_groups_backside_privileges') . ' as ugbp
						where
							id_group=' . $this->db->escape($id_group) . ' and
							id_group!=0
					');
					$this->db->query('
						delete
							ugfp
						from
							' . $this->db->dbprefix('users_groups_frontside_privileges') . ' as ugfp
						where
							id_group=' . $this->db->escape($id_group) . ' and
							id_group!=0
					');
					$this->db->query('
						delete
							u
						from
							' . $this->db->dbprefix('users') . ' as u
						where
							id_group=' . $this->db->escape($id_group) . ' and
							id_group!=0
					');

					// additional actions
					// NOTHING

					$this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_DELETE_USER_GROUP'));
				}
				// if query failed
				elseif (!$this->db->affected_rows())
				{
					$this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_DELETE_USER_GROUP'));
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

			redirect('/admin/users_groups');
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
				' . $this->db->dbprefix('users_groups') . '
			where
				id!=0
		')->num_rows();
		$config['base_url']	= '/admin/users_groups/page/';

		$this->pagination->initialize($config);
		$this->set('pages_line', $this->pagination->create_links());

		$this->set('groups_position_number', $config['cur_page']);

		// get groups per page
		$this->set('users_groups', $this->db->query('
			select
				*
			from
				' . $this->db->dbprefix('users_groups') . ' as ug
			where
				ug.id!=0
			order by
				ug.title asc
			limit
				' . $config['cur_page'] . ', ' . $this->pagination->per_page . '
		'));
	}
}

/* End of file admin/users_groups.php */
/* Location: ./application/controllers/admin/users_groups.php */