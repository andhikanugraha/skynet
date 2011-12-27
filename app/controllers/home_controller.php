<?php

class HomeController extends AppController {

	public function firstrun_check() {
		$check = User::find()->count_all();
		if (!$check) {
			// First Run!
			$chapter = new Chapter;
			$chapter->chapter_code = 'INA';
			$chapter->chapter_name = 'Kantor Nasional';
			$chapter->chapter_timezone = 'Asia/Jakarta';
			$chapter->save();

			$user = new User;
			$user->username = 'admin';
			$user->set_password('admin');
			$user->role = 5;
			$user->chapter_id = $chapter->id;
			$user->save();

			$chapters = array (
				'DPS' => 'Bali',
				'BPP' => 'Balikpapan',
				'BNA' => 'Banda Aceh',
				'BDG' => 'Bandung',
				'BMS' => 'Banjarmasin',
				'BGR' => 'Bogor',
				'JKT' => 'Jakarta',
				'KRW' => 'Karawang',
				'MKS' => 'Makassar',
				'MLG' => 'Malang',
				'MDN' => 'Medan',
				'PDG' => 'Padang',
				'PLM' => 'Palembang',
				'SMD' => 'Samarinda',
				'SMG' => 'Semarang',
				'SUB' => 'Surabaya',
				'JOG' => 'Yogyakarta',
			);
			
			$area = array (
				'DPS' => 'Bali',
				'BPP' => 'Kalimantan Timur',
				'BNA' => 'Aceh',
				'BDG' => 'Jawa Barat',
				'BMS' => 'Kalimantan Selatan',
				'BGR' => 'Jawa Barat',
				'JKT' => 'DKI Jakarta',
				'KRW' => 'Jawa Barat',
				'MKS' => 'Sulawesi Selatan',
				'MLG' => 'Jawa Timur',
				'MDN' => 'Sumatera Utara',
				'PDG' => 'Sumatera Barat',
				'PLM' => 'Sumatera Selatan',
				'SMD' => 'Kalimantan Timur',
				'SMG' => 'Jawa Tengah',
				'SUB' => 'Jawa Timur',
				'JOG' => 'DI Yogyakarta',
			);
			
			$timezones = array (
				'DPS' => 'Asia/Ujung_Pandang',
				'BPP' => 'Asia/Ujung_Pandang',
				'BNA' => 'Asia/Jakarta',
				'BDG' => 'Asia/Jakarta',
				'BMS' => 'Asia/Ujung_Pandang',
				'BGR' => 'Asia/Jakarta',
				'JKT' => 'Asia/Jakarta',
				'KRW' => 'Asia/Jakarta',
				'MKS' => 'Asia/Ujung_Pandang',
				'MLG' => 'Asia/Jakarta',
				'MDN' => 'Asia/Jakarta',
				'PDG' => 'Asia/Jakarta',
				'PLM' => 'Asia/Jakarta',
				'SMD' => 'Asia/Ujung_Pandang',
				'SMG' => 'Asia/Jakarta',
				'SUB' => 'Asia/Jakarta',
				'JOG' => 'Asia/Jakarta',
			);

			foreach ($chapters as $code => $name) {
				$chapter = new Chapter;
				$chapter->chapter_code = $code;
				$chapter->chapter_name = $name;
				$chapter->chapter_timezone = $timezones[$code];
				$chapter->chapter_area = $area[$code];
				$chapter->save();
				
				$user = new User;
				$user->username = 'chapter_' . strtolower(str_replace(' ', '_', $name));
				$user->set_password('antarbudaya');
				$user->role = 4;
				$user->chapter_id = $chapter->id;
				$user->save();
			}
		}
	}

	public function index() {
		$this->firstrun_check();
	}

}