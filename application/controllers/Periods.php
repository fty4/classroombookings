<?php
defined('BASEPATH') OR exit('No direct script access allowed');


use app\components\Calendar;


class Periods extends MY_Controller
{


	public function __construct()
	{
		parent::__construct();

		$this->load->language('admin');
		$this->load->language('periods');
		$this->load->language('period');
		$this->load->language('dates');

		$this->require_logged_in();
		$this->require_auth_level(ADMINISTRATOR);

		$this->load->model('periods_model');
		$this->load->helper('period');

		$this->data['days'] = Calendar::get_days_of_week();
	}



	/**
	 * Periods index page
	 *
	 */
	function index()
	{
		$this->data['menu_active'] = 'admin/periods';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));
		$this->data['breadcrumbs'][] = array('periods', lang('periods_page_index'));

		$this->data['title'] = lang('periods_page_index');

		$filter = $this->input->get();
		$filter['sort'] = '-day_1, -day_2, -day_3, -day_4, -day_5, -day_6, -day_7, time_start';
		$filter['limit'] = NULL;

		$this->data['filter'] = $filter;
		$this->data['total'] = $this->periods_model->count($filter);
		$this->data['periods'] = $this->periods_model->find($filter);

		$this->blocks['tabs'] = 'periods/menu';

		$this->render('periods/index');
	}


	/**
	 * Add a period
	 *
	 */
	function add()
	{
		$this->data['period'] = NULL;

		$this->data['menu_active'] = 'admin/periods/add';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));
		$this->data['breadcrumbs'][] = array('periods', lang('periods_page_index'));
		$this->data['breadcrumbs'][] = array('periods/add', lang('periods_add_page_title'));

		$this->data['title'] = lang('periods_add_page_title');

		$this->blocks['tabs'] = 'periods/menu';

		if ($this->input->post()) {
			$this->save_period();
		}

		$this->render('periods/update');
	}


	/**
	 * Update a period
	 *
	 * @param int $id		ID of period to update
	 *
	 */
	public function update($period_id = 0)
	{
		$period = $this->find_period($period_id);

		$this->data['period'] = $period;

		$this->data['menu_active'] = 'admin/periods/update';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));
		$this->data['breadcrumbs'][] = array('periods', lang('periods_page_index'));
		$this->data['breadcrumbs'][] = array("periods/update/{$period->period_id}", lang('periods_update_page_title'));

		$this->data['title'] = html_escape($period->name) . ': ' . lang('periods_update_page_title');

		$this->blocks['tabs'] = 'periods/context/menu';

		if ($this->input->post()) {
			$this->save_period($period);
		}

		$this->render('periods/update');
	}


	/**
	 * Save changes to period: update or add new
	 *
	 * @param $period		period object if updating, NULL to add new period.
	 *
	 */
	private function save_period($period = NULL)
	{
		$this->load->library('form_validation');
		$this->load->config('form_validation', TRUE);

		$rules = $this->config->item('periods_add_update', 'form_validation');
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == FALSE) {
			$this->notice('error', lang('error_form_validation'));
			return;
		}

		$keys = [
			'name',
			'time_start',
			'time_end',
			'bookable',
			'day_1',
			'day_2',
			'day_3',
			'day_4',
			'day_5',
			'day_6',
			'day_7',
		];

		$period_data = array_fill_safe($keys, $this->input->post());
		$success = FALSE;

		if ($period !== NULL) {

			// Update period
			$res = $this->periods_model->update($period_data, ['period_id' => $period->period_id]);
			$id = $period->period_id;

			if ($res) {
				$success = TRUE;
				$this->notice('success', lang('periods_update_status_success'));
			} else {
				$this->notice('error', lang('periods_update_status_error'));
			}

		} else {

			// Add new period
			$res = $this->periods_model->insert($period_data);
			$id = $res;

			if ($res) {
				$success = TRUE;
				$this->notice('success', lang('periods_add_status_success'));
			} else {
				$this->notice('error', lang('periods_add_status_error'));
			}

		}

		if ($success) {
			redirect('periods');
		}
	}


	/**
	 * Delete period
	 *
	 * @param integer $period_id		ID of period to delete
	 *
	 */
	public function delete($period_id = 0)
	{
		$period = $this->find_period($period_id);

		$this->data['period'] = $period;

		$this->data['menu_active'] = 'admin/periods/delete';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));
		$this->data['breadcrumbs'][] = array('periods', lang('periods_page_index'));
		$this->data['breadcrumbs'][] = array('periods/delete', lang('periods_delete_page_title'));

		$this->data['title'] = html_escape($period->name) . ': ' . lang('periods_delete_page_title');

		$this->blocks['tabs'] = 'periods/context/menu';

		if ($this->input->post('period_id') == $period->period_id && $this->input->post('action') == 'delete') {

			$res = $this->periods_model->delete(['period_id' => $period->period_id]);
			$success = FALSE;

			if ($res) {
				$this->notice('success', lang('periods_delete_status_success'), [
					'name' => $period->name,
				]);
			} else {
				$this->notice('error', lang('periods_delete_status_error'));
			}

			return redirect('periods');
		}

		$this->render('periods/delete');
	}


	private function find_period($id = 0, $include = [])
	{
		$default_inc = [];
		$all_inc = array_merge($default_inc, $include);

		$period = $this->periods_model->find_one([
			'period_id' => $id,
			'include' => $all_inc,
		]);

		if ( ! $period) {
			$this->render_error(array(
				'http' => 404,
				'title' => lang('not_found'),
				'description' => lang('periods_not_found'),
			));
		}

		return $period;
	}


}
