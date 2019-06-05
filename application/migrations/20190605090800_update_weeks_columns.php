<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_weeks_columns extends CI_Migration
{


	public function up()
	{
		$fields = array(
			'bgcol' => array(
				'name' => 'colour',
				'type' => 'CHAR',
				'constraint' => 6,
				'null' => TRUE,
			),
		);

		$this->dbforge->modify_column('weeks', $fields);

		$this->dbforge->drop_column('weeks', 'fgcol');
		$this->dbforge->drop_column('weeks', 'icon');
	}


	public function down()
	{
	}


}
