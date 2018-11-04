<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH . 'third_party/bitmask.php');

class Periods_model extends CI_Model
{


	public $days = array();
	public $days_bitmask;


	public function __construct()
	{
		parent::__construct();

		$this->days[1] = 'Monday';
		$this->days[2] = 'Tuesday';
		$this->days[3] = 'Wednesday';
		$this->days[4] = 'Thursday';
		$this->days[5] = 'Friday';
		$this->days[6] = 'Saturday';
		$this->days[7] = 'Sunday';

		$this->days_bitmask = new bitmask;
		$this->days_bitmask->assoc_keys = $this->days;
	}




	function Get($period_id = NULL)
	{
		if ($period_id == NULL) {
			return $this->crud_model->Get('periods', NULL, NULL, NULL, 'time_start asc');
		} else {
			return $this->crud_model->Get('periods', 'period_id', $period_id);
		}
	}




	function Add($data)
	{
		return $this->crud_model->Add('periods', 'period_id', $data);
	}




	function Edit($period_id, $data)
	{
		return $this->crud_model->Edit('periods', 'period_id', $period_id, $data);
	}




	/**
	 * Deletes a period with the given ID
	 *
	 * @param   int   $id   ID of period to delete
	 *
	 */
	function Delete($id)
	{
		return $this->crud_model->Delete('periods', 'period_id', $id);
	}




}
