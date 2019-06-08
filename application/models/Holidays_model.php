<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Holidays_model extends MY_Model
{


	public $table = 'holidays';
	public $primary_key = 'holiday_id';


	public function __construct()
	{
		parent::__construct();
	}


}
