<?php

// Project Gatotkaca
// Model: Session
//
// Session data is accessed as an array of a Session object.
//

class Session extends HeliumRecord implements ArrayAccess {

	public $id;
	public $user_id;
	public $token = '';
	public $data = array();
	public $is_persistent = false;
	public $expires_on;
	public $user_agent;
	public $ip_address;
	public $user_password_hash;

	public $check_password_hash;
	public $check_user_agent;
	public $check_ip_address;
	public $session_length;

	public function init() {
		$this->belongs_to('user');
		$this->auto_serialize('data');
		
		$this->check_password_hash = Helium::conf('session_check_password_hash');
		$this->check_user_agent = Helium::conf('session_check_user_agent');
		$this->check_ip_address = Helium::conf('session_check_ip_address');
		$this->session_length = Helium::conf('session_length');
	}
	
	public function defaults() {
		$this->token = $this->generate_token();
		$this->ip_address = $_SERVER['REMOTE_ADDR'];
		$this->user_agent = $_SERVER['HTTP_USER_AGENT'];
		$this->extend_expiry();
	}

	public function validate() {
		// for a session to be valid:
		// - its expiry date must be later than now
		// - the stored password must match the current password
		// - the user agent must match the client's user agent
		// - the stored IP address must match the client's IP address

		// check expiry
		if (!$this->expires_on->later_than('now'))
			return false;

		// check password_hash
		if ($this->check_password_hash) {
			$user = $this->user;
			// only do this check if we're logged in
			if (($user) && ($user->password_hash != $this->user_password_hash))
				return false;
		}

		// check user agent
		if ($this->check_user_agent) {
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
			if ($user_agent != $this->user_agent)
				return false;
		}

		// check ip address
		if ($this->check_ip_address) {
			$ip_address = $_SERVER['REMOTE_ADDR'];
			if ($ip_address != $this->ip_address)
				return false;
		}
		
		return true;
	}

	public function validate_or_destroy() {
		if (!$this->validate()) {
			$this->destroy();
			return false;
		}
		else
			return true;
	}
	
	public function generate_token() {
		// generate session token
		return uniqid('', true);
	}
	
	public function make_persistent() {
		$this->is_persistent = true;
	}

	public function extend_expiry($length = 0) {
		if (!$length)
			$length = $this->session_length;

		$this->expires_on = new HeliumDateTime;
		$this->expires_on->modify('+' . $length);
	}

	public function before_save() {
		// if the session is persistent, extend the expiry date
		$this->extend_expiry();
	}

	public static function find_by_token($token) {
		$find = Session::find(compact('token'));
		$find->fetch();

		return $find->first();
	}

	public function flash($var_name) {
		$return = $this->data[$var_name];
		unset($this->data[$var_name]);

		return $return;
	}

	// ArrayAccess functions

	public function offsetGet($offset) {
		return $this->data[$offset];
	}

	public function offsetSet($offset, $value) {
		$this->data[$offset] = $value;
	}

	public function offsetUnset($offset) {
		unset($this->data[$offset]);
	}

	public function offsetExists($offset) {
		return isset($this->data[$offset]);
	}
}