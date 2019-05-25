<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Test extends MY_Controller
{


	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}


	function index($page = 0)
	{
		$this->load->model('users_model');
		if ( ! isset($_GET['limit'])) {
			// $_GET['limit'] = 10;
		} else {
			// $_GET['limit'] = (int) $_GET['limit'];
		}

		$_GET['something'] = 'else';

		$filter = $this->input->get();
		$filter['sort'] = 'username';
		$filter['limit'] = 10;
		$filter['offset'] = $page;

		$this->data['filter'] = $filter;
		$this->data['total'] = $this->users_model->count($filter);
		$this->data['users'] = $this->users_model->find($filter);

		$pagination_config = [
			'base_url' => site_url('test/index'),
			'total_rows' => $this->data['total'],
			'per_page' => $filter['limit'],
			'reuse_query_string' => TRUE,
			// 'use_page_numbers' => TRUE,
			// 'suffix' => '?' . http_build_query($this->input->get()),
		];
		$this->load->library('pagination');
		$this->pagination->initialize($pagination_config);

		echo json_encode($this->data['users']);

		$links = $this->pagination->create_links();
		if (strlen($links)) {
			echo $links;
		}
		// echo $this->pagination->create_links();
	}

}
