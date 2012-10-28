<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends MY_Controller {

	public function __construct()
	{
		parent::MY_Controller();

		if (!$this->session->userdata('logged_as')) redirect('/admin/auth');

		$this->set('title', $this->lang->line('NAV_TITLE_MAIN'));
	}
	
	// controller page
	public function index()
	{
		// if privileges
		if ($this->get('components_privileges')->main->view)
		{
			// set form data
			$this->set('formdata', $this->session->userdata('formdata'));

			// get messages
			$this->set('messages', $this->db->query('
				select
					u.private_message as private_message,
					s.root_message as root_message
				from
					' . $this->db->dbprefix('users') . ' as u,
					' . $this->db->dbprefix('settings') . ' as s
				where
					u.id=' . $this->db->escape($this->session->userdata('logged_as')) . '
				limit 1
			')->row());

			// get user shortcuts
			$this->set('user_shortcuts', $this->db->query('
				select
					us.id as id,
					us.id_user as id_user,
					us.title as title,
					us.url as url,
					s.filename as filename
				from
					' . $this->db->dbprefix('users_shortcuts') . ' as us,
					' . $this->db->dbprefix('shortcuts') . ' as s
				where
					us.id_user=' . $this->db->escape($this->session->userdata('logged_as')) . ' and
					us.id_shortcut=s.id
				order by
					us.title asc
			'));

			// get user shortcuts
			$this->set('shortcuts', $this->db->query('
				select
					*
				from
					' . $this->db->dbprefix('shortcuts') . ' as s
				order by
					s.filename asc
			'));

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

	// edit user private message
	public function edit_user_message()
	{
		// if privileges
		if ($this->get('components_privileges')->main->edit)
		{
			if ($this->input->post())
			{
				$this->load->library('form_validation');

				$this->form_validation->set_rules('user_message', 'lang:CONTENT_MAIN_USER_MESSAGE', 'trim|xss_clean');

				// if validation return error
				if ($this->form_validation->run() == FALSE)
				{
					$this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_EDIT_USER_MESSAGE'));

					$this->_action_error = TRUE;
				}
				// if successfully verified
				else
				{
					// update data
					$this->db->query('
						update
							' . $this->db->dbprefix('users') . ' as u
						set
							u.private_message=' . $this->db->escape($this->input->post('user_message')) . '
						where
							u.id=' . $this->db->escape($this->session->userdata('logged_as')) . '
						limit 1
					');

					// if query ok
					if (!$this->db->_error_number())
					{
						$this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_EDIT_USER_MESSAGE'));
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
					'user_message' => $this->input->post('user_message', TRUE)
				));

				redirect('/admin/main#user_message');
			}
			// if action ok
			else
			{
				redirect('/admin/main#user_message');
			}
		}

		// no privileges
		else
		{
			$this->_backside_load_tpl_no_privileges();
		}
	}

	// edit root message
	public function edit_root_message()
	{
		// if privileges
		if ($this->get('components_privileges')->main->edit && !$this->get('userdata')->id_group)
		{
			if ($this->input->post())
			{
				$this->load->library('form_validation');

				$this->form_validation->set_rules('root_message', 'lang:CONTENT_MAIN_ROOT_MESSAGE', 'trim|xss_clean');

				// if validation return error
				if ($this->form_validation->run() == FALSE)
				{
					$this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_EDIT_ROOT_MESSAGE'));

					$this->_action_error = TRUE;
				}
				// if successfully verified
				else
				{
					// update data
					$this->db->query('
						update
							' . $this->db->dbprefix('settings') . ' as s
						set
							s.root_message=' . $this->db->escape($this->input->post('root_message')) . '
						limit 1
					');

					// if query ok
					if (!$this->db->_error_number())
					{
						$this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_EDIT_ROOT_MESSAGE'));
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
					'root_message' => $this->input->post('root_message', TRUE)
				));

				redirect('/admin/main#root_message');
			}
			// if action ok
			else
			{
				redirect('/admin/main#root_message');
			}
		}

		// no privileges
		else
		{
			$this->_backside_load_tpl_no_privileges();
		}
	}

	// add user shortcut
	public function add_user_shortcut()
	{
		// if privileges
		if ($this->get('components_privileges')->main->add)
		{
			if ($this->input->post())
			{
				$this->load->library('form_validation');

				$this->form_validation->set_rules('title', 'lang:FIELD_TITLE', 'trim|required|max_length[250]|xss_clean');
				$this->form_validation->set_rules('url', 'lang:FIELD_URL', 'trim|required|max_length[250]|xss_clean');
				$this->form_validation->set_rules('id_shortcut', 'lang:FIELD_ICON', 'trim|required|is_natural_no_zero|xss_clean');

				// if validation return error
				if ($this->form_validation->run() == FALSE)
				{
					$this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_ADD_USER_SHORTCUT'));

					$this->_action_error = TRUE;
				}
				// if successfully verified
				else
				{
					// insert data
					$this->db->query('
						insert into
							' . $this->db->dbprefix('users_shortcuts') . '
						select
							0,
							' . $this->db->escape($this->session->userdata('logged_as')) . ',
							s.id,
							' . $this->db->escape($this->input->post('title')) . ',
							' . $this->db->escape($this->input->post('url')) . '
						from
							' . $this->db->dbprefix('shortcuts') . ' as s
						where
							s.id=' . $this->db->escape($this->input->post('id_shortcut')) . '
					');

					// if query ok
					if (!$this->db->_error_number() && $this->db->affected_rows())
					{
						$this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_ADD_USER_SHORTCUT'));
					}
					// if query failed
					elseif (!$this->db->affected_rows())
					{
						$this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_ADD_USER_SHORTCUT'));

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
					'url' => $this->input->post('url', TRUE),
					'id_shortcut' => $this->input->post('id_shortcut', TRUE)
				));

				redirect('/admin/main#add_user_shortcut');
			}
			// if action ok
			else
			{
				redirect('/admin/main');
			}
		}

		// no privileges
		else
		{
			$this->_backside_load_tpl_no_privileges();
		}
	}

	// delete user shortcut
	public function delete_user_shortcut($id_shortcut = 0)
	{
		// if privileges
		if ($this->get('components_privileges')->main->delete)
		{
			if (is_numeric($id_shortcut) && $id_shortcut > 0)
			{
				// delete data
				$this->db->query('
					delete from
						' . $this->db->dbprefix('users_shortcuts') . '
					where
						id=' . $this->db->escape($id_shortcut) . ' and
						id_user=' . $this->db->escape($this->session->userdata('logged_as')) . '
				');

				// if query ok
				if (!$this->db->_error_number() && $this->db->affected_rows())
				{
					$this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_DELETE_USER_SHORTCUT'));
				}
				// if query failed
				elseif (!$this->db->affected_rows())
				{
					$this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_DELETE_USER_SHORTCUT'));
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

			redirect('/admin/main');
		}

		// no privileges
		else
		{
			$this->_backside_load_tpl_no_privileges();
		}
	}

	// change resource
	public function change_resource($id_resource = '')
	{
		if ($this->db->query('select id from ' . $this->db->dbprefix('resources') . ' where id=' . $this->db->escape($id_resource))->num_rows() > 0)
		{
			$this->session->set_userdata('id_resource_current', $id_resource);

			$this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_CHANGE_RESOURCE'));
		}
		else
		{
			$this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_CHANGE_RESOURCE'));
		}

		redirect('/admin/main');
	}
}

/* End of file admin/main.php */
/* Location: ./application/controllers/admin/main.php */