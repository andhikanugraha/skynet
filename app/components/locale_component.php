<?php

class LocaleComponent extends HeliumComponent {
	
	public function init() {
		// do two things:
		// 1. add a locale for translations
		// 2. set a local timezone

		$translations = array(
			'Monday' => 'Senin',
			'Tuesday' => 'Selasa',
			'Wednesday' => 'Rabu',
			'Thursday' => 'Kamis',
			'Friday' => 'Jumat',
			'Saturday' => 'Sabtu',
			'Sunday' => 'Minggu',
			'January' => 'Januari',
			'February' => 'Februari',
			'March' => 'Maret',
			'May' => 'Mei',
			'June' => 'Juni',
			'July' => 'Juli',
			'August' => 'Agustus',
			'October' => 'Oktober',
			'December' => 'Desember'
		);
		HeliumDateTime::add_locale('id', $translations);
		HeliumDateTime::set_default_timezone(Helium::conf('site_timezone'));
	}
	
}