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

		$this->load->view('activity/list_activities.html',$data);
	}


	public function add_activity_tpl()
	{
		$data['title'] = "增加活动";
		$data['action'] = "add_activity";
		$this->load->view('activity/activity.html',$data);
	}

	public function add_activity()
	{

	}

	public function update_activity_tpl()
	{
		$data['title'] = "更新活动";
		$data['action'] = "update_activity";
		$this->load->view('activity/activity.html',$data);
	}

	public function update_activity()
	{

	}

	public function delete_activity()
	{

	}

}