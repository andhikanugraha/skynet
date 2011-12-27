<?php

// Project Gatotkaca
// Common functions wrapper

class Gatotkaca {
	static $parsed_cookie_params = false;
	static $cookie_path;
	static $cookie_domain;

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

	public static function setcookie($name, $value, $expire) {
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

	public static function sanitize_school($school) {
		// sanitize school name
		// use last school if multiple schools were used
		if ($slash = strpos($school, '/'))
			$school = substr($school, $slash + 1);
		// trim
		$school = trim($school);

		// sanitize the casing
		$lowercased = strtolower($school);
		$cased = ucwords($lowercased);
		$cased = implode('-', array_map('ucfirst', explode('-', $cased))); // from php.net/ucwords
		$school = $cased;
		
		// sanitize misspellings
		$mispell = array(
			'/Negri/i' => 'Negeri',
			'/Band(in|u)g/i' => 'Bandung',
			'/Bdg/i' => 'Bandung',
			'/Alfa Centaury/i' => 'Alfa Centauri',
			'/\s+/' => ' ',
			'/[.,]/' => '',
		);
		// do replacing here as it will affect subsequent patterns
		$school = preg_replace(array_keys($mispell), $mispell, $school);
		
		// sanitize SMAN -> SMA Negeri, etc.
		$uniform = array(
			'/\s+/' => ' ',
			'/^SM(A|K|P)N\s/i' => 'SM$1 Negeri ',
			'/^SM(A|P)K\s/i' => 'SM$1 Kristen ',
			'/^SM(A|P)T\s/i' => 'SM$1 Terpadu ',
			'/^M(A|Ts)N\s/i' => 'M$1 Negeri ',
			'/Sekolah Menengah Atas/i' => 'SMA',
			'/Sekolah Menengah Kejuruan/i' => 'SMK',
			'/^Madrasah Aliyah\s/' => 'MA ',
			'/^Madrasah Tsanawiyah\s/' => 'MTs ',
			'/\sKota\s/i' => ' ',
			'/\sSwasta\s/i' => ' ',
			'/Kab\s/i' => 'Kabupaten ',
			'/([0-9])([a-z])/i' => '$1 $2',
		);
		$school = preg_replace(array_keys($uniform), $uniform, $school);

		// sanitize specific school names
		$specific = array(
			'/([0-9])$/' => '$1 Bandung',
			'/^SM(A|P) Taruna Bakti.*/i' => 'SM$1 Taruna Bakti Bandung',
			'/^SM(A|P).*Yahya$/i' => 'SM$1 Kristen Yahya Bandung',
			'/^SM(A|P).*Yahya Bandung$/i' => 'SM$1 Kristen Yahya Bandung',
			'/^SMA.*Alfa Centauri$/i' => 'SMA Alfa Centauri Bandung',
			'/Sukamanah$/' => 'Sukamanah Tasikmalaya',
			'/^.*Darul Arqam.+$/i' => 'MA Darul Arqam Muhammadiyah Daerah Garut',
			'/^.*Pribadi.+$/i' => 'Pribadi Bilingual Boarding School Bandung',
			'/^.*Muthahhari$/i' => 'SMA Plus Muthahhari Bandung',
			'/^(.*Margahayu)( Bandung)?$/i' => '$1 Kabupaten Bandung',
			'/^SMA Terpadu Baiturrahman$/i' => 'SMA Terpadu Baiturrahman Kabupaten Bandung'
		);
		$school = preg_replace(array_keys($specific), $specific, $school);

		// revert abbreviations
		$abbreviations = array('SMA ', 'SMK ', 'SMP ', 'SD ', 'MA ', 'MTs ', 'MI ', 'BPK ', 'BPI ', 'IBS ', 'ITUS ');
		$school = str_ireplace($abbreviations, $abbreviations, $school);

		return $school;
	}
}