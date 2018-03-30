<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller 
{

	private $member;

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Article_model");

		$this->load->model("Member_model");
		$this->load->model("Article_cate_model");
	}

	public function index($page=1)
	{

		$config_web = $this->db->where('module','web')->get('setting')->row_array();
		$data['config_web'] = unserialize($config_web['config']);
		$data['article_cate'] = $this->db->get('article_cates')->result_array();
		$data['articles_recent'] = $this->db->order_by('id DESC')->limit(5)->get('articles')->result_array();
		$data['friendlinks'] = $this->db->where('is_show',1)->order_by('orders DESC,id DESC')->get('friendlinks')->result_array();
		$data['menu'] = $this->db->order_by('orders DESC,id DESC')->get('menu')->result_array();


		$page_size = 10;
		$total = $this->db->where('type',1)->count_all_results('articles');
		$base_url = base_url()."home/index/";
		$this->page($base_url,$page_size,$total);
		$page_start = ($page-1)*$page_size;

		$data['articles'] = $this->db->order_by('id DESC')->limit($page_size,$page_start)->get('articles')->result_array();



		$this->load->view("home.html",$data);

	}


	public function login()
	{
		$data['title'] = "会员登录";
		$config_web = $this->db->where('module','web')->get('setting')->row_array();
		$data['config_web'] = unserialize($config_web['config']);
		

		$this->load->view("member/login.html",$data);
	}

	public function check_login()
	{
		echo $this->Member_model->checkLogin();
		exit();
	}

	public function reg_tpl()
	{
		$data['title'] = "会员注册";
		$config_web = $this->db->where('module','web')->get('setting')->row_array();
		$data['config_web'] = unserialize($config_web['config']);


		$this->load->view("member/reg.html",$data);
	}

	public function reg()
	{
		$email 		= $this->input->post('email',TRUE);
		$password 	= $this->input->post('password',TRUE);
		$captcha  	= $this->input->post('captcha',TRUE);

		if ( empty($email) ) 
		{
			echo 'empty email'; 
			exit();
		}

		if( $_SESSION['captcha']['code'] == $captcha )
		{
			if ( !empty($email) )
			{

				if ( $this->Member_model->checkName($email) )
				{
					$arr = array(
						'email' 	=> $email,
						'password' 	=> md5($password),
						);

					$member_id = $this->Member_model->add($arr);
					$result = 1;

					//登录状态
					$_SESSION['member']['email'] = $email;
					$_SESSION['member']['id'] = $member_id;
					$_SESSION['member']['is_login'] = 1;
					$add = $this->db->select('nickname')->where('member_id',$member_id)->get('members_data')->row_array();
					$_SESSION['member']['nickname'] = $add['nickname'];

				}
				else 
				{
					$result = "用户名重复";
				}
				
			}
			else
			{
				$result = "用户名为空";
			}
		}
		else
		{
			$result = '验证码有误';
		}

		echo $result;
		exit();
		

	}


	public function captcha()
	{
		$this->load->helper('captcha');
		$vals = array(
		    // 'word'      	=> '123456',
		    'img_path'  	=> './captcha/',
		    'img_url'   	=> base_url().'captcha/',
		    'font_path' 	=> './path/to/fonts/texb.ttf',
		    'img_width' 	=> '100',
		    'img_height'    => 30,
		    'expiration'    => 300,
		    'word_length'   => 4,
		    'font_size' 	=> 16,
		    'img_id'    	=> 'capid',
		    // 'pool'      	=> '0123456789acdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
		    'pool'      	=> '0123456789',

		    // White background and border, black text and red grid
		    'colors'    => array(
		        'background' => array(255, 255, 255),
		        'border' => array(255, 255, 255),
		        'text' => array(0, 0, 0),
		        'grid' => array(255, 40, 40)
		    )
		);

		$cap = create_captcha($vals);
		$_SESSION['captcha']['code'] = $cap['word'];
		echo $cap['image'];
		exit();
	}


	public function send_email()
	{

		$email = $this->input->get('email');
		$code = $this->create_email_code($email);
		$res = $this->email("tangleiwangyi@163.com",$email,$code);
		echo $res;
		exit();
	}

	public function email_activate()
	{
		$code = $this->input->get('code');
		$to = $this->input->get('email');

		$sql = "SELECT * FROM csz_code WHERE email='{$to}'";
		$res = $this->db->query($sql);
		$row = $res->row_array();
		if ( $row['expiretime'] > time() )
		{
			if ( isset($_SESSION['member']['email']) and $row['email'] == $_SESSION['member']['email'] )
			{
				if ( $code == $row['email_code'] )
				{
					echo "激活成功";
					$where = "id={$_SESSION['member']['id']}";
					$this->Member_model->update1(array('active'=>1),$where);
					exit();
				}
				else
				{
					echo "验证码有误，请重新发送邮件激活";
					exit();
				}
			}
			else
			{
				echo "请登录后验证";
				redirect(base_url().'member/');
				exit();
			}
		}
		else
		{
			echo "验证码已经过期，请重新发送邮件激活。";
			exit();
		}
	}

	public function email($from="tangleiwangyi@163.com",$to,$code)
	{
		$this->load->library('email');

		$config_web = $this->db->select('config')->where('module','email')->get('setting')->row_array();
		$config_email = unserialize($config_web['config']);

		$config = array(
			'protocol' 	=> 'smtp',
			'smtp_host' => $config_email['smtp_sever'],
			'smtp_user' => $config_email['smtp_email'],
			'smtp_pass' => $config_email['smtp_password'],
			'mailtype'  => 'html',
			);

		$this->email->initialize($config);


		$this->email->from($from,$config_email['smtp_name']);
		$this->email->to($to);
		// $this->email->cc('another@another-example.com');
		// $this->email->bcc('them@their-example.com');

		$this->email->subject('会员邮箱验证');
		$html = "
		<html>
		<head><title>激活个人账号</title></head>
		<body>
			CAI 软件诚邀您激活账号，开始下载，测试，使用CAI 软件。<a href='http://soft.caisangzi.com/home/email_activate?email={$to}&code={$code}' target='_blank'>激活地址（请摸我）</a>，如果链接无法点开，请粘贴下面的url到服务器打开激活。
			<p>http://soft.caisangzi.com/home/email_activate?email={$to}&code={$code}</p>
		</body>
		</html>";
		$this->email->message($html);

		$res = $this->email->send();
		$string = $this->email->print_debugger(array('headers'));

		if ( $res )
		{
			echo '1';
			exit();
		}
		else
		{
			echo '邮件发送失败';
			exit();
		}
	}


	private function create_email_code($email)
	{

		$str = rand(100000,999999);
		$time = time();

		$str_code = md5($str.$time);

		$arr = array(
			'email' 	=> $email,
			'email_code'=> $str_code,
			'expiretime'=> $time + 3600,
			);


		if ( $this->Member_model->checkName($email) )
		{
			$this->db->insert('csz_code',$arr);
		}
		else
		{
			$where = "email='{$email}'";
			$sql = $this->db->update_string('csz_code',$arr,$where);
			$this->db->query($sql);
		}
		return $str_code;

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
