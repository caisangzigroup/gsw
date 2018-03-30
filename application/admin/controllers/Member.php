<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends MY_Controller 
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Member_model");
	}

	public function list_members($page=1)
	{
		$data['title'] = "会员列表";

		$page_size = 20;
		$total = $this->db->count_all('members');
		$base_url = base_url()."admin.php/member/list_members/";
		$this->page($base_url,$page_size,$total);
		$page_start = ($page-1)*$page_size;

		$this->db->select('members.*,members_data.nickname');
		$this->db->from('members');
		$this->db->join('members_data','members.id=members_data.member_id','left');
		$data['members'] = $this->db->order_by('members.id DESC')->limit($page_size,$page_start)->get()->result_array();


		$this->load->view('member/list.html',$data);
	}


	public function add_member_tpl()
	{
		$data['title'] 	= "增加会员";
		$data['action'] = "add_member";

		$data['fields'] = $this->db->where('model','member')->order_by('orders DESC')->get('fields')->result_array();

		$this->load->view('member/member.html',$data);
	}


	public function add_member()
	{
		// echo json_encode($_POST);
		$res = $this->Member_model->add($_POST);
		echo $res;
	}


	public function update_member_tpl($id)
	{	
		
		$data['title'] = "更新会员信息";
		$data['action'] = "update_member";

		$id = !empty($id) ? intval($id) : exit('param id error');

		$this->db->select('members.*,members_data.*');
		$this->db->from('members');
		$this->db->join('members_data','members.id=members_data.member_id','left');
		$data['member'] = $this->db->where("members.id={$id}")->get()->row_array();

		$data['fields'] = $this->db->where('model','member')->order_by('orders DESC')->get('fields')->result_array();


		$this->load->view('member/member.html',$data);
	}


	public function update_member()
	{
		// echo json_encode($_POST);
		$res = $this->Member_model->update($_POST);
		echo $res;
	}

	public function delete_members()
	{
		$ids = $this->input->get("ids");
		$ids = !empty($ids) ? $ids : exit("参数传递有误");
		$ids = substr($ids,0,-1);
		$arr_id = explode(",", $ids);
		foreach ($arr_id as $k => $v) {
			$mk = $this->Member_model->delete($v);
			if (!$mk) exit('0'); 
		}
		echo '1';
		exit();
	}


	public function reset_password()
	{
		$id = $this->input->get("id",TRUE);
		if ( empty($id) ) { echo 'id error'; exit(); }

		$this->db->set('password', md5('123456'));
		$this->db->where('id',$id);
		$this->db->update('members'); 

		echo '1';
		exit();
	}


	public function forbid_member()
	{
		$id = $this->input->get("id",TRUE);
		if ( empty($id) ) { echo 'id error'; exit(); }

		$this->db->set('status', 2);
		$this->db->where('id',$id);
		$this->db->update('members'); 

		echo '1';
		exit();
	}

	public function adjust_members()
	{
		$ids = $this->input->get("ids");
		$ids = !empty($ids) ? $ids : exit("参数传递有误");
		$ids = substr($ids,0,-1);
		$arr_id = explode(",", $ids);
		foreach ($arr_id as $k => $v) {
			$mk = $this->Member_model->adjust_member($v);
			if (!$mk) exit('0'); 
		}
		echo '1';
		exit();
	}


	
}