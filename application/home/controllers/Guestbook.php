<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guestbook extends CI_Controller 
{
	private $member;

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Article_model");
		$this->load->model("Setting_model");
		$this->load->model("Article_cate_model");
		if ( isset( $_SESSION['member'] ) )
		{
			$member = $this->db->select('nickname')->where('member_id',$_SESSION['member']['id'])->get('members_data')->row_array();
			$member['nickname'] = empty( $member['nickname'] ) ? '匿名会员' : $member['nickname'];
			$this->member = array('is_login'=>'1','nickname'=>$member['nickname']);
		}
		else
		{
			$this->member = array('is_login'=>'','nickname'=>'');
		}
		
	}


	public function index()
	{

		$config_web = $this->db->where('module','web')->get('setting')->row_array();
		$data['config_web'] = unserialize($config_web['config']);
		$data['member'] = $this->member;
		$data['article_cate'] = $this->db->get('article_cates')->result_array();
		$data['articles_recent'] = $this->db->order_by('id DESC')->limit(5)->get('articles')->result_array();
		$data['friendlinks'] = $this->db->where('is_show',1)->order_by('orders DESC,id DESC')->get('friendlinks')->result_array();
		$data['menu'] = $this->db->order_by('orders DESC,id DESC')->get('menu')->result_array();

		$data['guestbook'] = $this->db->where('is_show',1)->get('guestbook')->result_array();

		$this->load->view("guestbook/main.html",$data);


	}

	public function add()
	{
		$title 		= $this->input->post('title',true);
		$content 	= $this->input->post('content',true);
		$contact 	= $this->input->post('contact',true);
		$name 		= $this->input->post('name',true);


		$arr = array(
			'title' 	=> $title,
			'content' 	=> $content,
			'contact' 	=> $contact,
			'name' 		=> $name,
			'updatetime'=> time(),
			'createtime'=> time(),

			);

		$this->db->insert('guestbook',$arr);

		echo '1';



	}

}