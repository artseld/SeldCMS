<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    // global data container
    protected $container = array();

    // backside message statuses
    const STATUS_DONE       = 'done';
    const STATUS_WARNING    = 'warning';
    const STATUS_ERROR      = 'error';

    // backside variables
    protected $_message = array(
        'status' => FALSE,
        'body' => ''
    );
    protected $_action_error = FALSE;
    protected $_formdata = FALSE;

    public function MY_Controller()
    {
        parent::__construct();

        // It is very important!
        @session_start();

        if (!$this->db->conn_id) die('Unable to connect to database server.');
        //$this->output->enable_profiler(TRUE);

      	// set language file
        $this->lang->load('project', 'english');
        $this->config->set_item('language', 'english');

        // load document model
        $this->load->model('document_model');

        // get global settings
        $query = $this->document_model->get_settings('array');
        foreach ($query as $k => $v) $this->set('global.' . $k, $v);

        // detect resource and set
        $query = $this->db->query('select id from ' . $this->db->dbprefix('resources') . ' where url=\'' . $this->db->escape_str($this->uri->segment(1)) . '\' limit 1');
        if ($query->num_rows() > 0) {
            $this->set(array(
                'uri.segment'       => 2,
                'resource.id'       => $query->row()->id,
                'resource.prefix'   => $this->uri->segment(1) . '/',
            ));
        }
        else
        {
            $this->set(array(
                'uri.segment'       => 1,
                'resource.id'       => $this->get('global.id_resource_default'),
                'resource.prefix'   => '',
            ));
        }

        // get resource settings
        $query = $this->document_model->get_resources_settings($this->get('resource.id'), 'array');
        foreach ($query as $k => $v) $this->set('resource.' . $k, $v);

        // load IP block model
        $this->load->model('ipblock_model');
        $this->ipblock_model->set_global($this->get('?global'));
        if ($this->ipblock_model->is_ip_blocked() &&
            $this->uri->segment($this->get('uri.segment')) != $this->get('resource.url_ipblocked')) {
            redirect(base_url() . $this->get('resource.prefix') . $this->get('resource.url_ipblocked'));
        } elseif (!$this->ipblock_model->is_ip_blocked() &&
            $this->uri->segment($this->get('uri.segment')) == $this->get('resource.url_ipblocked')) {
            redirect(base_url() . $this->get('resource.prefix'));
        }

        // Admin area pre-actions
        if ($this->uri->segment($this->get('uri.segment')) == 'admin')
        {
            // set default resource if not selected
            if (!$this->session->userdata('id_resource_current')) $this->session->set_userdata('id_resource_current', $this->get('global.id_resource_default'));

            // set message
            if (!$this->session->userdata('message')) $this->session->set_userdata('message', $this->_message);

            // set form data
            if (!$this->session->userdata('formdata')) $this->session->set_userdata('formdata', $this->_formdata);

            // prepare user data
            if ($this->session->userdata('logged_as'))
            {
                // unset resource data
                $this->remove('?resource');

                $this->load->model('admin/userdata_model');
                $this->set('userdata', $this->userdata_model->get_user());

                // go to auth controller if no privileges for adminpanel entering
                if (!$this->get('userdata')->flag_backside_access && $this->uri->segment($this->get('uri.segment') + 1) != 'auth') redirect('/admin/auth');

                $this->set('resources_list', $this->document_model->get_resources_list());
                $this->set('components_privileges', $this->userdata_model->get_components_privileges());
                $this->set('modules_privileges', $this->userdata_model->get_modules_privileges());

                // get nav modules
                $this->load->model('admin/mdata_model');
                $this->set('mdata', $this->mdata_model->get_modules());
            }
        }
    }

    /**
     * Load page template in frontside
     */
    protected function _frontside_load_tpl($url = '')
    {
        // set uri alias
        $this->set('uri.alias', $url);

        // get document
        $query = $this->document_model->get_document($this->get('resource.id'), $url, 'array');
        if (!$query) $query = $this->document_model->get_document($this->get('resource.id'), $this->get('resource.url_error_404'), 'array');
        if (!$query) { show_404('Document not found.'); exit; }
        foreach ($query as $k => $v) $this->set('document.' . $k, ($k == 'body' ? htmlspecialchars_decode($v) : $v));

        // get resource settings
        $query = $this->document_model->get_resources_settings($this->get('resource.id'), 'array');
        foreach ($query as $k => $v) $this->set('resource.' . $k, $v);

        // prepare user data
        if ($this->session->userdata('logged_as'))
        {
            $this->load->model('userdata_model');

            $query = $this->userdata_model->get_user();
            foreach ($query as $k => $v) $this->set('user.' . $k, $v);

            $query = $this->userdata_model->get_modules_privileges();
            if (!empty($query)) foreach ($query as $k => $v) $this->set('access.' . $k, $v);
        }

        // run parser
        $this->load->library('parser');

        // get containers
        $query = $this->document_model->get_containers_list($this->get('resource.id'), $this->get('document.id'), $this->get('document.id_module'));
        foreach ($query->result() as $row)
        {
            // container access decision
            if (
                $row->flag_free_access ||
                (!$row->flag_free_access && $this->session->userdata('logged_as') && $this->get('user.flag_frontside_access'))
            ) {
                if ($row->id_type == Document_model::TYPE_DYNAMIC) {
                    ob_start();
                    $this->set('containers.' . $row->alias, (eval($row->body) !== FALSE) ? ob_get_contents() : '{containers.' . $row->alias . '.ERROR}');
                    ob_clean();
                } else {
                    $this->set('containers.' . $row->alias, $this->parser->parse_string($row->body, $this->get(), TRUE));
                }
            }
            else
            {
                $this->set('containers.' . $row->alias, null);
            }
        }

        // enable caching if it need
        if ($this->get('global.flag_caching') && $this->get('document.flag_caching')) $this->output->cache($this->get('global.caching_duration'));

        // parse it!
        $this->parser->parse($this->get('document.template_alias') . '_tpl', $this->get());
    }

    /**
     * Load page template in backside
     * @param string $controller
     * @param string $method default = FALSE
     */
    protected function _backside_load_tpl($controller, $method = FALSE)
    {
        $this->set('lang', $this->lang);
        $this->set('message', $this->session->userdata('message'));
        $this->set('id_resource_current', $this->session->userdata('id_resource_current'));
        $this->load->view('admin/header_tpl', $this->get());
        $this->load->view('admin/nav_tpl', $this->get());
        $this->load->view('admin/pre_content_tpl', $this->get());
        $this->load->view('admin/' . strtolower($controller) . (($method) ? '_' . strtolower($method) : '') . '_tpl', $this->get());
        $this->load->view('admin/post_content_tpl', $this->get());
        $this->load->view('admin/footer_tpl', $this->get());
    }

    /**
     * Load no privileges page
     */
    protected function _backside_load_tpl_no_privileges()
    {
        $this->_backside_set_message('error', $this->lang->line('MESSAGE_UNSC_NO_PRIVILEGES'));

        $this->_backside_load_tpl('no_privileges');

        // delete temporary session data
        $this->_backside_delete_tmp_session_data();
    }

    /**
     * Set template message
     * @param string $status
     * @param string $body
     */
    protected function _backside_set_message($status, $body)
    {
        $this->_message['status']   = $status;
        $this->_message['body']     = $body;

        // create message
        $this->session->set_userdata('message', $this->_message);
    }

    /**
     * Delete temporary session data
     */
    protected function _backside_delete_tmp_session_data()
    {
        // delete message
        $this->session->unset_userdata('message');

        // delete form data
        $this->session->unset_userdata('formdata');
    }

    /**
     * Set element(s) to container
     * @param string|array $key key name or array (key => value)
     * @param mixed $value
     * @return MY_Controller
     */
    public final function set($key, $value = null)
    {
        if (is_array($key)) {
            $this->container = array_merge($this->container, $key);
        } else {
            $this->container[$key] = $value;
        }
        return $this;
    }

    /**
     * Get element(s) from container
     * @param string $key string (can use `?` in the beginning of string for search)
     * @return mixed|array
     */
    public final function get($key = null)
    {
        if ( null !== $key ) {
            if ( strpos($key, '?') === 0 ) {
                $search = ltrim($key, '?');
                $result = array();
                foreach ($this->container as $k => $v) {
                    if (false !== strpos($k, $search)) $result[$k] = $v;
                }
                return $result;
            }
            return (isset($this->container[$key])) ? $this->container[$key] : null;
        }
        return $this->container;
    }

    /**
     * Remove element(s) from container
     * @param string $key string (can use `?` in the beginning of string for search)
     * @return MY_Controller
     */
    public final function remove($key = null)
    {
        if ( null !== $key ) {
            if ( strpos($key, '?') === 0 ) {
                $search = ltrim($key, '?');
                foreach ($this->container as $k => $v) {
                    if (false !== strpos($k, $search)) unset($this->container[$k]);
                }
            } elseif (isset($this->container[$key])) {
                unset($this->container[$key]);
            }
        } else {
            $this->container = array();
        }
        return $this;
    }

}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */