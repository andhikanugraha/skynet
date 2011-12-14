<?php

/**
 * ApplicantOtherAchievement
 *
 * @author Andhika Nugraha <andhika.nugraha@gmail.com>
 * @package applicant
 */
class ApplicantOtherAchievement extends HeliumRecord {
	public $id;
	public $applicant_id;
	public $activity;
	public $achievement;
	public $year;

	public init() {
		$this->belongs_to('applicant');
	}
}

