<?php

/**
 * LocaleComponent
 *
 * Application localization
 *
 * @author Andhika Nugraha <andhika.nugraha@gmail.com>
 * @package locale
 */
class LocaleComponent extends HeliumComponent {
	public static $translations = array(
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
		'December' => 'Desember',
		'(Day)' => '(Tanggal)',
		'(Month)' => '(Bulan)',
		'(Year)' => '(Tahun)',
		'Town' => 'Kota',
		'State/Province' => 'Provinsi',
		'Postcode' => 'Kode Pos',
		'Mobile Phone' => 'HP',
		'Home Phone' => 'Telepon',
		'Fax' => 'Faks',
		'Asia/Jakarta' => 'WIB',
		'Asia/Ujung_Pandang' => 'WITA',
		'Asia/Jayapura' => 'WIT'
	);
	
	public function init() {
		$translations = self::$translations;

		HeliumDateTime::add_locale('id', $translations);
		HeliumDateTime::set_default_locale('id');
		self::set_timezone(Helium::conf('site_timezone'));

		foreach ($translations as $k => $v) {
			FormDisplay::$translations[$k] = $v;
		}
		
		FormDisplay::$address_states = array(
			'Aceh',
			'Sumatera Utara',
			'Sumatera Barat',
			'Riau',
			'Jambi',
			'Sumatera Selatan',
			'Bengkulu',
			'Lampung',
			'Kepulauan Bangka Belitung',
			'Kepulauan Riau',
			'DKI Jakarta',
			'Jawa Barat',
			'Jawa Tengah',
			'DI Yogyakarta',
			'Jawa Timur',
			'Banten',
			'Bali',
			'Nusa Tenggara Barat',
			'Nusa Tenggara Timur',
			'Kalimantan Barat',
			'Kalimantan Tengah',
			'Kalimantan Selatan',
			'Kalimantan Timur',
			'Sulawesi Utara',
			'Sulawesi Tengah',
			'Sulawesi Selatan',
			'Sulawesi Tenggara',
			'Gorontalo',
			'Sulawesi Barat',
			'Maluku',
			'Maluku Utara',
			'Papua Barat',
			'Papua'
		);
		
		function __($string) {
			return	LocaleComponent::$translations[$string] ?
					LocaleComponent::$translations[$string] :
					$string;
		}
	}
	
	public static function set_timezone($timezone) {
		HeliumDateTime::set_default_timezone($timezone);
	}
}