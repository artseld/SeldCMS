<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends MY_Controller {

	public function __construct()
	{
		parent::MY_Controller();

		if (!$this->session->userdata('logged_as')) redirect('/admin/auth');

		$this->set('title', $this->lang->line('NAV_TITLE_SETTINGS'));
	}
	
	// controller page
	public function index()
	{
		// if privileges
		if ($this->get('components_privileges')->settings->view)
		{
			// set form data
			$this->set('formdata', $this->session->userdata('formdata'));

			// get resource settings
			$this->set('resource_settings', $this->db->query('
				select
					*
				from
					' . $this->db->dbprefix('resources_settings') . ' as rs
				where
					rs.id_resource=' . $this->db->escape($this->session->userdata('id_resource_current')) . '
				limit 1
			')->row());

			// get global settings
			$this->set('global_settings', $this->db->query('
				select
					*
				from
					' . $this->db->dbprefix('settings') . ' as s
				limit 1
			')->row());

			// get resources
			$this->set('resources', $this->db->query('
				select
					*
				from
					' . $this->db->dbprefix('resources') . ' as r
				order by
					r.title asc
			'));

			// get users groups
			$this->set('users_groups', $this->db->query('
				select
					*
				from
					' . $this->db->dbprefix('users_groups') . ' as ug
				where
					ug.id!=0
				order by
					ug.title asc
			'));

			// get countries
			$this->set('countries_groups', $this->db->query('
				select
					distinct c.group_alias as alias
				from
					' . $this->db->dbprefix('countries') . ' as c
				order by
					c.group_alias asc
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

	// edit resource settings
	public function edit_resource()
	{
		// if privileges
		if ($this->get('components_privileges')->settings->edit)
		{
			if ($this->input->post())
			{
				$this->load->library('form_validation');

				$this->form_validation->set_rules('meta_keywords', 'lang:FIELD_META_KEYWORDS', 'trim|xss_clean');
				$this->form_validation->set_rules('meta_description', 'lang:FIELD_META_DESCRIPTION', 'trim|xss_clean');
				$this->form_validation->set_rules('meta_additional', 'lang:FIELD_META_ADDITIONAL', 'trim|xss_clean');
				$this->form_validation->set_rules('title_browser', 'lang:FIELD_TITLE_BROWSER', 'trim|max_length[250]|xss_clean');
				$this->form_validation->set_rules('title_page', 'lang:FIELD_TITLE_PAGE', 'trim|max_length[250]|xss_clean');
				$this->form_validation->set_rules('title_menu', 'lang:FIELD_TITLE_MENU', 'trim|max_length[250]|xss_clean');
				$this->form_validation->set_rules('url_error_404', 'lang:FIELD_URL_ERROR_404', 'trim|required|max_length[250]|xss_clean');
                                $this->form_validation->set_rules('url_ipblocked', 'lang:FIELD_URL_IPBLOCKED', 'trim|required|max_length[250]|xss_clean');

				// if validation return error
				if ($this->form_validation->run() == FALSE)
				{
					$this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_EDIT_SETTINGS'));

					$this->_action_error = TRUE;
				}
				// if successfully verified
				else
				{
					// update data
					$this->db->query('
						update
							' . $this->db->dbprefix('resources_settings') . ' as rs
						set
							rs.meta_keywords=' . $this->db->escape($this->input->post('meta_keywords')) . ',
							rs.meta_description=' . $this->db->escape($this->input->post('meta_description')) . ',
							rs.meta_additional=' . $this->db->escape($this->input->post('meta_additional')) . ',
							rs.title_browser=' . $this->db->escape($this->input->post('title_browser')) . ',
							rs.title_page=' . $this->db->escape($this->input->post('title_page')) . ',
							rs.title_menu=' . $this->db->escape($this->input->post('title_menu')) . ',
							rs.url_error_404=' . $this->db->escape($this->input->post('url_error_404')) . ',
                                                        rs.url_ipblocked=' . $this->db->escape($this->input->post('url_ipblocked')) . '
						where
							rs.id_resource=' . $this->db->escape($this->session->userdata('id_resource_current')) . '
						limit 1
					');

					// if query ok
					if (!$this->db->_error_number())
					{
						$this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_EDIT_SETTINGS'));
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
					'meta_keywords' => $this->input->post('meta_keywords', TRUE),
					'meta_description' => $this->input->post('meta_description', TRUE),
					'meta_additional' => $this->input->post('meta_additional', TRUE),
					'title_browser' => $this->input->post('title_browser', TRUE),
					'title_page' => $this->input->post('title_page', TRUE),
					'title_menu' => $this->input->post('title_menu', TRUE),
					'url_error_404' => $this->input->post('url_error_404', TRUE),
                                        'url_ipblocked' => $this->input->post('url_ipblocked', TRUE)
				));

				redirect('/admin/settings#resource_settings');
			}
			// if action ok
			else
			{
				redirect('/admin/settings#resource_settings');
			}
		}

		// no privileges
		else
		{
			$this->_backside_load_tpl_no_privileges();
		}
	}

	// edit global settings
	public function edit_global()
	{
		// if privileges
		if ($this->get('components_privileges')->settings->edit)
		{
			if ($this->input->post())
			{
				$this->load->library('form_validation');

				$this->form_validation->set_rules('id_resource_default', 'lang:FIELD_RESOURCE', 'trim|required|is_natural_no_zero|xss_clean');
				$this->form_validation->set_rules('id_users_group_default', 'lang:FIELD_USERS_GROUP', 'trim|required|is_natural_no_zero|xss_clean');
				$this->form_validation->set_rules('flag_countries_selector', 'lang:FIELD_GLOBAL_COUNTRY_SELECTOR', 'trim|required|trigger|xss_clean');
				$this->form_validation->set_rules('countries_selector_alias', 'lang:FIELD_GLOBAL_COUNTRY_LANGUAGE', 'trim|required|exact_length[2]|xss_clean');
				$this->form_validation->set_rules('delimiter_breadcrumbs', 'lang:FIELD_DELIMITER_BREADCRUMBS', 'trim|max_length[25]|xss_clean');
                                $this->form_validation->set_rules('flag_use_rte', 'lang:FIELD_USE_RTE', 'trim|required|trigger|xss_clean');
				$this->form_validation->set_rules('flag_caching', 'lang:FIELD_GLOBAL_CACHING', 'trim|required|trigger|xss_clean');
				$this->form_validation->set_rules('caching_duration', 'lang:FIELD_CACHING_DURATION', 'trim|is_natural|xss_clean');
				$this->form_validation->set_rules('flag_logging', 'lang:FIELD_GLOBAL_LOGGING', 'trim|required|trigger|xss_clean');
                                $this->form_validation->set_rules('max_failed_attempts', 'lang:FIELD_MAX_FAILED_ATTEMPTS', 'trim|required|is_natural|max_length[2]|xss_clean');
                                $this->form_validation->set_rules('blocking_duration', 'lang:FIELD_BLOCKING_DURATION', 'trim|is_natural|xss_clean');
                                $this->form_validation->set_rules('clear_interval', 'lang:FIELD_CLEAR_INTERVAL', 'trim|is_natural|xss_clean');
		
				// if validation return error
				if ($this->form_validation->run() == FALSE)
				{
					$this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_EDIT_SETTINGS'));

					$this->_action_error = TRUE;
				}
				// if successfully verified
				else
				{
					// update data
					$this->db->query('
						update
							' . $this->db->dbprefix('settings') . ' as s,
							' . $this->db->dbprefix('resources') . ' as r,
							' . $this->db->dbprefix('users_groups') . ' as ug
						set
							s.id_resource_default=r.id,
							s.id_users_group_default=ug.id,
							s.flag_countries_selector=' . $this->db->escape($this->input->post('flag_countries_selector')) . ',
							s.countries_selector_alias=' . $this->db->escape($this->input->post('countries_selector_alias')) . ',
							s.delimiter_breadcrumbs=' . $this->db->escape($this->input->post('delimiter_breadcrumbs')) . ',
                                                        s.flag_use_rte=' . $this->db->escape($this->input->post('flag_use_rte')) . ',
							s.flag_caching=' . $this->db->escape($this->input->post('flag_caching')) . ',
							s.caching_duration=' . $this->db->escape($this->input->post('caching_duration')) . ',
							s.flag_logging=' . $this->db->escape($this->input->post('flag_logging')) . ',
                                                        s.max_failed_attempts=' . $this->db->escape($this->input->post('max_failed_attempts')) . ',
                                                        s.blocking_duration=' . $this->db->escape($this->input->post('blocking_duration')) . ',
                                                        s.clear_interval=' . $this->db->escape($this->input->post('clear_interval')) . '
						where
							r.id=' . $this->db->escape($this->input->post('id_resource_default')) . ' and
							ug.id=' . $this->db->escape($this->input->post('id_users_group_default')) . ' and
							ug.id!=0 and
							(select
								count(1)
							from
								' . $this->db->dbprefix('countries') . '
							where
								group_alias=' . $this->db->escape($this->input->post('countries_selector_alias')) . ') > 0
					');

					// if query ok
					if (!$this->db->_error_number())
					{
						$this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_EDIT_SETTINGS'));
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
					'id_resource_default' => $this->input->post('id_resource_default', TRUE),
					'id_users_group_default' => $this->input->post('id_users_group_default', TRUE),
					'flag_countries_selector' => $this->input->post('flag_countries_selector', TRUE),
					'countries_selector_alias' => $this->input->post('countries_selector_alias', TRUE),
                                        'flag_use_rte' => $this->input->post('flag_use_rte', TRUE),
					'delimiter_breadcrumbs' => $this->input->post('delimiter_breadcrumbs', TRUE),
					'flag_caching' => $this->input->post('flag_caching', TRUE),
					'caching_duration' => $this->input->post('caching_duration', TRUE),
					'flag_logging' => $this->input->post('flag_logging', TRUE),
                                        'max_failed_attempts' => $this->input->post('max_failed_attempts', TRUE),
                                        'blocking_duration' => $this->input->post('blocking_duration', TRUE),
                                        'clear_interval' => $this->input->post('clear_interval', TRUE)
				));

				redirect('/admin/settings#global_settings');
			}
			// if action ok
			else
			{
				redirect('/admin/settings#global_settings');
			}
		}

		// no privileges
		else
		{
			$this->_backside_load_tpl_no_privileges();
		}
	}
}

/* End of file admin/settings.php */
/* Location: ./application/controllers/admin/settings.php */