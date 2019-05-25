<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Users_model extends MY_Model
{


	public $table = 'users';
	public $primary_key = 'user_id';


	public function __construct()
	{
		parent::__construct();
	}


	public function wake_values($row, $find_query = NULL)
	{
		$row->department = [];

		if ($find_query) {

			$include = $find_query->get_include();

			if (in_array('department', $include)) {
				$row = $this->populate_department($row);
			}

		}

		return $row;
	}


	public function sleep_values($data = [])
	{
		$password_fields = ['set_password_1', 'new_password_1'];

		foreach ($password_fields as $key) {
			if (array_key_exists($key, $data) && strlen($data[$key])) {
				$data['password'] = password_hash($data[$key], PASSWORD_DEFAULT);
			}
		}

		print_r($data);

		return parent::sleep_values($data);
	}


	public function populate_department($row)
	{
		$this->load->model('departments_model');

		$dept = $this->departments_model->find_one([
			'department_id' => $row->department_id,
		]);

		$row->department = $dept;

		return $row;
	}


}
