<?php

/**
 * RegistrationCode
 *
 * @author Andhika Nugraha <andhika.nugraha@gmail.com>
 * @package chapter
 */
class RegistrationCode extends HeliumRecord {

	const token_length = 16;

	public $lifetime = '8 days';
	
	public $id;
	public $token;
	public $expires_on;
	public $availability;
	public $applicant_id;
	public $created_at;
	public $program_year;
	public $chapter_id;
	public $generated_by;

	public function init() {
		$this->belongs_to('chapter');
		$this->belongs_to('registration_code_batch');
	}

	public function defaults() {
		$this->token = $this->generate_token();
		$this->expires_on = new HeliumDateTime;

		$lifetime = Helium::conf('registration_code_lifetime');
		if (!$lifetime)
			$lifetime = $this->lifetime;

		$this->expires_on->modify('+' . $lifetime);
	}

	public static function generate_token($length = self::token_length) {
		$token = '';
		for ($i=1; $i<$length+3; $i++) {
			$rand = mt_rand(65, 90); // between A and Z.
			if ($rand >= 55 && $rand <= 90) {
				if ($rand < 65)
					$token .= (string) ($rand - 13);
				else
					$token .= chr($rand);
			}
			else
				$i--;
		}

		$token = substr($token, 0, $length);

		return $token;
	}

	public static function find_by_token($token) {
		// Possible hashing algorithm going on here

		$find = RegistrationCode::find(compact('token'));
		return $find->first();
	}

	public function redeem() {
		$this->availability = false;
	}

	public function is_available() {
		return $this->availability;
	}

	public function is_expired() {
		return !$this->expires_on->later_than('now');
	}

	public function is_valid() {
		return $this->is_available() && !$this->is_expired();
	}
	
	public function get_chunked() {
		$chunk_size = ceil(strlen($this->token) / 2);
		return implode(' ', str_split($this->token, $chunk_size));
	}

	/**
	 * @deprecated
	 */
	public function validate() {
		if (!$this->availability) {
			$this->validation_error = 'token_unavailable';
			return false;
		}

		if (!$this->expires_on->later_than('now')) {
			$this->validation_error = 'token_expired';
			return false;
		}

		return true;
	}
};