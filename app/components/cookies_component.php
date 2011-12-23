<?php

/**
 * CookiesComponent
 *
 * @author Andhika Nugraha <andhika.nugraha@gmail.com>
 * @package auth
 */
class CookiesComponent extends HeliumComponent {
	public static $parsed_cookie_params = false;
	public static $cookie_path;
	public static $cookie_domain;

	public static function set_cookie($name, $value, $expire) {
		if (!self::$parsed_cookie_params) {
			$base_uri = Helium::conf('base_uri');
			$parts = parse_url($base_uri);
			extract($parts);
			ltrim($path, '/');
			$path = '/' . $path;
			self::$cookie_path = $path;
			self::$cookie_domain = $host;
			self::$parsed_cookie_params = true;
		}

		if (is_object($expire) && $expire instanceof DateTime)
			$expire = $expire->getTimestamp();

		$path = self::$cookie_path;
		$domain = self::$cookie_domain;
		$secure = (bool) $_SERVER['HTTPS'];
		$httponly = true;

		$try = @setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);

		if ($try)
			$_COOKIE[$name] = $value;

		return $try;
	}
}