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

		// $this->migrate_weekdates();
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
			$weekday = $value->format('N');
			$str = "({$date_value}, {$weekday}, {$year->year_id})";
			$rows[] = $str;
		}

		$values = implode(',', $rows);

		$sql = "INSERT INTO {$this->table}
				(`date`, `weekday`, `year_id`)
				VALUES {$values}
				ON DUPLICATE KEY UPDATE
				`date` = VALUES(`date`),
				`weekday` = VALUES(`weekday`),
				`year_id` = VALUES(`year_id`)";

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
		$dates = [];

		foreach ($holidays as $holiday) {

			$start_date = new DateTime($holiday->date_start);
			$end_date = new DateTime($holiday->date_end);
			$end_date->modify('+1 day');
			$period = new DatePeriod($start_date, $interval, $end_date);

			foreach ($period as $key => $value) {
				$dates[] = [
					'date' => $value->format('Y-m-d'),
					'holiday_id' => $holiday->holiday_id,
				];
			}
		}

		return $this->db->update_batch($this->table, $dates, 'date');
	}


	/**
	 * Take date <=> week_id associations and update table
	 *
	 * @param  int $year_id		ID of year to update date week assignments for.
	 * @param  array $data		2D array of date => week_id
	 *
	 */
	public function set_weeks($year_id, $data = [])
	{
		$year = $this->years_model->find_one(['year_id' => $year_id]);
		if ( ! $year) {
			return FALSE;
		}

		$year_start = new DateTime($year->date_start);
		$year_end = new DateTime($year->date_end);

		// Clear existing assignments for all dates in the year
		$sql = "UPDATE {$this->table} SET week_id = NULL WHERE year_id = ?";
		$this->db->query($sql, $year->year_id);

		foreach ($data as $date => $week_id) {
			$dt = new DateTime($date);

			// Skip date if it is outside of the year
			if ($dt < $year_start || $dt > $year_end) {
				continue;
			}

			$dates[] = [
				'date' => $date,
				'week_id' => strlen($week_id) ? (int) $week_id : NULL,
			];

		}

		return $this->db->update_batch($this->table, $dates, 'date');
	}


	/**
	 * Migrate from legacy weekdates table into new dates table
	 *
	 */
	public function migrate_weekdates()
	{
		$sql = 'SELECT * FROM weekdates';
		$query = $this->db->query($sql);
		if ($query->num_rows() == 0) {
			return;
		}
		$weekdates = $query->result();

		$sql = 'SELECT week_id FROM weeks';
		$query = $this->db->query($sql);
		if ($query->num_rows() == 0) {
			return;
		}
		$weeks = $query->result();
		$week_ids = [];
		foreach ($weeks as $row) {
			$week_ids[] = $row->week_id;
		}

		$dates = [];

		foreach ($weekdates as $row) {

			// Check week ID exists
			if ( ! in_array($row->week_id, $week_ids)) {
				continue;
			}

			$dt = new DateTime($row->date);
			$days = 0;
			while ($days < 7) {
				// Add all dates for the rest of the week
				$dates[] = [
					'date' => $dt->format('Y-m-d'),
					'week_id' => $row->week_id,
				];
				$dt->modify('+1 day');
				$days++;
			}
		}

		return $this->db->update_batch($this->table, $dates, 'date');
	}


}
