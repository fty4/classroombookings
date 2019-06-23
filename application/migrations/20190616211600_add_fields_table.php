<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_fields_table extends CI_Migration
{


	public function up()
	{
		$fields = array(
			'field_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE,
			),
			'entity' => array(
				'type' => 'CHAR',
				'constraint' => 2,
				'null' => FALSE,
			),
			'type' => array(
				'type' => 'VARCHAR',
				'constraint' => 64,
				'null' => FALSE,
			),
			'title' => array(
				'type' => 'VARCHAR',
				'constraint' => 30,
				'null' => FALSE,
			),
			'name' => array(
				'type' => 'VARCHAR',
				'constraint' => 40,
				'null' => FALSE,
			),
			'hint' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => TRUE,
			),
			'required' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'unsigned' => TRUE,
				'null' => FALSE,
				'default' => 0,
			),
			'position' => array(
				'type' => 'SMALLINT',
				'constraint' => 3,
				'unsigned' => TRUE,
				'null' => FALSE,
				'default' => 0,
			),
			'options' => array(
				'type' => 'TEXT',
				'null' => TRUE,
			),
		);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('field_id', TRUE);

		$this->dbforge->create_table('fields', TRUE, array('ENGINE' => 'InnoDB'));
	}


	public function down()
	{
	}


}
