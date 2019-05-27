<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Users extends MY_Controller
{


	public function __construct()
	{
		parent::__construct();

		$this->load->language('settings');
		$this->load->language('users');
		$this->load->language('users_import');

		$this->require_logged_in();
		$this->require_auth_level(ADMINISTRATOR);

		$this->load->model('users_model');
		$this->load->model('departments_model');
		$this->load->helper('number');
		$this->load->helper('user');
		$this->load->helper('string');

		$this->data['max_size_bytes'] = max_upload_file_size();
		$this->data['max_size_human'] = byte_format(max_upload_file_size());
	}


	/**
	* Users index page
	*
	*/
	function index($page = 0)
	{
		// Cleanup import-related files if necessary
		cleanup_import();

		$this->data['menu_active'] = 'settings/users';
		$this->data['breadcrumbs'][] = array('settings', lang('settings_page_title'));
		$this->data['breadcrumbs'][] = array('users', lang('users_page_index'));

		$this->data['title'] = lang('users_page_index');

		if ( ! isset($_GET['limit'])) {
			$_GET['limit'] = 10;
		} else {
			$_GET['limit'] = (int) $_GET['limit'];
		}

		$filter = $this->input->get();
		$filter['sort'] = 'username';
		$filter['limit'] = 10;
		$filter['offset'] = $page;

		$this->data['filter'] = $filter;
		$this->data['total'] = $this->users_model->count($filter);
		$this->data['users'] = $this->users_model->find($filter);

		$pagination_config = [
			'base_url' => site_url('users/index'),
			'total_rows' => $this->data['total'],
			'per_page' => $filter['limit'],
			'reuse_query_string' => TRUE,
		];
		$this->load->library('pagination');
		$this->pagination->initialize(pagination_config($pagination_config));

		$this->blocks['tabs'] = 'users/menu';

		$this->render('users/index');
	}


	/**
	 * View summary of user account.
	 *
	 * @param integer $id		ID of user to view
	 *
	 */
	public function view($id = 0)
	{
		$user = $this->find_user($id);

		$this->data['user'] = $user;

		$this->data['menu_active'] = 'settings/users/view';
		$this->data['breadcrumbs'][] = array('settings', lang('settings_page_title'));
		$this->data['breadcrumbs'][] = array('users', lang('users_page_index'));
		$this->data['breadcrumbs'][] = array('users/view/' . $id, html_escape($user->username));

		$this->data['title'] = html_escape($user->username);

		$this->blocks['tabs'] = 'users/context/menu';

		$this->render('users/view');
	}


	/**
	 * Add a new user account.
	 *
	 */
	public function add()
	{
		$this->data['user'] = NULL;

		$this->data['menu_active'] = 'settings/users/add';
		$this->data['breadcrumbs'][] = array('settings', lang('settings_page_title'));
		$this->data['breadcrumbs'][] = array('users', lang('users_page_index'));
		$this->data['breadcrumbs'][] = array('users/add', lang('users_add_page_title'));

		$this->init_form_elements();

		$this->data['title'] = lang('users_add_page_title');

		$this->data['menu_active'] = 'settings/users/add';
		$this->blocks['tabs'] = 'users/menu';

		if ($this->input->post()) {
			$this->save_user();
		}

		$this->render('users/update');
	}


	/**
	 * Update a user account
	 *
	 * @param int $id		ID of user to update
	 *
	 */
	public function update($id = 0)
	{
		$user = $this->find_user($id);

		$this->data['user'] = $user;

		$this->data['menu_active'] = 'settings/users/update';
		$this->data['breadcrumbs'][] = array('settings', lang('settings_page_title'));
		$this->data['breadcrumbs'][] = array('users', lang('users_page_index'));
		$this->data['breadcrumbs'][] = array('users/view/' . $id, html_escape($user->username));
		$this->data['breadcrumbs'][] = array('users/update/' . $id, lang('users_update_page_title'));

		$this->init_form_elements();

		$this->data['title'] = html_escape($user->username) . ': ' . lang('users_update_page_title');

		$this->blocks['tabs'] = 'users/context/menu';

		if ($this->input->post()) {
			$this->save_user($user);
		}

		$this->render('users/update');
	}


	/**
	 * Change Password page for given user.
	 *
	 * @param  int $id		ID of user to change password for.
	 *
	 */
	public function change_password($id = 0)
	{
		$user = $this->find_user($id);

		$this->data['user'] = $user;

		$this->data['menu_active'] = 'settings/users/password';
		$this->data['breadcrumbs'][] = array('settings', lang('settings_page_title'));
		$this->data['breadcrumbs'][] = array('users', lang('users_page_index'));
		$this->data['breadcrumbs'][] = array('users/view/' . $id, html_escape($user->username));
		$this->data['breadcrumbs'][] = array('users/change_password/' . $id, lang('users_change_password_page_title'));

		$this->init_form_elements();

		$this->data['title'] = html_escape($user->username) . ': ' . lang('users_change_password_page_title');

		$this->blocks['tabs'] = 'users/context/menu';

		if ($this->input->post()) {
			$this->save_password($user);
		}

		$this->render('users/password');
	}


	/**
	 * Delete user account
	 *
	 * @param integer $id		ID of user to delete
	 *
	 */
	public function delete($id = 0)
	{
		$user = $this->find_user($id);

		$this->data['user'] = $user;

		$this->data['menu_active'] = 'settings/users/delete';
		$this->data['breadcrumbs'][] = array('settings', lang('settings_page_title'));
		$this->data['breadcrumbs'][] = array('users', lang('users_page_index'));
		$this->data['breadcrumbs'][] = array('users/view/' . $id, html_escape($user->username));
		$this->data['breadcrumbs'][] = array('users/delete/' . $id, lang('users_delete_page_title'));

		$this->init_form_elements();

		$this->data['title'] = html_escape($user->username) . ': ' . lang('users_delete_page_title');

		$this->blocks['tabs'] = 'users/context/menu';

		if ($this->input->post('user_id') == $user->user_id && $this->input->post('action') == 'delete') {

			$res = $this->users_model->delete(['user_id' => $user->user_id]);
			$success = FALSE;

			if ($res) {
				$this->notice('success', lang('users_delete_status_success'), [
					'username' => $user->username,
				]);
			} else {
				$this->notice('error', lang('users_delete_status_error'));
			}

			return redirect("users");
		}

		$this->render('users/delete');
	}


	/**
	 * Save changes to user: update or add new
	 *
	 * @param $user		User object if updating, NULL to add new user.
	 *
	 */
	private function save_user($user = NULL)
	{
		$this->load->library('form_validation');

		$this->load->config('form_validation', TRUE);
		$this->form_validation->set_rules($this->config->item('user_details', 'form_validation'));

		if ($user) {
			// Update
			$this->form_validation->set_rules('username', "lang:user_field_username", "required|trim|max_length[64]|user_username_unique[{$user->user_id}]");
			$this->form_validation->set_rules('email', "lang:user_field_email", "trim|max_length[255]|valid_email|user_email_unique[{$user->user_id}]");
		} else {
			// Add
			$this->form_validation->set_rules('username', "lang:user_field_username", "required|trim|max_length[64]|user_username_unique");
			$this->form_validation->set_rules('email', "lang:user_field_email", "trim|max_length[255]|valid_email|user_email_unique");
			$this->form_validation->set_rules('set_password_1', 'lang:user_field_set_password_1', 'required|trim|min_length[8]');
			$this->form_validation->set_rules('set_password_2', 'lang:user_field_set_password_2', 'matches[set_password_1]');
		}

		if ($this->form_validation->run() == FALSE) {
			$this->notice('error', lang('error_form_validation'));
			return;
		}

		$keys = [
			'username',
			'authlevel',
			'enabled',
			'email',
			'department_id',
			'firstname',
			'lastname',
			'displayname',
			'ext',
			'set_password_1',
		];

		$user_data = array_fill_safe($keys, $this->input->post());
		$success = FALSE;

		if ($user !== NULL) {

			// Update user
			$res = $this->users_model->update($user_data, ['user_id' => $user->user_id]);
			$id = $user->user_id;

			if ($res) {
				$success = TRUE;
				$this->notice('success', lang('users_update_status_success'));
			} else {
				$this->notice('error', lang('users_update_status_error'));
			}


		} else {

			// Add new user
			$res = $this->users_model->insert($user_data);
			$id = $res;

			if ($res) {

				$success = TRUE;

				$this->notice('success', lang('users_add_status_success'), [
					'username' => $user_data['username'],
					'password' => $user_data['set_password_1'],
				]);

			} else {
				$this->notice('error', lang('users_add_status_error'));
			}


		}

		if ($success) {
			redirect("users/view/{$id}");
		}
	}


	/**
	 * Save new password (Change password page)
	 *
	 * @param $user		User object to update
	 *
	 */
	private function save_password($user)
	{
		$this->load->library('form_validation');

		$this->load->config('form_validation', TRUE);
		$this->form_validation->set_rules($this->config->item('user_password', 'form_validation'));

		if ($this->form_validation->run() == FALSE) {
			$this->notice('error', lang('error_form_validation'));
			return;
		}

		$keys = [
			'new_password_1',
		];

		$user_data = array_fill_safe($keys, $this->input->post());
		$success = FALSE;

		// Update user
		$res = $this->users_model->update($user_data, ['user_id' => $user->user_id]);
		$id = $user->user_id;

		if ($res) {
			$success = TRUE;
			$this->notice('success', lang('users_change_password_status_success'), [
				'password' => $user_data['new_password_1'],
			]);
		} else {
			$this->notice('error', lang('users_change_password_status_error'));
		}

		if ($success) {
			redirect("users/view/{$id}");
		}
	}


	private function find_user($id = 0)
	{
		$user = $this->users_model->find_one([
			'user_id' => $id,
			'include' => ['department'],
		]);

		if ( ! $user) {
			$this->render_error(array(
				'http' => 404,
				'title' => 'Not found',
				'description' => lang('users_not_found'),
			));
		}

		return $user;
	}


	private function init_form_elements()
	{
		$this->data['authlevel_options'] = [
			ADMINISTRATOR => lang('user_authlevel_administrator'),
			TEACHER => lang('user_authlevel_teacher'),
		];

		$departments = $this->departments_model->find([
			'sort' => 'name',
			'limit' => NULL,
		]);

		$this->data['departments'] = results_dropdown('department_id', 'name', $departments, '(None)');
	}


}
