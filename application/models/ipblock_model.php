<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ipblock_model extends CI_Model {

    private $_ip_record = null;
    private $_cfg = array(
        'global.max_failed_attempts'    => 5,
        'global.blocking_duration'      => 30,
        'global.clear_interval'         => 15
    );

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Set global data
     * @param array $cfg get by reference
     */
    public function set_global(array & $cfg) {
        $this->_cfg = $cfg;
    }

    /**
     * Block IP action
     * @param string $ip ip-address in human readable format
     * @param boolean $rewrite re-write new record anyway
     * @return boolean
     */
    public function block_ip($ip = null, $rewrite = false) {
        if (!$ip) $ip = $_SERVER['REMOTE_ADDR'];
        if (!$this->is_ip_blocked($ip, $rewrite)) {
            $record = $this->_get_ip_record($ip, $rewrite);
            if ($record) {
                $this->db->query('
                    update ' . $this->db->dbprefix('ip_blacklist') . ' set last_attempt = now(), ' .
                    ($record->failed_attempts >= $this->_cfg['global.max_failed_attempts'] ?
                        'datetime_until = now() + interval ' . $this->_cfg['global.blocking_duration'] . ' minute,
                            failed_attempts = 0 ' :
                        'datetime_until = null, ' .
                        ((strtotime($record->last_attempt) + $this->_cfg['global.clear_interval']*60) > time() ?
                            'failed_attempts = failed_attempts+1 ' :
                            'failed_attempts = 1 ')
                     ) . 'where ip = \'' . $this->_aton($ip) . '\'
                ');
            } else {
                $this->db->query('
                    insert into ' . $this->db->dbprefix('ip_blacklist') . '
                    values (\'' . $this->_aton($ip) . '\', now(), 1, null, 0)
                ');
            }
            if ($this->db->_error_number()) return false;
        }
        return true;
    }

    /**
     * Check if IP is blocked
     * @param string $ip ip-address in human readable format
     * @param boolean $rewrite re-write new record anyway
     * @return boolean
     */
    public function is_ip_blocked($ip = null, $rewrite = false) {
        if (!$ip) $ip = $_SERVER['REMOTE_ADDR'];
        $record = $this->_get_ip_record($ip, $rewrite);
        if ($record && ($record->flag_permanent ||
            strtotime($record->datetime_until) > time())) {
            return true;
        }
        return false;
    }

    /**
     * Convert IP address to Number
     * @param string $ip
     * @return string
     */
    private function _aton($ip) {
        //if (function_exists('inet_pton')) {
        //    return inet_pton($ip);
        //}
        return ip2long($ip);
    }

    /**
     * Get IP record
     * @param string $ip ip-address in human readable format
     * @param boolean $rewrite re-write new record anyway
     * @return mixed false|DB row
     */
    private function _get_ip_record($ip = null, $rewrite = false) {
        if (null === $this->_ip_record || $rewrite) {
            $this->_ip_record = false;
            if (!$ip) $ip = $_SERVER['REMOTE_ADDR'];
            $record = $this->db->query('
                select * from ' . $this->db->dbprefix('ip_blacklist') . '
                where ip = \'' . $this->_aton($ip) . '\'
            ');
            if ($record->num_rows()) $this->_ip_record = $record->row();
        }
        return $this->_ip_record;
    }

}

/* End of file ipblock_model.php */
/* Location: ./application/models/ipblock_model.php */