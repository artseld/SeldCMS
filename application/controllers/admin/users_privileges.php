<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_privileges extends MY_Controller {

	public function __construct()
	{
		parent::MY_Controller();

		if (!$this->session->userdata('logged_as')) redirect('/admin/auth');

		$this->set('title', $this->lang->line('NAV_TITLE_USERS_PRIVILEGES'));
	}
	
	// controller page
	public function index($page = 0)
	{
		// if privileges
		if ($this->get('components_privileges')->users_privileges->view)
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

	// edit privileges
	public function edit_privileges($id_group = 0)
	{
		// if privileges
		if ($this->get('components_privileges')->users_privileges->edit)
		{
			if (is_numeric($id_group) && $id_group > 0)
			{
				// get components
				$this->set('components', $this->db->query('
					select
						*
					from
						' . $this->db->dbprefix('components') . '
				'));

				// get modules
				$this->set('modules', $this->db->query('
					select
						*
					from
						' . $this->db->dbprefix('modules') . '
				'));

				if ($this->input->post())
				{
					// prepare edit array
					$edit_arr = array();

					// prepare update strings
					$f_update_str = ''; // frontside update string
					$b_update_str = ''; // backside update string

					$this->load->library('form_validation');

					$this->form_validation->set_rules('flag_frontside_access', 'lang:FIELD_FRONTSIDE_ACCESS', 'trim|trigger|xss_clean');
					$this->form_validation->set_rules('flag_backside_access', 'lang:FIELD_BACKSIDE_ACCESS', 'trim|trigger|xss_clean');

					// components rules
					foreach ($this->_tpl_data['components']->result() as $row)
					{
						$edit_arr['bc_' . $row->alias . '_flag_view_access']	= ($this->input->post('bc_' . $row->alias . '_flag_view_access') ? TRUE : FALSE);
						$this->form_validation->set_rules('bc_' . $row->alias . '_flag_view_access', 'bc_' . $row->alias . '_flag_view_access', 'trim|trigger|xss_clean');
						$edit_arr['bc_' . $row->alias . '_flag_edit_access']	= ($this->input->post('bc_' . $row->alias . '_flag_edit_access') ? TRUE : FALSE);
						$this->form_validation->set_rules('bc_' . $row->alias . '_flag_edit_access', 'bc_' . $row->alias . '_flag_edit_access', 'trim|trigger|xss_clean');
						$edit_arr['bc_' . $row->alias . '_flag_add_access']	= ($this->input->post('bc_' . $row->alias . '_flag_add_access') ? TRUE : FALSE);
						$this->form_validation->set_rules('bc_' . $row->alias . '_flag_add_access', 'bc_' . $row->alias . '_flag_add_access', 'trim|trigger|xss_clean');
						$edit_arr['bc_' . $row->alias . '_flag_delete_access']	= ($this->input->post('bc_' . $row->alias . '_flag_delete_access') ? TRUE : FALSE);
						$this->form_validation->set_rules('bc_' . $row->alias . '_flag_delete_access', 'bc_' . $row->alias . '_flag_delete_access', 'trim|trigger|xss_clean');
						$f_update_str .= NULL;
						$b_update_str .= '(' . $this->db->escape($id_group) . ', ' . $row->id . ', \'component\', ' . $this->db->escape($this->input->post('bc_' . $row->alias . '_flag_view_access')) . ', ' . $this->db->escape($this->input->post('bc_' . $row->alias . '_flag_edit_access')) . ', ' . $this->db->escape($this->input->post('bc_' . $row->alias . '_flag_add_access')) . ', ' . $this->db->escape($this->input->post('bc_' . $row->alias . '_flag_delete_access')) . '),';
					}
					// modules rules
					foreach ($this->_tpl_data['modules']->result() as $row)
					{
						$edit_arr['fm_' . $row->alias . '_flag_access']		= ($this->input->post('fm_' . $row->alias . '_flag_access') ? TRUE : FALSE);
						$this->form_validation->set_rules('fm_' . $row->alias . '_flag_access', 'fm_' . $row->alias . '_flag_access', 'trim|trigger|xss_clean');
						$edit_arr['bm_' . $row->alias . '_flag_view_access']	= ($this->input->post('bm_' . $row->alias . '_flag_view_access') ? TRUE : FALSE);
						$this->form_validation->set_rules('bm_' . $row->alias . '_flag_view_access', 'bm_' . $row->alias . '_flag_view_access', 'trim|trigger|xss_clean');
						$edit_arr['bm_' . $row->alias . '_flag_edit_access']	= ($this->input->post('bm_' . $row->alias . '_flag_edit_access') ? TRUE : FALSE);
						$this->form_validation->set_rules('bm_' . $row->alias . '_flag_edit_access', 'bm_' . $row->alias . '_flag_edit_access', 'trim|trigger|xss_clean');
						$edit_arr['bm_' . $row->alias . '_flag_add_access']		= ($this->input->post('bm_' . $row->alias . '_flag_add_access') ? TRUE : FALSE);
						$this->form_validation->set_rules('bm_' . $row->alias . '_flag_add_access', 'bm_' . $row->alias . '_flag_add_access', 'trim|trigger|xss_clean');
						$edit_arr['bm_' . $row->alias . '_flag_delete_access']	= ($this->input->post('bm_' . $row->alias . '_flag_delete_access') ? TRUE : FALSE);
						$this->form_validation->set_rules('bm_' . $row->alias . '_flag_delete_access', 'bm_' . $row->alias . '_flag_delete_access', 'trim|trigger|xss_clean');
						$f_update_str .= '(' . $this->db->escape($id_group) . ', ' . $row->id . ', ' . $this->db->escape($this->input->post('fm_' . $row->alias . '_flag_access')) . '),';
						$b_update_str .= '(' . $this->db->escape($id_group) . ', ' . $row->id . ', \'module\', ' . $this->db->escape($this->input->post('bm_' . $row->alias . '_flag_view_access')) . ', ' . $this->db->escape($this->input->post('bm_' . $row->alias . '_flag_edit_access')) . ', ' . $this->db->escape($this->input->post('bm_' . $row->alias . '_flag_add_access')) . ', ' . $this->db->escape($this->input->post('bm_' . $row->alias . '_flag_delete_access')) . '),';
					}

					$f_update_str	= rtrim($f_update_str, ',');
					$b_update_str	= rtrim($b_update_str, ',');

					// if validation return error
					if ($this->form_validation->run() == FALSE)
					{
						$this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_EDIT_PRIVILEGES'));

						$this->_action_error = TRUE;
					}
					// if successfully verified
					else
					{
						// update data
						$this->db->query('
							update
								' . $this->db->dbprefix('users_groups') . ' as ug
							set
								ug.flag_frontside_access=' . $this->db->escape($this->input->post('flag_frontside_access')) . ',
								ug.flag_backside_access=' . $this->db->escape($this->input->post('flag_backside_access')) . '
							where
								ug.id=' . $this->db->escape($id_group) . ' and
								ug.id!=0
							limit 1
						');

						// if query ok
						if (!$this->db->_error_number())
						{
							// additional queries
							// update frontside privileges
							$this->db->query('
								delete
									ugfp
								from
									' . $this->db->dbprefix('users_groups_frontside_privileges') . ' as ugfp
								where
									ugfp.id_group=' . $this->db->escape($id_group) . ' and
									ugfp.id_group!=0
							');
							$this->db->query('
								insert into
									' . $this->db->dbprefix('users_groups_frontside_privileges') . '
									(id_group, id_module, flag_access)
								values
									' . $f_update_str . '
							');
							// update backside privileges
							$this->db->query('
								delete
									ugbp
								from
									' . $this->db->dbprefix('users_groups_backside_privileges') . ' as ugbp
								where
									ugbp.id_group=' . $this->db->escape($id_group) . ' and
									ugbp.id_group!=0
							');
							$this->db->query('
								insert into
									' . $this->db->dbprefix('users_groups_backside_privileges') . '
									(id_group, id_element, type_element, flag_view_access, flag_edit_access, flag_add_access, flag_delete_access)
								values
									' . $b_update_str . '
							');

							// additional actions
							// NOTHING

							$this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_EDIT_PRIVILEGES'));
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
						$edit_arr['flag_frontside_access']	= ($this->input->post('flag_frontside_access') ? TRUE : FALSE);
						$edit_arr['flag_backside_access']	= ($this->input->post('flag_backside_access') ? TRUE : FALSE);
						$this->session->set_userdata('formdata', array(
							'edit' => $edit_arr
						));
					}

					redirect('/admin/users_privileges/edit_privileges/' . $id_group . '#edit_privileges');
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

				// get groups privileges
				$this->set('fm_privileges', $this->db->query('
					select
						m.alias as alias,
						m.title as title,
						ugfp.flag_access as flag_access
					from
						' . $this->db->dbprefix('users_groups_frontside_privileges') . ' as ugfp,
						' . $this->db->dbprefix('modules') . ' as m
					where
						ugfp.id_group=' . $this->db->escape($id_group) . ' and
						ugfp.id_module=m.id
					order by
						m.alias asc
				'));
				$this->set('bc_privileges', $this->db->query('
					select
						c.alias as alias,
						ugbp.flag_view_access as flag_view_access,
						ugbp.flag_edit_access as flag_edit_access,
						ugbp.flag_add_access as flag_add_access,
						ugbp.flag_delete_access as flag_delete_access
					from
						' . $this->db->dbprefix('users_groups_backside_privileges') . ' as ugbp,
						' . $this->db->dbprefix('components') . ' as c
					where
						ugbp.id_group=' . $this->db->escape($id_group) . ' and
						ugbp.id_element=c.id and
						ugbp.type_element=\'component\'
					order by
						c.alias asc
				'));
				$this->set('bm_privileges', $this->db->query('
					select
						m.alias as alias,
						m.title as title,
						ugbp.flag_view_access as flag_view_access,
						ugbp.flag_edit_access as flag_edit_access,
						ugbp.flag_add_access as flag_add_access,
						ugbp.flag_delete_access as flag_delete_access
					from
						' . $this->db->dbprefix('users_groups_backside_privileges') . ' as ugbp,
						' . $this->db->dbprefix('modules') . ' as m
					where
						ugbp.id_group=' . $this->db->escape($id_group) . ' and
						ugbp.id_element=m.id and
						ugbp.type_element=\'module\'
					order by
						m.alias asc
				'));
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
		$config['base_url']	= '/admin/users_privileges/page/';

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

/* End of file admin/users_privileges.php */
/* Location: ./application/controllers/admin/users_privileges.php */