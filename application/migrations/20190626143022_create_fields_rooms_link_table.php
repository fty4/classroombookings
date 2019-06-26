<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_fields_rooms_link_table extends CI_Migration
{


	public function up()
	{
		$fields = array(
			'field_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'null' => FALSE,
			),
			'room_id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'null' => FALSE,
			),
		);

		$this->dbforge->add_field($fields);
		$this->dbforge->add_key(['field_id', 'room_id'], TRUE);

		$this->dbforge->create_table('link_fields_rooms', TRUE, array('ENGINE' => 'InnoDB'));
	}


	public function down()
	{
	}


}
