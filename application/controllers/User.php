<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class User extends MY_Controller
{


	public function __construct()
	{
		parent::__construct();
	}


	/**
	* Account details page
	*
	*/
	function index()
	{
		$this->require_logged_in();

		$this->data['menu_active'] = 'user';
		$this->data['breadcrumbs'][] = array('user', lang('user_page_account_title'));

		$this->data['user'] = $this->users_model->find_one(array(
			'user_id' => $this->userauth->user->user_id,
			'enabled' => 1,
		));

		if ( ! $this->data['user']) {
			$this->render_error(array(
				'http' => 404,
				'title' => 'Not found',
				'description' => "Could not find your user record.",
			));
		}

		$this->data['title'] = lang('user_page_account_details_title');

		// $this->blocks['content'] = 'user/account/details';
		$this->blocks['tabs'] = 'user/account/menu';

		if ($this->input->post()) {
			$this->save_details();
		}

		return $this->render('user/account/details');
	}


	private function save_details()
	{
		$id = $this->userauth->user->user_id;

		$this->load->library('form_validation');

		$this->load->config('form_validation', TRUE);
		$this->form_validation->set_rules($this->config->item('user_account_details', 'form_validation'));
		$this->form_validation->set_rules('email', "lang:user_field_email", "trim|max_length[191]|valid_email|user_email_unique[{$id}]");

		if ($this->form_validation->run() == FALSE) {
			$this->notice('error', "Please check the form fields and try again.");
			return;
		}

		$user_data = [
			'email' => $this->input->post('email'),
			'firstname' => $this->input->post('firstname'),
			'lastname' => $this->input->post('lastname'),
			'displayname' => $this->input->post('displayname'),
		];

		// Update
		$res = $this->users_model->update($user_data, ['user_id' => $id]);

		if ($res) {
			$this->notice('success', "Your account details have been updated.");
			redirect('user');
		} else {
			$this->notice('error', "There was an error updating your account details.");
		}
	}


	public function password()
	{
		$this->require_logged_in();

		$this->data['menu_active'] = 'user/password';
		$this->data['breadcrumbs'][] = array('user', lang('user_page_account_title'));

		$this->data['user'] = $this->users_model->find_one(array(
			'user_id' => $this->userauth->user->user_id,
		));

		if ( ! $this->data['user']) {
			$this->render_error(array(
				'http' => 404,
				'title' => 'Not found',
				'description' => "Could not find your user record.",
			));
		}

		$this->data['title'] = lang('user_page_password_title');

		// $this->blocks['content'] = 'user/account/password';
		// $this->blocks['sidebar'] = 'user/account/menu';
		$this->blocks['tabs'] = 'user/account/menu';

		if ($this->input->post()) {
			$this->save_password();
		}

		return $this->render('user/account/password');
	}


	private function save_password()
	{
		$id = $this->userauth->user->user_id;
		$username = $this->userauth->user->username;

		$this->load->library('form_validation');

		$this->load->config('form_validation', TRUE);
		$this->form_validation->set_rules($this->config->item('user_password', 'form_validation'));
		$this->form_validation->set_rules('current_password', "lang:user_field_current_password", "required|is_current_password[{$username}]");

		if ($this->form_validation->run() == FALSE) {
			$this->notice('error', "Please check the form fields and try again.");
			return;
		}

		$new_password = $this->input->post('new_password_1');

		$user_data = [
			'password' => password_hash($new_password, PASSWORD_DEFAULT),
		];

		// Update
		$res = $this->users_model->update($user_data, ['user_id' => $id]);

		if ($res) {
			$this->notice('success', "Your password has been changed.");
			redirect('user/password');
		} else {
			$this->notice('error', "There was an error changing your password.");
		}
	}


	public function login()
	{
		if ($this->userauth->loggedin()) {
			redirect('user');
		}

		$this->data['title'] = lang('user_page_login_title');

		if ($this->input->post()) {

			$this->load->library('form_validation');
			$this->form_validation->set_rules('username', 'Username', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');

			if ($this->form_validation->run() !== FALSE) {
				// Validation passed.
				if ($this->userauth->trylogin($this->input->post('username'), $this->input->post('password'))) {
					$this->notice("success", lang('user_login_form_success'));
					$uri = '';
					if (isset($_SESSION['redirect_uri'])) {
						$uri = $_SESSION['redirect_uri'];
						unset($_SESSION['redirect_uri']);
					}
					redirect($uri);
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
		if ($this->input->method() !== 'post') {
			redirect('user');
		}

		$this->userauth->logout();
		redirect();
	}


	public function dismiss_tip()
	{
		$name = $this->input->post('tip');
		$this->tips->dismiss($name);
		$this->output->enable_profiler(FALSE);
		$this->output->set_header('X-IC-Remove: 1');
		$this->output->set_output('');
	}


}
