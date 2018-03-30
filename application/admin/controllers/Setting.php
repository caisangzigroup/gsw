<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends MY_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Setting_model");
	}

	public function index()
	{

		$data['title'] = 'CAI程序设置';

		$config_web = $this->db->select('config')->where('module','web')->get('setting')->row_array();
		$data['config_web'] = unserialize($config_web['config']);

		$config_web = $this->db->select('config')->where('module','email')->get('setting')->row_array();
		$data['config_email'] = unserialize($config_web['config']);

		$this->load->view('setting/main.html',$data);
	}

	public function update($id)
	{
		$id = !empty($id) ? intval($id) : exit('param id error');

		if ( $id == 1 ) 
		{
			$title 			= $this->input->post("title");
			$des 			= $this->input->post("des");
			$keywords 		= $this->input->post("keywords"); 

			$tmp = array(
				'title' 		=> $title,
				'keywords' 		=> $keywords,
				'description' 	=> $des,
				);


			$arr = array(
				'config'   		=> serialize($tmp),
				'module' 		=> 'web',
				'updatetime'	=> time(),	
			);

		} 
		elseif ( $id == 3 ) 
		{
			$smtp_sever 	= $this->input->post("smtp_sever");
			$smtp_email 	= $this->input->post("smtp_email");
			$smtp_password 	= $this->input->post("smtp_password"); 
			$smtp_name 		= $this->input->post("smtp_name"); 

			$tmp = array(
				'smtp_sever' 		=> $smtp_sever,
				'smtp_email' 		=> $smtp_email,
				'smtp_password' 	=> $smtp_password,
				'smtp_name'			=> $smtp_name,
				);


			$arr = array(
				'config'   		=> serialize($tmp),
				'module' 		=> 'email',
				'updatetime'	=> time(),	
			);
		} 
		elseif ( $id == 5 )
		{
			$comment_member_can = $this->input->post("comment_member_can");
			$comment_status 	= $this->input->post("comment_status");
			$comment_show 		= $this->input->post("comment_show");
			$tmp = array(
				'member_can' 		=> $comment_member_can,
				'status' 			=> $comment_status,
				'show' 				=> $comment_show,
				);

			$arr = array(
				'config'   		=> serialize($tmp),
				'module' 		=> 'comment',
				'updatetime'	=> time(),	
			);


		}
		

		$where = "id={$id}";
		$mk = $this->Setting_model->update($arr,$where);
		
		echo $mk;
	}
}