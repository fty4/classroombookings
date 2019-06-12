<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Process_years_add_dates extends CI_Migration
{


	public function up()
	{
		$sql = 'SELECT year_id FROM years';
		$query = $this->db->query($sql);

		if ($query->num_rows() > 0) {
			$this->load->model('dates_model');
			// Process year entries (should be one) and create the dates entries
			foreach ($query->result() as $row) {
				$this->dates_model->refresh_year($row->year_id);
			}
			// Copy old weekdates and apply weeks to new dates table
			$this->dates_model->migrate_weekdates();
		}

	}


	public function down()
	{
	}


}
