<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends MY_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("User_model");
	}

	public function upload_picture()
	{
		$now = time();
		$upload_path = './uploads/article/'.date("Y",$now).'/'.date("m",$now).'/';
		if (!file_exists($upload_path)){
            mkdir ($upload_path,0777,true);
        } 

		$whitelist = array('jpg', 'jpeg', 'png', 'gif');
		$name      = null;
		$error     = 'No file uploaded.';

		if (isset($_FILES)) {
			if (isset($_FILES['ajaxTaskFile'])) {
				$tmp_name = $_FILES['ajaxTaskFile']['tmp_name'];
				$name     = basename($_FILES['ajaxTaskFile']['name']);
				$error    = $_FILES['ajaxTaskFile']['error'];
				
				if ($error === UPLOAD_ERR_OK) {
					$extension = pathinfo($name, PATHINFO_EXTENSION);
					$newname  = md5(uniqid(mt_rand())).".".$extension;
					if (!in_array($extension, $whitelist)) {
						$error = 'Invalid file type uploaded.';
					} else {
						move_uploaded_file($tmp_name, $upload_path.$newname);
					}
				}
			}
		}

		echo json_encode(array(
			'name'  => $newname,
			'pic_url'	=> '/uploads/article/'.date("Y",$now).'/'.date("m",$now).'/'.$newname,
			'error' => $error,
		));
		die();
	}

	public function upload_picture_article()
    {

    	//创建目录
    	$now = time();
    	$upload_path = './uploads/article/'.date("Y",$now).'/'.date("m",$now).'/';
    	if (!file_exists($upload_path)){
            mkdir ($upload_path,0777,true);
        } 

        $config['upload_path']      = $upload_path;
        $config['allowed_types']    = 'gif|jpg|png';
        // $config['allowed_types']    = array('gif','jpg','png');
        $config['max_size']     	= 100;
        $config['max_width']        = 1024;
        $config['max_height']       = 768;
        $config['encrypt_name']		= true;
        

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('apic'))
        {
            $error = array('error' => $this->upload->display_errors());
            echo json_encode($error);
        }
        else
        {
            // $data = array('upload_data' => $this->upload->data());
            $upload_data = $this->upload->data();
            $data = array('url'=>'/uploads/article/'.date("Y",$now).'/'.date("m",$now).'/'.$upload_data['file_name']);
            echo json_encode($data);
        }
        exit();

    }

}