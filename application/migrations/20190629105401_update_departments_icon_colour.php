<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_departments_icon_colour extends CI_Migration
{


	public function up()
	{
		$fields = array(
			'description' => array(
				'name' => 'description',
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => TRUE,
			),
			'icon' => array(
				'name' => 'icon',
				'type' => 'VARCHAR',
				'constraint' => 64,
				'null' => TRUE,
			),
		);

		$this->dbforge->modify_column('departments', $fields);

		$fields = array(
			'colour' => array(
				'type' => 'CHAR',
				'constraint' => 6,
				'null' => TRUE,
				'after' => 'icon',
			),
		);

		$this->dbforge->add_column('departments', $fields);

		$queries[] = 'UPDATE departments SET description = NULL WHERE description = ""';
		$queries[] = 'UPDATE departments SET icon = NULL';
		foreach ($queries as $sql) {
			$this->db->query($sql);
		}
	}


	public function down()
	{
	}


}
