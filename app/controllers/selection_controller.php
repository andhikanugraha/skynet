<?php

class SelectionController extends GatotkacaController {
	public $applicant;

	public function init() {}

	private function check_auth() {
		$this->require_role('applicant');
		$role = $this->session->user->role;
		$this->applicant = $this->session->user->applicant;
		$this->active_applicant = $this->applicant->active_applicant;
		if (!$this->applicant->submitted)
			$this->auth->land();
	}

	private function check_results_ready($selection_stage) {
		// let's change this
		// let's just check the Events module to figure out whether or not it is time for result announcement

		$selection_stage = (int) $selection_stage;

		if (!$selection_stage) // stage 0 = registration
			return false;

		$db = Helium::db();
		$passed_col = "selection_{$selection_stage}_passed";
		$data_ready = (bool) $db->get_var("SELECT COUNT(*) FROM active_applicants WHERE $passed_col=1");

		// $type = 'results_announcement';
		// $announcement_event = Event::find(compact('selection_stage', 'event_type'));
		// $date = $announcement_event->event_start;
		$dates = Helium::conf('dates');
		$date = $dates['selection_' . $selection_stage . '_announcement'];
		$now = new HeliumDateTime;
		$time_ready = $now->later_than($date);

		return $data_ready && $time_ready;
	}

	public function index() {	
		// 'announcement' sounds ambiguous. let's use 'results'

		// use Events module to figure out the next stage of selections
		// $next_event = Event::find_next();
		// $next_event_type = $next_event->event_type;
		// $next_selection = Event::find_next('selection');
		// $next_selection_stage = $next_event->selection_stage;
		$next_selection_stage = 3;
		$next_event_type = 'rereg'; // results_announcement, rereg or selection

		$previous_selection_stage = 2;

		switch ($next_event_type) {
			case 'selection':
				if ($this->active_applicant->can_join_selection($next_selection_stage))
					$target_action = 'event';
				else // didn't pass previous selection stages
					$target_action = 'results';
				break;
			case 'results_announcement':
			case 'rereg':
			// default:
				$target_action = 'results';
		}

		Gatotkaca::redirect(array('controller' => 'selection', 'action' => $target_action));
	}

	public function results() {
		$this->check_auth();

		// if the current participant is not eligible for the next selection,
		// we are not announcing the results from the previous selections,
		// but the result from the first selection stage where the current applicant failed.
		// $next_selection = Event::find_next('selection');
		// $next_selection_stage = $next_event->selection_stage;
		$next_selection_stage = 3;

		if ($this->active_applicant->can_join_selection($next_selection_stage)) {
			$selection_stage_announced = $next_selection_stage - 1;
			$failed = false;
		}
		else {
			$selection_stage_announced = $this->active_applicant->get_first_selection_failed();
			$next_selection_stage = $selection_stage_announced + 1;
			$failed = true;
		}

		$selection_announced = 'selection_' . $selection_stage_announced;
		$next_selection = 'selection_' . $next_selection_stage;
		$dates = Helium::conf('dates');
		$announcement_date = $dates[$selection_announced . '_announcement'];
		$announcement_schedule = new HeliumDateTime($announcement_date);
		$now = new HeliumDateTime;

		$results_ready = $failed;

		$results_ready = $this->check_results_ready($selection_stage_announced);

		$this['results_ready'] = $results_ready;
		$this['selection_announced'] = $selection_announced;
		$this['next_selection'] = $next_selection;
		$this['dates'] = $dates;

		if ($results_ready) {
			$passed_col = "selection_{$selection_stage_announced}_passed";
			$this['applicant'] = $this->applicant->active_applicant;
			$this['applicant_name'] = $this->applicant->active_applicant->full_name;
			$this['the_result'] = (bool) $this->applicant->active_applicant->$passed_col;
		}
	}
}
?>