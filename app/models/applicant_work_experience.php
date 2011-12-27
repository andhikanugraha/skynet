<?php

/**
 * ApplicantWorkExperience
 *
 * @author Andhika Nugraha <andhika.nugraha@gmail.com>
 * @package applicant
 */
class ApplicantWorkExperience extends HeliumRecord {
	public $id;
	public $applicant_id;
	public $organization;
	public $position;
	public $year;
	public $length;

	public function init() {
		$this->belongs_to('applicant');
	}
}

