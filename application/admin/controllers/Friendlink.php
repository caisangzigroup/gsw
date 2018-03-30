<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Friendlink extends MY_Controller 
{

	public function __construct()
	{
		parent::__construct();
	}


	public function index( $page = 1 )
	{
		$data['title'] = "友情链接管理";

		$page_size = 10;
		$total = $this->db->count_all('friendlinks');
		$base_url = base_url()."admin.php/friendlink/index/";
		$this->page($base_url,$page_size,$total);
		$page_start = ($page-1)*$page_size;

		$data['links'] = $this->db->order_by('orders DESC,id DESC')->limit($page_size,$page_start)->get('friendlinks')->result_array();

		$this->load->view("friendlink/list.html",$data);
	}

	public function add_tpl()
	{
		$data['title'] = "增加友情链接";
		$data['action'] = "add";

		$this->load->view("friendlink/links.html",$data);
	}

	public function add()
	{
		
		$url = $this->input->post('url');
		$title = $this->input->post('title');

		$arr = array(
			'url' 			=> $url,
			'title'			=> $title,
			'updatetime' 	=> time(),
			'createtime' 	=> time(),
			);

		$res = $this->db->insert('friendlinks',$arr);
		if ( !$res ) exit('insert error');
		echo "1";
		exit();
	}

	public function update_tpl($id)
	{

		$data['title'] = "修改友情链接";
		$data['action'] = "update";

		$data['link'] = $this->db->where('id',$id)->get('friendlinks')->row_array();


		$this->load->view("friendlink/links.html",$data);
	}

	public function update($id)
	{
		$url = $this->input->post('url');
		$title = $this->input->post('title');

		$arr = array(
			'url' 			=> $url,
			'title'			=> $title,
			'updatetime' 	=> time(),
			);

		$res = $this->db->where('id',$id)->update('friendlinks',$arr);
		if ( !$res ) exit('update error');
		echo "1";
		exit();
	}

	public function delete()
	{
		$ids = $this->input->get("ids");
		$ids = !empty($ids) ? $ids : exit("参数传递有误");
		$ids = substr($ids,0,-1);
		$arr_id = explode(",", $ids);
		foreach ($arr_id as $k => $v) {
			$mk = $this->db->where('id',$v)->delete('friendlinks');
			if (!$mk) exit('0'); 
		}
		echo '1';
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
			$this->db->set('orders',$temp[0])->where('id',$temp[1])->update('friendlinks');
		}
		
		echo '1';
		exit();

	}


}