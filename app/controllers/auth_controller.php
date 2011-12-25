<?php

class AuthController extends AppController {

	public $default_action = 'login';

	public function login() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$username = trim($_POST['username']);
			$username = substr($username, 0, 255);
			$password = $_POST['password'];

			$try = $this->auth->process_login($username, $password);

			if ($try) {
				if ($_POST['remember'])
					$this->sessions->make_persistent();

				$this->session['just_logged_in'] = true;
				$this->auth->land();
			}
			else {
				$this->session['username'] = $username;
				$this['mode'] = 'fail';
			}
		}
		$dates = Helium::conf('dates');
		$reg_deadline = $dates['registration_deadline'];
		$now = new HeliumDateTime;
		$this['can_register'] = $now->earlier_than($reg_deadline);

		$lp = $this->session['last_params'];
		$destination_name = $lp['controller'] . '::' . $lp['action'];
		$this['destination_name'] = $destination_name;
		$this['destination_controller'] = $lp['controller'];
	}

	public function logout() {
		$this->render = false;
		$this->auth->process_logout();
		Gatotkaca::redirect('/');
	}
}