<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Commands extends CI_Controller
{


	public function __construct()
	{
		parent::__construct();
		if ( ! $this->input->is_cli_request()) {
			show_error("Not available");
		}
	}


	public function create_migration($name = '')
	{
		echo "\n";

		$this->load->library('parser');
		$this->load->helper('file');

		if (empty($name)) {
			echo "No migration name given.\n\n";
			exit(1);
		}

		$ts = date('YmdHis');
		$name = strtolower($name);
		$filename = "{$ts}_{$name}.php";
		$filepath = APPPATH . "migrations/{$filename}";

		$vars = [
			'name' => ucfirst($name),
		];

		$template = file_get_contents(APPPATH . 'migrations/template.php');
		$parsed = $this->parser->parse_string($template, $vars, TRUE);

		if (write_file($filepath, $parsed)) {
			echo "Created new migration {$filename}!\n";
		} else {
			echo "Error creating migration.\n";
			exit(3);
		}

	}


}
