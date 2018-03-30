<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_model extends CI_Model
{
	private $table;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->table = "csz_setting";
	}

	public function getWebConfig()
	{
		$sql = " SELECT * FROM {$this->table} WHERE module = 'web'";
		$res = $this->db->query($sql);
		$row = $res->row_array();
		return unserialize($row['config']);
	}

	public function update($arr,$where)
	{
		$sql = $this->db->update_string($this->table,$arr,$where);
		$res = $this->db->query($sql);
		if (!$res) { exit("error update");  }
		echo '1';
		exit();
	}

}