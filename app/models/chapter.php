<?php

/**
 * Chapter
 *
 * @author Andhika Nugraha <andhika.nugraha@gmail.com>
 * @package chapter
 */
class Chapter extends HeliumPartitionedRecord {
	public $id;
	public $chapter_name;
	public $chapter_code;
	public $chapter_timezone;
	
	public function init() {
		$this->add_vertical_partition('chapter_info');
	}
	
	public function rebuild() {
		if ($this->is_national_office()) {
			$this->applicants = Applicant::find('all');
			$this->registration_codes = RegistrationCode::find('all');
		}
		else {
			$this->has_many('applicants');
			$this->has_many('registration_codes');
		}

		$this->has_many('users');
	}
	
	public function before_save() {
		$this->chapter_code = strtoupper($this->chapter_code);
	}
	
	public function is_national_office() {
		return ($this->id == 1);
	}
	
	public function get_applicant_count() {
		return Applicant::find(array('chapter_id' => $this->id))->count_all();
	}

	public function get_user_count() {
		return User::find(array('chapter_id' => $this->id))->count_all();
	}
	
	public function get_registration_code_count() {
		return RegistrationCode::find(array('chapter_id' => $this->id))->count_all();
	}
	
	public function get_inline_address() {
		return str_replace(array("\r", "\n"), ', ', $this->chapter_address);
	}
	
	public function get_mappable_address() {
		return $this->get_inline_address() . ', Indonesia';
	}
}

