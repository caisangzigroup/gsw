<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Single extends MY_Controller 
{
	public function __construct()
	{
		parent::__construct();
	}


	public function list_singles()
	{
		$data['title'] = '单页设置';
		$article_cates = $this->db->get('article_cates')->result_array();
		$data['article_cates'] = $this->Article_cate_model->getSubTree($article_cates);

		$page = isset($page) ? $page : 1;
		$page_size = 10;
		$total = $this->db->count_all_results('singles');
		$base_url = base_url()."admin.php/single/list_singles/";
		$this->page($base_url,$page_size,$total);
		$page_start = ($page-1)*$page_size;

		// $data['articles'] = $this->db->where('type','2')->order_by('articles.id DESC')->limit($page_size,$page_start)->get('articles')->result_array();
		$data['singles'] = $this->db->order_by('id DESC')->limit($page_size,$page_start)->get('singles')->result_array();

		$this->load->view('single/list_singles.html',$data);
	}


	public function add_tpl()
	{
		$data['title'] = '新增单页';
		$data['action'] = 'add';
		$article_cates = $this->db->get('article_cates')->result_array();
		$data['article_cates'] = $this->Article_cate_model->getSubTree($article_cates);


		$this->load->view('single/single.html',$data);
	}

	public function add()
	{
		$title 			= $this->input->post("title",TRUE);
		$body 			= $this->input->post("body",TRUE);
		$des 			= $this->input->post("des",TRUE);
		$keywords 		= $this->input->post("keywords",TRUE);
		$pic 			= $this->input->post("pic_url",TRUE);
		$author 		= $this->input->post("author",TRUE);
		$is_menu 		= $this->input->post("is_menu",TRUE);


		$arr = array(
			'title' 		=> $title,
			'des' 			=> $des,
			'keywords'		=> $keywords,
			'createtime'	=> time(),
			'updatetime'	=> time(),
			'pic'			=> $pic,
			'author' 		=> $author,
			'is_menu' 		=> $is_menu,
			);


		$res = $this->db->insert('singles',$arr);
		if ( !$res ) exit("insert error");
		$single_id = $this->db->insert_id();
		$arr_data = array(
			'single_id' 	=> $single_id,
			'body'			=> $body,
			);

		$res2 = $this->db->insert('singles_data',$arr_data);
		if ( !$res2 ) exit("insert data error");

		
		echo 1;
	}

	public function update_tpl($id)
	{
		$data['title'] = '修改单页';
		$data['action'] = 'update';	
		$article_cates = $this->db->get('article_cates')->result_array();
		$data['article_cates'] = $this->Article_cate_model->getSubTree($article_cates);

		
		$this->db->select('singles.*,singles_data.body');
		$this->db->from('singles');
		$this->db->join('singles_data','singles.id=singles_data.single_id','left');
		$data['single'] = $this->db->where('singles.id',$id)->get()->row_array();

		$this->load->view('single/single.html',$data);
	}

	public function update($id)
	{
		$title 			= $this->input->post("title",TRUE);
		$body 			= $this->input->post("body",TRUE);
		$des 			= $this->input->post("des",TRUE);
		$keywords 		= $this->input->post("keywords",TRUE);
		$pic 			= $this->input->post("pic_url",TRUE);
		$author 		= $this->input->post("author",TRUE);
		$is_menu 		= $this->input->post("is_menu",TRUE);


		$arr = array(
			'title' 		=> $title,
			'des' 			=> $des,
			'keywords'		=> $keywords,
			'updatetime'	=> time(),
			'pic'			=> $pic,
			'author' 		=> $author,
			'is_menu' 		=> $is_menu,
			);

		$where = "id={$id}";
		$res = $this->db->update('singles',$arr,$where);
		if ( !$res ) exit("update error");

		$res2 = $this->db->set('body',$body)->where('single_id',$id)->update('singles_data');
		if ( !$res2 ) exit("update data error");

		
		echo 1;
	}


	public function delete()
	{
		$ids = $this->input->get("ids");
		$ids = !empty($ids) ? $ids : exit("参数传递有误");
		$ids = substr($ids,0,-1);
		$arr_id = explode(",", $ids);
		foreach ($arr_id as $k => $v) {

			$mk1 = $this->db->where('id',$v)->delete('singles');
			$mk2 = $this->db->where('single_id',$v)->delete('singles_data');
			if ( !($mk1 && $mk2) ) exit(0);
		}
		echo '1';
		exit();
	}


}