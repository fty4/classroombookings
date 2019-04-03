<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Settings extends MY_Controller
{


	public function __construct()
	{
		parent::__construct();

		$this->load->language('settings');

		$this->require_logged_in();
	}


	/**
	* Settings index page
	*
	*/
	function index()
	{
		redirect('settings/options');
		/*
		$this->require_logged_in();
		$this->require_auth_level(ADMINISTRATOR);

		$this->data['menu_active'] = 'settings';
		$this->data['breadcrumbs'][] = array('', lang('home'));
		$this->data['breadcrumbs'][] = array('settings', lang('settings_page_title'));

		$this->data['title'] = lang('settings_page_title');

		$this->blocks['content'] = 'settings/index';
		$this->blocks['sidebar'] = 'settings/menu';

		return $this->render('layouts/types/two-columns');
		*/
	}


	/**
	* Settings: General options
	*
	*/
	function options()
	{
		$this->require_logged_in();
		$this->require_auth_level(ADMINISTRATOR);

		$this->data['menu_active'] = 'settings/options';
		$this->data['breadcrumbs'][] = array('', lang('home'));
		$this->data['breadcrumbs'][] = array('settings', lang('settings_page_title'));
		$this->data['breadcrumbs'][] = array('settings', lang('settings_options_page_title'));

		$this->data['title'] = lang('settings_options_page_title');

		$this->data['settings'] = $this->settings_model->get_all('crbs');

		$this->blocks['content'] = 'settings/options';
		$this->blocks['sidebar'] = 'settings/menu';

		if ($this->input->post()) {
			$this->save_options();
		}

		return $this->render('layouts/types/two-columns');
	}


	private function save_options()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('name', "lang:settings_general_field_name", "required|trim|max_length[255]");
		$this->form_validation->set_rules('website', "lang:settings_general_field_website", 'trim|prep_url|max_length[255]|valid_url');
		$this->form_validation->set_rules('bia', "lang:settings_general_field_bia", 'required|integer');

		if ($this->form_validation->run() == FALSE) {
			$this->notice('error', "Please check the form fields and try again.");
			return;
		}

		$options_data = [
			'name' => $this->input->post('name'),
			'website' => $this->input->post('website'),
			'bia' => $this->input->post('bia'),
			'login_hint' => $this->input->post('login_hint'),
		];

		// Update
		$res = $this->settings_model->set($options_data);

		if ($res) {
			$this->notice('success', "The settings have been saved.");
			redirect('settings/options');
		} else {
			$this->notice('error', "There was an error saving the settings.");
		}
	}


	/**
	* Settings: Look and feel
	*
	*/
	function visual()
	{
		$this->require_logged_in();
		$this->require_auth_level(ADMINISTRATOR);

		$this->data['menu_active'] = 'settings/visual';
		$this->data['breadcrumbs'][] = array('', lang('home'));
		$this->data['breadcrumbs'][] = array('settings', lang('settings_page_title'));
		$this->data['breadcrumbs'][] = array('settings', lang('settings_visual_page_title'));

		$this->data['title'] = lang('settings_visual_page_title');

		$this->data['settings'] = $this->settings_model->get_all('crbs');

		$this->data['theme_options'] = [
			'blue' => 'Blue',
			'red' => 'Red',
			'green' => 'Green',
			'olive' => 'Olive',
			'purple' => 'Purple',
			'orange' => 'Orange',
		];

		$this->blocks['content'] = 'settings/visual';
		$this->blocks['sidebar'] = 'settings/menu';

		return $this->render('layouts/types/two-columns');
	}


}
