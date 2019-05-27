<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Users_import extends MY_Controller
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
		$this->load->helper('json');

		$this->data['max_size_bytes'] = max_upload_file_size();
		$this->data['max_size_human'] = byte_format(max_upload_file_size());
	}


	/**
	* User Import start page
	*
	*/
	public function index()
	{
		$this->data['menu_active'] = 'settings/users/import';
		$this->data['breadcrumbs'][] = array('settings', lang('settings_page_title'));
		$this->data['breadcrumbs'][] = array('users', lang('users_page_index'));
		$this->data['breadcrumbs'][] = array('users_import', lang('users_import_page_index'));

		$this->data['title'] = lang('users_import_page_index');

		$this->init_form_elements();

		$this->blocks['tabs'] = 'users/menu';

		if ($this->input->post()) {
			$this->process_import();
		}

		cleanup_import();

		$this->render('users/import/index');
	}


	/**
	 * Show the results of the import.
	 *
	 * The results are stored in a temporary file, the filename
	 * of which is stored in the session.
	 *
	 */
	public function results()
	{
		if ( ! array_key_exists('import_results', $_SESSION)) {
			$this->notice('error', lang('users_import_results_error') . ' (DATA)');
			return redirect('users_import');
		}

		$filename = FCPATH . "local/{$_SESSION['import_results']}";
		if ( ! is_file($filename)) {
			$this->notice('error', lang('users_import_results_error') . ' (FILE)');
			return redirect('users_import');
		}

		$raw = @file_get_contents($filename);
		$result = json_decode_safe($raw, FALSE);

		$this->data['result'] = $result;

		$this->data['menu_active'] = 'settings/users/import';
		$this->data['breadcrumbs'][] = array('settings', lang('settings_page_title'));
		$this->data['breadcrumbs'][] = array('users', lang('users_page_index'));
		$this->data['breadcrumbs'][] = array('users_import', lang('users_import_page_index'));
		$this->data['breadcrumbs'][] = array('users_import/results', lang('users_import_page_results'));

		$this->data['title'] = lang('users_import_page_results');

		$this->blocks['tabs'] = 'users/menu';

		$this->render('users/import/results');
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


	/**
	 * Handle the uploaded CSV file, process it for users, and add them.
	 *
	 */
	private function process_import()
	{
		$has_csv = (isset($_FILES['userfile'])
			&& isset($_FILES['userfile']['name'])
			&& ! empty($_FILES['userfile']['name']));

		if ( ! $has_csv) {
			$this->notice('error', lang('users_import_no_file'));
			return;
		}

		$this->load->helper('file');
		$this->load->helper('string');

		$upload_config = array(
			'upload_path' => FCPATH . 'local',
			'allowed_types' => 'csv',
			'max_size' => $this->data['max_size_bytes'],
			'encrypt_name' => TRUE,
		);

		$this->load->library('upload', $upload_config);

		// Default values supplied in form
		$defaults = array(
			'password' => $this->input->post('password'),
			'authlevel' => $this->input->post('authlevel'),
			'enabled' => $this->input->post('enabled'),
		);

		if ( ! $this->upload->do_upload()) {
			$error = $this->upload->display_errors('','');
			$this->notice('error', $error);
			return;
		}

		$data = $this->upload->data();

		$file_path = $data['full_path'];
		$results = array();
		$handle = fopen($file_path, 'r');
		$line = 0;

		// Parse CSV file
		while (($row = fgetcsv($handle, filesize($file_path), ',')) !== FALSE) {

			if ($row[0] == 'username') {
				$line++;
				continue;
			}

			$user = array(
				'username' => trim($row[0]),
				'firstname' => trim($row[1]),
				'lastname' => trim($row[2]),
				'email' => trim($row[3]),
				'password' => trim($row[4]),
				'authlevel' => $defaults['authlevel'],
				'enabled' => $defaults['enabled'],
				'department_id' => NULL,
				'ext' => NULL,
				'displayname' => trim("{$row[1]} {$row[2]}"),
			);

			if (empty($user['password'])) {
				$user['password'] = $defaults['password'];
			}

			$id = $this->add_user($user);
			$status = (is_numeric($id) ? 'success' : $id);

			$results[] = array(
				'line' => $line,
				'status' => $status,
				'id' => $id,
				'user' => $user,
			);

			$line++;

		}

		// Finish with CSV
		fclose($handle);
		@unlink($file_path);

		// Write results to temp file
		$data = json_encode($results);
		$res_filename = ".".random_string('alnum', 25);
		write_file(FCPATH . "local/{$res_filename}", $data);

		// Reference the file in the session for the next page to retrieve.
		$_SESSION['import_results'] = $res_filename;

		return redirect('users_import/results');
	}


	/**
	 * Add a user row from the imported CSV file
	 *
	 * @return  mixed		ID on success, string for reason if error.
	 *
	 */
	private function add_user($data = [])
	{
		if (empty($data['username'])) {
			return 'username_empty';
		}

		if (empty($data['password'])) {
			return 'password_empty';
		}

		$user_count = $this->users_model->count([
			'username' => $data['username'],
		]);

		if ($user_count > 0) {
			return 'username_exists';
		}

		$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

		$res = $this->users_model->insert($data);
		if ($res) {
			return $res;
		}

		return 'db_error';
	}


}
