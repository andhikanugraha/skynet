<?php

// Model: Registration Code

class RegistrationCode extends HeliumRecord {

	public $token = '';
	public $expires_on;
	public $availability = true;
	public $user_id = 0;

	public $lifetime = '8 days';
	public $validation_error = '';

	public $source;
	public $updated_at;

	const token_length = 8;

	public function init() {
		// $this->belongs_to('user');
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
		$find = RegistrationCode::find(compact('token'));
		return $find->first();
	}

	public function redeem() {
		$this->availability = false;
		$this->save();
	}

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