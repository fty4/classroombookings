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


	/**
	 * Content blocks
	 *
	 * @var array
	 */
	public $blocks = array();


	private $notices = array();


	public function __construct()
	{
		parent::__construct();

		$this->output->enable_profiler(config_item('show_profiler') === TRUE);

		$this->load->library('session');
		$this->load->library('form_validation');

		$this->init_layout();

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


		// Load menus
		$this->load->model('menu_model');
		$this->data['menus']['user'] = $this->menu_model->get_user_menu();
		$this->data['menus']['main'] = $this->menu_model->get_main_menu();
	}


	private function init_layout()
	{
		// Set some defaults
		$this->data['title'] = NULL;
		$this->data['breadcrumbs'] = array();
		$this->data['css'] = array();
		$this->data['js'] = array();
		$this->data['menus'] = array();

		// Add assets (scripts/styles)
		$this->register_assets();
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
		// Load blocks first
		$this->data['blocks'] = array();

		foreach ($this->blocks as $block_name => $block_view) {
			$this->data['blocks'][ $block_name ] = $this->load->view($block_view, $this->data, TRUE);
		}

		// View in `body` can use $blocks['whatever']
		$this->data['body'] = $this->load->view($view, $this->data, TRUE);

		// Load main layout with all data
		$this->load->view($this->layout, $this->data);
	}


	/**
	 * Renders a pretty error page and exits.
	 *
	 */
	public function render_error($params = array())
	{
		$defaults = array(
			'http' => 500,
			'title' => 'Error',
			'description' => "There was a problem with the request. Plase try again.",
			'action' => '',
			'icon' => 'alert-triangle',
		);

		$data = array_merge($defaults, $params);
		$this->data['title'] = $data['title'];
		$this->data['description'] = $data['description'];
		$this->data['icon'] = $data['icon'];
		$this->data['action'] = $data['action'];

		$this->output->set_status_header($data['http'], $data['title']);

		$this->render('partials/error');
		$this->output->_display();
		exit;
	}


	public function render_json($data = array(), $http = 200)
	{
		$this->output->set_status_header($http);
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data, JSON_NUMERIC_CHECK));
		$this->output->_display();
		exit;
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


	public function notice($type = '', $content = '', $vars = array())
	{
		$this->notices[] = array(
			'type' => $type,
			'content' => $content,
			'vars' => $vars,
		);

		$_SESSION['notices'] = $this->notices;
	}


}
