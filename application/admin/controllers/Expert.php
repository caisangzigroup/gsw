<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expert extends MY_Controller 
{
	public function __construct()
	{
		parent::__construct();
		// $this->load->model("Member_model");
	}
	

	public function list_experts()
	{
		$data['title'] = "专家列表";
		$article_cates = $this->db->get('article_cates')->result_array();
		$data['article_cates'] = $this->Article_cate_model->getSubTree($article_cates);

		$this->load->view('expert/list_experts.html',$data);
	}


	public function add_expert_tpl()
	{

		$data['title'] = "增加专家";
		$data['action'] = "add_expert";
		$article_cates = $this->db->get('article_cates')->result_array();
		$data['article_cates'] = $this->Article_cate_model->getSubTree($article_cates);


		$this->load->view('expert/expert.html',$data);
	}

	public function add_expert()
	{

	}

	public function update_expert_tpl($id)
	{
		$data['title'] = "更新专家";
		$data['action'] = "update_expert";
		$article_cates = $this->db->get('article_cates')->result_array();
		$data['article_cates'] = $this->Article_cate_model->getSubTree($article_cates);

		
		$this->load->view('expert/expert.html',$data);
	}

	public function update_expert($id)
	{

	}

	public function delete_expert()
	{

	}

}