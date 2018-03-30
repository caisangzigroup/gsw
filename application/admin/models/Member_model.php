<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_model extends CI_Model 
{
	private $table1;
	private $table2;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
		$this->table1 = "csz_members";
		$this->table2 = "csz_members_data";
	}


	public function getAll($where="1")
	{
		$sql = "SELECT a.*,b.* FROM {$this->table1} AS a LEFT JOIN {$this->table2} AS b ON a.id=b.member_id WHERE {$where}";
		$res = $this->db->query($sql);
		$row = $res->result_array();
		return $row;
	}

	public function checkMember()
	{
		return 1;
	}


	public function getOne($where)
	{
		$sql = "SELECT a.*,b.* FROM {$this->table1} AS a 
		LEFT JOIN {$this->table2} AS b ON a.id=b.member_id 
		WHERE {$where} ";

		$res = $this->db->query($sql);
		$row = $res->row_array();
		return $row;
	}

	public function getNums($where="1")
	{
		$sql = "SELECT id FROM {$this->table1} WHERE {$where}";
		$res = $this->db->query($sql);
		return $res->num_rows();
	}

	public function delete($id)
	{
		if ( empty($id) ) exit();
		$res = $this->db->delete($this->table1,array('id'=>$id));
		$res_data = $this->db->delete($this->table2,array('member_id'=>$id));
		return 1;
	}


	public function adjust_member($id)
	{
		if ( empty($id) ) exit();
		$status = $this->input->get('status');
		if ( empty($status) ) exit();
		

		$this->db->set('status',$status);
		$this->db->where('id',$id);
		$this->db->update($this->table1);

		return 1;
	}

	public function add($arr)
	{
		$arr['openid'] 	= isset($arr['openid']) ? $arr['openid'] : '';
		$now 	= time();
		$sn 	= $this->createSn();
		$arr_base 	= array(
			'sn' 			=> $sn,
			'name' 			=> $arr['name'],
			'password' 		=> md5('123456'),//后台添加的初始密码
			'phone'			=> $arr['phone'],
			'email'			=> $arr['email'],
			'status'		=> $arr['status'],
			'openid'		=> $arr['openid'],
			'updatetime'	=> $now,
			'createtime'	=> $now,
			'lastlogintime'	=> $now,
			);

		$sql1 = $this->db->insert_string($this->table1,$arr_base);
		$res1 = $this->db->query($sql1);
		if (!$res1) exit();
		$last_id = $this->db->insert_id();

		$arr_data 	= array(
			'member_id'			=> $last_id,
			'truename' 			=> $arr['truename'],
			'contact'			=> $arr['contact'],
			'addr'				=> $arr['addr'],
			'gender'			=> $arr['gender'],
			'province'			=> $arr['province'],
			'city'				=> $arr['city'],
			'headimgurl'		=> $arr['headimgurl'],
			'nickname'			=> $arr['nickname'],
			);

		$sql2 = $this->db->insert_string($this->table2,$arr_data);
		$res2 = $this->db->query($sql2);
		if (!$res2) exit();
		return 1;

	}

	public function update($arr)
	{
		$arr['openid'] 	= isset($arr['openid']) ? $arr['openid'] : '';
		$member_id = $arr['id'];
		$member = $this->db->where('id',$member_id)->get('members')->row_array();
		$now 	= time();
		$sn 	= $this->createSn();
		$arr_base 	= array(
			'name' 			=> $arr['name'],
			'phone'			=> $arr['phone'],
			'email'			=> $member['email'],
			'status'		=> $arr['status'],
			'openid'		=> $arr['openid'],
			'active'		=> $arr['active'],
			'updatetime'	=> $now,
			);

		$where1 = "id={$arr['id']}";
		$sql1 = $this->db->update_string($this->table1,$arr_base,$where1);
		$res1 = $this->db->query($sql1);
		if (!$res1) exit('2');


		$arr_data 	= array(
			'truename' 			=> $arr['truename'],
			'contact'			=> $arr['contact'],
			'addr'				=> $arr['addr'],
			'gender'			=> $arr['gender'],
			'province'			=> $arr['province'],
			'city'				=> $arr['city'],
			'headimgurl'		=> $arr['headimgurl'],
			'nickname'			=> $arr['nickname']
			);

		$where2 = "member_id={$arr['id']}";
		$sql2 = $this->db->update_string($this->table2,$arr_data,$where2);
		$res2 = $this->db->query($sql2);
		if (!$res2) exit('3');

		return 1;
	}

	public function getTableFields()
	{
		$fields1 = $this->db->list_fields($this->table1);
		$fields2 = $this->db->list_fields($this->table2);
		foreach($fields2 as $k => $v)
		{
			if ( $v == "id" or $v == "member_id" ) unset($fields2[$k]);
		}

		$arr = array_merge($fields1,$fields2);
		return $arr;
	}


	private function createSn()
	{
		return '123456';
	}



}