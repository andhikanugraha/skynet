<?php

// reCAPTCHA for Gatotkaca

class RECAPTCHA {
	
	public $public_key = '';
	public $private_key = '';
	public $error = '';
	
	public function __construct() {
		$this->public_key = Helium::conf('recaptcha_public_key');
		$this->private_key = Helium::conf('recaptcha_private_key');
	}

	public function get_html() {
		require_once 'recaptchalib.php';
		return recaptcha_get_html($this->public_key);
	}
	
	public function check_answer() {
		require_once 'recaptchalib.php';

		$resp = recaptcha_check_answer ($this->private_key,
										$_SERVER["REMOTE_ADDR"],
										$_POST["recaptcha_challenge_field"],
										$_POST["recaptcha_response_field"]);

		if ($resp->is_valid)
			return true;
		else {
			$this->error = $resp->error;
			return false;
		}
	}

}