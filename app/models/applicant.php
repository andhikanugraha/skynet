<?php

/**
 * Applicant
 *
 * @author Andhika Nugraha <andhika.nugraha@gmail.com>
 * @package applicant
 */
class Applicant extends HeliumPartitionedRecord {
	/**
	 * Record properties
	 */
	public $id;
	public $user_id;
	public $chapter_id;
	public $local_id;
	public $test_id;
	public $program_year;
	public $confirmed;
	public $finalized;
	public $expires_on;
	public $sanitized_full_name;
	public $sanitized_high_school_name;
	public $place_of_birth;
	public $date_of_birth;
	
	/**
	 * Validation properties
	 */
	public $validation_errors = array();
	public $incomplete_fields = array();
	
	/**
	 * Default values
	 */
	public function defaults() {
		$this->expires_on = new HeliumDateTime;
		$this->confirmed = false;
		$this->finalized = false;
	}

	/**
	 * Associations and partitions
	 */
	public function init() {
		$this->belongs_to('user');
		$this->belongs_to('chapter');

		$this->has_one('picture');

		$this->has_many('applicant_siblings');
		$this->has_many('applicant_organizations');
		$this->has_many('applicant_sports_achievements');
		$this->has_many('applicant_arts_achievements');
		$this->has_many('applicant_work_experiences');
		$this->has_many('applicant_other_achievements');
		
		$this->add_vertical_partition('applicant_activities');
		$this->add_vertical_partition('applicant_contact_info');
		$this->add_vertical_partition('applicant_education');
		$this->add_vertical_partition('applicant_fathers');
		$this->add_vertical_partition('applicant_guardians');
		$this->add_vertical_partition('applicant_high_schools');
		$this->add_vertical_partition('applicant_mothers');
		$this->add_vertical_partition('applicant_personal_details');
		$this->add_vertical_partition('applicant_personality');
		$this->add_vertical_partition('applicant_primary_school_grade_history');
		$this->add_vertical_partition('applicant_program_choices');
		$this->add_vertical_partition('applicant_recommendations');
		$this->add_vertical_partition('applicant_referral');
		$this->add_vertical_partition('applicant_secondary_school_grade_history');
		$this->add_vertical_partition('applicant_selection_progress');
		$this->add_vertical_partition('applicant_telkomsel');
		$this->add_vertical_partition('applicant_travel_history');
	}

	/**
	 * Default values
	 */
	public function before_save() {
		// Sanitized entries
		$this->sanitized_full_name = self::sanitize_name($this->full_name);
		$this->sanitized_high_school_name = self::sanitize_school($this->high_school_name);
		
		if (!$this->finalized && !$this->test_id)
			$this->test_id = $this->generate_test_id();
	}

	/**
	 * Finalize applicant if form is valid
	 */
	public function finalize() {
		if ($this->validate()) {
			$this->finalized = true;
			$this->local_id = $this->generate_local_id();
			$this->test_id = $this->generate_test_id();
			return true;
		}
		else
			return false;
	}

	/**
	 * Confirm applicant re-registration if has been finalized
	 */
	public function confirm() {
		if ($this->finalized) {
			$this->confirmed = true;
			return true;
		}
		else
			return false;
	}

	/**
	 * Generate local ID based on chapter
	 */
	public function generate_local_id() {
		$db = Helium::db();
		$local_id = (int) $db->get_var("SELECT local_id FROM applicants WHERE chapter_id='{$this->chapter_id}' ORDER BY local_id DESC LIMIT 0,1");
		
		return $local_id + 1;
	}

	/**
	 * Generate test ID based on chapter
	 *
	 * Generates a temporary test ID if not finalized yet.
	 */
	public function generate_test_id() {
		$chapter_code = $this->chapter->chapter_code;
		if ($this->finalized) {
			$base = "INAYPsc/'%s-'%s/%s/%s";
			$program_year = 2014;
			$start_year = $program_year - 1;
			$ycl = substr($start_year, 2);
			$ycr = substr($program_year, 2);
			return sprintf($base, $ycl, $ycr, $chapter_code, str_pad($this->local_id, 4, '0', STR_PAD_LEFT));
		}
		else
			return "U/$chapter_code/" . strtoupper(substr(sha1(time()), 0, 8));
	}

	/**
	 * Sanitize name
	 */
	public static function sanitize_name($name) {
		$name = trim($name);
		$name = strtolower($name);
		$name = ucwords($name);

		foreach (array('-', ' \'', 'O\'') as $delimiter) {
	    	if (strpos($name, $delimiter)!==false) {
	    		$name = implode($delimiter, array_map('ucfirst', explode($delimiter, $name)));
	    	}
	    }

		return $name;
	}

	/**
	 * Sanitize school name
	 */
	public static function sanitize_school($school) {
		// sanitize school name
		// use last school if multiple schools were used
		if ($slash = strpos($school, '/'))
			$school = substr($school, $slash + 1);
		// trim
		$school = trim($school);

		// sanitize the casing
		$school = self::sanitize_name($school);
		
		// sanitize misspellings
		$mispell = array(
			'/Negri/i' => 'Negeri',
			// '/Alfa Centaury/i' => 'Alfa Centauri',
			'/\s+/' => ' ',
			'/[.,]/' => '',
		);
		
		$chapters = Chapter::find('all');
		foreach ($chapters as $chapter) {
			$pattern = "/{$chapter->chapter_code}/i";
			$mispell[$pattern] = $chapter->chapter_name;
		}

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
			// '/^SM(A|P) Taruna Bakti.*/i' => 'SM$1 Taruna Bakti Bandung',
			// '/^SM(A|P).*Yahya$/i' => 'SM$1 Kristen Yahya Bandung',
			// '/^SM(A|P).*Yahya Bandung$/i' => 'SM$1 Kristen Yahya Bandung',
			// '/^SMA.*Alfa Centauri$/i' => 'SMA Alfa Centauri Bandung',
			// '/Sukamanah$/' => 'Sukamanah Tasikmalaya',
			// '/^.*Darul Arqam.+$/i' => 'MA Darul Arqam Muhammadiyah Daerah Garut',
			// '/^.*Pribadi.+$/i' => 'Pribadi Bilingual Boarding School Bandung',
			// '/^.*Muthahhari$/i' => 'SMA Plus Muthahhari Bandung',
			// '/^(.*Margahayu)( Bandung)?$/i' => '$1 Kabupaten Bandung',
			// '/^SMA Terpadu Baiturrahman$/i' => 'SMA Terpadu Baiturrahman Kabupaten Bandung'
		);
		
		if ($this && $this->chapter) {
			$specific['/([0-9])$/'] = '$1 ' . $this->chapter->chapter_name;
		}
		
		$school = preg_replace(array_keys($specific), $specific, $school);

		// revert abbreviations
		$abbreviations = array('SMA ', 'SMK ', 'SMP ', 'SD ', 'MA ', 'MTs ', 'MI ', 'BPK ', 'BPI ', 'IBS ', 'ITUS ');
		$school = str_ireplace($abbreviations, $abbreviations, $school);

		return $school;
	}

	/**
	 * Find by user
	 */
	public function find_by_user($user_id) {
		if (is_object($user_id))
			$user_id = $user_id->id;

		$try = Applicant::find(compact('user_id'));

		return $try->first();
	}
	
	/**
	 * Validate details
	 *
	 * Make sure all the required fields are filled in.
	 */
	public function validate() {
		$check = $errors = array();

		$applicant_id = $this->id;
		$d = $this->applicant_detail;

		// $compulsory =  'nama_lengkap alamat_lengkap  ';
		$compulsory .= 'pendidikan_sd_nama_sekolah pendidikan_smp_nama_sekolah pendidikan_sma_nama_sekolah ';
		$compulsory .= 'kepribadian_sifat_dan_kepribadian kepribadian_kelebihan_dan_kekurangan kepribadian_kondisi_membuat_tertekan kepribadian_masalah_terberat kepribadian_rencana ';
		$compulsory .= 'rekomendasi_lingkungan_sekolah_nama rekomendasi_lingkungan_sekolah_alamat rekomendasi_lingkungan_sekolah_pekerjaan rekomendasi_lingkungan_sekolah_hubungan ';
		$compulsory .= 'rekomendasi_lingkungan_luar_sekolah_nama rekomendasi_lingkungan_luar_sekolah_alamat rekomendasi_lingkungan_luar_sekolah_alamat rekomendasi_lingkungan_luar_sekolah_pekerjaan rekomendasi_lingkungan_luar_sekolah_hubungan ';
		$compulsory .= 'rekomendasi_teman_dekat_nama rekomendasi_teman_dekat_alamat rekomendasi_teman_dekat_hubungan ';
		$compulsory = trim($compulsory);
		$co = explode(' ', $compulsory);
		foreach ($co as $f) {
			$try = trim($d->$f, "- \t\n\r\0\x0B");
			if (!$try) {
				$check['incomplete'] = false;
				$this->incomplete_fields[] = $f;
			}
		}

		$check['picture'] = (bool) $this->picture;

		list($a, $y, $j) = array($d->program_afs, $d->program_yes, $d->program_jenesys);
		if (!$a && !$y && !$j) {
			$check['program'] = false;
		}

		// $ttl = $_POST['ttl']['tanggal'];
		// $bd = new HeliumDateTime("$ttl[year]-$ttl[month]-$ttl[day]");
		// $check['birth_date'] = $bd->later_than('1994-08-31') && $bd->earlier_than('1996-04-02');

		foreach ($check as $c => $v) {
			if (!$v)
				$errors[] = $c;
		}

		$this->validation_errors = $errors;
		
		if ($errors)
			return false;
		else
			return true;
	}
}