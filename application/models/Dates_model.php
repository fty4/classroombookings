<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Dates_model extends MY_Model
{


	public $table = 'dates';
	public $primary_key = 'date';


	public function __construct()
	{
		parent::__construct();
		$this->load->model('years_model');
		$this->load->model('holidays_model');
		$this->load->model('weeks_model');
	}


	/**
	 * Refresh the dates table with all dates in year (date_start -> date_end).
	 *
	 * If dates do not exist, they will be added.
	 * If they do, they will be updated.
	 * The given $year_id will be applied to each date entry.
	 *
	 */
	public function refresh_year($year_id)
	{
		$year = $this->years_model->find_one(['year_id' => $year_id]);
		if ( ! $year) {
			return FALSE;
		}

		$sql = "UPDATE {$this->table} SET year_id = NULL WHERE year_id = ?";
		$this->db->query($sql, $year->year_id);

		$start_date = new DateTime($year->date_start);
		$end_date = new DateTime($year->date_end);
		$end_date->modify('+1 day');
		$interval = new DateInterval('P1D');
		$period = new DatePeriod($start_date, $interval, $end_date);
		$rows = [];

		foreach ($period as $key => $value) {
			$date_value = $this->db->escape($value->format('Y-m-d'));
			$str = "({$date_value}, {$year->year_id})";
			$rows[] = $str;
		}

		$values = implode(',', $rows);

		$sql = "INSERT INTO {$this->table} (`date`, `year_id`)
				VALUES {$values}
				ON DUPLICATE KEY UPDATE
				`date` = VALUES(`date`), `year_id` = VALUES(`year_id`)";

		$this->db->query($sql);
	}


	/**
	 * Update the dates table with holiday IDs as appropriate.
	 *
	 * @param  int $year_id		ID of year to get holidays for
	 * @param  int $holiday_id		ID of single holiday to update. Optional.
	 *
	 * Each holiday:
	 * Update dates set holiday_id = NULL where holiday_id = holiday_id
	 *
	 * Get dates and update table with holiday_id
	 *
	 */
	public function refresh_holidays($year_id)
	{
		$year = $this->years_model->find_one(['year_id' => $year_id]);
		if ( ! $year) {
			return FALSE;
		}

		$sql = "UPDATE {$this->table} SET holiday_id = NULL WHERE year_id = ?";
		$this->db->query($sql, $year->year_id);

		$holidays = $this->holidays_model->find([
			'year_id' => $year_id,
			'limit' => NULL,
		]);

		if (empty($holidays)) {
			return FALSE;
		}

		$interval = new DateInterval('P1D');
		$rows = [];

		foreach ($holidays as $holiday) {

			$start_date = new DateTime($holiday->date_start);
			$end_date = new DateTime($holiday->date_end);
			$end_date->modify('+1 day');
			$period = new DatePeriod($start_date, $interval, $end_date);

			foreach ($period as $key => $value) {
				$date_value = $this->db->escape($value->format('Y-m-d'));
				$str = "({$date_value}, {$holiday->holiday_id})";
				$rows[] = $str;
			}
		}

		$values = implode(',', $rows);

		$sql = "INSERT INTO {$this->table} (`date`, `holiday_id`)
				VALUES {$values}
				ON DUPLICATE KEY UPDATE
				`date` = VALUES(`date`),
				`holiday_id` = VALUES(`holiday_id`)";

		$this->db->query($sql);
	}


	/**
	 * Take date <=> week_id associations and update table
	 *
	 */
	public function set_weeks()
	{

	}


}
