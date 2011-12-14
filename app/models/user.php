<?php

// Project Gatotkaca
// Model: User

class User extends HeliumRecord {

	public $id;
	public $username;
	public $password_hash;
	public $email;
	public $role; // either 'applicant', 'volunteer' or 'admin'

	public static $roles = array('applicant', 'volunteer', 'admin', 'sponsors.telkomsel');

	public function rebuild() {
		if ($this->capable_of('applicant'))
			$this->has_one('applicant');
	}

	public function capable_of($role) {
		switch ($role) {
			case 'volunteer':
				return ($this->role == $role) || ($this->role == 'admin');
				break;
			case 'sponsors.telkomsel':
			case 'applicant':
			case 'admin':
			default:
				return ($this->role == $role);
		}
	}

	public function get_landing_page() {
		switch ($this->role) {
			case 'applicant':
				$land = $this->applicant->get_landing_page();
				break;
			case 'volunteer':
				$land = array('controller' => 'volunteer');
				break;
			case 'admin':
				$land = array('controller' => 'admin');
				break;
			case 'sponsors.telkomsel':
				$land = array('controller' => 'telkomsel');
		}

		return $land;
	}

	public static function hash_password($unhashed_password) {
		return sha1($unhashed_password);
	}

	public static function find_by_username_and_password($username, $password) {
		$password_hash = self::hash_password($password);

		$find = self::find(compact('username', 'password_hash'));

		if ($find)
			return $find->first();
		else
			return false;
	}

	public static function find_by_username_and_password_hash($username, $password_hash) {
		$find = self::find(compact($username, $password_hash));

		if ($find)
			return $find->first();
		else
			return false;
	}
	
	public function set_password($unhashed_password) {
		$this->password_hash = $this->hash_password($unhashed_password);
	}
}