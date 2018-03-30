<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Article_cate_model extends CI_Model
{
	private $table;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->table = "csz_article_cates";
	}

	public function getAll()
	{
		$sql = "SELECT * FROM {$this->table}";
		$res = $this->db->query($sql);
		if ( !$res ) exit("error");
		$arr = $res->result_array();
		return $arr;
	}

	public function getOne($where="1")
	{
		$sql = "SELECT * FROM {$this->table} WHERE {$where}";
		$res = $this->db->query($sql);
		if ( !$res ) exit("error");
		$arr = $res->row_array();
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

	public function getField($field,$cid)
	{
		$sql = "SELECT {$field} FROM {$this->table} WHERE id={$cid}";
		$res = $this->db->query($sql);
		if ( !$res ) exit("error");
		$arr = $res->row_array();
		return $arr[$field];
	}

	public function delete($id)
	{
		$data = $this->db->select('pid,id')->get($this->table)->result_array();

		$son = $this->getSubTree($data,$id);
		foreach ($son as $k => $v) 
		{
			$this->db->delete($this->table,array('id'=>$v['id']));
		}
		$this->db->delete($this->table,array('id'=>$id));
		
		return 1;
	}

	/**
	 * 获取子孙树
	 * @param   array        $data   待分类的数据
	 * @param   int/string   $id     要找的子节点id
	 * @param   int          $lev    节点等级
	 */
	function getSubTree($data , $id = 0 , $lev = 0) {
	    static $son = array();

	    foreach($data as $key => $value) {
	        if($value['pid'] == $id) {
	            $value['lev'] = $lev;
	            $son[] = $value;
	            $this->getSubTree($data , $value['id'] , $lev+1);
	        }
	    }

	    return $son;
	 }

}