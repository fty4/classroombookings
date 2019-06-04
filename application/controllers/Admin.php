<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Admin extends MY_Controller
{


	public function __construct()
	{
		parent::__construct();

		$this->load->language('admin');

		$this->require_logged_in();
		$this->require_auth_level(ADMINISTRATOR);
	}


	/**
	* Admin index page
	*
	*/
	function index()
	{
		$this->require_logged_in();
		$this->require_auth_level(ADMINISTRATOR);

		$this->data['menu_active'] = 'admin';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));

		$this->data['title'] = lang('admin_page_title');
		$this->data['items'] = $this->menu_model->get_admin_menu();

		return $this->render('admin/index');
	}


}
