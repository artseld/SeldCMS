<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class MY_Form_validation extends CI_Form_validation {

	public function __construct($rules = array()) {

		parent::__construct($rules);

	}

	// matches_pattern()
	// Ensures a string matches a basic pattern
	// # numeric, ? alphabetical, ~ any character
	public function matches_pattern($str, $pattern)
	{
		$characters = array('#', '?', '~');

		$regex_characters = array('[0-9]', '[a-zA-Z]', '.');

		$pattern = str_replace($characters, $regex_characters, $pattern);

		if (preg_match('/^' . $pattern . '$/', $str)) return TRUE;
		return FALSE;
	}

	// is trigger (0 or 1)
	public function trigger($str)
	{
		if ($str == 1 || $str == 0) return TRUE;

		return FALSE;
	}

	/**
     * Minimum Value
     *
     * @access    public
     * @param     value entered by user
     * @param     min number value
     * @return    bool
     */
    public function min_value($str, $value)
	{
		if (preg_match("/^[-+]?[0-9]*\.?[0-9]+$/", $str))
		{
			return ($str < $value) ? FALSE : TRUE;
		}

		return FALSE;
	}

    /**
     * Maximum Value
     *
     * @access    public
     * @param     value entered by user
     * @param     max number value
     * @return    bool
     */
	public function max_value($str, $value)
	{
		if (preg_match("/^[-+]?[0-9]*\.?[0-9]+$/", $str))
		{
			return ($str > $value) ? FALSE : TRUE;
		}

		return FALSE;
    }

	/**
     * Between Decision
     *
     * @access    public
     * @param     values entered by user
     * @param     min & max number values
     * @return    bool
     */
	public function between($number, $range_str)
	{
		if (preg_match("/^[-+]?[0-9]*\.?[0-9]+$/", $number))
		{
			$range = explode('|', $range_str);
			return (($range[0] > $number) && ($number < $range[1])) ? FALSE : TRUE;
		}

		return FALSE;
	}

	// is english
	public function is_english($str)
	{
		return ( ! preg_match("/^([-\sa-zA-Z(),.0-9_\/-])+$/i", $str)) ? FALSE : TRUE;
	}

	// is phone
	public function is_phone($str)
	{
		return ( ! preg_match("/^([-+\s()0-9-])+$/i", $str)) ? FALSE : TRUE;
	}

    // is url
    function is_url($str)
    {
        return (bool) filter_var($str, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED);
    }
}

/* End of file MY_Form_validation.php */
/* Location: ./application/libraries/MY_Form_validation.php */