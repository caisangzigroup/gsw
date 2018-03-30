<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Html_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function html_text($cname,$name,$value,$disabled)
	{
		if ( $v['disabled'] ==1 ) $disabled =  "disabled";
		$str = 
		'
		<div class="form-group">
            <label>'.$cname.'</label>
            <input class="form-control" id="'.$name.'" value="'.$value.'" '.$disabled.'>
        </div>
		';

		return $str;
 
	}



}