<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expert extends CI_Controller 
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Member_model");
		$this->load->model("Setting_model");
		$this->load->helper ( 
			array (
				'form',
				'url'
			) 
		);

		if ( $_SESSION['member']['is_login'] != 1 )
		{
			redirect(base_url().'home/login');
		}
	}

	public function index()
	{
		// $this->db->set_dbprefix("ck_");
		// print_r($_SESSION);
		$data['title'] = '专家中心';
		$data['article_nums'] = $this->db->count_all('articles'); 

		$where = "members_data.id={$_SESSION['member']['id']}";
		$this->db->select('members.*,members_data.nickname');
		$this->db->from('members');
		$this->db->join('members_data', 'members.id = members_data.member_id', 'left');
		$data['member'] = $this->db->where($where)->get()->row_array();

		$data['articles'] = $this->db->order_by('createtime DESC')->get('articles')->result_array();


		$this->load->view('member/center.html',$data);
	}


	public function edit()
	{
		// echo json_encode($_POST);
		echo $this->Member_model->update($_POST);

		exit();
	} 


	public function show()
	{
		// print_r($_SESSION);
		$data['title'] = '个人信息';

		$where = "members_data.id={$_SESSION['member']['id']}";
		$this->db->select('members.*,members_data.*');
		$this->db->from('members');
		$this->db->join('members_data', 'members.id = members_data.member_id', 'left');
		$data['member'] = $this->db->where($where)->get()->row_array();
		$data['fields'] = $this->db->where('is_edit=1')->order_by("orders DESC")->get('fields')->result_array();


		$this->load->view("member/profile.html",$data);

	}

	public function show_authority()
	{

		$data['title'] = '拥有权限';
		$data['articles'] = $this->db->order_by('createtime DESC')->get('articles')->result_array();


		$this->load->view("member/authority.html",$data);
	}


	// public function list_comments()
	// {
	// 	$data['title'] = '我的评论&回复';

	// 	$this->db->select("comments.*,articles.title");
	// 	$this->db->from('comments');
	// 	$this->db->join('articles','comments.aid=articles.id','left');
	// 	$data['comments'] = $this->db->where('comments.member_id',$_SESSION['member']['id'])->get()->result_array();
	// 	foreach ($data['comments'] as $v) 
	// 	{
	// 		$key = $v['reply_id'];
	// 		$this->db->select("comments.*,articles.title");
	// 		$this->db->from('comments');
	// 		$this->db->join('articles','comments.aid=articles.id','left');
	// 		$data['comments'][$key]['add'] = $this->db->where('reply_id',$v['reply_id'])->get()->result_array();

	// 	}
		

	// 	$this->load->view("member/list_comments.html",$data);

	// }


	public function add_demand_tpl()
	{
		$data['title'] = '发布需求';
		$data['action'] = 'add_demand';
		$this->load->view("member/demand.html",$data);
	}

	public function add_demand()
	{

	}

	public function show_demand()
	{

	}

	public function list_demands()
	{
		$data['title'] = '需求列表';

		
		$this->load->view("member/list_demands.html",$data);
	}


	public function list_guestbook()
	{
		$data['title'] = '我的留言';

		$data['guestbook'] = $this->db->where('member_id',$_SESSION['member']['id'])->order_by('id DESC')->get('guestbook')->result_array();

		$this->load->view("member/list_guestbook.html",$data);

	}


	public function add_guestbook_tpl()
	{
		$data['title'] = '发布留言';

		$data['action'] = "add_guestbook";

		$this->load->view("member/guestbook.html",$data);
	}


	public function add_guestbook()
	{
		$member_id = $_SESSION['member']['id'];
		$content = $this->input->post('des');
		$title = $this->input->post('title');
		$contact = "会员后台提问";

		$arr = array(
			'member_id' 	=> $member_id,
			'content'		=> $content,
			'title'			=> $title,
			'contact'		=> $contact,
			'createtime'	=> time(),
			'updatetime'	=> time(),
			'is_show'		=> 0
			);


		$this->db->insert('guestbook',$arr);


		echo "1";

	}

	public function update_guestbook_tpl($id)
	{
		$data['title'] = '修改留言';

		$data['action'] = "update_guestbook";

		$data['guestbook'] = $this->db->where('id',$id)->where('member_id',$_SESSION['member']['id'])->get('guestbook')->row_array();

		$this->load->view("member/guestbook.html",$data);
	}


	public function update_guestbook($id)
	{
		$member_id = $_SESSION['member']['id'];
		$content = $this->input->post('des');
		$title = $this->input->post('title');

		$arr = array(
			'content'		=> $content,
			'title'			=> $title,
			'updatetime'	=> time(),
			);


		$this->db->where('id',$id)->update('guestbook',$arr);


		echo "1";
	}



	public function logout()
	{
		unset($_SESSION['member']);
		redirect(base_url()."member/");
	}

	
	



}