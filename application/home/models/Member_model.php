<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member_model extends CI_Model 
{
	private $table1;
	private $table2;
	private $key1;
	private $key2;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
		$this->table1 = "csz_members";
		$this->table2 = "csz_members_data";
	}


	public function getAll( $where="1" )
	{
		$sql = "SELECT a.* FROM {$this->table1} AS a LEFT JOIN {$this->table2} AS b ON a.id=b.member_id WHERE {$where}";
		$res = $this->db->query($sql);
		$row = $res->result_array($res);
		return $row;
	}

	public function getOne($where="1")
	{
		$sql = "SELECT a.*,b.* FROM {$this->table1} AS a LEFT JOIN {$this->table2} AS b ON a.id=b.member_id WHERE {$where}";
		$res = $this->db->query($sql);
		$row = $res->row_array();
		return $row;
	}

	public function getNums($where="1")
	{
		return $this->db->where($where)->count_all_results($this->table1);
	}

	public function checkLogin()
	{

		$email = $this->input->post('username',TRUE);

		$password = $this->input->post('password',TRUE);
		if ( empty($email) ) exit('empty username'); 

		$where = "email='{$email}'";
		$member = $this->getOne($where);
		if ( !empty( $member['id'] ) )
		{

			if ( $member['password'] == md5($password) )
			{
				$result = 1;
				$arr = array('lastlogintime'=>time());
				$where2 = "id={$member['id']}";
				$this->update1($arr,$where2);
				$_SESSION['member']['email'] = $member['email'];
				$_SESSION['member']['id'] = $member['id'];
				$_SESSION['member']['is_login'] = 1;
				$add = $this->db->select('nickname')->where('member_id',$member['id'])->get('members_data')->row_array();
				$_SESSION['member']['nickname'] = $add['nickname'];
			}
			else
			{
				$result = "密码不匹配";
			}
		}
		else
		{
			$result = "用户名不存在";
		}



		return $result;
	}


	public function checkName($email)
	{
		$where = "email='{$email}'";
		$sql = "SELECT id FROM {$this->table1} WHERE {$where}";
		$res = $this->db->query($sql);
		$row = $res->row_array();
		if ( empty($row['id']) )
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function add($arr)
	{

		$arr_keys1 = $this->db->list_fields($this->table1);
		foreach ($arr as $k => $v) 
		{
			if ( in_array($k,$arr_keys1) )
			{
				$arr1[$k] = $v;
			}
		}

		$arr1['sn'] = $this->createSn();
		$arr1['updatetime'] = time();
		$arr1['createtime'] = time();
		$arr1['lastlogintime'] = time();

		$res2 = $this->db->insert($this->table1,$arr1);

		$id = $this->db->insert_id();

		$arr_keys2 = $this->db->list_fields($this->table2);
		foreach ($arr as $k2 => $v2) 
		{
			if ( in_array( $k,$arr_keys2 ) )
			{
				$arr2[$k2] = $v2;
			}
		}

		$arr2['member_id'] = $id;
		$res2 = $this->db->insert($this->table2,$arr2);

		return $id;

	}


	public function update($arr)
	{

		$id = !empty($arr['id']) ? $arr['id'] : exit("id error");

		$arr_keys1 = $this->db->list_fields($this->table1);
		foreach ($arr as $k => $v) 
		{
			if ( in_array($k,$arr_keys1) )
			{
				$arr1[$k] = $v;
			}
		}
		$arr1['updatetime'] = time();
		$where1 = "id={$id}";
		$res1 = $this->db->update($this->table1,$arr1,$where1);


		$arr_keys2 = $this->db->list_fields($this->table2);

		foreach ($arr as $k2 => $v2) 
		{
			if ( in_array( $k2,$arr_keys2 ) )
			{
				$arr2[$k2] = $v2;
			}
		}

		$where2 = "member_id={$id}";
		$sql2 = $this->db->update_string($this->table2,$arr2,$where2);
		$this->db->query($sql2);

		return '1';
	}


	private function createSn()
	{
		return '123456';
	}

	private function setName($val)
	{
		$res = !empty($val) ? $val : '';
		return $res;
	}


	public function update1($arr,$where)
	{
		$sql = $this->db->update_string($this->table1,$arr,$where);
		$res = $this->db->simple_query($sql);
		if ( !$res ) return 'error'; 
	}




	public function getOne1($where)
	{
		$sql = "SELECT * FROM {$this->table1} WHERE {$where}";
		$res = $this->db->query($sql);
		$row = $res->row_array();
		return $row;
	}



}