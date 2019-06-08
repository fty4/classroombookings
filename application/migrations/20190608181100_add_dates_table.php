<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_dates_table extends CI_Migration
{


	public function up()
	{
		$fields = array(
			'date' => array(
				'type' => 'DATE',
				'null' => FALSE,
			),
			'year_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'null' => FALSE,
			),
			'week_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'null' => TRUE,
			),
			'holiday_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'null' => TRUE,
			),
		);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('date', TRUE);

		$this->dbforge->create_table('dates', TRUE, array('ENGINE' => 'InnoDB'));
	}


	public function down()
	{
	}


}
