<?php

/**
 * ApplicantArtsAchievement
 *
 * @author Andhika Nugraha <andhika.nugraha@gmail.com>
 * @package applicant
 */
class ApplicantArtsAchievement extends HeliumRecord {
	public $id;
	public $applicant_id;
	public $championship;
	public $kind;
	public $achievement;
	public $year;

	public function init() {
		$this->belongs_to('applicant');
	}
}

