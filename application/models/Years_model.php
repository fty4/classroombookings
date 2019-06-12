<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Years_model extends MY_Model
{


	public $table = 'years';
	public $primary_key = 'year_id';


	public function __construct()
	{
		parent::__construct();
	}


	public function wake_values($row, $find_query = NULL)
	{
		$row->dates = [];
		$row->holidays = [];

		if ($find_query) {

			$include = $find_query->get_include();

			if (in_array('dates', $include)) {
				$row = $this->populate_dates($row);
			}

			if (in_array('holidays', $include)) {
				$row = $this->populate_holidays($row);
			}

		}


		return $row;
	}


	public function populate_dates($row)
	{
		$this->load->model('dates_model');

		$items = $this->dates_model->find([
			'year_id' => $row->year_id,
			'limit' => NULL,
		]);

		$row->dates = $items;
		return $row;
	}


	public function populate_holidays($row)
	{
		$this->load->model('holidays_model');

		$items = $this->holidays_model->find([
			'year_id' => $row->year_id,
			'limit' => NULL,
		]);

		$row->holidays = $items;
		return $row;
	}


	function _GetMondays($school_id = NULL, $holidays = NULL)
	{
		// Get academic year dates
		$AcademicYear = $this->GetAcademicYear();

		// Get holidays (use global object as we're calling a function in the Holidays model
		/*if($holidays == NULL){
			$holidays = $this->CI->M_holidays->Get();
			if($holidays){
				foreach($holidays as $holiday){
					$hols[$holiday->holiday_id]['start'] = strtotime($holiday->date_start);
					$hols[$holiday->holiday_id]['end'] = strtotime($holiday->date_end);
				}
			} else {
				$hols = false;
			}
		}*/

		#print_r($hols);


		/*$weeks_query = $this->db->query("SELECT week_id, date FROM weekdates WHERE school_id='$school_id'");
		$results = $weeks_query->result_array();
		foreach($results as $row){
			$weeks[$row['date']] = $row['week_id'];
		}*/
		$weeks = $this->WeekDateIDs();

		// Set date format
		$date_format = "Y-m-d";

		$ay_start = strtotime($AcademicYear->date_start);	//mktime(0,0,0,9,4,2006);
		$ay_end = strtotime($AcademicYear->date_end);	//mktime(0,0,0,7,20,2007);

		#echo "Start: $ay_start, End: $ay_end";

		$i=0;
		while ($ay_start <= $ay_end) {

			if (date("w", $ay_start) == 1) {
				$nextdate = date("Y-m-d", $ay_start);
			} else {
				$nextdate = date("Y-m-d", strtotime("last Monday", $ay_start));
			}

			$ay_start = strtotime("+1 week", $ay_start);

			#echo "This: $ay_start";
			$dates[$i]['date'] = $nextdate;

			if ($weeks) {
				$dates[$i]['week_id'] = (array_key_exists($nextdate, $weeks)) ? $weeks[$nextdate] : 0;	//$this->WeekExists($nextdate);
			}

			#$query_str = "SELECT holiday_id FROM holidays WHERE date_start <= '$nextdate' AND date_end >= '$nextdate' LIMIT 1";
			#$query = $this->db->query($query_str);
			#$found_hol = false;
			$nextdate = strtotime($nextdate);

			/*if(isset($hols) && $hols){
				foreach($hols as $hol){
					if( ($hol['start'] <= $nextdate) AND ($hol['end'] >= $nextdate) ){
						$found_hol = true;
					}
				}
				$dates[$i]['holiday'] = $found_hol;	//false;	//($query->num_rows() == 1) ? true : false;
			} else {
				$dates[$i]['holiday'] = false;
			}*/

			$i++;
		}	// End while loop

		return $dates;
	}




	function _WeekDateIDs()
	{
		$weeks_query = $this->db->query("SELECT week_id, date FROM weekdates");
		$results = $weeks_query->result_array();
		foreach ($results as $row) {
			$weeks[$row['date']] = $row['week_id'];
		}

		if (isset($weeks)) {
			return $weeks;
		} else {
			return false;
		}
	}




	/**
	 * Checks to see if a given week-commencing date belongs to a given school week
	 *
	 * @param		date		$date		Date of week to check
	 * @return		int		Week_ID on true, otherwise false
	 *
	 */
	function _WeekExists($date)
	{
		$this->db->where('date', $date);
		$this->db->limit('1');
		$query_get = $this->db->get('weekdates');

		if ($query_get->num_rows() == 1) {
			// Got it!
			$row = $query_get->row();
			return $row->week_id;
		} else {
			// No results
			return 0;
		}
	}






	function _UpdateMondays($week_id, $dates)
	{
		// First get rid of all current dates for this week
		$this->db->where('week_id', $week_id);
		$this->db->delete('weekdates');

		// Database info that stays the same
		$data['week_id'] = $week_id;

		// Loop all dates
		foreach ($dates as $date) {

			// Database array
			$data['date'] = $date;

			// Check to see if this date already exists
			$query = $this->db->query("SELECT `date` FROM weekdates WHERE `date`='$date'");
			$rows = $query->num_rows();

			if ($rows == 1) {
				// We got one row where the date is another week_id, so change it:
				#$this->db->query("UPDATE weekdates SET week_id='$week_id' WHERE date='$date' AND school_id='$school_id'");
				$where = "`date`='$date' ";
				$str = $this->db->update_string('weekdates', $data, $where);
			} else {
				$str = $this->db->insert_string('weekdates', $data);
			}
			// Run query
			$this->db->query($str);
			$str = '';
			$where = '';

			/*
			$this->db->where('date', $date);
			$update = $this->db->update('weekdates', $data);
			if(!$update){
				$this->db->insert('weekdates', $data);
			}*/
			/*  // Attempt insert
			$insert = $this->db->insert('weekdates', $data);
			// If insert fails, it means the row for the date already exists (but assigned to another week_id)
			if(!$insert){
				// So update it, but change week_id
				$this->db->where('date', $date);
				$update = $this->db->update('weekdates', $data);
			}*/
		}
	}




}
