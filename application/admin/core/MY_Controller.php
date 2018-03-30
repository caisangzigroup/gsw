<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	function  __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('session');
		$this->load->model("User_model");
		$this->load->model("Article_cate_model");
		$this->load->helper ( array (
				'form',
				'url'
		) );
		if ( $_SESSION['user']['is_login'] != 1 )
		{
			redirect(base_url().'admin.php/login');
		}
	}

	function page($base_url,$per_page,$total_nums)
	{
		$this->load->library('pagination');

		$config['base_url'] = $base_url;
		$config['total_rows'] = $total_nums;
		$config['per_page'] = $per_page;
		$config['use_page_numbers'] = TRUE;
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config ['first_link'] = '首页';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config ['last_link'] = '末页';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config ['next_link'] = '下一页';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config ['prev_link'] = '上一页';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="javascript:;">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		$this->pagination->initialize($config);
	}
}

 ?>