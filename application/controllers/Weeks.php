<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Weeks extends MY_Controller
{


	public function __construct()
	{
		parent::__construct();

		$this->load->language('admin');
		$this->load->language('settings');
		$this->load->language('weeks');
		$this->load->language('week');

		$this->require_logged_in();
		$this->require_auth_level(ADMINISTRATOR);

		$this->load->model('weeks_model');
		$this->load->helper('week');
	}



	/**
	 * Weeks index page
	 *
	 */
	function index()
	{
		$this->data['menu_active'] = 'admin/weeks';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));
		$this->data['breadcrumbs'][] = array('weeks', lang('weeks_page_index'));

		$this->data['title'] = lang('weeks_page_index');

		$filter = $this->input->get();
		$filter['sort'] = 'name';
		$filter['limit'] = NULL;

		$this->data['filter'] = $filter;
		$this->data['total'] = $this->weeks_model->count($filter);
		$this->data['weeks'] = $this->weeks_model->find($filter);

		$this->blocks['tabs'] = 'weeks/menu';

		$this->render('weeks/index');
	}


	/**
	 * Add a new timetable week
	 *
	 */
	public function add()
	{
		$this->data['week'] = NULL;

		$this->data['menu_active'] = 'admin/weeks/add';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));
		$this->data['breadcrumbs'][] = array('weeks', lang('weeks_page_index'));
		$this->data['breadcrumbs'][] = array('weeks/add', lang('weeks_add_page_title'));

		$this->data['title'] = lang('weeks_add_page_title');

		$this->data['menu_active'] = 'admin/weeks/add';
		$this->blocks['tabs'] = 'weeks/menu';

		if ($this->input->post()) {
			$this->save_week();
		}

		$this->render('weeks/update');
	}



	/**
	 * Update a timetable week
	 *
	 * @param int $id		ID of week to update
	 *
	 */
	public function update($id = 0)
	{
		$week = $this->find_week($id);

		$this->data['week'] = $week;

		$this->data['menu_active'] = 'admin/weeks/update';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));
		$this->data['breadcrumbs'][] = array('weeks', lang('weeks_page_index'));
		$this->data['breadcrumbs'][] = array('weeks/update/' . $id, lang('weeks_update_page_title'));

		$this->data['title'] = html_escape($week->name) . ': ' . lang('weeks_update_page_title');

		$this->blocks['tabs'] = 'weeks/context/menu';

		if ($this->input->post()) {
			$this->save_week($week);
		}

		$this->render('weeks/update');
	}


	/**
	 * Save changes to week: update or add new
	 *
	 * @param $week		Week object if updating, NULL to add new week.
	 *
	 */
	private function save_week($week = NULL)
	{
		$this->load->library('form_validation');

		$this->load->config('form_validation', TRUE);
		$this->form_validation->set_rules($this->config->item('weeks_add_update', 'form_validation'));

		if ($this->form_validation->run() == FALSE) {
			$this->notice('error', lang('error_form_validation'));
			return;
		}

		$keys = [
			'name',
			'colour',
		];

		$week_data = array_fill_safe($keys, $this->input->post());
		$success = FALSE;

		if ($week !== NULL) {

			// Update week
			$res = $this->weeks_model->update($week_data, ['week_id' => $week->week_id]);
			$id = $week->week_id;

			if ($res) {
				$success = TRUE;
				$this->notice('success', lang('weeks_update_status_success'));
			} else {
				$this->notice('error', lang('weeks_update_status_error'));
			}

		} else {

			// Add new week
			$res = $this->weeks_model->insert($week_data);
			$id = $res;

			if ($res) {

				$success = TRUE;

				$this->notice('success', lang('weeks_add_status_success'));

			} else {
				$this->notice('error', lang('weeks_add_status_error'));
			}


		}

		if ($success) {
			redirect("weeks");
		}
	}



	/**
	 * Delete timetable week
	 *
	 * @param integer $id		ID of user to delete
	 *
	 */
	public function delete($id = 0)
	{
		$week = $this->find_week($id);

		$this->data['week'] = $week;

		$this->data['menu_active'] = 'admin/weeks/delete';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));
		$this->data['breadcrumbs'][] = array('weeks', lang('weeks_page_index'));
		$this->data['breadcrumbs'][] = array('weeks/delete/' . $id, lang('weeks_delete_page_title'));

		$this->data['title'] = html_escape($week->name) . ': ' . lang('weeks_delete_page_title');

		$this->blocks['tabs'] = 'weeks/context/menu';

		if ($this->input->post('week_id') == $week->week_id && $this->input->post('action') == 'delete') {

			$res = $this->weeks_model->delete(['week_id' => $week->week_id]);
			$success = FALSE;

			if ($res) {
				$this->notice('success', lang('weeks_delete_status_success'), [
					'name' => $week->name,
				]);
			} else {
				$this->notice('error', lang('weeks_delete_status_error'));
			}

			return redirect("weeks");
		}

		$this->render('weeks/delete');
	}


	private function find_week($id = 0, $include = [])
	{
		$default_inc = [];
		$all_inc = array_merge($default_inc, $include);

		$week = $this->weeks_model->find_one([
			'week_id' => $id,
			'include' => $all_inc,
		]);

		if ( ! $week) {
			$this->render_error(array(
				'http' => 404,
				'title' => lang('not_found'),
				'description' => lang('weeks_not_found'),
			));
		}

		return $week;
	}




	 function _academicyear()
	 {
	 	$this->data['academicyear'] = $this->weeks_model->GetAcademicYear();

	 	if ( ! $this->data['academicyear'])
	 	{
	 		$this->data['academicyear'] = new Stdclass();
	 		$this->data['academicyear']->date_start = date("Y-m-d");
	 		$this->data['academicyear']->date_end = date("Y-m-d", strtotime("+1 Year", strtotime(date("Y-m-d"))));
	 	}

	 	$this->data['title'] = 'Academic Year';
	 	$this->data['showtitle'] = $this->data['title'];
	 	$this->data['body'] = $this->load->view('weeks/weeks_academicyear', $this->data, True);

	 	return $this->render();
	 }




	 function _saveacademicyear()
	 {
	 	$this->load->library('form_validation');

		$this->form_validation->set_rules('date_start', 'Start date', 'required|min_length[8]|max_length[10]');
		$this->form_validation->set_rules('date_end', 'End date', 'required|min_length[8]|max_length[10]');

		if ($this->form_validation->run() == FALSE) {
			return $this->academicyear();
		}

 		$start_date = explode('/', $this->input->post('date_start'));
 		$end_date = explode('/', $this->input->post('date_end'));

 		$year_data = array(
 			'date_start' => sprintf("%s-%s-%s", $start_date[2], $start_date[1], $start_date[0]),
 			'date_end' => sprintf("%s-%s-%s", $end_date[2], $end_date[1], $end_date[0]),
 		);

 		$this->weeks_model->SaveAcademicYear($year_data);

 		$this->session->set_flashdata('saved', msgbox('info', 'The Academic Year dates have been updated.'));

	 	redirect('weeks/academicyear');
	 }


}
