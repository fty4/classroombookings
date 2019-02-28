<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{


	public function __construct()
	{
		parent::__construct();

		$this->load->helper('file');

		$this->require_logged_in();
	}


	/**
	* Page: index
	*
	* This function simply returns the manage() function
	*
	*/
	function index()
	{
		$this->data['title'] = 'Dashboard';
		$this->data['heading'] = setting('name');
		$this->data['body'] = '';
		return $this->render('dashboard/index');
	}


}
