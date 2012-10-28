<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Document_model extends CI_Model {

    const TYPE_DYNAMIC  = 1;
    const TYPE_STATIC   = 2;

    // return containers
    protected $_settings = FALSE;
    protected $_resources = FALSE;
    protected $_resources_settings = FALSE;
    protected $_document = FALSE;
    protected $_tree = FALSE;
    protected $_containers = FALSE;

    // tree helpvars
    protected $_tree_resource;
    protected $_tree_resource_prefix;
    protected $_tree_disable_mainpage;
    protected $_tree_field_to_show;

    public function __construct()
    {
        parent::__construct();
    }

    // get global settings
    public function get_settings($type = 'object')
    {
        $this->_settings = $this->db->query('
            select
                *
            from
                ' . $this->db->dbprefix('settings') . ' as s
            limit 1
        ');

        if ($type == 'object') return $this->_settings->row();
        else return $this->_settings->row_array();
    }

    // get resources list
    public function get_resources_list()
    {
        $this->_resources = $this->db->query('
            select
                r.id as id,
                r.url as url,
                r.title as title,
                r.comments as comments,
                if (
                    (select
                        id_resource_default
                    from
                        ' . $this->db->dbprefix('settings') . '
                    limit 1) = r.id,
                    1,
                    0
                ) as flag_is_default
            from
                ' . $this->db->dbprefix('resources') . ' as r
            order by
                r.title asc
        ');

        return $this->_resources;
    }

    // get resources settings
    public function get_resources_settings(& $id_resource, $type = 'object')
    {
        $this->_resources_settings = $this->db->query('
            select
                rs.*
            from
                ' . $this->db->dbprefix('resources_settings') . ' as rs
            where
                rs.id_resource=' . $this->db->escape($id_resource) . '
        ');

        if ($type == 'object') return $this->_resources_settings->row();
        else return $this->_resources_settings->row_array();
    }

    // get document
    public function get_document(
        & $id_resource,
        $url = '',
        $type = 'object'
    ) {
        // set page selector attribute
        $where_is_page = (
            !empty($url) ?
            's.url=' . $this->db->escape($url) :
            's.flag_is_mainpage=1'
        ) .
        (
            ($this->session->userdata('logged_as')) ?
            ' and (((
                select
                    ug.flag_frontside_access
                from
                    ' . $this->db->dbprefix('users') . ' as u,
                    ' . $this->db->dbprefix('users_groups') . ' as ug
                where
                    u.id=' . $this->db->escape($this->session->userdata('logged_as')) . ' and
                    u.id_group=ug.id
                ) = 1 and if (
                    s.id_module = 0,
                    1,
                    (select
                        ugfp.flag_access
                    from
                        ' . $this->db->dbprefix('users') . ' as u,
                        ' . $this->db->dbprefix('users_groups_frontside_privileges') . ' as ugfp,
                        ' . $this->db->dbprefix('modules') . ' as m
                    where
                        u.id=' . $this->db->escape($this->session->userdata('logged_as')) . ' and
                        u.id_group=ugfp.id_group and
                        m.id=s.id_module and
                        m.id=ugfp.id_module
                    limit 1)
                ) = 1 and s.flag_free_access=0) or s.flag_free_access=1)' :
            ' and s.flag_free_access=1'
        );

        $this->_document = $this->db->query('
            select
                s.*,
                t.alias as template_alias
            from
                ' . $this->db->dbprefix('structure') . ' as s,
                ' . $this->db->dbprefix('templates') . ' as t
            where
                s.id_resource=' . $this->db->escape($id_resource) . ' and
                s.flag_publication=1 and
                t.id=s.id_template and
                ' . $where_is_page . '
            limit 1
        ');

        if ($type == 'object') return $this->_document->row();
        else return $this->_document->row_array();
    }

    // get containers list
    public function get_containers_list(& $id_resource, & $id_document, & $id_module)
    {
        $this->_containers = $this->db->query('
            select
                c.*
            from
                ' . $this->db->dbprefix('containers') . ' as c,
                ' . $this->db->dbprefix('containers_groups') . ' as cg
            where
                c.id_group=cg.id and
                c.id_resource=' . $this->db->escape($id_resource) . ' and
                (c.id_document=0 or c.id_document=' . $this->db->escape($id_document) . ') and
                (cg.id_module=' . $this->db->escape($id_module) . ' or cg.id_module is NULL)
            order by
                c.id_type asc,
                cg.priority asc,
                cg.title asc,
                c.priority asc,
                c.title asc
        ');
        // DYNAMIC first, STATIC last

        return $this->_containers;
    }

    // get breadcrumbs [5 sub-levels max]
    public function get_breadcrumbs(
        & $id_resource,
        & $id_document,
        $resource_url_prefix = '',
        $delimiter_breadcrumbs = '/',
        $flag_show_current = TRUE,
        $field_to_show = 'title_menu',
        $breadcrumbs_identifier = 'class="breadcrumbs"'
    ) {
        // check flag show current
        $flag_show_current = intval($flag_show_current);

        // check field to show
        $field_to_show = strval($field_to_show);

        // check breadcrumbs identifier
        $breadcrumbs_identifier = strval($breadcrumbs_identifier);

        // select parents
        $query = $this->db->query('
            select innerq.* from (
            select
                if (
                    t1.flag_is_mainpage=1,
                    \'\',
                    t1.url
                ) as url,
                t1.flag_is_mainpage as flag_is_mainpage,
                t1.' . $field_to_show . ' as ' . $field_to_show . ',
                t1.id as lev1,
                t2.id as lev2,
                t3.id as lev3,
                t4.id as lev4,
                t5.id as lev5
            from
                ' . $this->db->dbprefix('structure') . ' as t1
            left join
                ' . $this->db->dbprefix('structure') . ' as t2 on t2.id_parent=t1.id
            left join
                ' . $this->db->dbprefix('structure') . ' as t3 on t3.id_parent=t2.id
            left join
                ' . $this->db->dbprefix('structure') . ' as t4 on t4.id_parent=t3.id
            left join
                ' . $this->db->dbprefix('structure') . ' as t5 on t5.id_parent=t4.id
            where
                (t5.id=' . $this->db->escape($id_document) . ' or
                t4.id=' . $this->db->escape($id_document) . ' or
                t3.id=' . $this->db->escape($id_document) . ' or
                t2.id=' . $this->db->escape($id_document) . ' or
                t1.id=' . $this->db->escape($id_document) . ' or
                t1.flag_is_mainpage=1) and
                t1.id_resource=' . $this->db->escape($id_resource) . '
            ) as innerq
            group by
                innerq.lev1
            order by
                innerq.flag_is_mainpage desc,
                innerq.lev5 desc,
                innerq.lev4 desc,
                innerq.lev3 desc,
                innerq.lev2 desc,
                innerq.lev1 desc
        ');

        // make breadcrumbs
        $num_rows = $query->num_rows();	$i = 1;
        $str = '<div ' . $breadcrumbs_identifier . '>';
        foreach ($query->result() as $row)
        {
            if ($i == $num_rows && !$flag_show_current) break;
            if ($i == $num_rows)
            {
                $str .= '<strong>' . $row->{$field_to_show} . '</strong> ';
            }
            else
            {
                $str .= '<a href="/' . $resource_url_prefix . $row->url . '">' . $row->{$field_to_show} . '</a> ' . $delimiter_breadcrumbs . ' ';
            }
            $i++;
        }
        $str .= '</div>';

        // return result string
        return $str;
    }

    // get tree
    public function get_tree(
        & $id_resource,
        $id_document_root = 0,
        $resource_url_prefix = '',
        $flag_disable_mainpage = TRUE,
        $field_to_show = 'title_menu',
        $tree_identifier = 'class="tree"'
    ) {
        // set helpvars
        $this->_tree_resource = intval($id_resource);
        $this->_tree_resource_prefix = strval($resource_url_prefix);
        $this->_tree_disable_mainpage = intval($flag_disable_mainpage);
        $this->_tree_field_to_show = strval($field_to_show);

        // open tree tag
        $this->_tree = '<ul ' . $tree_identifier . '>';

        // start get tree elements
        $this->_get_subtree($id_document_root);

        // close tree tag
        $this->_tree .= '<li class="hidden" style="display: none;">&nbsp;</li></ul>';

        // return result string
        return $this->_tree;
    }

    // get subtree : protected method
    protected function _get_subtree(& $id_parent = 0)
    {
        // get level pages list
        $pages = $this->db->query('
            select
                s.*,
                if (
                    s.flag_is_mainpage=1,
                    \'\',
                    s.url
                ) as url,
                s.' . $this->_tree_field_to_show . ' as ' . $this->_tree_field_to_show . ',
                (select
                    count(1)
                from
                    ' . $this->db->dbprefix('structure') . '
                where
                    id_resource=' . $this->_tree_resource . ' and
                    id_parent=s.id)
                as children_number
                ' . ($this->session->userdata('logged_as') ? ',(
                    select
                        ug.flag_frontside_access
                    from
                        ' . $this->db->dbprefix('users') . ' as u,
                        ' . $this->db->dbprefix('users_groups') . ' as ug
                    where
                        u.id=' . $this->db->escape($this->session->userdata('logged_as')) . ' and
                        u.id_group=ug.id
                    limit 1
                ) as frontside_access' : ',\'\' as frontside_access') . '
                ' . ($this->session->userdata('logged_as') ? ',if (
                    s.id_module = 0,
                    1,
                    (select
                        ugfp.flag_access
                    from
                        ' . $this->db->dbprefix('users') . ' as u,
                        ' . $this->db->dbprefix('users_groups_frontside_privileges') . ' as ugfp,
                        ' . $this->db->dbprefix('modules') . ' as m
                    where
                        u.id=' . $this->db->escape($this->session->userdata('logged_as')) . ' and
                        u.id_group=ugfp.id_group and
                        m.id=s.id_module and
                        m.id=ugfp.id_module
                    limit 1)
                ) as module_access' : ',\'\' as module_access') . '
            from
                ' . $this->db->dbprefix('structure') . ' as s
            where
                s.id_resource=' . $this->_tree_resource . ' and
                s.id_parent=' . $this->db->escape($id_parent) . '
                ' . ($this->_tree_disable_mainpage ? 'and s.flag_is_mainpage=0' : '') . '
            order by
                s.flag_is_mainpage desc,
                s.priority asc,
                s.id asc
        ');

        // add page
        foreach ($pages->result() as $row)
        {
            if (
                $row->flag_publication &&
                $row->flag_display_in_menu &&
                ($row->flag_free_access || (!$row->flag_free_access && $this->session->userdata('logged_as') && $row->frontside_access && $row->module_access))
            ) {
                $this->_tree .= '<li><a href="/' . $this->_tree_resource_prefix . $row->url . '">' . $row->{$this->_tree_field_to_show} . '</a>';

                if ($row->children_number)
                {
                    $this->_tree .= '<ul>';
                    $this->_get_subtree($row->id);
                    $this->_tree .= '</ul>';
                }

                $this->_tree .= '</li>';
            }
        }
    }
}

/* End of file document_model.php */
/* Location: ./application/models/document_model.php */