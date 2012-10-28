<?php

class Tree_model extends CI_Model {

	protected $_tree_text = FALSE;
	protected $_tree_text_list = FALSE;
	protected $_tree_text_prefix = '&nbsp; ';
	protected $_tree_text_list_edit = FALSE;
	protected $_id_document_edit = 0;
	protected $_flag_make_page_edit = TRUE;
	protected $_privileges;

	public function __construct()
	{
		parent::__construct();
	}

	// make tree method
	public function make_tree($privileges, $id_document_edit = 0)
	{
		// set user data
		$this->_privileges = $privileges;

		// set id document for edit page
		$this->_id_document_edit = $id_document_edit;

		// open tree tag
		$this->_tree_text = '<ul class="inner_list">';

		// prepare tree lists
		$this->_tree_text_list = array();
		$this->_tree_text_list[] = array(
			'id' => 0,
			'title' => '&nbsp; '
		);
		$this->_tree_text_list_edit = array();
		$this->_tree_text_list_edit[] = array(
			'id' => 0,
			'title' => '&nbsp; '
		);

		// start get tree elements
		$this->_make_subtree(0);

		// close tree tag
		$this->_tree_text .= '<li class="hidden">&nbsp;</li></ul>';
	}

	// get tree
	public function get_tree()
	{
		// return tree
		return $this->_tree_text;
	}

	// get tree as list
	public function get_tree_list()
	{
		// return tree list
		return $this->_tree_text_list;
	}

	// get tree as list for edit action
	public function get_tree_list_edit()
	{
		// return tree list fro edit action
		return $this->_tree_text_list_edit;
	}

	// make subtree
	protected function _make_subtree($id_parent = 0, $tree_text_prefix = '')
	{
		// get level pages list
		$pages = $this->db->query('
			select
				*,
				(select
					count(1)
				from
					' . $this->db->dbprefix('structure') . '
				where
					id_resource=' . $this->session->userdata('id_resource_current') . ' and
					id_parent=s.id) as children_number
			from
				' . $this->db->dbprefix('structure') . ' as s
			where
				s.id_resource=' . $this->session->userdata('id_resource_current') . ' and
				s.id_parent=' . $id_parent . '
			order by
				s.flag_is_mainpage desc,
				s.priority asc,
				s.id asc
		');

		// add page
		foreach ($pages->result() as $row)
		{
			$this->_tree_text .= '<li>';
			$this->_tree_text_list[] = array(
				'id' => $row->id,
				'title' => $tree_text_prefix . $row->title_page
			);
			// if is edited document switch flag to FALSE
			if ($this->_id_document_edit == $row->id)
			{
				$this->_flag_make_page_edit = FALSE;
			}
			if ($this->_flag_make_page_edit) $this->_tree_text_list_edit[] = array(
				'id' => $row->id,
				'title' => $tree_text_prefix . $row->title_page
			);

			$this->_make_page($row);

			if ($row->children_number)
			{
				$this->_tree_text .= '<ul>';
				$this->_make_subtree($row->id, $tree_text_prefix . $this->_tree_text_prefix);
				$this->_tree_text .= '</ul>';
			}

			// if is edited document switch flag to TRUE
			if ($this->_id_document_edit == $row->id)
			{
				$this->_flag_make_page_edit = TRUE;
			}

			$this->_tree_text .= '</li>';
		}
	}

	// make page for results
	protected function _make_page($page)
	{
		$this->_tree_text .= ($page->children_number ? '<img src="/img/admin/structure_folder.png" alt="[folder]" />' : '<img src="/img/admin/structure_file.png" alt="[document]" />') . ($this->_privileges->edit ? '<a href="/admin/structure/edit_document/' . $page->id . '#edit_document" title="' . $this->lang->line('ACT_EDIT') . '" class="edit_row_link">' : '<a>') . $page->title_page . '</a><sup title="ID: ' . $page->id . '">[' . $page->id . ']</sup>' . ($this->_privileges->delete ? ' <a href="/admin/structure/delete_document/' . $page->id . '" class="delete_tree_link" title="' . $this->lang->line('ACT_DELETE') . '">&nbsp;</a>' : '');
	}
}

/* End of file tree_model.php */
/* Location: ./application/models/admin/tree_model.php */