<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Periods_model extends MY_Model
{


	public $table = 'periods';
	public $primary_key = 'period_id';

	public $days = array();


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
	}


	public function GetBookable($day_num = NULL)
	{
		$out = array();
		$where = array('bookable' => '1');

		if ($day_num !== NULL && is_numeric($day_num))
		{
			$where["day_{$day_num}"] = '1';
		}

		$this->db->where($where);
		$this->db->order_by('time_start', 'ASC');
		$query = $this->db->get('periods');

		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				$out[$row->period_id] = $row;
			}
		}

		return $out;
	}


}
