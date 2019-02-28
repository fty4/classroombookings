<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class User extends MY_Controller
{


	public function __construct()
	{
		parent::__construct();
	}


	/**
	* Page: index
	*
	* This function simply returns the manage() function
	*
	*/
	function index()
	{
		$this->require_logged_in();
		$this->data['heading'] = 'Edit your account';
		$this->data['title'] = 'Account';
		return $this->render('user/index');
	}


	public function login()
	{
		if ($this->userauth->loggedin()) {
			redirect('user');
		}

		$this->data['heading'] = lang('user_log_in_heading');
		$this->data['title'] = lang('user_action_log_in');

		if ($this->input->post()) {

			$this->load->library('form_validation');
			$this->form_validation->set_rules('username', 'Username', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');

			if ($this->form_validation->run() !== FALSE) {
				// Validation passed.
				if ($this->userauth->trylogin($this->input->post('username'), $this->input->post('password'))) {
					$this->notice("success", lang('user_login_form_success'));
					redirect();
				} else {
					// $this->notice('error', "Incorrect username/password");
				}
			}

			$this->notice("error", lang('user_login_form_error'));
		}


		return $this->render('user/login');
	}


	public function logout()
	{
		if ($this->input->method() != 'post') {
			redirect('user');
		}

		$this->userauth->logout();
		redirect();
	}


}
