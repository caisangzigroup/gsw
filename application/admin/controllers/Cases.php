<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cases extends MY_Controller 
{
	public function __construct()
	{
		parent::__construct();
		// $this->load->model("Member_model");
	}

	public function list_cases()
	{
		$data['title'] = "案例列表";
		$article_cates = $this->db->get('article_cates')->result_array();
		$data['article_cates'] = $this->Article_cate_model->getSubTree($article_cates);

		$this->load->view('case/list_cases.html',$data);
	}


	public function add_case_tpl()
	{
		$data['title'] = "增加案例";
		$data['action'] = "add_case";
		$article_cates = $this->db->get('article_cates')->result_array();
		$data['article_cates'] = $this->Article_cate_model->getSubTree($article_cates);


		$this->load->view('case/case.html',$data);
	}

	public function add_case()
	{

	}

	public function update_case_tpl()
	{
		$data['title'] = "更新案例";
		$data['action'] = "update_case";
		$article_cates = $this->db->get('article_cates')->result_array();
		$data['article_cates'] = $this->Article_cate_model->getSubTree($article_cates);

		
		$this->load->view('case/case.html',$data);
	}

	public function update_case()
	{

	}

	public function delete_case()
	{

	}

}