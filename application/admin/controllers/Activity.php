<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class activity extends MY_Controller 
{
	public function __construct()
	{
		parent::__construct();
		// $this->load->model("Member_model");
	}

	public function list_activities()
	{
		$data['title'] = "活动列表";
		$article_cates = $this->db->get('article_cates')->result_array();
		$data['article_cates'] = $this->Article_cate_model->getSubTree($article_cates);

		$this->load->view('activity/list_activities.html',$data);
	}


	public function add_activity_tpl()
	{
		$data['title'] = "增加活动";
		$data['action'] = "add_activity";
		$article_cates = $this->db->get('article_cates')->result_array();
		$data['article_cates'] = $this->Article_cate_model->getSubTree($article_cates);


		$this->load->view('activity/activity.html',$data);
	}

	public function add_activity()
	{

	}

	public function update_activity_tpl()
	{
		$data['title'] = "更新活动";
		$data['action'] = "update_activity";
		$article_cates = $this->db->get('article_cates')->result_array();
		$data['article_cates'] = $this->Article_cate_model->getSubTree($article_cates);

		
		$this->load->view('activity/activity.html',$data);
	}

	public function update_activity()
	{

	}

	public function delete_activity()
	{

	}

}