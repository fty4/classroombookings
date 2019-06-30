<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Holidays extends MY_Controller
{


	public function __construct()
	{
		parent::__construct();

		$this->load->language('admin');
		$this->load->language('years');
		$this->load->language('holidays');
		$this->load->language('holiday');

		$this->require_logged_in();
		$this->require_auth_level(ADMINISTRATOR);

		$this->load->model('years_model');
		$this->load->model('holidays_model');
		$this->load->model('dates_model');
		$this->load->helper('holiday');
	}



	/**
	 * Holidays index page for specific academic year
	 *
	 */
	function year($year_id = 0)
	{
		$this->data['menu_active'] = 'admin/holidays/year';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));
		$this->data['breadcrumbs'][] = array('academic_years', lang('years_page_index'));

		$year = $this->find_year($year_id);

		$this->data['year'] = $year;
		$this->data['title'] = lang('holidays_page_year');
		$this->data['breadcrumbs'][] = array("academic_years/view/{$year->year_id}", html_escape($year->name));
		$this->data['breadcrumbs'][] = array("holidays/year/{$year_id}", lang('holidays_page_year'));

		$filter = $this->input->get();
		$filter['sort'] = 'date_start';
		$filter['limit'] = NULL;
		$filter['year_id'] = $year->year_id;

		$this->data['filter'] = $filter;
		$this->data['total'] = $this->holidays_model->count($filter);
		$this->data['holidays'] = $this->holidays_model->find($filter);

		$this->blocks['tabs'] = 'holidays/menu';

		$this->render('holidays/year');
	}


	/**
	 * Add a holiday
	 *
	 */
	function add($year_id = 0)
	{
		$this->data['menu_active'] = 'admin/holidays/add';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));
		$this->data['breadcrumbs'][] = array('academic_years', lang('years_page_index'));

		$year = $this->find_year($year_id);

		$this->data['year'] = $year;
		$this->data['title'] = lang('holidays_add_page_title');

		$this->data['breadcrumbs'][] = array("academic_years/view/{$year->year_id}", html_escape($year->name));
		$this->data['breadcrumbs'][] = array("holidays/year/{$year->year_id}", lang('holidays_page_year'));
		$this->data['breadcrumbs'][] = array("holidays/add/{$year->year_id}", lang('holidays_add_page_title'));

		$this->data['holiday'] = NULL;

		$this->blocks['tabs'] = 'holidays/menu';

		if ($this->input->post()) {
			$this->save_holiday();
		}

		$this->render('holidays/update');
	}


	/**
	 * Update a holiday
	 *
	 * @param int $id		ID of holiday to update
	 *
	 */
	public function update($holiday_id = 0)
	{
		$this->data['menu_active'] = 'admin/holidays/update';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));
		$this->data['breadcrumbs'][] = array('academic_years', lang('years_page_index'));

		$holiday = $this->find_holiday($holiday_id);
		$year = $this->find_year($holiday->year_id);

		$this->data['holiday'] = $holiday;
		$this->data['year'] = $year;

		$this->data['title'] = html_escape($holiday->name) . ': ' . lang('holidays_update_page_title');
		$this->data['breadcrumbs'][] = array("academic_years/view/{$year->year_id}", html_escape($year->name));
		$this->data['breadcrumbs'][] = array("holidays/year/{$year->year_id}", lang('holidays_page_year'));
		$this->data['breadcrumbs'][] = array("holidays/update/{$holiday->holiday_id}", lang('holidays_update_page_title'));

		$this->blocks['tabs'] = 'holidays/context/menu';

		if ($this->input->post()) {
			$this->save_holiday($holiday);
		}

		$this->render('holidays/update');
	}


	/**
	 * Save changes to holiday: update or add new
	 *
	 * @param $holiday		holiday object if updating, NULL to add new holiday.
	 *
	 */
	private function save_holiday($holiday = NULL)
	{
		$year_start = $this->data['year']->date_start;
		$year_end = $this->data['year']->date_end;

		$this->load->library('form_validation');
		$this->load->config('form_validation', TRUE);

		$rules = $this->config->item('holidays_add_update', 'form_validation');

		$rules[] = [
			'field' => 'date_start',
			'label' => 'lang:holiday_field_date_start',
			'rules' => "trim|required|max_length[10]|date_after[$year_start]|date_before[$year_end]",
		];

		$rules[] = [
			'field' => 'date_end',
			'label' => 'lang:holiday_field_date_end',
			'rules' => "trim|required|max_length[10]|date_after[$year_start]|date_before[$year_end]|date_after[date_start]",
		];

		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == FALSE) {
			$this->notice('error', lang('error_form_validation'));
			return;
		}

		$keys = [
			'name',
			'date_start',
			'date_end',
		];

		$holiday_data = array_fill_safe($keys, $this->input->post());
		$success = FALSE;

		// Add year ID from the already-set var
		$holiday_data['year_id'] = $this->data['year']->year_id;

		if ($holiday !== NULL) {

			// Update holiday
			$res = $this->holidays_model->update($holiday_data, ['holiday_id' => $holiday->holiday_id]);
			$id = $holiday->holiday_id;

			if ($res) {
				$success = TRUE;
				$this->notice('success', lang('holidays_update_status_success'));
			} else {
				$this->notice('error', lang('holidays_update_status_error'));
			}

		} else {

			// Add new holiday
			$res = $this->holidays_model->insert($holiday_data);
			$id = $res;

			if ($res) {

				$success = TRUE;
				$this->notice('success', lang('holidays_add_status_success'));

			} else {
				$this->notice('error', lang('holidays_add_status_error'));
			}

		}

		if ($success) {
			$this->dates_model->refresh_holidays($this->data['year']->year_id);
			redirect("holidays/year/{$this->data['year']->year_id}");
		}
	}


	/**
	 * Delete holiday
	 *
	 * @param integer $holiday_id		ID of holiday to delete
	 *
	 */
	public function delete($holiday_id = 0)
	{
		$this->data['menu_active'] = 'admin/holidays/delete';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));
		$this->data['breadcrumbs'][] = array('academic_years', lang('years_page_index'));

		$holiday = $this->find_holiday($holiday_id);
		$year = $this->find_year($holiday->year_id);

		$this->data['holiday'] = $holiday;
		$this->data['year'] = $year;
		$this->data['title'] = html_escape($holiday->name) . ': ' . lang('holidays_delete_page_title');

		$this->data['breadcrumbs'][] = array("academic_years/view/{$year->year_id}", html_escape($year->name));
		$this->data['breadcrumbs'][] = array("holidays/year/{$year->year_id}", lang('holidays_page_year'));
		$this->data['breadcrumbs'][] = array("holidays/delete/{$year->year_id}", lang('holidays_delete_page_title'));

		$this->blocks['tabs'] = 'holidays/context/menu';

		if ($this->input->post('holiday_id') == $holiday->holiday_id && $this->input->post('action') == 'delete') {

			$res = $this->holidays_model->delete(['holiday_id' => $holiday->holiday_id]);
			$success = FALSE;

			if ($res) {
				$this->dates_model->refresh_holidays($year->year_id);
				$this->notice('success', lang('holidays_delete_status_success'), [
					'name' => $holiday->name,
				]);
			} else {
				$this->notice('error', lang('holidays_delete_status_error'));
			}

			return redirect("holidays/year/{$year->year_id}");
		}

		$this->render('holidays/delete');
	}


	private function find_holiday($id = 0, $include = [])
	{
		$default_inc = [];
		$all_inc = array_merge($default_inc, $include);

		$holiday = $this->holidays_model->find_one([
			'holiday_id' => $id,
			'include' => $all_inc,
		]);

		if ( ! $holiday) {
			$this->render_error(array(
				'http' => 404,
				'title' => lang('not_found'),
				'description' => lang('holidays_not_found'),
			));
		}

		return $holiday;
	}


	private function find_year($id = 0, $include = [])
	{
		$default_inc = [];
		$all_inc = array_merge($default_inc, $include);

		$year = $this->years_model->find_one([
			'year_id' => $id,
			'include' => $all_inc,
		]);

		if ( ! $year) {
			$this->render_error(array(
				'http' => 404,
				'title' => lang('not_found'),
				'description' => lang('years_not_found'),
			));
		}

		return $year;
	}


}
