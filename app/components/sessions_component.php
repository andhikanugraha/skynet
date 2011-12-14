<?php

// Project Gatotkaca
// Component: Sessions

// 'persistent' means 'renew every visit'.
// if a session is not persistent, its cookie is deleted on browser close.

class SessionsComponent extends HeliumComponent {
	public $session = array();
	public $cookie_name = 'gatotkaca';

	// callbacks/events
	public $on_cookie_fail;

	public function __construct() {
		$this->cookie_name = Helium::conf('session_cookie_name');
	}

	public function init($controller) {
		$this->session = $this->fetch_session();

		$controller->session = &$this->session;

		if ($this->session->is_persistent)
			$this->renew_cookie();

		$this->on_cookie_fail = function() { return true; };
	}

	public function fetch_token_from_cookie() {
		return $_COOKIE[$this->cookie_name];
	}

	public function fetch_session() {
		$session_token = $this->fetch_token_from_cookie();

		if ($session_token) {
			$session = Session::find_by_token($session_token);
			if ($session && !$session->validate()) {
				$session->destroy();
				unset($session);
			}
		}
		if (!$session) {
			$session = new Session;
			$session->save();
			$this->renew_cookie($session);
		}

		return $session;
	}

	public function renew_cookie(Session $session_object = null) {
		if (!$session_object)
			$session_object = $this->session;

		$name = $this->cookie_name;
		$value = $session_object->token;
		$expire = $session_object->is_persistent ? $session_object->expires_on : 0;

		$try = Gatotkaca::setcookie($name, $value, $expire);

		if (!$try)
			call_user_func($this->on_cookie_fail);

		return $try;
	}

	public function flush_session() { // destroy the old session and start a new one. (logout and set new session data)
		$this->session->destroy();

		$this->session = new Session;
		$this->renew_cookie();

		return $this->session;
	}

	public function destroy_session() { // destroy the session and end up with no session at all. (logout-only)
		$this->session->destroy();

		$path = $this->cookie_path;
		$domain = $this->cookie_domain;
		$secure = $_SERVER['HTTPS'];

		$try = @setcookie($this->cookie_name, '', 0, $path, $domain, $secure, true);

		if (!$try)
			call_user_func($this->on_cookie_fail);

		return $try;
	}

	public function make_persistent() {
		$this->session->is_persistent = true;
		$this->session->save();
		$this->renew_cookie();
	}

	public function flash($var_name) {
		$return = $this->session[$var_name];
		unset($this->session[$var_name]);

		return $return;
	}

	public function __destruct() {
		if ($this->session)
			$this->session->save();
	}

}