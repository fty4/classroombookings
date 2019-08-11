<?php
defined('BASEPATH') OR exit('No direct script access allowed');

namespace app\components;

use \DateTime;
use \DateInterval;
use \DatePeriod;


class Calendar
{

	private $CI;

	private $config = [];

	private $year;
	private $weeks;
	private $dates = [];

	private $year_start = null;
	private $year_end = null;

	private $first_day;
	private $last_day;
	private $last_day_name;

	// Should the calendar have nav buttons?
	private $show_navigation = false;

	// For DateTime calculations
	private $day_names = [
		'1' => 'Monday',
		'2' => 'Tuesday',
		'3' => 'Wednesday',
		'4' => 'Thursday',
		'5' => 'Friday',
		'6' => 'Saturday',
		'7' => 'Sunday',
	];


	public function __construct($params = [])
	{
		$this->CI =& get_instance();

		$defaults = [
			'year' => NULL,
			'weeks' => NULL,
			'navigation' => FALSE,
			'date_links' => FALSE,
			'inputs' => FALSE,
			'nav_url_format' => '',
			'date_url_format' => '',
			'class' => '',
			'active_date' => NULL,
		];

		$options = array_merge($defaults, $params);
		$this->config = $options;

		$this->weeks = $options['weeks'];

		if ($options['year']) {

			$this->year = $year = $options['year'];

			if (isset($year->dates)) {
				$this->dates = array_index($year->dates, 'date');
			}

			if ($year) {
				$this->year_start = new DateTime($year->date_start);
				$this->year_end = new DateTime($year->date_end);
			}

		}

		// First day of week
		$this->first_day = (int) setting('week_starts');

		// Get last day of week
		$day_name = $this->day_names["{$this->first_day}"];
		$dt = new DateTime($day_name);
		$dt->modify('-1 day');
		$this->last_day = $dt->format('N');
		$this->last_day_name = $dt->format('l');
	}


	public function get_all_months()
	{
		$start_date = new DateTime($this->year->date_start);
		$end_date = new DateTime($this->year->date_end);
		$interval = new DateInterval('P1M');
		$period = new DatePeriod($start_date, $interval, $end_date);

		$out = [];
		foreach ($period as $k => $v) {
			$out[] = $this->month($v);
		}

		return $out;
	}


	public function has_holiday($date)
	{
		$exists = array_key_exists($date, $this->dates);
		$has_hol = $exists && strlen($this->dates[$date]->holiday_id);
		return $has_hol;
	}


	public function date_week_id($date)
	{
		$exists = array_key_exists($date, $this->dates);
		$has_week = $exists && strlen($this->dates[$date]->week_id);
		return $has_week ? $this->dates[$date]->week_id : NULL;
	}


	/**
	 * Get array of day numbers, in order, starting from the configured first day of week.
	 *
	 */
	public static function get_days_of_week()
	{
		$day = (int) setting('week_starts');
		if ($day > 7 || $day < 1) {
			$day = 1;
		}

		$days = [];

		while (count($days) < 7) {
			$days[] = $day;
			if ($day == 7) {
				$day = 1;
			} else {
				$day++;
			}
		}

		return $days;
	}


	/**
	 * Get all dates for the given month.
	 * Considers the first day of the week, as well as days in the previous + next months.
	 *
	 */
	public function get_month_dates($month)
	{
		$week_starts_day_name = $this->day_names["{$this->first_day}"];

		// first day of month
		$start_date = new DateTime($month->format('Y-m-01'));
		$end_date = new DateTime($month->format('Y-m-t'));
		$interval = new DateInterval('P1D');

		// Expand prev boundary to align with first day of week + prev month days
		$start_date->modify('+1 day');
		$start_date->modify("last {$week_starts_day_name}");

		// Get last day of week (-1 of first day)
		$dt = clone $start_date;
		$dt->modify('-1 day');
		$week_ends_day_name = $dt->format('l');

		// Expand next boundary to align with last day of week + next month days
		$end_date->modify('+1 week');
		$end_date->modify("last {$week_ends_day_name}");
		$end_date->modify("+1 day");

		$period = new DatePeriod($start_date, $interval, $end_date);
		return $period;
	}


	/**
	 * Get the custom CSS - styles the calendar ranges with week colours.
	 *
	 */
	public function get_css()
	{
		$css = '';

		$template = $this->CI->load->view('css/academic-year-calendar.css', [], TRUE);
		$this->CI->load->library('parser');

		foreach ($this->weeks as $week) {

			$bright = colour_brightness($week->colour);

			$vars = [
				'week_id' => $week->week_id,
				'range_bg' => colour_tint($week->colour, .15),
				'range_fg' => colour_shade($week->colour, .60),
				'boundary_bg' => '#' . $week->colour,
				'boundary_border' => colour_shade($week->colour, .9),
				'boundary_fg' => $bright > 130 ? '#000000' : '#ffffff',
			];

			$css .= $this->CI->parser->parse_string($template, $vars, TRUE) . "\n";
		}

		return $css;
	}


	/**
	 * Get markup for a given month.
	 *
	 */
	public function month($month)
	{
		$month_num = $month->format('n');

		// Defaults
		$nav = '';
		$header = '';
		$body = '';
		$container = '';

		// Navigation
		$month_name = strtolower($month->format('F'));
		$month_label = lang("month_{$month_name}");
		$title = "{$month_label} {$month->format('Y')}";
		$nav_left = '';
		$nav_right = '';

		if ($this->config['navigation']) {

			$vars = [ '{date}', '{yyyy}', '{mm}', '{dd}' ];

			$left_date = clone($month);
			$left_date->modify('-1 month');
			$left_date->modify('last day of this month');

			$values = [
				'date' => $left_date->format('Y-m-d'),
				'yyyy' => $left_date->format('Y'),
				'mm' => $left_date->format('m'),
				'dd' => $left_date->format('d'),
			];

			$left_url = str_replace($vars, $values, $this->config['nav_url_format']);
			$left_url = site_url($left_url);

			$disabled = ($left_date < $this->year_start ? 'disabled' : '');

			$nav_left = "<button
				data-ic-target='.calendar-wrapper'
				data-ic-get-from='{$left_url}'
				class='btn btn-action btn-link btn-lg btn-icon-only'
				{$disabled}
				>" . icon('chevron-left') . "</button>";

			$right_date = clone($month);
			$right_date->modify('+1 month');
			$right_date->modify('first day of this month');

			$values = [
				'date' => $right_date->format('Y-m-d'),
				'yyyy' => $right_date->format('Y'),
				'mm' => $right_date->format('m'),
				'dd' => $right_date->format('d'),
			];

			$right_url = str_replace($vars, $values, $this->config['nav_url_format']);
			$right_url = site_url($right_url);

			$disabled = ($right_date > $this->year_end ? 'disabled' : '');

			$nav_right = "<button
				data-ic-target='.calendar-wrapper'
				data-ic-get-from='{$right_url}'
				class='btn btn-action btn-link btn-lg btn-icon-only'
				{$disabled}
				>" . icon('chevron-right') . "</button>";
		}

		$nav = "<div class='calendar-nav navbar'>";
		$nav .= "{$nav_left}<div class='navbar-primary'>{$title}</div>{$nav_right}";
		$nav .= "</div>";

		// Header
		$columns = [];
		$days = self::get_days_of_week();
		foreach ($days as $day_num) {
			$label = lang("day_{$day_num}_short");
			$columns[] = "<div class='calendar-date'>{$label}</div>";
		}
		$header = "<div class='calendar-header'>";
		$header .= implode("\n", $columns);
		$header .= "</div>";

		// Body
		// Each date cell element
		$dates = [];
		// Get month dates with prev/next on either side
		$period = $this->get_month_dates($month);

		// Week starts on... update every time date's week day num == first_day
		$week_start = '';

		foreach ($period as $dt) {

			$classes = [];
			$data = [];
			$week_end = '';
			$disabled = '';

			$date_month_num = $dt->format('n');
			$date_day_num = $dt->format('N');
			$date_num = $dt->format('j');
			$date_ymd = $dt->format('Y-m-d');

			// Checks for prev/next month dates
			if ($date_month_num < $month_num) {
				$classes[] = 'prev-month';
			} elseif ($date_month_num > $month_num) {
				$classes[] = 'next-month';
			}

			if ($date_day_num == $this->first_day && $dt != $this->year_start) {
				$week_start = $dt->format('Y-m-d');
			}

			if ($date_day_num == $this->first_day && $date_month_num == $month_num) {
				$classes[] = 'range-start';
			}

			// Adjust if date is start of academic year.
			// Ensures range correctly starts here
			if ($dt == $this->year_start) {
				$week_start = $dt->format('Y-m-d');
				$classes[] = 'range-start';
			}

			// Looks neater if we don't add range-end to last day of week
			if ($date_day_num == $this->last_day && $date_month_num == $month_num) {
				// $classes[] = 'range-end';
			}

			// De-activate buttons if they're outside the range of the academic year
			if ($dt < $this->year_start || $dt > $this->year_end) {
				$disabled = "disabled='disabled'";
			}

			$data['data-date'] = $date_ymd;
			$data['data-weekstarts'] = $week_start;
			$data['data-ui'] = 'calendar_date';

			// Check if date is already configured for a week
			$week_id = $this->date_week_id($date_ymd);
			$data['data-weekid'] = $week_id;

			$input = '';
			if ($this->config['inputs']) {
				$input = form_hidden("dates[{$date_ymd}]", $week_id);
			}


			// Got a Week ID already? Add range + data-attr
			if ($week_id) {
				$classes[] = 'calendar-range';
				$classes[] = "week-{$week_id}";
			}

			$date_class = implode(" ", $classes);
			$data_str = _stringify_attributes($data);

			// Add badge for holidays
			$date_item_class = $this->has_holiday($date_ymd) ? 'badge' : '';

			// If date is 'current' or 'active' date, add highlighting class.
			if ($this->config['active_date'] == $dt) {
				$date_item_class .= 'date-item-active';
			}

			if ($this->config['date_links']) {

				// `date_links` turn the buttons into <a> tags that go to a URL

				$vars = [ '{date}', '{yyyy}', '{mm}', '{dd}' ];

				$values = [
					'date' => $dt->format('Y-m-d'),
					'yyyy' => $dt->format('Y'),
					'mm' => $dt->format('m'),
					'dd' => $dt->format('d'),
				];

				$date_url = str_replace($vars, $values, $this->config['date_url_format']);
				$date_url = site_url($date_url);

				$button = "<a
					href='{$date_url}'
					class='date-item {$date_item_class}'
					data-ui='calendar_date_btn' {$disabled}>{$date_num}</a>";

			} else {

				$button = "<button
					type='button'
					class='date-item {$date_item_class}'
					data-ui='calendar_date_btn'
					{$disabled}>{$date_num}</button>";

			}

			$dates[] = "<div href='#' class='calendar-date {$date_class}' {$data_str}>{$button}{$input}</div>\n";
		}

		$body = "<div class='calendar-body'>";
		$body .= implode("\n", $dates);
		$body .= "</div>";

		$container = "{$header}\n{$body}";

		$out = "<div class='calendar {$this->config['class']}'>{$nav}\n{$container}\n</div>";

		return $out;
	}


}
