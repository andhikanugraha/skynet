<?php

// auth component

class AuthComponent extends HeliumComponent {

	public $prerequisite_components = array('sessions');

	public $controller;
	public $session;

	public $auth_controller_name = 'auth';
	public $login_action_name = 'login';
	public $logout_action_name = 'logout';

	public function init($controller) {
		$this->controller = $controller;
		$this->sessions = $controller->sessions;
		$this->session = $controller->session;

		$controller->_alias_method(array($this, 'require_authentication'));
		$controller->_alias_method(array($this, 'require_role'));
		
		$auth = $this;
		$controller->is_logged_in = function() use ($auth) { return $auth->is_logged_in(); };
		$controller->require_authentication = function() use ($auth) { return $auth->require_authentication(); };
		$controller->require_role = function($role) use ($auth) { return $auth->require_role($role); };
	}

	public function process_login($username, $password) {
		$user = User::find_by_username_and_password($username, $password);
		if ($user) {
			$this->session->user_password_hash = $user->password_hash;
			$this->session->user = $user;
			$this->session->save();
			return true;
		}
		else
			return false;
	}

	public function redirect_to_login_page() {
		@header('HTTP/1.1 401 Unauthorized');
		Gatotkaca::redirect(array('controller' => $this->auth_controller_name, 'action' => $this->login_action_name));
	}

	public function process_logout() {
		$this->controller->sessions->destroy_session();
	}

	public function is_logged_in() {
		return (bool) $this->session->user_id;
	}

	public function require_authentication() {
		if (!$this->is_logged_in()) {
			$this->controller->render = false;
			$this->session['last_params'] = $this->controller->params;
			$this->session->save();
			$this->redirect_to_login_page();
		}
	}

	public function require_role($role) {
		$this->require_authentication();

		if (!$this->session->user->capable_of($role)) {
			$this->controller->render = false;
			$this->session['last_params'] = $this->controller->params;
			$this->session->save();
			$this->redirect_to_login_page();
		}
	}

	public function land() {
		if ($this->is_logged_in()) {
			$user = $this->session->user;
			Gatotkaca::redirect($user->get_landing_page());
		}
		elseif ($lp = $this->session->flash('last_params'))
			Gatotkaca::redirect($lp);
		exit;
	}
}