<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Departments_model extends MY_Model
{


	public $table = 'departments';
	public $primary_key = 'department_id';


	public function __construct()
	{
		parent::__construct();
	}


}
