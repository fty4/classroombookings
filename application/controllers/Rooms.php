<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Rooms extends MY_Controller
{


	public function __construct()
	{
		parent::__construct();

		$this->load->language('admin');
		$this->load->language('rooms');
		$this->load->language('room');

		$this->require_logged_in();
		$this->require_auth_level(ADMINISTRATOR);

		$this->load->model('rooms_model');
		$this->load->model('users_model');
		$this->load->model('fields_model');
		$this->load->helper('room');
		$this->load->helper('user');
		$this->load->helper('number');
		$this->load->helper('field');

		$this->data['max_size_bytes'] = max_upload_file_size();
		$this->data['max_size_human'] = byte_format(max_upload_file_size());
	}


	/**
	 * Rooms index page
	 *
	 */
	function index()
	{
		$this->data['menu_active'] = 'admin/rooms';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));
		$this->data['breadcrumbs'][] = array('rooms', lang('rooms_page_index'));

		$this->data['title'] = lang('rooms_page_index');

		$filter = $this->input->get();
		$filter['sort'] = 'name';
		$filter['limit'] = NULL;
		$filter['include'] = ['user'];

		$this->data['filter'] = $filter;
		$this->data['total'] = $this->rooms_model->count($filter);
		$this->data['rooms'] = $this->rooms_model->find($filter);

		$this->blocks['tabs'] = 'rooms/menu';

		$this->render('rooms/index');
	}


	/**
	 * Add a room
	 *
	 */
	function add()
	{
		$this->data['room'] = NULL;

		$this->data['custom_fields'] = $this->fields_model->get_field_values('RM');

		$this->data['menu_active'] = 'admin/rooms/add';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));
		$this->data['breadcrumbs'][] = array('rooms', lang('rooms_page_index'));
		$this->data['breadcrumbs'][] = array('rooms/add', lang('rooms_add_page_title'));

		$this->init_form_elements();

		$this->data['title'] = lang('rooms_add_page_title');

		$this->blocks['tabs'] = 'rooms/menu';

		if ($this->input->post()) {
			$this->save_room();
		}

		$this->render('rooms/update');
	}


	/**
	 * Update a room
	 *
	 * @param int $id		ID of room to update
	 *
	 */
	public function update($room_id = 0)
	{
		$room = $this->find_room($room_id);

		$this->data['room'] = $room;

		$this->data['menu_active'] = 'admin/rooms/update';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));
		$this->data['breadcrumbs'][] = array('rooms', lang('rooms_page_index'));
		$this->data['breadcrumbs'][] = array("rooms/update/{$room->room_id}", lang('rooms_update_page_title'));

		$this->init_form_elements();

		$this->data['title'] = html_escape($room->name) . ': ' . lang('rooms_update_page_title');

		$this->blocks['tabs'] = 'rooms/context/menu';

		if ($this->input->post()) {
			$this->save_room($room);
		}

		$this->render('rooms/update');
	}


	/**
	 * Save changes to room: update or add new
	 *
	 * @param $room		room object if updating, NULL to add new room.
	 *
	 */
	private function save_room($room = NULL)
	{
		$this->load->library('form_validation');
		$this->load->config('form_validation', TRUE);

		$rules = $this->config->item('rooms_add_update', 'form_validation');
		$custom_rules = $this->fields_model->get_validation_rules('RM');
		$rules = array_merge($rules, $custom_rules);
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == FALSE) {
			$this->notice('error', lang('error_form_validation'));
			return;
		}

		$keys = [
			'name',
			'user_id',
			'bookable',
			'custom_fields',
			'booking_field_ids',
		];

		$room_data = array_fill_safe($keys, $this->input->post());
		$success = FALSE;

		// Got a new photo?
		$upload_data = $this->handle_upload('userfile');

		if ($upload_data['success'] == FALSE) {
			$this->notice('error', $upload_data['reason']);
			return;
		}

		if (strlen($upload_data['filename']) || $this->input->post('delete_photo') == 1) {

			$this->delete_photo();
			$room_data['photo'] = '';

			if (strlen($upload_data['filename'])) {
				$room_data['photo'] = $upload_data['filename'];
			}
		}

		if ($room !== NULL) {

			// Update room
			$res = $this->rooms_model->update($room_data, ['room_id' => $room->room_id]);
			$id = $room->room_id;

			if ($res) {
				$success = TRUE;
				$this->notice('success', lang('rooms_update_status_success'));
			} else {
				$this->notice('error', lang('rooms_update_status_error'));
			}

		} else {

			// Add new room
			$res = $this->rooms_model->insert($room_data);
			$id = $res;

			if ($res) {
				$success = TRUE;
				$this->notice('success', lang('rooms_add_status_success'));
			} else {
				$this->notice('error', lang('rooms_add_status_error'));
			}

		}

		if ($success) {
			redirect('rooms');
		}
	}


	/**
	 * Delete room
	 *
	 * @param integer $room_id		ID of room to delete
	 *
	 */
	public function delete($room_id = 0)
	{
		$room = $this->find_room($room_id);

		$this->data['room'] = $room;

		$this->data['menu_active'] = 'admin/rooms/delete';
		$this->data['breadcrumbs'][] = array('admin', lang('admin_page_title'));
		$this->data['breadcrumbs'][] = array('rooms', lang('rooms_page_index'));
		$this->data['breadcrumbs'][] = array('rooms/delete', lang('rooms_delete_page_title'));

		$this->data['title'] = html_escape($room->name) . ': ' . lang('rooms_delete_page_title');

		$this->blocks['tabs'] = 'rooms/context/menu';

		if ($this->input->post('room_id') == $room->room_id && $this->input->post('action') == 'delete') {

			$res = $this->rooms_model->delete(['room_id' => $room->room_id]);
			$success = FALSE;

			if ($res) {
				$this->notice('success', lang('rooms_delete_status_success'), [
					'name' => $room->name,
				]);
			} else {
				$this->notice('error', lang('rooms_delete_status_error'));
			}

			return redirect('rooms');
		}

		$this->render('rooms/delete');
	}


	private function init_form_elements()
	{
		$users = $this->users_model->find([
			'sort' => 'username',
			'limit' => NULL,
		]);

		$this->data['users'] = results_dropdown('user_id', function($user) {
			return $user->username . ' (' . UserHelper::best_display_name($user) . ')';
		}, $users, '');

		$fields_bookings = $this->fields_model->fields_by_entity('BK');
		$this->data['fields_bookings'] = results_dropdown('field_id', 'title', $fields_bookings);
	}


	private function find_room($id = 0, $include = [])
	{
		$default_inc = ['custom_fields', 'fields_bookings'];
		$all_inc = array_merge($default_inc, $include);

		$room = $this->rooms_model->find_one([
			'room_id' => $id,
			'include' => $all_inc,
		]);

		if ( ! $room) {
			$this->render_error(array(
				'http' => 404,
				'title' => lang('not_found'),
				'description' => lang('rooms_not_found'),
			));
		}

		return $room;
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


	private function delete_photo()
	{
		$photo = setting('photo');
		@unlink(FCPATH . 'uploads/' . $photo);
		return;
	}


}
