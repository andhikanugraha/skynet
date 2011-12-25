<?php

/**
 * User
 *
 * @author Andhika Nugraha <andhika.nugraha@gmail.com>
 * @package auth
 */
class User extends HeliumRecord {

	public $id;
	public $username;
	public $password_hash;
	public $email;
	public $role;	// now an integer
	public $chapter_id;
	public $email_verified;

	public function init() {
		$this->belongs_to('chapter');
	}

	public function rebuild() {
		if ($this->role == 1) {
			$this->has_one('applicant');
		}
	}

	public function capable_of($role, $chapter_id = 0) {
		switch ($role) {
			case 'applicant':
				$min = 1;
				break;
			case 'volunteer':
				$min = 2;
				break;
			case 'chapter':
				$min = 3;
				break;
			case 'chadmin':
				$min = 4;
				break;
			case 'nadmin':
			case 'admin':
			default:
				$min = 5;
		}

		if ($chapter_id) {
			if ($this->role >= 5)
				return true;
			else
				return $this->role >= $min && $this->chapter_id = $chapter_id;
		}
		else
			return $this->role >= $min;
	}

	/**
	 * @deprecated
	 */
	public function get_landing_page() {
		switch ($this->role) {
			case 1:
				if ($this->applicant->finalized)
					$land = array();
				elseif ($this->applicant->confirmed)
					$land = array();
				else
					$land = array('controller' => 'applicant', 'action' => 'form');
				$land = array('controller' => 'applicant', 'action' => 'form', 'id' => $this->applicant->finalized);
				break;
			case 'volunteer':
				$land = array('controller' => 'volunteer');
				break;
			case 5:
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