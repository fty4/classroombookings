<?php

class MY_Form_validation extends CI_Form_validation
{


	protected $CI;


	public function __construct()
	{
		parent::__construct();
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
			$this->set_message('user_email_unique', 'There is already an account using that email address.');
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


}
