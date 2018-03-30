<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Field_model extends CI_Model
{
	private $table;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->table = "csz_fields";
	}

	public function getAll( $where = "1" )
	{
		$sql = "SELECT * FROM {$this->table} WHERE {$where}";
		$res = $this->db->query($sql);
		if ( !$res ) exit("error");
		$arr = $res->result_array();
		return $arr;
	}

	public function add($arr)
	{
		$sql = $this->db->insert_string($this->table,$arr);
		$res = $this->db->query($sql);
		if ( !$res ) exit("error");
		return 1;
	}

	public function update($arr,$where)
	{
		$sql = $this->db->update_string($this->table,$arr,$where);
		$res = $this->db->query($sql);
		if ( !$res ) exit("error");
		return 1;
	}

	public function getField($field,$where)
	{
		$sql = "SELECT {$field} FROM {$this->table} WHERE {$where}";
		$res = $this->db->query($sql);
		if ( !$res ) exit("error");
		$arr = $res->row_array();
		return $arr[$field];
	}

	public function delete($id)
	{
		$res = $this->db->delete($this->table,array('id'=>$id));
		return $res;
	}



}