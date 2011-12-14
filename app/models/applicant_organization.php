<?php

/**
 * ApplicantOrganization
 *
 * @author Andhika Nugraha <andhika.nugraha@gmail.com>
 * @package applicant
 */
class ApplicantOrganization extends HeliumRecord {
	public $id;
	public $applicant_id;
	public $name;
	public $kind;
	public $position;
	public $year;

	public init() {
		$this->belongs_to('applicant');
	}
}

