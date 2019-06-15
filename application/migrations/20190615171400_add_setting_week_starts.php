<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_setting_week_starts extends CI_Migration
{


	public function up()
	{
		$this->load->model('settings_model');
		$this->settings_model->set('week_starts', '1', 'crbs');
	}


	public function down()
	{
	}


}
