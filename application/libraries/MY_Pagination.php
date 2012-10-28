<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Pagination extends CI_Pagination {

	public $per_page	 		= 25;
	public $num_links			= 2;
	public $first_link   		= '<<';
	public $next_link			= '>';
	public $prev_link			= '<';
	public $last_link			= '>>';
	public $uri_segment			= 3;
	public $full_tag_open		= '<div class="pages_line">';
	public $full_tag_close		= '</div>';
	public $first_tag_open		= '';
	public $first_tag_close		= '&nbsp;';
	public $last_tag_open		= '';
	public $last_tag_close		= '&nbsp;';
	public $cur_tag_open		= '<a class="selected">';
	public $cur_tag_close		= '</a>&nbsp;';
	public $next_tag_open		= '';
	public $next_tag_close		= '&nbsp;';
	public $prev_tag_open		= '';
	public $prev_tag_close		= '&nbsp;';
	public $num_tag_open		= '';
	public $num_tag_close		= '&nbsp;';
	public $page_query_string	= FALSE;
	public $query_string_segment = 'per_page';

}

/* End of file MY_Pagination.php */
/* Location: ./application/libraries/MY_Pagination.php */