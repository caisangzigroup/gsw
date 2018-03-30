<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model 
{
	private $table;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
		$this->table = "csz_users";
	}

	public function checkLogin()
	{
		
		$username = $this->input->post('username');
		$password = md5($this->input->post('password'));

		
		$sql = "SELECT * FROM {$this->table} WHERE username = '{$username}'";
		$query = $this->db->query($sql);
		$row = $query->row();
	
		if ( isset($row->id) )
		{
			if ( $password == $row->password )
			{
				
				$data = array('updatetime' => time());
				$where = "username = '{$username}'";
				$sql_update = $this->db->update_string($this->table, $data, $where);
				$res = $this->db->simple_query($sql_update);
				if ( !$res ) exit("update faile");
				$_SESSION['user']['is_login'] 	= 1;
				$_SESSION['user']['username'] 	= $username;
				$_SESSION['user']['password'] 	= $password;
				$_SESSION['user']['uid'] 		= $row->id;
				redirect(base_url()."admin.php");
				
			}
			else
			{
				echo "密码错误";
				exit();
			}
		}
		else
		{
			echo "用户名不存在";
			exit();
		}
		
	}


	public function getAll()
	{
		$sql = "SELECT * FROM {$this->table}";
		$res = $this->db->query($sql);
		$rows = $res->result_array();
		return $rows;
	}

	public function getOne($where="1")
	{

		$sql = "SELECT * FROM {$this->table} WHERE ".$where;
		$mark = $this->db->simple_query($sql);
		if (!$mark) exit("参数传递错误!");
		$res = $this->db->query($sql);
		$row = $res->row_array();
		return $row;
	}

	public function update($arr,$where)
	{
		$sql = $this->db->update_string($this->table,$arr,$where);
		$res = $this->db->query($sql);
		if (!$res) exit("参数传递错误!");
		return 1;
	}

	public function add($arr)
	{
		$sql = $this->db->insert_string($this->table,$arr);
		$res = $this->db->query($sql);
		if (!$res) exit("参数传递错误!");
		return 1;
	}
}