<?php
defined('BASEPATH') OR exit('No direct script access allowed');


use app\components\Calendar;


class Settings extends MY_Controller
{


	public function __construct()
	{
		parent::__construct();

		$this->load->language('admin');
		$this->load->language('settings');

		$this->require_logged_in();
		$this->require_auth_level(ADMINISTRATOR);
	}


	/**
	* Settings: General options
	*
	*/
	function index()
	{
		$this->require_logged_in();
		$this->require_auth_level(ADMINISTRATOR);

		$this->data['menu_active'] = 'admin/settings';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));
		$this->data['breadcrumbs'][] = array('settings', lang('settings_page_title'));

		$this->data['title'] = lang('settings_page_title');

		$this->data['settings'] = $this->settings_model->get_all('crbs');
		$this->data['days'] = Calendar::get_days_of_week();

		$this->blocks['tabs'] = 'settings/menu';

		if ($this->input->post()) {
			$this->save_options();
		}

		return $this->render('settings/options');
	}


	private function save_options()
	{
		$this->load->library('form_validation');

		if ($this->form_validation->run('settings_options') == FALSE) {
			$this->notice('error', "Please check the form fields and try again.");
			return;
		}

		$options_data = [
			'name' => $this->input->post('name'),
			'website' => $this->input->post('website'),
			'bia' => $this->input->post('bia'),
			'login_hint' => $this->input->post('login_hint'),
			'week_starts' => $this->input->post('week_starts'),
		];

		// Update
		$res = $this->settings_model->set($options_data);

		if ($res) {
			$this->notice('success', "The settings have been saved.");
			redirect('settings');
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

		$this->data['menu_active'] = 'admin/settings/appearance';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));
		$this->data['breadcrumbs'][] = array('settings', lang('settings_page_title'));
		$this->data['breadcrumbs'][] = array('settings/visual', lang('settings_visual_page_title'));

		$this->data['title'] = lang('settings_visual_page_title');

		$this->data['settings'] = $this->settings_model->get_all('crbs');

		$this->blocks['tabs'] = 'settings/menu';

		$this->data['theme_options'] = [
			'red' => 'Red',
			'orange' => 'Orange',
			'green' => 'Green',
			'olive' => 'Olive',
			'blue' => 'Blue',
			'navy' => 'Navy',
			'purple' => 'Purple',
			'fuchsia' => 'Fuchsia',
			'grey' => 'Grey',
		];

		if ($this->input->post()) {
			$this->save_visual();
		}

		return $this->render('settings/visual');
	}


	private function save_visual()
	{
		$this->load->library('form_validation');

		if ($this->form_validation->run('settings_visual') == FALSE) {
			$this->notice('error', lang('error_form_validation'));
			return;
		}

		$settings_data = [
			'theme' => $this->input->post('theme'),
			'displaytype' => $this->input->post('displaytype'),
			'd_columns' => $this->input->post('d_columns'),
		];

		// Got a new logo?
		$upload_data = $this->handle_upload('userfile');

		if ($upload_data['success'] == FALSE) {
			$this->notice('error', $upload_data['reason']);
			return;
		}

		if (strlen($upload_data['filename']) || $this->input->post('delete_logo') == 1) {

			$this->delete_logo();
			$settings_data['logo'] = '';

			if (strlen($upload_data['filename'])) {
				$settings_data['logo'] = $upload_data['filename'];
			}
		}

		$res = $this->settings_model->set($settings_data);

		if ($res) {
			$this->notice('success', lang('settings_save_success'));
			redirect('settings/visual');
		} else {
			$this->notice('error', lang('settings_save_error'));
		}
	}


	private function handle_upload($name)
	{
		$out = [
			'success' => FALSE,
			'reason' => '',
			'filename' => '',
		];

		$has_file = (isset($_FILES[$name]) && isset($_FILES[$name]['name']) && ! empty($_FILES[$name]['name']));

		if ( ! $has_file) {
			$out['success'] = TRUE;
			return $out;
		}

		$upload_config = [
			'upload_path' => FCPATH . 'uploads',
			'allowed_types' => 'jpg|jpeg|png|gif',
			'max_width' => 2560,
			'max_height' => 2560,
			'encrypt_name' => TRUE,
		];

		$this->load->library('upload', $upload_config);

		if ( ! $this->upload->do_upload($name)) {
			// Not uploaded
			$error = $this->upload->display_errors('', '');
			if ($error !== 'You did not select a file to upload') {
				$out['success'] = FALSE;
				$out['reason'] = $error;
				return $out;
			}

			$out['success'] = TRUE;
			return $out;
		}

		// File uploaded
		$upload_data = $this->upload->data();

		$width = 400;

		if ($upload_data['image_width'] > $width) {

			// Resize
			$this->load->library('image_lib');

			$image_config = array(
				'image_library' => 'gd2',
				'source_image' => $upload_data['full_path'],
				'maintain_ratio' => TRUE,
				'width' => $width,
				'master_dim' => 'auto',
			);

			$this->image_lib->initialize($image_config);

			$res = $this->image_lib->resize();

			if ( ! $res) {
				$out['success'] = FALSE;
				$out['reason'] = $this->image_lib->display_errors('', '');
				return $out;
			}
		}

		$out['success'] = TRUE;
		$out['filename'] = $upload_data['file_name'];
		return $out;
	}


	private function delete_logo()
	{
		$logo = setting('logo');
		@unlink(FCPATH . 'uploads/' . $logo);
		return;
	}


}
