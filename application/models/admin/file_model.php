<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class File_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('file');
    }

    // rewrite resources file
    public function rewrite_resources_file()
    {
        $str_to_write = array();

        $resources = $this->db->query('
            select
                url
            from
                ' . $this->db->dbprefix('resources') . '
        ');

        $i = 0;

        foreach ($resources->result() as $row)
        {
            $str_to_write[$i++] = $row->url;
        }
		
        write_file(APPPATH . 'config/resources.txt', implode('|', $str_to_write), 'w+');
    }

    // rewrite template file
    public function rewrite_template_file($template_alias, $flag_delete = FALSE)
    {
        if ($flag_delete)
        {
            if (file_exists(APPPATH . 'views/' . $template_alias . '_tpl.php')) unlink(APPPATH . 'views/' . $template_alias . '_tpl.php');
        }
        else
        {
            // replace actions
            $str_to_write = str_replace('<?', '&lt;?', $this->input->post('body'));
            $str_to_write = str_replace('?>', '?&gt;', $str_to_write);

            write_file(APPPATH . 'views/' . $template_alias . '_tpl.php', $str_to_write, 'w+');
        }
    }
}

/* End of file file_model.php */
/* Location: ./application/models/admin/file_model.php */