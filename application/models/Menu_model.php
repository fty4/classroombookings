<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends CI_Model
{


	public function __construct()
	{
		parent::__construct();
	}


	public function get_user_menu()
	{
		$items = [];

		$is_logged_in = $this->userauth->loggedin();

		$label = '';
		if ($is_logged_in) {
			$label = strlen($this->userauth->user->displayname) > 1 ? $this->userauth->user->displayname : $this->userauth->user->username;
		}
		$items[] = array(
			'label' => $label,
			'url' => site_url('user'),
			'icon' => 'user',
			'visible' => $is_logged_in,
		);

		$items[] = array(
			'label' => lang('user_action_log_out'),
			'url' => site_url('user/logout'),
			'icon' => 'log-out',
			'visible' => $is_logged_in,
			'link_attrs' => array('data-method' => 'post'),
		);

		$items[] = array(
			'label' => lang('user_action_log_in'),
			'url' => site_url('user/login'),
			'icon' => 'log-in',
			'visible' => !$is_logged_in,
		);

		return $items;
	}


	public function get_main_menu()
	{
		$items = [];

		$is_logged_in = $this->userauth->loggedin();

		$items[] = array(
			'label' => 'Dashboard',
			'url' => site_url('dashboard'),
			'icon' => 'home',
			'visible' => $is_logged_in,
		);

		$items[] = array(
			'label' => 'Bookings',
			'url' => site_url('bookings'),
			'icon' => 'check-square',
			'visible' => $is_logged_in,
		);

		$items[] = array(
			'label' => 'Settings',
			'url' => site_url('settings'),
			'icon' => 'settings',
			'visible' => $is_logged_in,
		);

		// $items[] = array(
		// 	'label' => 'Reports',
		// 	'url' => site_url('reports'),
		// 	'icon' => 'activity',
		// 	'visible' => $is_logged_in,
		// );

		// $items[] = array(
		// 	'label' => 'Event log',
		// 	'url' => site_url('event-log'),
		// 	'icon' => 'alert-triangle',
		// 	'visible' => $is_logged_in,
		// );


		return $items;
	}


}
