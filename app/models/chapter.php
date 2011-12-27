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
}

