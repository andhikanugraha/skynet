<?php

/**
 * RegistrationCodeBatch
 *
 * A batch of registration codes.
 *
 * @author Andhika Nugraha <andhika.nugraha@gmail.com>
 * @package chapter
 */
class RegistrationCodeBatch extends HeliumRecord {
	public $lifetime = '8 days';

	public $id;
	public $expires_on;
	public $created_at;
	public $program_year;
	public $chapter_id;
	public $generated_by;

	public function init() {
		$this->belongs_to('chapter');
		$this->has_many('registration_codes');
	}

	public function defaults() {
		$this->created_at = new HeliumDateTime('now');

		$this->expires_on = new HeliumDateTime;

		$lifetime = Helium::conf('registration_code_lifetime');
		if (!$lifetime)
			$lifetime = $this->lifetime;

		$this->expires_on->modify('+' . $lifetime);
	}

	public function get_generator() {
		if ($this->generated_by)
			return User::find($this->generated_by);
	}
	
	public function get_view_link() {
		return PathsComponent::build_url(array('controller' => 'registration_code', 'action' => 'view', 'batch_id' => $this->id));
	}

	public function get_print_link() {
		return PathsComponent::build_url(array('controller' => 'registration_code', 'action' => 'view_pdf', 'batch_id' => $this->id));
	}
	
	public function get_available_code_count() {
		$now = new HeliumDateTime('now');
		
		if ($this->expires_on->earlier_than($now))
			return 0;
		else {
			$codes = clone $this->registration_codes;
			return $codes->narrow(array('availability' => 1))->count_all();
		}
	}
}
