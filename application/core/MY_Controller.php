<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{


	/**
	 * Global data for view
	 *
	 * @var array
	 *
	 */
	public $data = array();


	/**
	 * Defaut layout file (relative to application/views)
	 *
	 * @var  string
	 *
	 */
	public $layout = 'layouts/main';


	private $notices = array();


	public function __construct()
	{
		parent::__construct();

		$this->output->enable_profiler(config_item('show_profiler') === TRUE);

		$this->load->library('session');
		$this->load->library('form_validation');

		// Set some defaults
		$this->data['title'] = NULL;
		$this->data['breadcrumbs'] = array();
		$this->data['css'] = array();
		$this->data['js'] = array();

		// Add assets (scripts/styles)
		$this->register_assets();

		// Checks for NOT install/upgrade mode
		if (get_class($this) !== 'Install' && get_class($this) !== 'Upgrade') {

			if ( ! config_item('is_installed')) {
				redirect('install');
			}

			$this->load->database();
			$this->load->library('userauth');

			$this->load->library('migration');
			$this->migration->latest();
		}


		// Load menus for page
		$this->load->model('menu_model');
		$this->data['menus']['user'] = $this->menu_model->get_user_menu();
		$this->data['menus']['main'] = $this->menu_model->get_main_menu();

	}


	public function require_logged_in()
	{
		if ( ! $this->userauth->loggedin()) {
			redirect('user/login');
		}
	}


	public function require_auth_level($level)
	{
		if ( ! $this->userauth->is_level($level)) {
			$this->session->set_flashdata('auth', msgbox('error', $this->lang->line('crbs_mustbeadmin')));
			redirect('controlpanel');
		}
	}


	public function render($view = '')
	{
		$this->data['body'] = $this->load->view($view, $this->data, TRUE);
		$this->load->view($this->layout, $this->data);
	}


	/**
	 * Add the default assets to the list to be outputted in the layout.
	 *
	 */
	public function register_assets()
	{
		$css = 'crbs.min.css?v=' . VERSION;
		$js = 'crbs.min.js?v=' . VERSION;

		if (ENVIRONMENT === 'development')
		{
			$css = 'crbs.css?v=' . time();
			$js = 'crbs.js?v=' . time();
		}

		$this->data['css'][] = base_url("application/assets/dist/{$css}");
		$this->data['js'][] = base_url("application/assets/dist/{$js}");
	}


	public function notice($type = '', $content = '')
	{
		$this->notices[] = array('type' => $type, 'content' => $content);
		$_SESSION['notices'] = $this->notices;
		// $this->session->mark_as_flash('notices');
	}


}
