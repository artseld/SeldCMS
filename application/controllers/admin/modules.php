<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modules extends MY_Controller {

	public function __construct()
	{
		parent::MY_Controller();

		if (!$this->session->userdata('logged_as')) redirect('/admin/auth');

		$this->set('title', $this->lang->line('NAV_TITLE_MODULES'));
	}
	
	// controller page
	public function index($page = 0)
	{
		// if privileges
		if ($this->get('components_privileges')->modules->view)
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

	// add module
	public function add_module()
	{
		// if privileges
		if ($this->get('components_privileges')->modules->add)
		{
			if ($this->input->post())
			{
				$this->load->library('form_validation');

				$this->form_validation->set_rules('alias', 'lang:FIELD_ALIAS', 'trim|required|max_length[50]|alpha|xss_clean');

				// if validation return error
				if ($this->form_validation->run() == FALSE)
				{
					$this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_ADD_MODULE'));

					$this->_action_error = TRUE;
				}
				// if successfully verified
				else
				{
					if (file_exists(APPPATH . 'install/' . $this->input->post('alias', TRUE) . '.php'))
					{
						$file_error = FALSE;
						// insert data
						$this->db->query('
							insert into
								' . $this->db->dbprefix('modules') . '
							values (
								0,
								' . $this->db->escape($this->input->post('alias')) . ',
								' . $this->db->escape($this->lang->line('FIELD_UNDEFINED')) . ',
								\'\'
							)
						');
					}
					else
					{
						$file_error = TRUE;
					}

					// if query ok
					if (!$this->db->_error_number() && $this->db->affected_rows() && !$file_error)
					{
						// additional queries
						$id_module = $this->db->query('
							select
								max(id) as id
							from
								' . $this->db->dbprefix('modules') . '
						')->row()->id;
						$this->db->query('
							insert into
								' . $this->db->dbprefix('users_groups_frontside_privileges') . '
							select
								ug.id as id_group,
								' . $id_module . ' as id_module,
								if (ug.id=0, 1, 0) as flag_access
							from
								' . $this->db->dbprefix('users_groups') . ' as ug
						');
						$this->db->query('
							insert into
								' . $this->db->dbprefix('users_groups_backside_privileges') . '
							select
								ug.id as id_group,
								' . $id_module . ' as id_element,
								\'module\' as type_element,
								if (ug.id=0, 1, 0) as flag_view_access,
								if (ug.id=0, 1, 0) as flag_edit_access,
								if (ug.id=0, 1, 0) as flag_add_access,
								if (ug.id=0, 1, 0) as flag_delete_access
							from
								' . $this->db->dbprefix('users_groups') . ' as ug
						');

						// additional actions
						require_once(APPPATH . 'install/' . $this->input->post('alias', TRUE) . '.php');

						if (!$this->_action_error) $this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_ADD_MODULE'));
					}
					// if query failed
					elseif (!$this->db->affected_rows() || $file_error)
					{
						$this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_ADD_MODULE'));

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
					'alias' => $this->input->post('alias', TRUE)
				));

				redirect('/admin/modules#add_module');
			}
			// if action ok
			else
			{
				redirect('/admin/modules');
			}
		}

		// no privileges
		else
		{
			$this->_backside_load_tpl_no_privileges();
		}
	}

	// delete module
	public function delete_module($id_module = 0)
	{
		// if privileges
		if ($this->get('components_privileges')->modules->delete)
		{
			if (is_numeric($id_module) && $id_module > 0)
			{
				$alias = $this->db->query('
					select
						alias
					from
						' . $this->db->dbprefix('modules') . '
					where
						id=' . $this->db->escape($id_module) . '
					limit 1
				')->row()->alias;

				if (file_exists(APPPATH . 'install/' . $alias . '_unins.php'))
				{
					$file_error = FALSE;
					// delete data
					$this->db->query('
						delete
							m
						from
							' . $this->db->dbprefix('modules') . ' as m
						where
							m.id=' . $this->db->escape($id_module) . '
					');
				}
				else
				{
					$file_error = TRUE;
				}

				// if query ok
				if (!$this->db->_error_number() && $this->db->affected_rows() && !$file_error)
				{
					// additional queries
					$this->db->query('
						update
							' . $this->db->dbprefix('structure') . '
						set
							id_module_instance=0
						where
							id_module_instance in (
								select
									id
								from
									' . $this->db->dbprefix('modules_instances') . '
								where
									id_module=' . $this->db->escape($id_module) . '
							)
					');
					$this->db->query('
						delete
							mc
						from
							' . $this->db->dbprefix('modules_controllers') . ' as mc
						where
							mc.id_module=' . $this->db->escape($id_module) . '
					');
					$this->db->query('
						delete
							md
						from
							' . $this->db->dbprefix('modules_datasets') . ' as md
						where
							md.id_module=' . $this->db->escape($id_module) . '
					');
					$this->db->query('
						delete
							mid
						from
							' . $this->db->dbprefix('modules_instances_datasets') . ' as mid
						where
							mid.id_instance in (
								select
									id
								from
									' . $this->db->dbprefix('modules_instances') . '
								where
									id_module=' . $this->db->escape($id_module) . '
							)
					');
					$this->db->query('
						delete
							mi
						from
							' . $this->db->dbprefix('modules_instances') . ' as mi
						where
							mi.id_module=' . $this->db->escape($id_module) . '
					');
					$this->db->query('
						delete
							ugfp
						from
							' . $this->db->dbprefix('users_groups_frontside_privileges') . ' as ugfp
						where
							ugfp.id_module=' . $this->db->escape($id_module) . '
					');
					$this->db->query('
						delete
							ugbp
						from
							' . $this->db->dbprefix('users_groups_backside_privileges') . ' as ugbp
						where
							ugbp.id_element=' . $this->db->escape($id_module) . ' and
							ugbp.type_element=\'module\'
					');

					// additional actions
					require_once(APPPATH . 'install/' . $alias . '_unins.php');

					if (!$this->_action_error) $this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_DELETE_MODULE'));
				}
				// if query failed
				elseif (!$this->db->affected_rows() || $file_error)
				{
					$this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_DELETE_MODULE'));
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

			redirect('/admin/modules');
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

		$config['cur_page']     = ($page != "" && is_numeric($page)) ? $page : 0;
		$config['total_rows']   = $this->db->query('
			select
				id
			from
				' . $this->db->dbprefix('modules') . '
		')->num_rows();
		$config['base_url']     = '/admin/modules/page/';

		$this->pagination->initialize($config);
		$this->set('pages_line', $this->pagination->create_links());

		$this->set('modules_position_number', $config['cur_page']);

		// get modules per page
		$this->set('modules', $this->db->query('
			select
				*
			from
				' . $this->db->dbprefix('modules') . '
			order by
				title asc,
				alias asc
			limit
				' . $config['cur_page'] . ', ' . $this->pagination->per_page . '
		'));
	}
}

/* End of file admin/modules.php */
/* Location: ./application/controllers/admin/modules.php */