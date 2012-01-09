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
	public $finalized = false;
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
		$this->add_vertical_partition('applicant_family');
		$this->add_vertical_partition('applicant_personal_details');
		$this->add_vertical_partition('applicant_personality');
		$this->add_vertical_partition('applicant_primary_school_grade_history');
		$this->add_vertical_partition('applicant_program_choices');
		$this->add_vertical_partition('applicant_recommendations');
		$this->add_vertical_partition('applicant_referral');
		$this->add_vertical_partition('applicant_secondary_school_grade_history');
		$this->add_vertical_partition('applicant_selection_progress');
		$this->add_vertical_partition('applicant_travel_history');
	}

	/**
	 * Default values
	 */
	public function before_save() {
		// Sanitized entries
		$this->sanitized_full_name = $this->sanitize_name($this->full_name);

		if ($this->applicant_address_city) {
			$city = $this->applicant_address_city;

			if ($this->applicant_address_province == 'DKI Jakarta' && $city{0} == 'J')
				$city = 'Jakarta';
		}
		else
			$city = $this->chapter->chapter_name;
		$this->sanitized_high_school_name = $this->sanitize_school($this->high_school_name, $city);
		
		if ($this->in_acceleration_class)
			$this->program_yes = false;
		
		if (!$this->finalized && !$this->test_id)
			$this->test_id = $this->generate_test_id();
	}

	/**
	 * Finalize applicant if form is valid
	 */
	public function finalize() {
		if ($this->validate()) {
			$this->finalized = true;
			if (!$this->local_id)
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
	 * Is applicant expired?
	 */
	public function is_expired() {
		return !$this->expires_on->later_than('now');
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
			$base = "INAYPsc/%s-%s/%s/%s";
			$program_year = 2014;
			$start_year = $program_year - 1;
			$ycl = substr($start_year, 2);
			$ycr = substr($program_year, 2);
			return sprintf($base, $ycl, $ycr, $chapter_code, str_pad($this->local_id, 4, '0', STR_PAD_LEFT));
		}
		else
			return "XYZ" . strtoupper(substr(sha1(mt_rand()), 0, 16));
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
	public static function sanitize_school($school, $city = '') {
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
			'/^SM(A|K|P) ?N\s/i' => 'SM$1 Negeri ',
			'/^SM(A|P) ?K\s/i' => 'SM$1 Kristen ',
			'/^SM(A|P) ?T\s/i' => 'SM$1 Terpadu ',
			'/^M(A|Ts) ?N\s/i' => 'M$1 Negeri ',
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

		if ($city) {
			$specific['/([0-9])$/'] = '$1 ' . $city;
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

		$required = array('full_name', 'place_of_birth', 'applicant_email', 'applicant_address_street', 'sex', 'body_height', 'body_weight', 'blood_type', 'citizenship', 'religion', 'ayah_full_name', 'ibu_full_name', 'number_of_children_in_family', 'nth_child', 'high_school_name', 'high_school_admission_year', 'high_school_graduation_month', 'junior_high_school_name', 'junior_high_school_graduation_year', 'elementary_school_name', 'elementary_graduation_year', 'years_speaking_english', 'favorite_subject', 'dream', 'arts_hobby', 'sports_hobby', 'motivation', 'hopes', 'recommendations_school_name', 'recommendations_school_address', 'recommendations_school_occupation', 'recommendations_school_work_address', 'recommendations_school_relationship', 'recommendations_nonschool_name', 'recommendations_nonschool_address', 'recommendations_nonschool_occupation', 'recommendations_nonschool_work_address', 'recommendations_nonschool_relationship', 'recommendations_close_friend_name', 'recommendations_close_friend_address', 'recommendations_close_friend_relationship', 'personality', 'strengths_and_weaknesses', 'stressful_conditions', 'biggest_life_problem', 'plans');
		
		for ($i = 1; $i <= 10; $i++) {
			if ($i != 6 && $i != 9) {
				// Allow acceleration class in primary and secondary schools
				$required[] = "grades_y{$i}t1_rank";
				$required[] = "grades_y{$i}t1_total";
				$required[] = "grades_y{$i}t2_rank";
				$required[] = "grades_y{$i}t2_total";
			}
		}

		foreach ($required as $f) {
			$try = trim($this->$f, "- \t\n\r\0\x0B");
			if (!$try) {
				$check['incomplete'] = false;
				$this->incomplete_fields[] = $f;
			}
		}

		$check['picture'] = (bool) $this->picture;

		list($a, $y, $j) = array($this->program_afs, $this->program_yes, $this->program_jenesys);
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