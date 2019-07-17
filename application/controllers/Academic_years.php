<?php
defined('BASEPATH') OR exit('No direct script access allowed');


use app\components\Calendar;


class Academic_years extends MY_Controller
{


	public function __construct()
	{
		parent::__construct();

		$this->load->language('admin');
		$this->load->language('years');
		$this->load->language('year');
		$this->load->language('holidays');

		$this->require_logged_in();
		$this->require_auth_level(ADMINISTRATOR);

		$this->load->model('years_model');
		$this->load->model('weeks_model');
		$this->load->model('dates_model');
		$this->load->helper('year');
		$this->load->helper('week');
		$this->load->helper('colour');
		$this->load->helper('json');
	}



	/**
	 * Years index page
	 *
	 */
	function index()
	{
		$this->data['menu_active'] = 'admin/years';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));
		$this->data['breadcrumbs'][] = array('academic_years', lang('years_page_index'));

		$this->data['title'] = lang('years_page_index');

		$filter = $this->input->get();
		$filter['sort'] = '-date_start';
		$filter['limit'] = NULL;

		$this->data['filter'] = $filter;
		$this->data['total'] = $this->years_model->count($filter);
		$this->data['years'] = $this->years_model->find($filter);

		$this->blocks['tabs'] = 'years/menu';

		$this->render('years/index');
	}


	/**
	 * View an academic year
	 *
	 * @param int $id		ID of year to view
	 *
	 */
	public function view($id = 0)
	{
		$this->data['menu_active'] = 'admin/years/view';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));
		$this->data['breadcrumbs'][] = array('academic_years', lang('years_page_index'));

		$year = $this->find_year($id, ['dates', 'holidays']);

		$this->data['year'] = $year;
		$this->data['title'] = html_escape($year->name) . ': ' . lang('years_update_page_title');
		$this->data['breadcrumbs'][] = array("academic_years/view/{$year->year_id}", html_escape($year->name));

		$this->blocks['tabs'] = 'years/context/menu';

		$weeks = $this->weeks_model->find(['limit' => NULL]);
		$this->data['weeks'] = $weeks;

		$calendar = new Calendar([
			'year' => $year,
			'weeks' => $weeks,
			'class' => 'calendar-academic-year',
			'inputs' => TRUE,
		]);

		$this->data['calendar'] = $calendar;

		if ($this->input->post()) {
			$this->save_view($year);
		}

		$this->render('years/view');
	}


	private function save_view($year)
	{
		$values = $this->input->post('dates');

		if (empty($values)) {
			$this->notice('error', lang('error_form_validation'));
			return;
		}

		if ($this->dates_model->set_weeks($year->year_id, $values) !== FALSE) {
				$this->notice('success', lang('years_set_weeks_status_success'));
		} else {
			$this->notice('error', lang('years_set_weeks_status_error'));
		}

		redirect("academic_years/view/{$year->year_id}");
	}


	/**
	 * Add a new academic year
	 *
	 */
	public function add()
	{
		$this->data['year'] = NULL;

		$this->data['menu_active'] = 'admin/years/add';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));
		$this->data['breadcrumbs'][] = array('academic_years', lang('years_page_index'));
		$this->data['breadcrumbs'][] = array('academic_years/add', lang('years_add_page_title'));

		$this->data['title'] = lang('years_add_page_title');

		$this->data['menu_active'] = 'admin/years/add';
		$this->blocks['tabs'] = 'years/menu';

		if ($this->input->post()) {
			$this->save_year();
		}

		$this->render('years/update');
	}



	/**
	 * Update an academic year
	 *
	 * @param int $id		ID of year to update
	 *
	 */
	public function update($id = 0)
	{
		$this->data['menu_active'] = 'admin/years/update';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));
		$this->data['breadcrumbs'][] = array('academic_years', lang('years_page_index'));

		$year = $this->find_year($id);

		$this->data['year'] = $year;
		$this->data['title'] = html_escape($year->name) . ': ' . lang('years_update_page_title');

		$this->data['breadcrumbs'][] = array('academic_years/view/' . $id, html_escape($year->name));
		$this->data['breadcrumbs'][] = array('academic_years/update/' . $id, lang('years_update_page_title'));

		$this->blocks['tabs'] = 'years/context/menu';

		if ($this->input->post()) {
			$this->save_year($year);
		}

		$this->render('years/update');
	}


	/**
	 * Save changes to year: update or add new
	 *
	 * @param $year		year object if updating, NULL to add new year.
	 *
	 */
	private function save_year($year = NULL)
	{
		$this->load->library('form_validation');

		$this->load->config('form_validation', TRUE);
		$this->form_validation->set_rules($this->config->item('years_add_update', 'form_validation'));

		if ($this->form_validation->run() == FALSE) {
			$this->notice('error', lang('error_form_validation'));
			return;
		}

		$keys = [
			'name',
			'date_start',
			'date_end',
		];

		$year_data = array_fill_safe($keys, $this->input->post());
		$success = FALSE;

		if ($year !== NULL) {

			// Update year
			$res = $this->years_model->update($year_data, ['year_id' => $year->year_id]);
			$id = $year->year_id;

			if ($res) {
				$success = TRUE;
				$this->notice('success', lang('years_update_status_success'));
			} else {
				$this->notice('error', lang('years_update_status_error'));
			}

		} else {

			// Add new year
			$res = $this->years_model->insert($year_data);
			$id = $res;

			if ($res) {

				$success = TRUE;

				$this->notice('success', lang('years_add_status_success'));

			} else {
				$this->notice('error', lang('years_add_status_error'));
			}

		}

		if ($success) {
			$this->dates_model->refresh_year($id);
			$this->dates_model->refresh_holidays($id);
			redirect("academic_years/view/{$id}");
		}
	}



	/**
	 * Delete academic year
	 *
	 * @param integer $id		ID of year to delete
	 *
	 */
	public function delete($id = 0)
	{
		$this->data['menu_active'] = 'admin/years/delete';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));
		$this->data['breadcrumbs'][] = array('academic_years', lang('years_page_index'));

		$year = $this->find_year($id);

		$this->data['year'] = $year;
		$this->data['title'] = html_escape($year->name) . ': ' . lang('years_delete_page_title');

		$this->data['breadcrumbs'][] = array("academic_years/view/{$year->year_id}", html_escape($year->name));
		$this->data['breadcrumbs'][] = array("academic_years/delete/{$year->year_id}", lang('years_delete_page_title'));


		$this->blocks['tabs'] = 'years/context/menu';

		if ($this->input->post('year_id') == $year->year_id && $this->input->post('action') == 'delete') {

			$res = $this->years_model->delete(['year_id' => $year->year_id]);
			$success = FALSE;

			if ($res) {
				$this->notice('success', lang('years_delete_status_success'), [
					'name' => $year->name,
				]);
			} else {
				$this->notice('error', lang('years_delete_status_error'));
			}

			return redirect("academic_years");
		}

		$this->render('years/delete');
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
