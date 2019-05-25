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

		$items[] = array(
			'label' => "Account",
			'url' => 'user',
			'icon' => 'user',
			'visible' => $is_logged_in,
		);

		$items[] = array(
			'label' => lang('user_action_log_out'),
			'url' => 'user/logout',
			'icon' => 'log-out',
			'visible' => $is_logged_in,
			'link_attrs' => array('data-method' => 'post'),
		);

		// $items[] = array(
		// 	'label' => lang('user_action_log_in'),
		// 	'url' => 'user/login',
		// 	'icon' => 'log-in',
		// 	'visible' => !$is_logged_in,
		// );

		return $items;
	}


	public function get_main_menu()
	{
		$items = [];

		$is_logged_in = $this->userauth->loggedin();

		$items[] = array(
			'label' => 'Dashboard',
			'url' => 'dashboard',
			'icon' => 'home',
			'visible' => $is_logged_in,
		);

		$items[] = array(
			'label' => 'Bookings',
			'url' => 'bookings',
			'icon' => 'check-square',
			'visible' => $is_logged_in,
		);

		$items[] = array(
			'label' => 'Settings',
			'url' => 'settings',
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


	public function get_settings_menu()
	{
		$items = [];

		$is_admin = $this->userauth->is_level(ADMINISTRATOR);

		$items[] = array(
			'label' => 'General options',
			'description' => 'Change global settings.',
			'url' => 'settings/options',
			'icon' => 'sliders',
			'visible' => $is_admin,
			'link_class' => 'card-menu-item-clrs-navy',
		);

		$items[] = array(
			'label' => 'Look and feel',
			'description' => 'Customise the branding and appearance of the Bookings page.',
			'url' => 'settings/visual',
			'icon' => 'eye',
			'visible' => $is_admin,
			'link_class' => 'card-menu-item-clrs-blue',
		);

		$items[] = array(
			'id' => 'settings/users',
			'label' => 'Users',
			'description' => 'Add or remove user accounts.',
			'url' => 'users',
			'icon' => 'users',
			'visible' => $is_admin,
			'link_class' => 'card-menu-item-clrs-green',
		);

		$items[] = array(
			'label' => 'Week cycle',
			'description' => 'Set the dates of your academic year and timetable weeks.',
			'url' => 'weeks',
			'icon' => 'calendar',
			'visible' => $is_admin,
			'link_class' => 'card-menu-item-clrs-red',
		);

		$items[] = array(
			'label' => 'Periods',
			'description' => 'Set up the time periods of the school day.',
			'url' => 'periods',
			'icon' => 'clock',
			'visible' => $is_admin,
			'link_class' => 'card-menu-item-clrs-purple',
		);

		$items[] = array(
			'label' => 'Holidays',
			'description' => 'Add holiday dates when bookings cannot be made.',
			'url' => 'holidays',
			'icon' => 'sun',
			'visible' => $is_admin,
			'link_class' => 'card-menu-item-clrs-yellow',
		);

		$items[] = array(
			'label' => 'Rooms',
			'description' => 'Manage the rooms that can be booked.',
			'url' => 'rooms',
			'icon' => 'monitor',
			'visible' => $is_admin,
			'link_class' => 'card-menu-item-clrs-maroon',
		);

		$items[] = array(
			'label' => 'Departments',
			'description' => 'Add or remove departments.',
			'url' => 'departments',
			'icon' => 'layers',
			'visible' => $is_admin,
			'link_class' => 'card-menu-item-clrs-olive',
		);

		return $items;
	}


}
