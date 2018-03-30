<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Article_model extends CI_Model
{
	private $table1;
	private $table2;
	private $table3;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->table1 = "csz_articles";
		$this->table2 = "csz_articles_data";
		$this->table3 = "csz_article_cates";
	}

	public function getAll( $where = 1 )
	{
		$sql = "SELECT a.*,b.name AS catename FROM {$this->table1} AS a LEFT JOIN {$this->table3} AS b ON a.cid = b.id WHERE {$where}";
		$res = $this->db->query($sql);
		if ( !$res ) exit("error");
		$arr = $res->result_array();
		return $arr;
	}

	public function getNums($where = '')
	{
		if ( empty($where) )
		{
			return $this->db->count_all($this->table1);
		}
		else
		{
			return $this->db->where($where)->count_all_results($this->table1);
		}
		
	}

	public function add($title,$description,$cid,$keywords,$pic,$author,$body)
	{
		$arr = array(
			'title' 		=> $title,
			'des' 			=> $description,
			'cid'			=> $cid,
			'keywords'		=> $keywords,
			'createtime'	=> time(),
			'updatetime'	=> time(),
			'pic'			=> $pic,
			'author' 		=> $author,
			);
		$sql = $this->db->insert_string($this->table1,$arr);
		$res = $this->db->query($sql);
		if ( !$res ) exit("insert error");
		$article_id = $this->db->insert_id();
		$arr_data = array(
			'aid' 	=> $article_id,
			'body'	=> $body,
			);

		$sql2 = $this->db->insert_string($this->table2,$arr_data);
		$res2 = $this->db->query($sql2);
		if ( !$res2 ) exit("insert error");

		return 1;

	}

	public function getArticle($id)
	{
		$sql = "SELECT a.*,b.body FROM {$this->table1} AS a 
		LEFT JOIN {$this->table2} AS b ON a.id = b.aid
		WHERE a.id={$id}";
		$res = $this->db->query($sql);
		if ( !$res ) exit("select error");
		$row = $res->row_array();

		return $row;
	}

	public function update($arr,$article_id)
	{
		$body = $arr['body'];
		$where = "id={$article_id}";
		unset($arr['body']);
		$sql = $this->db->update_string($this->table1,$arr,$where);
		$res = $this->db->query($sql);
		if ( !$res ) exit("update error");
		$arr_data = array(
			'body'	=> $body,
			);

		$where2 = "aid={$article_id}";
		$sql2 = $this->db->update_string($this->table2,$arr_data,$where2);
		$res2 = $this->db->query($sql2);
		if ( !$res2 ) exit("update data error");

		return '1';
	}




}