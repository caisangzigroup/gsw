<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Industry extends MY_Controller 
{
	public function __construct()
	{
		parent::__construct();
		// $this->load->model("Member_model");
	}


	public function list_cates()
	{
		$data['title'] = "行业分类列表";


		$this->load->view('industry/list_cates.html',$data);
	}


	public function add_cate_tpl()
	{
		$data['title'] = "增加行业分类";
		$data['action'] = "add_cate";

		$this->load->view('industry/cate.html',$data);
	}

	public function add_cate()
	{

	}


	public function update_cate_tpl()
	{
		$data['title'] = "修改行业分类";
		$data['action'] = "update_cate";

		$this->load->view('industry/cate.html',$data);
	}

	public function update_cate()
	{

	}

	public function delete_cate()
	{

	}

}