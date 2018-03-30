<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Article extends CI_Controller 
{
	private $member;

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Article_model");
		$this->load->model("Setting_model");
		$this->load->model("Article_cate_model");
		if ( isset( $_SESSION['member'] ) )
		{
			$member = $this->db->select('nickname')->where('member_id',$_SESSION['member']['id'])->get('members_data')->row_array();
			$member['nickname'] = empty( $member['nickname'] ) ? '匿名会员' : $member['nickname'];
			$this->member = array('is_login'=>'1','nickname'=>$member['nickname']);
		}
		else
		{
			$this->member = array('is_login'=>'','nickname'=>'');
		}
		
	}


	public function show($id)
	{

		$config_web = $this->db->where('module','web')->get('setting')->row_array();
		$data['config_web'] = unserialize($config_web['config']);
		$data['member'] = $this->member;
		$config_comment = $this->db->where('module','comment')->get('setting')->row_array();
		$data['config_comment'] = unserialize($config_comment['config']);


		$data['article_cate'] = $this->db->get('article_cates')->result_array();

		$this->db->select('articles.*,articles_data.body');
		$this->db->from('articles');
		$this->db->join('articles_data','articles.id=articles_data.aid','left');
		$this->db->where('articles.id',$id);
		$data['article'] = $this->db->get()->row_array();
		$data['articles_recent'] = $this->db->order_by('id DESC')->limit(5)->get('articles')->result_array();
		$data['friendlinks'] = $this->db->where('is_show',1)->order_by('orders DESC,id DESC')->get('friendlinks')->result_array();
		$data['menu'] = $this->db->order_by('orders DESC,id DESC')->get('menu')->result_array();


		$data['comment_nums'] = $this->db->count_all('comments');
		$this->db->select('comments.*,members_data.nickname,members_data.headimgurl');
		$this->db->from('comments');
		$this->db->join('members_data','members_data.member_id=comments.member_id','left');
		$data['comments'] = $this->db->where('comments.aid',$id)->where('comments.status',1)->order_by('createtime ASC')->get()->result_array();

		foreach ( $data['comments'] as $k => $v ) 
		{
			$data['comments'][$k]['headimgurl'] = empty($v['headimgurl']) ? 'images/head-default.png' : $v['headimgurl'];
			$data['comments'][$k]['nickname'] = empty($v['nickname']) ? '匿名网友' : $v['nickname'];
			if ( $v['reply_id'] != 0 ) 
			{
				$reply_id = $v['reply_id'];
				$this->db->select('comments.*,members_data.nickname,members_data.headimgurl');
				$this->db->from('comments');
				$this->db->join('members_data','members_data.member_id=comments.member_id','left');
				$data['comments'][$k]['add'] = $this->db->where('comments.id',$reply_id)->get()->row_array();
				$data['comments'][$k]['add']['headimgurl'] = empty($v['add']['headimgurl']) ? 'images/head-default.png' : $v['add']['headimgurl'];
				$data['comments'][$k]['add']['nickname'] = empty($v['add']['nickname']) ? '匿名网友' : $v['add']['nickname'];
			}
		}

		$data['article_prev'] = $this->db->select('id,title')->where("id > {$id}")->get('articles')->row_array();
		$data['article_next'] = $this->db->select('id,title')->where("id < {$id}")->get('articles')->row_array();




		$this->load->view("article/article.html",$data);
		
	}

	public function category($cid,$page=1)
	{
		$config_web = $this->db->where('module','web')->get('setting')->row_array();
		$data['config_web'] = unserialize($config_web['config']);
		$data['article_cate'] = $this->db->get('article_cates')->result_array();
		$data['articles_recent'] = $this->db->order_by('id DESC')->limit(5)->get('articles')->result_array();
		$data['friendlinks'] = $this->db->where('is_show',1)->order_by('orders DESC,id DESC')->get('friendlinks')->result_array();
		$data['menu'] = $this->db->order_by('orders DESC,id DESC')->get('menu')->result_array();


		$page_size = 10;
		$total = $this->db->where('cid',$cid)->count_all_results('articles');
		$base_url = base_url()."article/category/{$cid}/";
		$this->page($base_url,$page_size,$total);
		$page_start = ($page-1)*$page_size;

		$where = "a.cid={$cid}";
		$data['articles'] = $this->db->where('cid',$cid)->order_by('id DESC')->limit($page_size,$page_start)->get('articles')->result_array();
		$this->load->view("article/list.html",$data);
	}

	public function page($base_url,$per_page,$total_nums)
	{
		$this->load->library('pagination');

		$config['base_url'] = $base_url;
		$config['total_rows'] = $total_nums;
		$config['per_page'] = $per_page;
		$config['use_page_numbers'] = TRUE;
		$config['full_tag_open'] = '<nav class="pagination" role="navigation">';
		$config['full_tag_close'] = '</nav>';
		
		$config ['next_link'] = '<i class="fa fa-angle-right"></i>';
		$config['next_tag_open'] = '';
		$config['next_tag_close'] = '';
		$config ['prev_link'] = '<i class="fa fa-angle-left"></i>';
		$config['prev_tag_open'] = '';
		$config['prev_tag_close'] = '';
		$config['cur_tag_open'] = '<span class="page-number active">';
		$config['cur_tag_close'] = '</span>';
		$config['num_tag_open'] = '<span class="page-number">';
		$config['num_tag_close'] = '</span>';
		// $config['display_pages'] = FALSE;

		$this->pagination->initialize($config);
	}

}