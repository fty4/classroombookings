<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Convert_room_fields extends CI_Migration
{


	public function up()
	{
		$this->create_location_field();
		$this->create_notes_field();
	}


	public function down()
	{
	}


	public function create_location_field()
	{
		// Add field configuration
		//

		$data = [
			'field_id' => 1,
			'entity' => 'RM',
			'type' => 'text_single',
			'title' => 'Location',
			'name' => 'location',
			'hint' => NULL,
			'required' => 0,
			'position' => 0,
		];

		$this->db->insert('fields', $data);

		// Create field data table
		//

		$schema = [
			'entity_id' => [
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE,
			],
			'data' => [
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => TRUE,
			],
		];

		$this->dbforge->add_field($schema);
		$this->dbforge->add_key('entity_id', TRUE);
		$this->dbforge->create_table("field_location_1", TRUE, array('ENGINE' => 'InnoDB'));

		// Copy data
		//

		$sql = "INSERT INTO field_location_1 (entity_id, data)
				SELECT room_id, location
				FROM rooms";
		$this->db->query($sql);
	}



	public function create_notes_field()
	{
		// Add field configuration
		//

		$data = [
			'field_id' => 2,
			'entity' => 'RM',
			'type' => 'text_multi',
			'title' => 'Notes',
			'name' => 'notes',
			'hint' => NULL,
			'required' => 0,
			'position' => 1,
		];

		$this->db->insert('fields', $data);

		// Create field data table
		//

		$schema = [
			'entity_id' => [
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE,
			],
			'data' => [
				'type' => 'TEXT',
				'null' => TRUE,
			],
		];

		$this->dbforge->add_field($schema);
		$this->dbforge->add_key('entity_id', TRUE);
		$this->dbforge->create_table("field_notes_2", TRUE, array('ENGINE' => 'InnoDB'));

		// Copy data
		//

		$sql = "INSERT INTO field_notes_2 (entity_id, data)
				SELECT room_id, notes
				FROM rooms";
		$this->db->query($sql);
	}


}
