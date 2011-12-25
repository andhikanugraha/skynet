<?php

/**
 * ApplicantSibling
 *
 * @author Andhika Nugraha <andhika.nugraha@gmail.com>
 * @package applicant
 */
class ApplicantSibling extends HeliumRecord {
	public $id;
	public $applicant_id;
	public $full_name;
	public $date_of_birth;
	public $occupation;

	public function init() {
		$this->belongs_to('applicant');
	}
}

