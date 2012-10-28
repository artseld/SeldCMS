<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Captcha_model extends CI_Model {

	// parameters
	protected $_vals = array(
		//'word'		=> 'Random word',
		'img_path'		=> './uploads/captcha/',
		'img_url'		=> '/uploads/captcha/',
		'font_path'		=> '/system/fonts/texb.ttf',
		'word_length'	=> 4,
		'img_width'		=> '100',
		'img_height'	=> '25',
		'expiration'	=> '3600'
	);
	protected $_auto_clear = FALSE;
	protected $_cap = FALSE;

	public function __construct()
	{
		parent::__construct();

		$this->load->helper('captcha');
	}

	// set parameters
	public function set_parameters()
	{
		if (is_array($override))
		{
			foreach ($override as $key => $value)
			{
				if (isset($this->_vals[$key])) $this->_vals[$key] = $value;
			}
		}
		$this->_auto_clear = $auto_clear;
	}

	// make capctha
	public function make()
	{
		$this->db->query('
			delete from
				' . $this->db->dbprefix('captcha') . '
			where
				captcha_time < ' . (time() - 300));

		$this->_cap = create_captcha($this->_vals);
        //
        $dati['image'] = $this->_cap['image'];
        // mette nel db
		$data = array(
			'captcha_id'	=> '',
            'captcha_time'	=> $this->_cap['time'],
            'ip_address'	=> $this->input->ip_address(),
            'word'			=> $this->_cap['word']
		);

        $query = $this->db->insert_string('captcha', $data);
        $this->db->query($query);

		if ($this->_auto_clear) $this->clear();

		return $dati['image'];
	}

	// check captcha
	public function check()
    {
		// Then see if a captcha exists:
		$row = $this->db->query('
			select
				count(*) as count
			from
				' . $this->db->dbprefix('captcha') . '
			where
				word = \'' . $this->db->escape_str($this->input->post('captcha')) . '\' and
				ip_address = ' . $this->db->escape($this->input->ip_address()) . ' and
				captcha_time > ' . (time() - 600) . '
		')->row();

		if ($row->count == 0)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	// clear captcha
	public function clear()
	{
		foreach(glob($this->_vals['img_path'] . '*.jpg') as $value)
		{
			if ($this->_vals['img_path'] . $this->_cap['time'] . '.jpg' != $value) unlink($value);
		}
	}
}

/* End of file captcha_model.php */
/* Location: ./application/models/captcha_model.php */