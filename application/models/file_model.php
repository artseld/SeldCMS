<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class File_model extends CI_Model {

    const UPLOAD_AVATAR     = 'avatar';
    const UPLOAD_IMAGE      = 'image';
    const UPLOAD_FILE       = 'file';

    const MINIMIZE_MIN      = 'min';
    const MINIMIZE_MAX      = 'max';
    const MINIMIZE_CROP     = 'crop';

    const EXT_JPG           = 'jpg';
    const EXT_PNG           = 'png';
    const EXT_GIF           = 'gif';

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('file');
    }

    public function do_minimize($params = array(), $quality = -1)
    {
        // check parameters
        if (!is_array($params) || !isset($config['width']) || !isset($config['height']) ||
            !isset($config['mimetype']) || !isset($config['filepath']) || !isset($config['type']) ||
            !in_array($params['type'], array(self::MINIMIZE_MIN, self::MINIMIZE_MAX, self::MINIMIZE_CROP))
        ) {
            show_error('Incorrect parameters', 500);
        }

        // get source size
        $size = getimagesize($params['filepath']);
        $width = $size[0];
        $height = $size[1];

        // create source
        $ext = $this->get_image_extension($params['mimetype']);
        switch ($ext) {
            case self::EXT_JPG: $src = imagecreatefromjpeg($params['filepath']); break;
            case self::EXT_PNG: $src = imagecreatefrompng($params['filepath']); break;
            case self::EXT_GIF: $src = imagecreatefromgif($params['filepath']); break;
        }

        // resize image
        $x = 0; $y = 0;
        switch ($params['type']) {
            case self::MINIMIZE_MIN: case self::MINIMIZE_MAX:
                $x_ratio = $params['width'] / $width;
                $y_ratio = $params['height'] / $height;
                if (($width <= $params['width']) && ($height <= $params['height'])) {
                    $tn_width = $width;
                    $tn_height = $height;
                } else {
                    $tn_width = ceil($y_ratio*$width);
                    $tn_height = $params['height'];
                }
        }
        switch ($params['type']) {
            case self::MINIMIZE_MIN:
                if (($x_ratio*$height) > $params['height']) {
                    $tn_height = ceil($x_ratio*$height);
                    $tn_width = $params['width'];
                }
                break;
            case self::MINIMIZE_MAX:
                if (($x_ratio*$height) < $params['height']) {
                    $tn_height = ceil($x_ratio*$height);
                    $tn_width = $params['width'];
		}
                break;
            case self::MINIMIZE_CROP:
		$tn_width  = $params['width'];
		$tn_height = $params['height'];
		$Ww = $width / $tn_width;
		$Hh = $height / $tn_height;
		if ( $Ww > $Hh ) {
                    $width = floor($tn_width * $Hh);
                    $X = ($size[0] - $width) >> 1;
		} else {
                    $height = floor($tn_height * $Ww);
                    $Y = ($size[1] - $height) >> 1;
		}
                break;
        }
        $dst = ImageCreateTrueColor($tn_width, $tn_height);
        ImageCopyResampled($dst, $src, 0, 0, $x, $y, $tn_width, $tn_height, $width, $height);

        // save file
        switch ($ext) {
            case self::EXT_JPG: imagejpeg($dst, $params['filepath'], $quality); break;
            case self::EXT_PNG: imagepng($dst, $params['filepath'], $quality); break;
            case self::EXT_GIF: imagegif($dst, $params['filepath']); break;
        }

        // clear image objects
        imagedestroy($src);
        imagedestroy($dst);
    }

    // get image extension by mimetype
    protected function get_image_extension($mimetype)
    {
	switch ($mimetype) {
            case 'jpg': case 'jpeg': case 'pjpeg': case 'image/jpg': case 'image/jpeg': case 'image/pjpeg':
                return self::EXT_JPG;
            case 'png': case 'x-png': case 'image/png': case 'image/x-png':
                return self::EXT_PNG;
            case 'gif': case 'image/gif':
                return self::EXT_GIF;
	}
        return null;
    }

    // upload file to server
    public function do_upload($type, $field_name, $encrypt_name = false, $use_subfolders = true)
    {
        $config = $this->get_upload_config($type);
        if ($encrypt_name) $config['encrypt_name'] = true;
        if ($use_subfolders) {
            $prefix_str = sha1(time());
            $config['upload_prefix'] = substr($prefix_str, 0, 2) . '/' . substr($prefix_str, 2, 2) . '/';
            $config['upload_path'] .= $config['upload_prefix'];
            if (!mkdir($config['upload_path'], 0, true)) {
                show_error('Unable to create subfolders before file uploading', 500);
            }
        }

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($field_name)) $status = false;
        else $status = true;

        return array( 'status' => $status, 'errors' => $this->upload->display_errors(), 'config' => $config, 'data' => $this->upload->data() );
    }

    // get upload config for selected type
    protected function get_upload_config($type)
    {
        if (!in_array($type, array(self::UPLOAD_AVATAR, self::UPLOAD_IMAGE, self::UPLOAD_FILE))) {
            show_error('Incorrect upload type selected', 500);
        }
        $config = array(
            'upload_path'   => APPPATH . '../uploads/users/',
            'allowed_types' => 'gif|jpg|png|doc|pdf|txt|zip|tar',
            'max_size'      => '1024',
            'owerwrite'     => true,
        );

        switch ($type) {
            case self::UPLOAD_AVATAR:
                $config['allowed_types']    = 'gif|jpg|png';
                $config['max_size']         = '100';
                $config['max_width']        = '110';
                $config['max_height']       = '110';
                break;
            case self::UPLOAD_IMAGE:
                $config['allowed_types']    = 'gif|jpg|png';
                $config['max_size']         = '1024';
                $config['max_width']        = '800';
                $config['max_height']       = '800';
                break;
            case self::UPLOAD_FILE:
                $config['allowed_types']    = 'doc|pdf|txt|zip|tar';
                $config['max_size']         = '1024';
                break;
            default:
                show_error('Upload type not implemented yet', 500);
                break;
        }

        return $config;
    }
}

/* End of file file_model.php */
/* Location: ./application/models/file_model.php */