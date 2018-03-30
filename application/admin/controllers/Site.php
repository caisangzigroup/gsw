<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Site extends MY_Controller 
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Article_model");
	}

	public function index()
	{
		$data['title'] = "网站文章管理";
		$this->load->view("site/main.html",$data);
	}

	public function list_articles($cid = '',$page="1")
	{
		$cid 	= !empty($cid) ? $cid : 0;
		$page 	= !empty($page) ? $page : 1;
		$data['title'] = "文章列表";
		$page_size = 20;
		$article_cates = $this->db->get('article_cates')->result_array();
		$data['article_cates'] = $this->Article_cate_model->getSubTree($article_cates);


		// $total = $this->Article_model->getNums();
		if ( $cid > 0 ) 
		{
			$total = $this->db->where("cid",$cid)->count_all_results('articles');
		}
		else
		{
			$total = $this->db->count_all_results('articles');
		}

		$base_url = base_url()."admin.php/site/list_articles/{$cid}/";
		$this->page($base_url,$page_size,$total);
		$page_start = ($page-1)*$page_size;

		$this->db->select('articles.*,article_cates.name AS catename',FALSE);
		$this->db->from('articles');
		$this->db->join('article_cates','articles.cid=article_cates.id','left');
		if ( $cid > 0 ) $this->db->where("cid",$cid);
		$data['articles'] = $this->db->order_by('articles.id DESC')->limit($page_size,$page_start)->get()->result_array();

		$this->load->view("site/list_articles.html",$data);

	}



	public function add_article_tpl()
	{

		$data['title'] = "添加文章";
		$data['action'] = 'add_article';
		$article_cates 	= $this->db->get('article_cates')->result_array();
		$data['article_cates'] = $this->getSubTree($article_cates);


		$this->load->view("site/article.html",$data);
	}

	public function add_article()
	{

		$title 			= $this->input->post("title",TRUE);
		$body 			= $this->input->post("body",TRUE);
		$des 			= $this->input->post("des",TRUE);
		$keywords 		= $this->input->post("keywords",TRUE);
		$cid 			= $this->input->post("cid",TRUE);
		$pic 			= $this->input->post("pic_url",TRUE);
		$author 		= $this->input->post("author",TRUE);

		$mk = $this->Article_model->add($title,$des,$cid,$keywords,$pic,$author,$body);
		
		echo $mk;
	}

	public function update_article_tpl($id)
	{

		$data['title'] = "更改文章";
		$data['action'] 	= 'update_article';
		$article_cates 	= $this->db->get('article_cates')->result_array();
		$data['article_cates'] = $this->getSubTree($article_cates);


		$id = !empty($id) ? intval($id) : exit('param id error');
		$data['article_id'] = $id;
		$this->db->select('articles.*,articles_data.body');
		$this->db->from('articles');
		$this->db->join('articles_data','articles.id=articles_data.aid','left');
		$data['article'] = $this->db->where("articles.id={$id}")->get()->row_array();
	
		$this->load->view("site/article.html",$data);
	}

	public function update_article($id)
	{
		$id = !empty($id) ? intval($id) : exit('param id error');
		$title 			= $this->input->post("title");
		$body 			= $this->input->post("body");
		$des 			= $this->input->post("des");
		$keywords 		= $this->input->post("keywords");
		$cid 			= $this->input->post("cid");
		$pic 			= $this->input->post("pic_url");
		$author 		= $this->input->post("author");

		$arr = array(
			'title' 	=> $title,
			'body' 		=> $body,
			'des' 		=> $des,
			'keywords' 	=> $keywords,
			'cid' 		=> $cid,
			'pic' 		=> $pic,
			'author' 	=> $author,
			'updatetime'=> time(),
			);

		$mk = $this->Article_model->update($arr,$id);
		
		echo $mk;
	}

	public function delete_articles()
	{
		$ids = $this->input->get("ids");
		$ids = !empty($ids) ? $ids : exit("参数传递有误");
		$ids = substr($ids,0,-1);
		$arr_id = explode(",", $ids);
		foreach ($arr_id as $k => $v) {
			$mk = $this->Article_model->delete($v);
			if (!$mk) exit('0'); 
		}
		echo '1';
		exit();

	}

	public function list_article_cates()
	{

		$data['title'] = "文章分类列表";
		$article_cates = $this->db->get('article_cates')->result_array();
		$data['article_cates'] = $this->Article_cate_model->getSubTree($article_cates);


		$this->load->view("site/list_article_cates.html",$data);
	}	

	public function add_article_cate_tpl()
	{
		$data['title'] = "添加文章分类";
		$data['action'] = 'add_article_cate';
		$article_cates = $this->db->get('article_cates')->result_array();
		$data['article_cates'] = $this->Article_cate_model->getSubTree($article_cates);


		$this->load->view("site/article_cate.html",$data);
	}

	public function add_article_cate()
	{

		$name 			= $this->input->post("name");
		$des 			= $this->input->post("des");
		$pid 			= $this->input->post("pid");
		$arr = array(
			'name' 			=> $name,
			'des' 			=> $des,
			'updatetime' 	=> time(),
			'createtime'   	=> time(),
			'pid'			=> $pid,
			);

		$mk = $this->db->insert('article_cates',$arr);
		if ( !$mk ) exit(0);

		echo 1;
		exit();
	}

	public function update_article_cate_tpl($id)
	{

		$data['title'] 	= "更改文章分类";	
		$data['cate'] 	= $this->db->where('id',$id)->get('article_cates')->row_array();
		$article_cates 	= $this->db->get('article_cates')->result_array();
		$data['article_cates'] = $this->Article_cate_model->getSubTree($article_cates);
		$data['action'] = 'update_article_cate';

		$id = !empty($id) ? intval($id) : exit('param id error');
		$data['cid'] = $id;
		$this->load->view("site/article_cate.html",$data);

	}

	public function update_article_cate($id)
	{

		$id = !empty($id) ? intval($id) : exit('param id error');
		$name 			= $this->input->post("name");
		$des 			= $this->input->post("des");
		$pid 			= $this->input->post("pid");
		$arr = array(
			'name' 		=> $name,
			'des' 		=> $des,
			'updatetime'=> time(),
			'pid'		=> $pid,
			);

		$where = "id={$id}";
		$mk = $this->db->where('id',$id)->update('article_cates',$arr);
		if ( !$mk ) exit(0);
		echo 1;
		exit();

	}

	public function delete_article_cate()
	{
		$id = $this->input->get("id");
		$id = !empty($id) ?  $id : exit('param id error');
		$mk = $this->Article_cate_model->delete($id);
		echo $mk;
		exit();
	}



	
}
