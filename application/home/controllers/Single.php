<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Single extends CI_Controller 
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

	public function show($id) 
	{
		$id = empty($id) ? exit('id error') : intval($id);
		$config_web = $this->db->where('module','web')->get('setting')->row_array();
		$data['config_web'] = unserialize($config_web['config']);
		$data['member'] = $this->member;
		$data['article_cate'] = $this->db->get('article_cates')->result_array();
		$data['articles_recent'] = $this->db->order_by('id DESC')->limit(5)->get('articles')->result_array();
		$data['friendlinks'] = $this->db->where('is_show',1)->order_by('orders DESC,id DESC')->get('friendlinks')->result_array();
		$data['menu'] = $this->db->order_by('orders DESC,id DESC')->get('menu')->result_array();


		$data['single'] = $this->db->select('singles.*,singles_data.body')->from('singles')->join('singles_data','singles.id=singles_data.single_id','left')->where('singles.id',$id)->get()->row_array();



		$this->load->view("single/single.html",$data);
	}

}