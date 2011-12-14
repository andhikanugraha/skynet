<?php

/**
 * Applicant
 *
 * @author Andhika Nugraha <andhika.nugraha@gmail.com>
 * @package applicant
 */
class Applicant extends HeliumPartitionedRecord {
	public function defaults() {
		$this->expires_on = new HeliumDateTime;
		$this->confirmed = false;
		$this->finalized = false;
	}

	public function init() {
		$this->belongs_to('user');

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

	/*
	public function find_by_user($user_id) {
		if (is_object($user_id))
			$user_id = $user_id->id;

		$try = Applicant::find(compact('user_id'));

		return $try->first();
	}

	public $validation_errors = array();
	public $incomplete_fields = array();
	
	public function validate_form() {
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
		
		if (!$errors)
			return true;
	}
	
	public function finalize() {
		if (!$this->validate_form())
			return false;

		$this->finalized = true;
		$this->save();

		return true;
	}
	
	public static function get_test_id($id = 0) {
		$id = $this ? $this->id : $id;
		$code = Helium::conf('applicant_prefix') . str_pad($id, 4, '0', STR_PAD_LEFT);
		return $code;
	}

	public function confirm() {
		$this->submitted = true;
		$this->application_stage = 'before_selection_1';
		$this->save();
	}
	
	public function get_landing_page() {
		$controller = 'applicant';
		$action = '';

		if ($this->submitted) {
			// determine stage of application here - TODO
			$controller = 'selection';
			$action = 'index';
		}	
		elseif ($this->expires_on->earlier_than('now'))
			$action = 'expired';
		elseif ($this->finalized)
			$action = 'finalized';
		else
			$action = 'form';

		return compact('controller', 'action');
	}
	
	*/
}