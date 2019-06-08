<?php

class MY_Form_validation extends CI_Form_validation
{


	protected $CI;


	public function __construct($rules = array())
	{
		parent::__construct($rules);
		$this->CI =& get_instance();
	}



	/**
	 * Check that an email address is not taken.
	 *
	 * To be used when updating account details - hence the $user_id param to check
	 *
	 */
	public function user_email_unique($email, $user_id = '')
	{
		if (strlen($user_id) && is_numeric($user_id)) {
			$sql = 'SELECT email FROM users WHERE user_id != ? AND email = ?';
			$query = $this->CI->db->query($sql, array($user_id, $email));
		} else {
			$sql = 'SELECT email FROM users WHERE email = ?';
			$query = $this->CI->db->query($sql, array($email));
		}

		$available = ($query->num_rows() == 0);

		if ( ! $available) {
			$this->set_message('user_email_unique', $this->CI->lang->line('user_validation_email_unavailable'));
		}

		return $available;
	}


	/**
	 * Check that a username is not taken.
	 *
	 * To be used when adding/updating user accounts, hence the $user_id param to check
	 *
	 */
	public function user_username_unique($username, $user_id = '')
	{
		if (strlen($user_id) && is_numeric($user_id)) {
			$sql = 'SELECT username FROM users WHERE user_id != ? AND username = ?';
			$query = $this->CI->db->query($sql, array($user_id, $username));
		} else {
			$sql = 'SELECT username FROM users WHERE username = ?';
			$query = $this->CI->db->query($sql, array($username));
		}

		$available = ($query->num_rows() == 0);

		if ( ! $available) {
			$this->set_message('user_username_unique', $this->CI->lang->line('user_validation_username_unavailable'));
		}

		return $available;
	}


	public function is_current_password($password, $username = '')
	{
		if (empty($username)) {
			$this->set_message('is_current_password', 'No current user specified.');
			return FALSE;
		}

		$result = $this->CI->userauth->authenticate($username, $password);

		if ($result === FALSE) {
			$this->set_message('is_current_password', "Incorrect password.");
			return FALSE;
		}

		return TRUE;
	}




	/**
	 * Run the supplied date in Y-m-d format through PHP's native checkdate() function
	 * to make sure it's actually valid.
	 *
	 */
	public function valid_date($date)
	{
		// Explode the date in Y-m-d format
		list($y, $m, $d) = explode('-', $date);
		$date_ok = checkdate($m, $d, $y);

		if (!$date_ok) {
			$this->set_message('valid_date', 'The date entered is not a valid date.');
		}

		return $date_ok;
	}


	public function date_after($value, $start_date = '')
	{
		$check_date = new \DateTime($value);
		$check_date->setTime(0, 0, 0);

		if ( ! $check_date) {
			$this->set_message('date_after', $this->CI->lang->line('form_validation_invalid_date'));
			return FALSE;
		}

		// If $start_date is a field reference, use that value
		if (isset($this->_field_data[$start_date], $this->_field_data[$start_date]['postdata'])) {
			$start_date = $this->_field_data[$start_date]['postdata'];
		}

		$start_date = new \DateTime($start_date);
		$start_date->setTime(0, 0, 0);
		if ( ! $start_date) {
			$this->set_message('date_after', $this->CI->lang->line('form_validation_invalid_date'));
			return FALSE;
		}

		if ($check_date < $start_date) {
			$fmt = $start_date->format('d/m/Y');
			$str = $this->CI->lang->line('form_validation_error_date_after');
			$this->set_message('date_after', sprintf($str, $fmt));
			return FALSE;
		}

		return TRUE;
	}


	public function date_before($value, $end_date = '')
	{
		$check_date = new \DateTime($value);
		$check_date->setTime(0, 0, 0);

		if ( ! $check_date) {
			$this->set_message('date_before', $this->CI->lang->line('form_validation_invalid_date'));
			return FALSE;
		}

		// If $end_date is a field reference, use that value
		if (isset($this->_field_data[$end_date], $this->_field_data[$end_date]['postdata'])) {
			$end_date = $this->_field_data[$end_date]['postdata'];
		}

		$end_date = new \DateTime($end_date);
		$end_date->setTime(0, 0, 0);
		if ( ! $end_date) {
			$this->set_message('date_before', $this->CI->lang->line('form_validation_invalid_date'));
			return FALSE;
		}

		if ($check_date > $end_date) {
			$fmt = $end_date->format('d/m/Y');
			$str = $this->CI->lang->line('form_validation_error_date_before');
			$this->set_message('date_before', sprintf($str, $fmt));
			return FALSE;
		}

		return TRUE;
	}


}
