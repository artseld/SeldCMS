<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class MY_Input extends CI_Input {

	function  __construct()
	{
		parent::__construct();
    }
 
    // --------------------------------------------------------------------
 
    /**
	* Clean Input Data
    *
	* This is a helper function. It escapes data and
    * standardizes newline characters to \n
	*
    * @access   private
	* @param    string
    * @return   string
	*/
	function _clean_input_data($str)
	{
		if (is_array($str))
        {
       	    $new_array = array();
            foreach ($str as $key => $val)
   	   	    {
   	            $new_array[$this->_clean_input_keys($key)] = $this->_clean_input_data($val);
            }
           	return $new_array;
       	}

   	    // We strip slashes if magic quotes is on to keep things consistent
        if (function_exists('get_magic_quotes_gpc') AND get_magic_quotes_gpc())
       	{
   	        $str = stripslashes($str);
        }

       	// Clean UTF-8 if supported
   	    if (UTF8_ENABLED === TRUE)
        {
       	    $str = $this->uni->clean_string($str);
   	    }

        // Should we filter the input data?
   	    if ($this->_enable_xss === TRUE)
        {
       	    $str = $this->security->xss_clean($str);
   	    }

		// Standardize newlines if needed
		if ($this->_standardize_newlines == TRUE)
		{
			if (strpos($str, "\r") !== FALSE)
			{
				//$str = str_replace(array( "\r\n", "\r"), PHP_EOL, $str);
				$str = preg_replace('/(?:\r\n|[\r\n])/', PHP_EOL, $str);
			}
		}

       	return $str;
   	}

    function post($index = '', $xss_clean = FALSE)
    {
        // this will be true if post() is called without arguments 
        if($index === '')
        {
            return ($_SERVER['REQUEST_METHOD'] === 'POST');
        }
        
        // otherwise do as normally
        return parent::post($index, $xss_clean);
    }
}  
 
/* End of file MY_Input.php */
/* Location: ./application/core/MY_Input.php */