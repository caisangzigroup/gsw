<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper ( array (
				'form',
				'url'
		) );
		$this->load->library('session');
		$this->load->model("User_model");//User_model模型类实例化对象
	}

	public function index()
	{
		$this->load->view('login.html');
	}

	public function checkLogin()
	{
		$this->User_model->checkLogin();
	}



	
	





}