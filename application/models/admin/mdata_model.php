<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mdata_model extends CI_Model {

	protected $_modules_data;

	public function __construct()
	{
		parent::__construct();
	}

	public function get_modules()
	{
		// get modules
		$this->_modules_data = $this->db->query('
			select
				m.id as id,
				m.alias as alias,
				m.title as title,
				m.comments as comments,
				mc.alias as calias,
				mc.title as ctitle,
				mc.comments as ccomments
			from
				' . $this->db->dbprefix('modules') . ' as m
			left join
				' . $this->db->dbprefix('modules_controllers') . ' as mc
			on
				m.id=mc.id_module
			order by
				m.title asc,
				m.alias asc,
				mc.title asc,
				mc.alias asc
		');

		// return modules data to controller
		return $this->_modules_data;
	}
}

/* End of file mdata_model.php */
/* Location: ./application/models/admin/mdata_model.php */