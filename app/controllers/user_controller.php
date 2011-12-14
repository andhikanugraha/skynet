<?php

class UserController extends GatotkacaController {

	public function prefs() {
		$this->require_authentication();

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$validate = array();
			
			$old_password = $_POST['old_password'];
			$password = $_POST['password'];
			$retype_password = $_POST['retype_password'];

			$validate['incomplete'] = isset($old_password, $password, $retype_password);

			$old_password_hash = User::hash_password($old_password);
			$validate['wrong_old_password'] = $old_password_hash == $this->session->user->password_hash;

			// validate password
			$validate['password'] = strlen($password) >= 8;

			// validate retype password
			$validate['retype_password'] = ($password == $retype_password);

			$errors = array();
			$valid = true;
			foreach ($validate as $type => $check) {
				if (!$check) {
					$valid = false;
					$errors[] = $type;
				}
			}

			if (!$valid) {
				$this['mode'] = 'fail';
				$this['errors'] = $errors;
			}
			else {
				$user = $this->session->user;
				$user->set_password($password);
				$password_hash = $user->hash_password($password);
				$this->session->user_password_hash = $password_hash;
				$user->save();
				$p = $this->params;
				$p['success'] = '1';
				Gatotkaca::redirect($p);
			}
		}

		$this['success'] = $this->params['success'];
	}

}