<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Guestbook extends MY_Controller 
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index($page=1)
	{
		$data['title'] = "留言板";

		$page_size = 10;
		$total = $this->db->count_all('guestbook');
		$base_url = base_url()."admin.php/guestbook/index/";
		$this->page($base_url,$page_size,$total);
		$page_start = ($page-1)*$page_size;

		$data['guestbooks'] = $this->db->order_by('id DESC')->limit($page_size,$page_start)->get('guestbook')->result_array();

		$this->load->view('guestbook/list.html',$data);
		
	}


	public function delete()
	{
		$ids = $this->input->get("ids");
		$ids = !empty($ids) ? $ids : exit("参数传递有误");
		$ids = substr($ids,0,-1);
		$arr_id = explode(",", $ids);
		foreach ($arr_id as $k => $v) {
			$mk = $this->db->where('id',$v)->delete('guestbook');
			if (!$mk) exit('0'); 
		}
		echo '1';
		exit();
	}

	public function reply_tpl($id)
	{

		$id = isset($id) ? intval($id) : exit('id error');

		$data['title'] = "回复留言";

		$data['guestbook'] = $this->db->where('id',$id)->get('guestbook')->row_array();

		$data['action'] = 'update';
		$this->load->view('guestbook/reply.html',$data);

	}

	public function update($id)
	{
		$id = isset($id) ? intval($id) : exit('id error');

		$reply = $this->input->post('reply');
		$arr = array(
			'reply' 	=> $reply,
			'updatetime'=> time(),
			);




		$this->db->where('id',$id)->update('guestbook',$arr);

		echo '1';
	}


}