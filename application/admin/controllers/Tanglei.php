<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tanglei extends MY_Controller 
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function fields()
	{
		$arr = array(
			array('sn','会员号','text','1'),
			array('password','密码','password','0'),
			array('phone','电话','text','0'),
			array('status','状态','select','0'),
			array('openid','微信id','text','1'),
			array('updatetime','更新时间','time','1'),
			array('createtime','创建时间','time','1'),
			array('lastlogintime','最后登录','time','1'),
			array('truename','真实姓名','text','0'),
			array('addr','居住地址','text','0'),
			array('contact','联系方式','text','0'),
			array('wx_nickname','昵称（微信）','text','1'),
			array('wx_gender','性别（微信）','select','0'),
			array('wx_province','省份（微信）','text','0'),
			array('wx_city','城市（微信）','text','0'),
			array('wx_province','省份（微信）','text','0'),
			array('wx_headimgurl','头像（微信）','img','0'),
			array('wx_subscribe','是否关注公众号（微信）','select','0'),
			array('wx_subscribe_time','关注公众号时间（微信）','time','0'),

		);

		foreach ( $arr as $k => $v )
		{
			$sql = "INSERT INTO `soft_caisangzi`.`csz_fields` (`id`, `name`, `model`, `cname`, `type`,`disabled`) VALUES (NULL, '{$v[0]}', 'member', '{$v[1]}', '{$v[2]}',{$v[3]})";

			$this->db->query($sql);
		}
	}
	


	
}



