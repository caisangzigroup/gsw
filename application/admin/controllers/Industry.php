<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Industry extends MY_Controller 
{
	public function __construct()
	{
		parent::__construct();
	}


	public function list_cates()
	{
		$data['title'] = "行业分类列表";
		$article_cates = $this->db->get('article_cates')->result_array();
		$data['article_cates'] = $this->Article_cate_model->getSubTree($article_cates);



		$industry_cates = $this->db->select("id,others,pid,name,updatetime")->get("industry_cates")->result_array();
		$data['industry_cates'] = $this->getSubTree($industry_cates);
		$this->load->view('industry/list_cates.html',$data);
	}


	public function add_cate_tpl()
	{
		$data['title'] = "增加行业分类";
		$data['action'] = "add_cate";
		$article_cates = $this->db->get('article_cates')->result_array();
		$data['article_cates'] = $this->Article_cate_model->getSubTree($article_cates);

		$industry_cates = $this->db->select("id,pid,name")->get("industry_cates")->result_array();
		$data['industry_cates'] = $this->getSubTree($industry_cates);
		$this->load->view('industry/cate.html',$data);
	}

	public function add_cate()
	{
		$name 			= $this->input->post("name");
		$others 		= $this->input->post("others");
		$pid 			= $this->input->post("pid");
		$arr = array(
			'name' 			=> $name,
			'others' 		=> $others,
			'updatetime' 	=> time(),
			'createtime'   	=> time(),
			'pid'			=> $pid,
			);

		$mk = $this->db->insert('industry_cates',$arr);
		if ( !$mk ) exit(0);

		echo 1;
		exit();
	}


	public function update_cate_tpl($id)
	{
		$data['title'] = "修改行业分类";
		$data['action'] = "update_cate";
		$industry_cates = $this->db->select("id,pid,name")->get("industry_cates")->result_array();
		$data['industry_cates'] = $this->getSubTree($industry_cates);

		$data['cate'] = $this->db->select("pid,id,name,others")->where('id',$id)->get("industry_cates")->row_array();

		$this->load->view('industry/cate.html',$data);
	}

	public function update_cate($id)
	{
		$id = !empty($id) ? intval($id) : exit('param id error');
		$name 			= $this->input->post("name");
		$others 			= $this->input->post("others");
		$pid 			= $this->input->post("pid");
		$arr = array(
			'name' 			=> $name,
			'others' 		=> $others,
			'updatetime'	=> time(),
			'pid'			=> $pid,
			);

		$where = "id={$id}";
		$mk = $this->db->where('id',$id)->update('industry_cates',$arr);
		if ( !$mk ) exit(0);
		echo 1;
		exit();
	}

	public function delete()
	{

		$ids = $this->input->get("ids");	
		$ids = !empty($ids) ? $ids : exit("参数传递有误");
		$ids = substr($ids,0,-1);
		$arr_id = explode(",", $ids);
		foreach ($arr_id as $k => $v) {
			$this->db->where('id',$v)->delete('industry_cates');
		}
		
		echo '1';
		exit();
	}
}