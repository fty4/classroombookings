<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Departments extends MY_Controller
{


	public function __construct()
	{
		parent::__construct();

		$this->load->language('admin');
		$this->load->language('departments');
		$this->load->language('department');

		$this->require_logged_in();
		$this->require_auth_level(ADMINISTRATOR);

		$this->load->model('departments_model');
		$this->load->config('lookups');
		$this->load->helper('department');
	}


	/**
	 * Departments index page
	 *
	 */
	function index()
	{
		$this->data['menu_active'] = 'admin/departments';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));
		$this->data['breadcrumbs'][] = array('departments', lang('departments_page_index'));

		$this->data['title'] = lang('departments_page_index');

		$filter = $this->input->get();
		$filter['sort'] = 'name';
		$filter['limit'] = NULL;

		$this->data['filter'] = $filter;
		$this->data['total'] = $this->departments_model->count($filter);
		$this->data['departments'] = $this->departments_model->find($filter);

		$this->blocks['tabs'] = 'departments/menu';

		$this->render('departments/index');
	}


	/**
	 * Update a department
	 *
	 * @param int $id		ID of department to update
	 *
	 */
	public function update($id = 0)
	{
		$this->data['menu_active'] = 'admin/departments/update';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));
		$this->data['breadcrumbs'][] = array('departments', lang('departments_page_index'));

		$department = $this->find_department($id);

		$this->init_form_elements();

		$this->data['department'] = $department;
		$this->data['title'] = $department->name . ': ' . lang('departments_update_page_title');
		$this->data['breadcrumbs'][] = array('departments/update/' . $department->department_id, lang('departments_update_page_title'));

		$this->blocks['tabs'] = 'departments/context/menu';

		if ($this->input->post()) {
			$this->save_department($department);
		}

		$this->render('departments/update');
	}


	/**
	 * Add a new department
	 *
	 */
	public function add()
	{
		$this->data['department'] = NULL;

		$this->init_form_elements();

		$this->data['menu_active'] = 'admin/departments/add';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));
		$this->data['breadcrumbs'][] = array('departments', lang('departments_page_index'));
		$this->data['breadcrumbs'][] = array('departments/add', lang('departments_add_page_title'));

		$this->data['title'] = lang('departments_add_page_title');

		$this->data['menu_active'] = 'admin/departments/add';
		$this->blocks['tabs'] = 'departments/menu';

		if ($this->input->post()) {
			$this->save_department();
		}

		$this->render('departments/update');
	}


	/**
	 * Save changes to department: update or add new
	 *
	 * @param $department		department object if updating, NULL to add new department.
	 *
	 */
	private function save_department($department = NULL)
	{
		$this->load->library('form_validation');

		$this->load->config('form_validation', TRUE);
		$this->form_validation->set_rules($this->config->item('departments_add_update', 'form_validation'));

		if ($this->form_validation->run() == FALSE) {
			$this->notice('error', lang('error_form_validation'));
			return;
		}

		$keys = [
			'name',
			'description',
			'colour',
			'icon',
		];

		$department_data = array_fill_safe($keys, $this->input->post());
		$success = FALSE;

		if ($department !== NULL) {

			// Update department
			$res = $this->departments_model->update($department_data, ['department_id' => $department->department_id]);
			$id = $department->department_id;

			if ($res) {
				$success = TRUE;
				$this->notice('success', lang('departments_update_status_success'));
			} else {
				$this->notice('error', lang('departments_update_status_error'));
			}

		} else {

			// Add new department
			$res = $this->departments_model->insert($department_data);
			$id = $res;

			if ($res) {

				$success = TRUE;

				$this->notice('success', lang('departments_add_status_success'));

			} else {
				$this->notice('error', lang('departments_add_status_error'));
			}

		}

		if ($success) {
			redirect("departments");
		}
	}


	private function init_form_elements()
	{
		$this->data['icons'] = $this->config->item('department_icons', 'lookups');
	}


	/**
	 * Delete department
	 *
	 * @param integer $id		ID of department to delete
	 *
	 */
	public function delete($id = 0)
	{
		$this->data['menu_active'] = 'admin/departments/delete';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));
		$this->data['breadcrumbs'][] = array('departments', lang('departments_page_index'));

		$department = $this->find_department($id);

		$this->data['department'] = $department;
		$this->data['title'] = html_escape($department->name) . ': ' . lang('departments_delete_page_title');
		$this->data['breadcrumbs'][] = array("departments/delete/{$department->department_id}", lang('departments_delete_page_title'));

		$this->blocks['tabs'] = 'departments/context/menu';

		if ($this->input->post('department_id') == $department->department_id && $this->input->post('action') == 'delete') {

			$res = $this->departments_model->delete(['department_id' => $department->department_id]);
			$success = FALSE;

			if ($res) {
				$this->notice('success', lang('departments_delete_status_success'), [
					'name' => $department->name,
				]);
			} else {
				$this->notice('error', lang('departments_delete_status_error'));
			}

			return redirect("departments");
		}

		$this->render('departments/delete');
	}



	private function find_department($id = 0, $include = [])
	{
		$default_inc = [];
		$all_inc = array_merge($default_inc, $include);

		$department = $this->departments_model->find_one([
			'department_id' => $id,
			'include' => $all_inc,
		]);

		if ( ! $department) {
			$this->render_error(array(
				'http' => 404,
				'title' => lang('not_found'),
				'description' => lang('departments_not_found'),
			));
		}

		return $department;
	}


}
