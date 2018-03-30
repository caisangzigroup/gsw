<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_model extends CI_Model
{
	private $table;

	public function __construct()
	{
		parent::__construct();
		$this->table = "csz_comments";
	}


}