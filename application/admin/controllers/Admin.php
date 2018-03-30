<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller 
{

	public function __construct()
	{
		parent::__construct();	
		
	}

	public function index()
	{
		$data['title'] = "管理后台";
		$article_cates = $this->db->get('article_cates')->result_array();
		$data['article_cates'] = $this->Article_cate_model->getSubTree($article_cates);

		$data['articles'] = $this->db->order_by('id DESC')->limit(5)->get('articles')->result_array(); //文章数量
		$data['article_nums'] = $this->db->count_all_results('articles');
		$data['singles'] = $this->db->order_by('id DESC')->limit(5)->get('singles')->result_array();
		$data['single_nums'] = $this->db->count_all_results('singles');
		$data['members'] = $this->db->order_by('lastlogintime DESC')->limit(5)->get('members')->result_array();
		$data['comment_nums'] = $this->db->count_all('comments');
		$data['last_comment'] = $this->db->order_by('createtime DESC')->get('comments')->first_row('array');
		$data['last_member'] = $this->db->order_by('createtime DESC')->get('members')->first_row('array');
		$data['guestbooks'] = $this->db->order_by('createtime DESC')->limit(5)->get('guestbook')->result_array();

		$this->load->view("admin/main.html",$data);
	}


	public function search()
	{

		$data['title'] = '搜索结果';
		$article_cates = $this->db->get('article_cates')->result_array();
		$data['article_cates'] = $this->Article_cate_model->getSubTree($article_cates);

		$keywords = $this->input->get('keywords');
		$data['keywords'] = $keywords;
		$keywords = !empty($keywords) ? $keywords : exit('关键字为空');
		
		$data['articles'] = $this->db->like('title', $keywords)->order_by('id DESC')->limit(10)->get('articles')->result_array();
		
		$this->db->select('members.*');
		$this->db->from('members');
		$this->db->join('members_data','members.id=members_data.member_id','left');
		$this->db->like('members.email', $keywords);
		$this->db->or_like('members_data.nickname', $keywords);
		$this->db->or_like('members.name', $keywords);
		$this->db->or_like('members.phone',$keywords);
		$data['members'] = $this->db->order_by('members.id DESC')->limit(10)->get()->result_array();

		$this->load->view('search.html',$data);



	}


	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url()."admin.php");
	}

	
}
