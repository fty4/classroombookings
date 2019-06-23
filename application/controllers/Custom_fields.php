<?php
defined('BASEPATH') OR exit('No direct script access allowed');


use app\components\Calendar;


class Custom_fields extends MY_Controller
{


	public function __construct()
	{
		parent::__construct();

		$this->load->language('admin');
		$this->load->language('fields');
		$this->load->language('field');

		$this->require_logged_in();
		$this->require_auth_level(ADMINISTRATOR);

		$this->load->model('fields_model');

		$this->load->helper('field');
		$this->load->helper('url');
	}



	/**
	 * Fields index page
	 *
	 */
	function index()
	{
		$this->init_form_elements();

		$this->data['menu_active'] = 'admin/fields';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));
		$this->data['breadcrumbs'][] = array('custom_fields', lang('fields_page_index'));

		$this->data['title'] = lang('fields_page_index');

		$filter = $this->input->get();
		$filter['sort'] = 'entity, position, title';
		$filter['limit'] = NULL;

		$this->data['filter'] = $filter;
		$this->data['total'] = $this->fields_model->count($filter);
		$this->data['fields'] = $this->fields_model->find($filter);

		$this->blocks['tabs'] = 'fields/menu';

		$this->render('fields/index');
	}


	/**
	 * Add a custom field
	 *
	 */
	function add()
	{
		$this->init_form_elements();

		$this->data['custom_field'] = NULL;

		$this->data['menu_active'] = 'admin/fields/add';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));
		$this->data['breadcrumbs'][] = array('custom_fields', lang('fields_page_index'));
		$this->data['breadcrumbs'][] = array('custom_fields/add', lang('fields_add_page_title'));

		$this->data['title'] = lang('fields_add_page_title');

		$this->blocks['tabs'] = 'fields/menu';

		if ($this->input->post()) {
			$this->save_field();
		}

		$this->render('fields/update');
	}


	/**
	 * Update a field
	 *
	 * @param int $id		ID of field to update
	 *
	 */
	public function update($field_id = 0)
	{
		$field = $this->find_field($field_id);

		$this->data['custom_field'] = $field;

		$this->data['menu_active'] = 'admin/fields/update';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));
		$this->data['breadcrumbs'][] = array('custom_fields', lang('fields_page_index'));
		$this->data['breadcrumbs'][] = array("custom_fields/update/{$field->field_id}", lang('fields_update_page_title'));

		$this->data['title'] = html_escape($field->title) . ': ' . lang('fields_update_page_title');

		$this->blocks['tabs'] = 'fields/context/menu';

		if ($this->input->post()) {
			$this->save_field($field);
		}

		$this->render('fields/update');
	}


	/**
	 * Save changes to period: update or add new
	 *
	 * @param $period		period object if updating, NULL to add new period.
	 *
	 */
	private function save_field($field = NULL)
	{
		$this->load->library('form_validation');
		$this->load->config('form_validation', TRUE);

		$rules = $this->config->item('fields_add_update', 'form_validation');
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == FALSE) {
			$this->notice('error', lang('error_form_validation'));
			return;
		}

		$keys = [
			'title',
			'entity',
			'type',
			'options',
			'required',
			'hint',
			'position',
		];

		$field_data = array_fill_safe($keys, $this->input->post());
		$success = FALSE;

		if ($field !== NULL) {

			// Can't change entity or class once created
			unset($field_data['type']);
			unset($field_data['entity']);

			// Update field
			$res = $this->fields_model->update($field_data, ['field_id' => $field->field_id]);
			$id = $field->field_id;

			if ($res) {
				$success = TRUE;
				$this->notice('success', lang('fields_update_status_success'));
			} else {
				$this->notice('error', lang('fields_update_status_error'));
			}

		} else {


			// Add new field
			$res = $this->fields_model->insert($field_data);
			$id = $res;

			if ($res) {
				$success = TRUE;
				$this->notice('success', lang('fields_add_status_success'));
			} else {
				$this->notice('error', lang('fields_add_status_error'));
			}
		}

		if ($success) {
			redirect('custom_fields');
		}
	}


	/**
	 * Delete field
	 *
	 * @param integer $field_id		ID of field to delete
	 *
	 */
	public function delete($field_id = 0)
	{
		$field = $this->find_field($field_id);

		$this->data['custom_field'] = $field;

		$this->data['menu_active'] = 'admin/fields/delete';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));
		$this->data['breadcrumbs'][] = array('custom_periods', lang('fields_page_index'));
		$this->data['breadcrumbs'][] = array('custom_periods/delete', lang('fields_delete_page_title'));

		$this->data['title'] = html_escape($field->title) . ': ' . lang('fields_delete_page_title');

		$this->blocks['tabs'] = 'fields/context/menu';

		if ($this->input->post('field_id') == $field->field_id && $this->input->post('action') == 'delete') {

			$res = $this->fields_model->delete(['field_id' => $field->field_id]);
			$success = FALSE;

			if ($res) {
				$this->notice('success', lang('fields_delete_status_success'), [
					'name' => $field->title,
				]);
			} else {
				$this->notice('error', lang('fields_delete_status_error'));
			}

			return redirect('custom_fields');
		}

		$this->render('fields/delete');
	}


	private function init_form_elements()
	{
		$this->data['entity_options'] = $this->fields_model->get_entities();
		$this->data['type_options'] = $this->fields_model->get_types();
	}


	private function find_field($id = 0, $include = [])
	{
		$default_inc = [];
		$all_inc = array_merge($default_inc, $include);

		$field = $this->fields_model->find_one([
			'field_id' => $id,
			'include' => $all_inc,
		]);

		if ( ! $field) {
			$this->render_error(array(
				'http' => 404,
				'title' => lang('not_found'),
				'description' => lang('fields_not_found'),
			));
		}

		return $field;
	}


}
