<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_rooms_fields extends CI_Migration
{


	public function up()
	{
		$fields = array(
			'user_id' => array(
				'name' => 'user_id',
				'type' => 'INT',
				'constraint' => 11,
				'null' => TRUE,
				'unsigned' => TRUE,
			),
			'name' => array(
				'name' => 'name',
				'type' => 'VARCHAR',
				'constraint' => 30,
				'null' => FALSE,
			),
			'photo' => array(
				'name' => 'photo',
				'type' => 'VARCHAR',
				'constraint' => 64,
				'null' => TRUE,
			),
		);

		$this->dbforge->modify_column('rooms', $fields);

		$this->dbforge->drop_column('rooms', 'icon');

		$sql = "ALTER TABLE `rooms` CHANGE `room_id` `room_id` int(11) unsigned NOT NULL AUTO_INCREMENT FIRST";
		$this->db->query($sql);
	}


	public function down()
	{
	}


}
