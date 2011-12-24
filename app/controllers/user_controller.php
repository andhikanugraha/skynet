<?php

class UserController extends GatotkacaController {

	public function prefs() {
		$this->require_authentication();

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$validate = array();

			$old_password = $_POST['old_password'];
			$password = $_POST['password'];
			$retype_password = $_POST['retype_password'];

			if (!$error) {
				$old_password_hash = User::hash_password($old_password);
				if ($old_password_hash != $this->session->user->password_hash)
					$error = 'previous_password_incorrect';
			}

			if (!$error && $password != $retype_password)
				$error = 'password_mismatch';

			if (!$error && strlen($password) < 8)
				$error = 'password_too_short';

			if (!$error) {
				$user = $this->session->user;
				$user->set_password($password);
				$password_hash = $user->hash_password($password);
				$this->session->user_password_hash = $password_hash;
				$user->save();
				$this->session['success'] = true;
				$this->http_redirect($this->params);
			}
			else {
				$this['error'] = $error;
			}
		}

		$this['success'] = $this->session->flash('success');
		$this['form'] = new FormDisplay;
	}

}