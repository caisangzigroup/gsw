<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends MY_Controller 
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$data['title'] = "网站导航";
		$article_cates = $this->db->get('article_cates')->result_array();
		$data['article_cates'] = $this->Article_cate_model->getSubTree($article_cates);

		$parent = $this->db->where('pid',0)->get("menu")->result_array();
		foreach ($parent as $k => $v) 
		{

			$parent[$k]['children'] = $this->db->where('pid',$v['id'])->get('menu')->result_array();
			foreach ($parent[$k]['children'] as $_k => $_v) 
			{
				$parent[$k]['children'][$_k]['children'] = $this->db->where('pid',$_v['id'])->get('menu')->result_array();
			}

		}

		$data['menus'] = $parent;

		$this->load->view('menu/main.html',$data);
	}

	public function add_tpl()
	{
		$data['title'] = "增加导航";
		$article_cates = $this->db->get('article_cates')->result_array();
		$data['article_cates'] = $this->Article_cate_model->getSubTree($article_cates);
		$data['action'] = 'add';

		$parent = $this->db->where('pid',0)->get("menu")->result_array();
		foreach ($parent as $k => $v) 
		{

			$parent[$k]['children'] = $this->db->where('pid',$v['id'])->get('menu')->result_array();
			foreach ($parent[$k]['children'] as $_k => $_v) 
			{
				$parent[$k]['children'][$_k]['children'] = $this->db->where('pid',$_v['id'])->get('menu')->result_array();
			}

		}

		$data['menus'] = $parent;

		

		$this->load->view('menu/menu.html',$data);
	}


	public function add()
	{

		$arr = array(
			'name' 		=> $this->input->post('name'),
			'is_show' 	=> 1,
			'pid' 		=> $this->input->post('pid'),
			'url'		=> $this->input->post('url'),
			'createtime'=> time(),
			'updatetime'=> time(),
			);

		$this->db->insert('menu',$arr);

		echo 1;
		exit();

	}


	public function update_tpl($id)
	{

		$data['title'] = "修改导航";
		$article_cates = $this->db->get('article_cates')->result_array();
		$data['article_cates'] = $this->Article_cate_model->getSubTree($article_cates);
		$data['action'] = 'update';

		$data['menu'] = $this->db->where('id',$id)->get('menu')->row_array();

		$parent = $this->db->where('pid',0)->get("menu")->result_array();
		foreach ($parent as $k => $v) 
		{

			$parent[$k]['children'] = $this->db->where('pid',$v['id'])->get('menu')->result_array();
			foreach ($parent[$k]['children'] as $_k => $_v) 
			{
				$parent[$k]['children'][$_k]['children'] = $this->db->where('pid',$_v['id'])->get('menu')->result_array();
			}

		}

		$data['menus'] = $parent;

		

		$this->load->view('menu/menu.html',$data);
	}


	public function update($id)
	{

		$id = isset($id) ? intval($id) : exit('id error');

		$arr = array(
			'name' 		=> $this->input->post('name'),
			'is_show' 	=> 1,
			'pid' 		=> $this->input->post('pid'),
			'url'		=> $this->input->post('url'),
			'updatetime'=> time()
		);

		$this->db->where('id',$id)->update('menu',$arr);

		echo 1;
		exit();


	}


	public function orders()
	{
		$orders = $this->input->post('orders');

		$orders = substr($orders,0,-1);
		$arr 	= explode(",", $orders);
		foreach ($arr as $k => $v) 
		{
			$temp = explode(':', $v);
			$this->db->set('orders',$temp[0])->where('id',$temp[1])->update('menu');
		}
		
		echo '1';
		exit();

	}


	public function delete()
	{

		$id = $this->input->get('id');
		$this->db->where('id',$id)->delete('menu');
		echo 1;
	}
}