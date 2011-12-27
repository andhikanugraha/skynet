<?php

// an applicant which has completed registration and is eligible for selections.

class ActiveApplicant extends HeliumRecord {
	public $full_name;
	public $school;
	public $selection1_chamber_id;
	public $applicant_id;
	public $id;

	public function init() {
		$this->belongs_to('applicant');
		$this->belongs_to('selection1_chamber');
	}
	
	public function get_applicant_detail() {
		if ($this->applicant_detail)
			return $this->applicant_detail;
		else {
			$applicant_id = $this->applicant_id;
			$applicant_detail = ApplicantDetail::find(compact('applicant_id'));
			$applicant_detail = $applicant_detail->first();
			return $this->applicant_detail = $applicant_detail;
		}
	}

	public function get_email() {
		return $this->get_applicant_detail()->alamat_lengkap['email'];
	}

	public function get_phone() {
		return $this->get_applicant_detail()->alamat_lengkap['hp'];
	}

	public function passed_selection($selection_stage) {
		$var = 'selection_' . $selection_stage . '_passed';
		return $this->$var;
	}

	public function can_join_selection($selection_stage, &$last_selection_failed = 0) {
		$selection_stage = (int) $selection_stage;
		if ($selection_stage == 1) // selection 1 - everyone's eligible
			return true;

		for ($i = 1; $i <= $selection_stage - 1; $i++) {
			if (!$this->passed_selection($i))
				return false;
		}

		return true;
	}

	public function get_first_selection_failed() {
		$last_selection = 3;
		for ($i = 1; $i <= $last_selection; $i++) {
			if (!$this->passed_selection($i))
				return $i;
		}
	}
}

// since the number of applicants is large, we cannot rely on Helium for simple syntax and we need to optimize using raw SQL.

?>