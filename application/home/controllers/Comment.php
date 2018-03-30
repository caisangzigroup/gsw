<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comment extends CI_Controller 
{
	private $config_comment;

	public function __construct()
	{
		parent::__construct();
		$config_comment = $this->db->where('module','comment')->get('setting')->row_array();
		$this->config_comment = unserialize($config_comment['config']);
	}

	public function post()
	{
		$config = $this->config_comment;

		$content 	= $this->input->post('con');
		$aid 		= $this->input->post('aid');
		$reply_id 	= $this->input->post('reply_id');
		if ( $config['member_can'] == 1 ) 
		{
			$member_id  = isset($_SESSION['member']['id']) ? $_SESSION['member']['id'] : exit('请登录后发布评论');
		}
		else 
		{
			$member_id = 0;
		}
		
		$status = ( $config['status'] == 1 ) ? 0 : 1;

		$arr = array(
			'content'	=> $content,
			'aid'		=> $aid,
			'status'	=> $status,
			'member_id'	=> $member_id,
			'createtime'=> time(),
			'updatetime'=> time(),
			'reply_id'	=> $reply_id,
			);

		$res = $this->db->insert('comments',$arr);

		if (!$res) exit('insert error');


		echo "1";
		exit();

	}

	public function list_comments($article_id)
	{

		$this->db->select('comments.*,members_data.nickname,members_data.headimgurl');
		$this->db->from('comments');
		$this->db->join('members_data','members_data.member_id=comments.member_id','left');
		$data['comments'] = $this->db->where('comments.aid',$article_id)->get()->result_array();

		$this->load->view('commemt/list.html',$data);
	}


}