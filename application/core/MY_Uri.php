<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class MY_Uri extends CI_Uri {

    function _filter_uri($str)
    {
        if ($str != '' AND $this->config->item('permitted_uri_chars') != '')
        {
            if ( ! preg_match("|^[".preg_quote($this->config->item('permitted_uri_chars'))."]+$|ui", $str))
            {
				show_error('The URI you submitted has disallowed characters.');
            }
        }
 
        return $str;
    }
} 

/* End of file MY_Uri.php */
/* Location: ./application/core/MY_Uri.php */