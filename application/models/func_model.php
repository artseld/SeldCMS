<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Func_model extends CI_Model {

	protected $_code_length = 12;
	protected $_code_str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

	public function __construct()
	{
		parent::__construct();
	}

	// get unique code
	public function get_code($length = 0)
	{
		if (!$length) $length = $this->_code_length;

		$str = '';

		if (strlen($this->_code_str) > 0)
		{
			for($i = 1; $i <= $length; $i++)
			{
				mt_srand((double)microtime() * 1000000);
				$num = mt_rand(0, strlen($this->_code_str) - 1);
				$str .= $this->_code_str[$num];
			}
		}

		// return string to controller
		return $str;
	}

	// get file extension
	public function get_extension($filename)
	{
		$x = explode('.', $filename);
        return '.' . end($x);
	}

	// transliterate string
	public function transliterate_str($str, $lang = 'ru', $spec_symbol = FALSE)
	{
		// convert language
		switch($lang)
		{
			case 'ru':
				$data = array(
					'Й' => 'I', 'й' => 'i', 'Ц' => 'C', 'ц' => 'c',
					'У' => 'U', 'у' => 'u', 'К' => 'K', 'к' => 'k',
					'Е' => 'E', 'е' => 'e', 'Н' => 'N', 'н' => 'n',
					'Г' => 'G', 'г' => 'g', 'Ш' => 'Sh', 'ш' => 'sh',
					'Щ' => 'Sch', 'щ' => 'sch', 'З' => 'Z', 'з' => 'z',
					'Х' => 'H', 'х' => 'h', 'Ъ' => '\'', 'ъ' => '\'',
					'Ф' => 'F', 'ф' => 'f', 'Ы' => 'Y', 'ы' => 'y',
					'В' => 'V', 'в' => 'v', 'А' => 'A', 'а' => 'a',
					'П' => 'P', 'п' => 'p', 'Р' => 'R', 'р' => 'r',
					'О' => 'O', 'о' => 'o', 'Л' => 'L', 'л' => 'l',
					'Д' => 'D', 'д' => 'd', 'Ж' => 'Zh', 'ж' => 'zh',
					'Э' => 'E', 'э' => 'e', 'Я' => 'Ya', 'я' => 'ya',
					'Ч' => 'Ch', 'ч' => 'ch', 'С' => 'S', 'с' => 's',
					'М' => 'M', 'м' => 'm', 'И' => 'I', 'и' => 'i',
					'Т' => 'T', 'т' => 't', 'Ь' => '\'', 'ь' => '\'',
					'Б' => 'B', 'б' => 'b', 'Ю' => 'Yu', 'ю' => 'yu',
					'Ё' => 'Yo', 'ё' => 'yo'
				);
				break;

			default:
				$data = array();
				break;
		}
		// convert special symbols
		if ($spec_symbol) $data = array_merge($data, array(
			'~' => $spec_symbol, '!' => $spec_symbol, '@' => $spec_symbol,
			'#' => $spec_symbol, '$' => $spec_symbol, '%' => $spec_symbol,
			'^' => $spec_symbol, '&' => $spec_symbol, '*' => $spec_symbol,
			'(' => $spec_symbol, ')' => $spec_symbol, '-' => $spec_symbol,
			'_' => $spec_symbol, '+' => $spec_symbol, '=' => $spec_symbol,
			'|' => $spec_symbol, '/' => $spec_symbol, '[' => $spec_symbol,
			']' => $spec_symbol, ':' => $spec_symbol, ';' => $spec_symbol,
			',' => $spec_symbol, '.' => $spec_symbol, '<' => $spec_symbol,
			'>' => $spec_symbol, '?' => $spec_symbol, '\\' => $spec_symbol,
			'{' => $spec_symbol, '}' => $spec_symbol, '`' => $spec_symbol,
			'№' => $spec_symbol, '"' => $spec_symbol, '\'' => $spec_symbol,
			' ' => $spec_symbol
		));
		// return result string
		return strtr($str, $data);
	}
}

/* End of file func_model.php */
/* Location: ./application/models/func_model.php */