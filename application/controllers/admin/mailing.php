<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mailing extends MY_Controller {

    public function __construct()
    {
        parent::MY_Controller();

        if (!$this->session->userdata('logged_as')) redirect('/admin/auth');

        $this->set('title', $this->lang->line('NAV_TITLE_MAILING'));

        // search pre-actions
        $this->set('keywords', array( 'text' => '' ));
        if ($this->session->flashdata('keywords_m'))
        {
            $this->set('keywords', $this->session->flashdata('keywords_m'));
            $this->session->keep_flashdata('keywords_m');
        }
        else $this->session->flashdata('keywords_m', array( 'text' => '' ));

        $this->set('global_flag_use_rte', $this->get('global.flag_use_rte'));
    }
	
    // controller page
    public function index($page = 0)
    {
        // if privileges
        if ($this->get('components_privileges')->mailing->view)
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
        if ($this->get('components_privileges')->mailing->view && $this->input->post())
        {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('text', 'lang:FIELD_KEYWORDS', 'trim|max_length[250]|xss_clean');

            // if validation return error
            if ($this->form_validation->run() != FALSE)
            {
                $this->session->set_flashdata('keywords_m', array(
                    'text' => $this->input->post('text', TRUE)
                ));
            }

            redirect('/admin/mailing');
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
        if ($this->get('components_privileges')->mailing->view)
        {
            $this->session->set_flashdata('keywords_m', array( 'text' => '' ));

            redirect('/admin/mailing');
        }

        // no privileges
        else
        {
            $this->_backside_load_tpl_no_privileges();
        }
    }

	// edit settings
	public function edit_settings()
	{
		// if privileges
		if ($this->get('components_privileges')->mailing->edit)
		{
			if ($this->input->post())
			{
				$this->load->library('form_validation');

				$this->form_validation->set_rules('accounts_num_in_stream', 'lang:FIELD_ACCOUNTS_NUM_IN_STREAM', 'trim|required|is_natural_no_zero|max_length[3]|xss_clean');
				$this->form_validation->set_rules('subscribers_category', 'lang:FIELD_MAILING_TYPE', 'trim|required|is_natural_no_zero|max_length[3]|xss_clean');
				$this->form_validation->set_rules('signature', 'lang:FIELD_SIGNATURE', 'trim|required|max_length[500]|xss_clean');

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
							' . $this->db->dbprefix('mailing') . ' as m,
							' . $this->db->dbprefix('mailing_subscribers_categories') . ' as msc
						set
							m.accounts_num_in_stream=' . $this->db->escape($this->input->post('accounts_num_in_stream')) . ',
							m.subscribers_category=msc.id,
							m.signature=' . $this->db->escape($this->input->post('signature')) . '
						where
							m.id_resource=' . $this->db->escape($this->session->userdata('id_resource_current')) . ' and
							msc.id=' . $this->db->escape($this->input->post('subscribers_category')) . '
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
					'accounts_num_in_stream' => $this->input->post('accouns_num_in_stream', TRUE),
					'signature' => $this->input->post('signature', TRUE)
				));

				redirect('/admin/mailing#settings');
			}
			// if action ok
			else
			{
				redirect('/admin/mailing#settings');
			}
		}

		// no privileges
		else
		{
			$this->_backside_load_tpl_no_privileges();
		}
	}

	// delete log
	public function delete_log($id_log = 0)
	{
		// if privileges
		if ($this->get('components_privileges')->mailing->delete)
		{
			if (is_numeric($id_log) && $id_log > 0)
			{
				// delete data
				$this->db->query('
					delete
						me
					from
						' . $this->db->dbprefix('mailing_events') . ' as me
					where
						me.id=' . $this->db->escape($id_log) . ' and
						me.id_resource=' . $this->db->escape($this->session->userdata('id_resource_current')) . '
				');

				// if query ok
				if (!$this->db->_error_number() && $this->db->affected_rows())
				{
					// additional queries
					// NOTHING

					// additional actions
					// NOTHING

					$this->_backside_set_message('done', $this->lang->line('MESSAGE_SC_DELETE_MAILING_LOG'));
				}
				// if query failed
				elseif (!$this->db->affected_rows())
				{
					$this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_DELETE_MAILING_LOG'));
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

			redirect('/admin/mailing');
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
				me.id
			from
				' . $this->db->dbprefix('mailing_events') . ' as me,
				' . $this->db->dbprefix('mailing_subscribers_categories') . ' as msc
			where
				me.id_resource=(select id_resource_default from ' . $this->db->dbprefix('settings') . ' limit 1) and
				me.subscribers_category=msc.id
				' . ($keywords ? 'and (
				' . (!empty($keywords['text']) ? '(
					me.datetime_event like \'%' . $this->db->escape_like_str($keywords['text']) . '%\' or
					me.subject like \'%' . $this->db->escape_like_str($keywords['text']) . '%\' or
					msc.title like \'%' . $this->db->escape_like_str($keywords['text']) . '%\'
				)' : '1') . '
				)' : '') . '
		')->num_rows();
		$config['base_url']	= '/admin/mailing/page/';

		$this->pagination->initialize($config);
		$this->set('pages_line', $this->pagination->create_links());

		$this->set('mailing_log_position_number', $config['cur_page']);

		// get mailing log per page
		$this->set('mailing_log', $this->db->query('
			select
				me.*,
				msc.title as category
			from
				' . $this->db->dbprefix('mailing_events') . ' as me,
				' . $this->db->dbprefix('mailing_subscribers_categories') . ' as msc
			where
				me.id_resource=(select id_resource_default from ' . $this->db->dbprefix('settings') . ' limit 1) and
				me.subscribers_category=msc.id
				' . ($keywords ? 'and (
				' . (!empty($keywords['text']) ? '(
					me.datetime_event like \'%' . $this->db->escape_like_str($keywords['text']) . '%\' or
					me.subject like \'%' . $this->db->escape_like_str($keywords['text']) . '%\' or
					msc.title like \'%' . $this->db->escape_like_str($keywords['text']) . '%\'
				)' : '1') . '
				)' : '') . '
			order by
				me.datetime_event desc
			limit
				' . $config['cur_page'] . ', ' . $this->pagination->per_page . '
		'));

		// get mailing data
		$this->set('mailing', $this->db->query('
			select
				*
			from
				' . $this->db->dbprefix('mailing') . ' as m
			where
				id_resource=' . $this->db->escape($this->session->userdata('id_resource_current')) . '
		')->row());

		// get subscribers categories
		$this->set('subscribers_categories', $this->db->query('
			select
				*
			from
				' . $this->db->dbprefix('mailing_subscribers_categories') . '
			order by
				id asc
		'));

		// Selection of recipients
		switch ($this->get('mailing')->subscribers_category) {
			// All active (no blocked)
			default :
				$whereRecipients = 'u.flag_access=1';
				break;
		}

		// set data for js-function
		$this->set('accounts_num', $this->db->query('
			select
				count(1) as cnt
			from
				' . $this->db->dbprefix('users') . ' u
			where
				' . $whereRecipients . '
		')->row()->cnt);
		$this->set('access_key', 'cU8rekujeseT');
		$this->set('current_resource', $this->session->userdata('id_resource_current'));
	}
}

/* End of file admin/mailing.php */
/* Location: ./application/controllers/admin/mailing.php */