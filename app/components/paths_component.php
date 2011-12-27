<?php

// Project Gatotkaca
// Common functions wrapper

class PathsComponent extends HeliumComponent {
	static $cookie_path;
	static $cookie_domain;

	public function init($controller) {
		$controller->_alias_method('http_redirect', array($this, 'redirect'));
		$controller->_alias_method(array($this, 'build_url'));
	}

	public static function redirect($target) {
		if (is_array($target) || strpos($target, ':') > 0)
			$target = self::build_url($target);

		@header('Location: ' . $target);

		exit;
	}

	public static function build_url($path) {
		if (is_array($path)) {
			$router = Helium::router();
			$path = Helium::router()->build_path($path);
		}

		$path = ltrim($path, '/');
		$path = '/' . $path;

		return Helium::conf('base_uri') . $path;
	}
}