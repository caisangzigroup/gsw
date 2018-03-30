<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comment extends MY_Controller 
{
	public function __construct()
	{
		parent::__construct();
	}

	public function list_comments($page=1)
	{
		$data['title'] = '用户评论列表';
		$article_cates = $this->db->get('article_cates')->result_array();
		$data['article_cates'] = $this->Article_cate_model->getSubTree($article_cates);

		$page_size = 20;
		$total = $this->db->count_all_results('comments');
		$base_url = base_url()."admin.php/comment/list_comments/";
		$this->page($base_url,$page_size,$total);
		$page_start = ($page-1)*$page_size;

		$this->db->select('articles.title,articles.id AS aid,members_data.nickname,comments.id,comments.reply_id,comments.content,comments.member_id,comments.createtime,comments.status',false);
		$this->db->from('comments');
		$this->db->join('articles','articles.id=comments.aid','left');
		$this->db->join('members_data','members_data.member_id=comments.member_id','left');
		$data['comments'] = $this->db->order_by('comments.id DESC')->limit($page_size,$page_start)->get()->result_array();

		foreach ($data['comments'] as $k => $v) 
		{
			if ( $v['nickname'] == '' ) $data['comments'][$k]['nickname'] = "匿名";
			$data['comments'][$k]['is_member'] = ( $v['member_id'] == 0 ) ? 0 : 1;

			$data['comments'][$k]['reply_num'] = $this->db->where('reply_id',$v['id'])->count_all_results('comments');
			
			
		}

		$this->load->view('comment/list.html',$data);
	}

	public function shield()
	{
		$ids = $this->input->get("ids");
		$ids = !empty($ids) ? $ids : exit("参数传递有误");
		$ids = substr($ids,0,-1);
		$arr_id = explode(",", $ids);
		foreach ($arr_id as $k => $v) {
			$this->db->set('content','该发言已经被管理员屏蔽');
			$this->db->set('updatetime',time());
			$this->db->where('id',$v);
			$mk = $this->db->update('comments');
			if (!$mk) exit('0'); 
		}
		echo '1';
		exit();
	}

	public function delete()
	{
		$ids = $this->input->get("ids");
		$ids = !empty($ids) ? $ids : exit("参数传递有误");
		$ids = substr($ids,0,-1);
		$arr_id = explode(",", $ids);
		foreach ($arr_id as $k => $v) {
			$this->db->where('id',$v);
			$mk = $this->db->delete('comments');
			if (!$mk) exit('0'); 
		}
		echo '1';
		exit();
	}

	public function adjust()
	{
		$status = $this->input->get("status");
		$ids = $this->input->get("ids");
		$ids = !empty($ids) ? $ids : exit("参数传递有误");
		$ids = substr($ids,0,-1);
		$arr_id = explode(",", $ids);
		foreach ($arr_id as $k => $v) {
			$this->db->set('status',$status);
			$this->db->where('id',$v);
			$mk = $this->db->update('comments');
			if (!$mk) exit('0'); 
		}
		echo '1';
		exit();
	}

	public function setting()
	{
		$data['title'] = '评论设置';
		$article_cates = $this->db->get('article_cates')->result_array();
		$data['article_cates'] = $this->Article_cate_model->getSubTree($article_cates);
		

		$config = $this->db->where('module','comment')->get('setting')->row_array();
		$data['config'] = unserialize($config['config']);
		$this->load->view('comment/setting.html',$data);
	}

	public function ajax_reply()
	{
		$id = $this->input->get("id");

		$data['reply'] = $this->db->where('reply_id',$id)->get("comments")->result_array();

		$str = $this->load->view('comment/ajax_list_reply.html',$data,TRUE);
		echo $str;
	}

}