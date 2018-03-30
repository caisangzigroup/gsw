<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("User_model");
	}


	public function list_users()
	{
		$data['title'] = "管理员列表";
		$article_cates = $this->db->get('article_cates')->result_array();
		$data['article_cates'] = $this->Article_cate_model->getSubTree($article_cates);


		$data['users'] = $this->User_model->getAll();
		$this->load->view("user/list_users.html",$data);
	}

	public function add_user_tpl()
	{
		$data['title'] = "添加管理员";
		$data['action'] = "add_user";
		$article_cates = $this->db->get('article_cates')->result_array();
		$data['article_cates'] = $this->Article_cate_model->getSubTree($article_cates);


		$this->load->view("user/user.html",$data);
	}

	public function add_user()
	{
		$password1 	= $this->input->post('password1');
		$username   = $this->input->post('username');
		$arr = array(
			'username'  => $username,
			'password' 	=> md5($password1),
			'updatetime'=> time(),
			'createtime'=> time(),
			);

		$res = $this->User_model->add($arr);
		echo $res;
		exit();

	}

	public function update_user_tpl($id)
	{
		$data['title'] = "修改管理员信息";
		$article_cates = $this->db->get('article_cates')->result_array();
		$data['article_cates'] = $this->Article_cate_model->getSubTree($article_cates);

		
		$id = !empty($id) ? intval($id) : exit('param id error');
		$data['id'] = $id;
		$data['action'] = "update_user";
		$where = "id={$id}";
		$data['user'] = $this->User_model->getOne($where);
		$this->load->view("user/user.html",$data);
	}

	public function update_user($id)
	{
		$password 	= $this->input->post('password');
		$password1 	= $this->input->post('password1');
		$password2 	= $this->input->post('password2');
		
		if ( empty($password1) ) {
			exit('密码为空！');
		}
		$where = "id={$id}";
		$userinfo 	= $this->User_model->getOne($where);
		if ( $password == '' ) 
		{
			echo "操作失败";
			exit();
		}
		else
		{
			if ( md5($password) == $userinfo['password'] ) 
			{
				if ( $password1 == $password2 )
				{
					$newpass = md5($password1);
					$arr = array(
						'password' => $newpass,
						'updatetime' => time(),
						);
					$where = " id={$userinfo['id']} ";
					echo $res = $this->User_model->update($arr,$where);
					exit();
				}
				else 
				{
					echo "新密码两次输入不符";
					exit();
				}
			}
			else
			{
				echo "登录密码错误";
				exit();
			}
		}
	}

}