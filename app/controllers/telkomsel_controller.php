<?php

class TelkomselController extends GatotkacaController {
	public $default_action = 'issue_registration_code';

	public function init() {
		$this->require_role('sponsors.telkomsel');
	}

	public function index() {
		
	}

	public function issue_registration_code() {
		// $now = new HeliumDateTime;
		// $now->setTime(23, 59, 59);
		// $w = $now->format('w');
		// // we expire the next sunday
		// $w = ( int) $w;
		// $int = 14 - $w;
		// switch ($w) {
		// 	case 1: // mon
		// 	case 2: // tue
		// 	case 3: // wed
		// 	case 4: // thu
		// 		$int = 7 - $w;
		// 		break;
		// 	// friday-sunday: issue this week
		// 	case 5: // fri
		// 		$int = 2;
		// 		break;
		// 	case 6: // sat
		// 		$int = 1;
		// 		break;
		// 	case 0: // sun
		// 		$int = 0;
		// 		break;
		// }
		// $expires_on = clone $now;
		// $expires_on->modify('+' . $int . ' days');
		$expires_on = new HeliumDateTime('2011-04-17 23:59:59');
		$this['expires_on'] = $expires_on;

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$code = new RegistrationCode;
			$code->source = 'telkomsel';
			$code->expires_on = $expires_on;
			$code->save();
			$this['code'] = $code;
		}

		$db = Helium::db();
		$this['count'] = $db->get_var("SELECT COUNT(*) FROM registration_codes WHERE source='telkomsel'");

		$this['fee'] = $this['reg_fee'] * $this['count'];
	}
}