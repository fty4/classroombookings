<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_holidays_add_year extends CI_Migration
{


	public function up()
	{
		$fields = array(
			'year_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'null' => TRUE,
				'after' => 'holiday_id',
			),
		);

		$this->dbforge->add_column('holidays', $fields);

		$queries = array();

		$queries[] = "ALTER TABLE `holidays` CHANGE `holiday_id` `holiday_id` int(11) unsigned NOT NULL AUTO_INCREMENT FIRST";

		$queries[] = 'UPDATE holidays h
				SET h.year_id = (
					SELECT y.year_id
					FROM years y
					WHERE h.date_start >= y.date_start
					AND h.date_start <= y.date_end
					LIMIT 1
				)';

		foreach ($queries as $sql) {
			$this->db->query($sql);
		}
	}


	public function down()
	{
	}


}
