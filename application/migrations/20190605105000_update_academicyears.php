<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_academicyears extends CI_Migration
{


	public function up()
	{
		// Rename academicyears => years
		$this->dbforge->rename_table('academicyears', 'years');

		// Add ID
		$fields = array(
			'year_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'null' => FALSE,
			),
			'name' => array(
				'type' => 'VARCHAR',
				'constraint' => 50,
				'null' => TRUE,
				'after' => 'year_id',
			),
		);
		$this->dbforge->add_column('years', $fields);

		// Do these first to make sure we have a "name" value before setting it to NOT NULL

		$queries = array();
		// Add PK
		$queries[] = 'ALTER TABLE `years` CHANGE `year_id` `year_id` int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST';
		// Add date index
		$queries[] = 'ALTER TABLE `years` ADD INDEX `idx_dates` (`date_start`, `date_end`)';
		// Set name automatically
		$queries[] = 'UPDATE years SET name = CONCAT(YEAR(date_start), " - ", YEAR(date_end))';

		foreach ($queries as $sql) {
			$this->db->query($sql);
		}

		// Modify other fields (and puts them after year_id)
		$fields = array(
			'name' => array(
				'name' => 'name',
				'type' => 'VARCHAR',
				'constraint' => 50,
				'after' => 'year_id',
				'null' => FALSE,
			),
			'date_start' => array(
				'name' => 'date_start',
				'type' => 'DATE',
				'after' => 'name',
				'null' => FALSE,
			),
			'date_end' => array(
				'name' => 'date_end',
				'type' => 'DATE',
				'after' => 'date_start',
				'null' => FALSE,
			),
		);
		$this->dbforge->modify_column('years', $fields);
	}


	public function down()
	{
	}


}
