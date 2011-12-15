<?php

class AdminController extends GatotkacaController {
	public function init() {
		$action = $this->_action();
		$sorta_public = array('stats', 'unfinalizer', 'applicant_list', 'view_selection_2_assignments');
		if (!in_array($action, $sorta_public))
			$this->require_role('admin');
		else
			$this->require_role('volunteer');
	}

	public function login_bypass() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$user_id = $_POST['user_id'];
			$user = User::find($user_id);
			$this->session->user_id = $user_id;
			$this->session->user_password_hash = $user->password_hash;
			$this->session->user = $user;
			$this->session->save();
			Gatotkaca::redirect('/');
			// $this->auth->land();
		}

		// table of usernames
		$db = Helium::db();
		$q = "SELECT
		user_id,
		username,
		nama_lengkap,
		pendidikan_sma_nama_sekolah
		FROM `users`
		LEFT JOIN applicants ON applicants.user_id = users.id
		LEFT JOIN applicant_details ON applicant_details.applicant_id = applicants.id
		WHERE 1";
		$rows = $this['rows'] = $db->get_results($q);
	}

	public function stats() {}

	public function applicant_list() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$applicant_id = $_POST['applicant_id'];
			$applicant = Applicant::find($applicant_id);
			$applicant->finalized = false;
			$applicant->save();
		}

		$applicants = Applicant::find('all');
		$applicants->include_association('applicant_detail');

		// page selection
		$applicants->set_batch_length(100);
		if (!$this->params['page'])
			$this->params['page'] = 1;
		$page = $this->params['page'];
		$applicants->set_batch_number($page);

		// stage selection
		$applicants->add_additional_column('expired', 'expires_on < CURRENT_TIMESTAMP');
		switch ($this->params['stage']) {
			case 'expired':
				$applicants->narrow(array('expired' => true, 'submitted' => false));
				break;
			case 'unexpired':
				$applicants->narrow(array('expired' => false));
				$applicants->widen(array('submitted' => true));
				break;
			case 'confirmed':
				$applicants->narrow(array('submitted' => true));
				break;
			case 'upcoming':
				$up = new HeliumDateTime;
				$up->setTime(23, 59, 59);
				$w = $up->format('w');
				// we expire sunday
				$w = (int) $w ? $w : 7;
				$int = 7 - $w;
				$up->modify('+' . $int . ' days');
				$up = (string) $up;
				$applicants->narrow(array('expires_on' => $up, 'submitted' => false));
				break;
			case 'anomaly':
				$applicants->narrow(array('submitted' => false, 'expired' => true, 'finalized' => true));
				break;
			// case 'school':
			// 	$school = $this->params['school'];
			// 	$school = addslashes($school);
			// 	$applicants->narrow("applicant_details.pendidikan_sma_nama_sekolah LIKE . '$school'");
			// 	var_dump($applicants);
			// 	break;
		}
		$this['applicants'] = $applicants;

		$this->stages = $this['stages'] = $stages = array(
			'' => 'All',
			'expired' => 'Expired',
			'unexpired' => 'Unexpired',
			'confirmed' => 'Confirmed',
			'upcoming' => 'Upcoming',
			'anomaly' => 'Anomalies'
			);

		if ($this->params['output'] == 'xlsx') {
			$this->render = false;
			$this->output_applicant_list_xlsx($applicants);
		}
	}

	private function output_applicant_list_xlsx($applicants) {
		// too much data to handle using the nice way, let's get raw here.
		ini_set('memory_limit', '128M');

		$db = Helium::db();

		$query_all = "SELECT `applicants`.*, (`applicant_details`.`nama_lengkap`) AS `nama_lengkap`,  (`applicant_details`.`pendidikan_sma_nama_sekolah`) AS `school`, `applicant_details`.`alamat_lengkap` AS `address`, (expires_on < CURRENT_TIMESTAMP) AS `expired` FROM `applicants` LEFT JOIN `applicant_details` ON `applicant_details`.`applicant_id`=`applicants`.`id` WHERE 1 ORDER BY id ASC";
		$applicants = $db->get_results($query_all, ARRAY_A);

		// Create new PHPExcel object
		$xlsx = new PHPExcel();

		// Set properties
		$xlsx->getProperties()->setCreator("Bina Antarbudaya Gatotkaca")
									 ->setLastModifiedBy("Bina Antarbudaya Gatotkaca")
									 ->setTitle("Bina Antarbudaya Applicant List")
									 ->setSubject("Bina Antarbudaya Applicant List")
									 ->setDescription("List of Bina Antarbudaya applicants.")
									 ->setKeywords("bina antarbudaya binaantarbudaya")
									 ->setCategory("List");

		$all = $confirmed = $anomalies = $expired = array(array('A' => 'test id', 'B' => 'name', 'C' => 'school', 'D' => 'phone', 'E' => 'email', 'F' => 'expiry', 'G' => 'stage'));

		foreach ($applicants as $a) {
			$cols = array();
			$cols['A'] = Applicant::get_test_id($a['id']);
			$cols['B'] = ApplicantDetail::sanitize_name($a['nama_lengkap']);
			$cols['C'] = Gatotkaca::sanitize_school($a['school']);
			$add = unserialize($a['address']);
			// for kantor nasional
			// $cols['D'] = $add['alamat'];
			// $cols['E'] = $add['kota'];
			$cols['D'] = $add['hp'];
			$cols['E'] = $add['email'];
			$exp = new HeliumDateTime($a['expires_on']);
			$cols['F'] = $exp->format('d F Y');
			if ($a['submitted']) {
			 	$cols['G'] = 'Confirmed';
				$confirmed[] = $cols;
			}
			elseif ($a['finalized'] && $a['expired']) {
				$cols['G'] = 'Anomaly';
				$anomalies[] = $cols;
			}
			elseif ($a['expired']) {
				$cols['G'] = 'Expired';
				$expired[] = $cols;
			}
			elseif ($a['finalized'])
				$cols['G'] = 'Finalized';
			else
				$cols['G'] = 'Unfinalized';

			$all[] = $cols;

			$i++;
		}

		foreach (array('all', 'confirmed', 'anomalies', 'expired') as $k => $name) {
			// set active sheet
			if ($k > 0)
				$xlsx->createSheet();
			$sheet = $xlsx->setActiveSheetIndex($k);

			// Rename sheet
			$title = ucfirst($name);
			$xlsx->getActiveSheet()->setTitle($title);

			// set column width
			$sheet->getColumnDimension('A')->setWidth(17); // id
			$sheet->getColumnDimension('B')->setWidth(40); // name
			$sheet->getColumnDimension('C')->setWidth(30); // school
			$sheet->getColumnDimension('D')->setWidth(13); // phone
			$sheet->getColumnDimension('E')->setWidth(32); // email
			$sheet->getColumnDimension('F')->setWidth(13); // exp
			$sheet->getColumnDimension('G')->setWidth(13); // stage

			$sheet->getStyle('A1:G1')->applyFromArray(
					array(
						'font'    => array(
							'bold'      => true
						),
						'alignment' => array(
							'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
						),
						'borders' => array(
							'bottom'     => array(
			 					'style' => PHPExcel_Style_Border::BORDER_THIN
			 				)
						),
					)
			);

			$data = $$name;
			$i = 1;
			foreach ($data as $cols) {
				foreach ($cols as $k => $v) {
					if ($v == '-')
						continue;
					$cell = $k . $i;
					$sheet->setCellValue($cell, $v);
					if (is_numeric($v))
						$sheet->getStyle($cell)->getNumberFormat()->setFormatCode(str_repeat('0', strlen($v)));
				}
				$i++;
			}
		}

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$xlsx->setActiveSheetIndex(0);

		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="applicants.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = PHPExcel_IOFactory::createWriter($xlsx, 'Excel2007');
		$writer->save('php://output');
		exit;
	}

	public function session_switcher() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$session_id = $_POST['session_id'];
			$session = Session::find($session_id);
			// var_dump($session); exit;
			$this->session->data = $session->data;
			$this->session->user_id = $session->user_id;
			$this->session->user_password_hash = $session->user_password_hash;
			$this->session->save();
			$this->session = Session::find($this->session->id);
			$this->auth->land();
		}

		// table of usernames
		$db = Helium::db();
		$q = "SELECT
		sessions.id AS session_id,
		username,
		nama_lengkap,
		pendidikan_sma_nama_sekolah
		FROM sessions
		LEFT JOIN applicants ON applicants.user_id = sessions.user_id
		LEFT JOIN users ON sessions.user_id = users.id
		LEFT JOIN applicant_details ON applicant_details.applicant_id = applicants.id
		WHERE sessions.user_id != 0";
		$rows = $this['rows'] = $db->get_results($q);
	}

	public function issue_registration_code() {
		$now = new HeliumDateTime;
		$now->setTime(23, 59, 59);
		$w = $now->format('w');
		// we expire the next sunday
		$w = ( int) $w;
		$int = 14 - $w;
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
		$expires_on = clone $now;
		$expires_on->modify('+' . $int . ' days');
		$this['expires_on'] = $expires_on;

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$codes = array();
			for ($i = 1; $i <= 500; $i++) {
				$codes[] = RegistrationCode::generate_token();
			}
			$sql = 'INSERT INTO registration_codes (token, expires_on, availability) VALUES ';
			foreach ($codes as $i => $code) {
				$sql .= " ('$code', '$expires_on', 1)";
				if ($i < (count($codes) - 1))
					$sql .= ',';
			}
			$db = Helium::db();
			// it should be fairly safe to assume that there are no conflicts
			$db->query($sql);
			$this['codes'] = $codes;
		}

		$db = Helium::db();
		$this['count'] = $db->get_var('SELECT COUNT(*) FROM registration_codes');

		$this['profit'] = $this['reg_fee'] * $this['count'];
	}

	public function unfinalizer() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$applicant_id = trim($_POST['applicant_id']);
			$applicant = Applicant::find($applicant_id);
			$applicant->finalized = false;
			$applicant->save();
		}
	}

	public function create_account() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$username = trim($_POST['username']);
			$password = $_POST['password'];
			$email = trim($_POST['email']);
			$role = trim($_POST['role']);
			$user = new User;
			$user = new User;
			$user->username = $username;
			$user->set_password($password);
			$user->email = $email;
			$user->role = $role;
			$try = @$user->save();
			$this['user'] = $user;
			if ($try)
				$this['success'] = true;
			else
				$this['error'] = true;
		}

		$this['roles'] = $available_roles = User::$roles;
	}

	// selection management

	public function is_migratable() {
		$db = Helium::db();
		// $count = $db->get_var('SELECT COUNT(*) FROM active_applicants');
		if ($count)
			return false;
		else
			return true;
	}

	public function migrate_applicants() {
		// migrate data while assigning chambers
		// chambers are assumed to be of equal capacity
		// the number of chambers equals the number of applicants divided by that capacity

		// use raw SQL
		$db = Helium::db();

		$migratable = $this['migratable'] = $this->is_migratable();

		if ($_SERVER['REQUEST_METHOD'] == 'POST' && $migratable) {

			// fetch all confirmed applicants
			$confirmed_applicants = $db->get_results('SELECT applicants.id, nama_lengkap AS full_name, pendidikan_sma_nama_sekolah AS school FROM applicants LEFT JOIN applicant_details ON applicants.id=applicant_details.id WHERE submitted=1 ORDER BY applicants.id ASC');

			if (!$confirmed_applicants) {
				$error = 'Failed retrieving applicants.';
			}
			else {
				// loop through applicants and make new active_applicants
				$active_applicants = array();
				foreach ($confirmed_applicants as $a) {
					$dummy = array();
					$dummy['full_name'] = ApplicantDetail::sanitize_name($a->full_name);
					$dummy['school'] = Gatotkaca::sanitize_school($a->school);
					$dummy['id'] = $a->id;
					$active_applicants[] = $dummy;
				}

				// generate the INSERT query
				$insert = 'INSERT INTO active_applicants (id, applicant_id, full_name, school) VALUES ';
				$first = true;
				foreach ($active_applicants as $a) {
					if ($first)
						$first = false;
					else
						$insert .= ', ';

					array_walk($a, function(&$v) { $v = addslashes($v); });
					$insert .= "('{$a[id]}', '{$a[id]}', '{$a[full_name]}', '{$a[school]}')";
				}

				// query the INSERT query
				$try = $db->query($insert);
				if (!$try) {
					$error = 'Failed inserting active_applicants.';
				}
				else {
					// fetch active applicants in order of schools and names
					$in_order = $db->get_results('SELECT * FROM active_applicants ORDER BY school, full_name, id ASC');

					// rooms
					$min_room = 1;
					// this variable has to be based of a form
					$capacity = $_POST['capacity'];
					$max_room = ceil(count($confirmed_applicants) / $capacity);

					// allocate chambers
					$i = $min_room;
					foreach ($in_order as $row) {
						$row->selection1_chamber_id = $i;

						if ($i == $max_room)
							$i = $min_room;
						else
							$i++;
					}

					// update database
					$updates = array();
					foreach ($in_order as $row) {
						$updates[] = "UPDATE active_applicants SET selection1_chamber_id='{$row->selection1_chamber_id}' WHERE id='{$row->id}'";
					}
					foreach ($updates as $update)
						$try = $db->query($update);

					for ($i = $min_room; $i <= $max_room; $i++) {
						$db->query("INSERT INTO selection1_chambers (id) VALUES ('$i')");
					}

					if (!$try) {
						$error = 'Failed assigning chambers.';
					}
					else {
						$this['success'] = true;
						$this['count'] = count($in_order);
						$this['active_applicants'] = $in_order;
					}
				}
			}
		}
		else {
			$this['eligible_applicants_count'] = $db->get_var('SELECT COUNT(*) FROM applicants WHERE submitted=1');
		}

		$this['error'] = $error;
	}

	public function edit_chamber_info() {
		$this['chambers'] = $chambers = Selection1Chamber::find();

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// process edits
			$chambers = $_POST['chambers'];
			foreach ($chambers as $id => $data) {
				$ch = Selection1Chamber::find($id);
				foreach ($data as $k => $v) {
					$ch->$k = $v;
				}
				$ch->save();
			}

			$this['success'] = true;
		}
	}

	public function output_chambers_list_xlsx($applicants) {
		$this->render = false;
		// too much data to handle using the nice way, let's get raw here.
		ini_set('memory_limit', '128M');

		$db = Helium::db();

		$query_all = "SELECT active_applicants.*, selection1_chambers.* FROM active_applicants LEFT JOIN selection1_chambers ON selection1_chambers.id=active_applicants.selection1_chamber_id WHERE 1 ORDER BY active_applicants.id ASC";
		$applicants = $db->get_results($query_all, ARRAY_A);

		// Create new PHPExcel object
		$xlsx = new PHPExcel();

		// Set properties
		$xlsx->getProperties()->setCreator("Bina Antarbudaya Gatotkaca")
									 ->setLastModifiedBy("Bina Antarbudaya Gatotkaca")
									 ->setTitle("Bina Antarbudaya Applicant List")
									 ->setSubject("Bina Antarbudaya Applicant List")
									 ->setDescription("List of Bina Antarbudaya applicants.")
									 ->setKeywords("bina antarbudaya binaantarbudaya")
									 ->setCategory("List");

		$all = $default = array(array('A' => 'test id', 'B' => 'name', 'C' => 'school', 'D' => 'chamber', 'E' => 'room', 'F' => 'venue', 'G' => ''));
		$chambers = array(0 => $default);

		foreach ($applicants as $a) {
			$cols = array();
			$cols['A'] = Applicant::get_test_id($a['applicant_id']);
			$cols['B'] = $a['full_name'];
			$cols['C'] = $a['school'];
			$cols['D'] = $a['selection1_chamber_id'];
			$cols['E'] = $a['chamber_name'];
			$cols['F'] = $a['venue'];
			$cols['G'] = $a['subvenue'];

			$all[] = $cols;
			$ch = $a['selection1_chamber_id'];
			if (!$chambers[$ch])
				$chambers[$ch] = $default;
			$chambers[$ch][] = $cols;

			$i++;
		}

		$chambers = array(0 => $all);
		foreach ($chambers as $k => $name) {
			// set active sheet
			if ($k > 0)
				$xlsx->createSheet();
			$sheet = $xlsx->setActiveSheetIndex($k);

			// Rename sheet
			if ($k == 0)
				$title = 'All';
			else
				$title = 'Chamber ' . $k;
			$xlsx->getActiveSheet()->setTitle($title);

			// set column width
			$sheet->getColumnDimension('A')->setWidth(17); // id
			$sheet->getColumnDimension('B')->setWidth(40); // name
			$sheet->getColumnDimension('C')->setWidth(30); // school
			$sheet->getColumnDimension('D')->setWidth(7); // phone
			$sheet->getColumnDimension('E')->setWidth(13); // email
			$sheet->getColumnDimension('F')->setWidth(19); // exp
			$sheet->getColumnDimension('G')->setWidth(13); // stage

			$sheet->getStyle('A1:G1')->applyFromArray(
					array(
						'font'    => array(
							'bold'      => true
						),
						'alignment' => array(
							'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
						),
						'borders' => array(
							'bottom'     => array(
			 					'style' => PHPExcel_Style_Border::BORDER_THIN
			 				)
						),
					)
			);

			$data = $chambers[$k];
			$i = 1;
			foreach ($data as $cols) {
				foreach ($cols as $k => $v) {
					if ($v == '-')
						continue;
					$cell = $k . $i;
					$sheet->setCellValue($cell, $v);
					if (is_numeric($v))
						$sheet->getStyle($cell)->getNumberFormat()->setFormatCode(str_repeat('0', strlen($v)));
				}
				$i++;
			}
		}

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$xlsx->setActiveSheetIndex(0);

		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="applicants.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = PHPExcel_IOFactory::createWriter($xlsx, 'Excel2007');
		$writer->save('php://output');
		exit;
	}

	public function prepare_selection_2() {
		// 1. Ask for the IDs of applicants that pass selection 1
		// 2. Ask for the number of personality interview chambers
		// 3. Ask for the number of English interview chambers
		// 4. Assign shifts and chambers to said applicants
		// 5. Update active_applicants and set selection1_pass = 1.

		// this action is divided into several stages in $_POST['stage'].
		// a. input (default/no defined stage)
		// b. confirmation
		// c. comms process (email announcement)

		$stage = $_POST['stage'];
		$this['stage'] = $stage;
		
		$view_assignments = array('controller' => 'admin', 'action' => 'view_selection_2_assignments');
		
		$db = Helium::db();
		$precheck = $db->get_var("SELECT COUNT(*) FROM active_applicants WHERE selection_1_passed=1");
		if ($precheck)
			Gatotkaca::redirect($view_assignments);

		switch ($_POST['stage']) {
			case 'confirm':
				// admin has entered applicant IDs and the number of chambers

				// sanitize applicant IDs
				$applicants = trim($_POST['applicants']);
				$applicants = str_replace(Helium::conf('applicant_prefix'), '', $applicants);
				$applicants = preg_split('/\s+/', $applicants);
				$applicant_ids = array();
				foreach ($applicants as $k => $v) {
					$n = (int) $v;
					if ($n && !in_array($n, $applicant_ids)) // no overlap allowed
						$applicant_ids[$k] = $n;
				}

				if ($applicant_ids) {
					$this['personality_chamber_count'] =
					$personality_chamber_count = intval($_POST['personality_chamber_count']);
					$this['english_chamber_count'] =
					$english_chamber_count = intval($_POST['english_chamber_count']);
				
					$range = '(' . implode(', ', $applicant_ids) . ')';
					$active_applicants = ActiveApplicant::find("applicant_id IN $range");
					$active_applicants->set_batch_length(0);
					$this['applicants'] = $active_applicants;
				
					// sanitize $applicant_ids, only include IDs that exist
					$applicant_ids = array();
					foreach ($active_applicants as $active_applicant) {
						$applicant_ids[] = $active_applicant->applicant_id;
					}
				}

				// validate here please
				$errors = array();
				if (!$applicant_ids)
					$errors[] = 'Invalid applicant IDs.';
				if (!$personality_chamber_count)
					$errors[] = 'Invalid personality chamber count.';
				if (!$english_chamber_count)
					$errors[] = 'Invalid English chamber count.';

				if ($errors) { // did not pass validation
					$this->session['errors'] = $errors;
					$this->session['personality_chamber_count'] = $_POST['personality_chamber_count'];
					$this->session['english_chamber_count'] = $_POST['english_chamber_count'];
					$this->session['applicants'] = $_POST['applicants'];
					$this->session->save();
					Gatotkaca::redirect(array('controller' => 'admin', 'action' => 'prepare_selection_2'));
				}
				else {
					// validated.
					unset(
						$this->session['applicants'],
						$this->session['personality_chamber_count'],
						$this->session['english_chamber_count']
					);
					$this->session['prepare_selection_2_input'] = compact('applicant_ids', 'personality_chamber_count', 'english_chamber_count');
				}
				break;
			case 'process':
				// admin has confirmed that the selection of applicants is correct.
				// we shall assign to these applicants their shifts and chambers.

				$input = $this->session->flash('prepare_selection_2_input');
				
				if (!$input)
					Gatotkaca::redirect($view_assignments);

				$applicant_ids = $input['applicant_ids'];
				$applicant_count = count($applicant_ids);

				$personality_min = 1;
				$personality_max = (int) $input['personality_chamber_count'];
				$english_min = 1;
				$english_max = (int) $input['english_chamber_count'];

				$highest_chamber_count = ($personality_max > $english_max) ? $personality_max : $english_max;

				// shifts are used to separate the order of interviewing for an applicant.
				// one chamber starts from 1, the other starts from half the total number of shifts then loops.
				$shift_min = 1;
				$shift_max = ceil($applicant_count / $highest_chamber_count);
				$shift_halfpoint = ceil($shift_max / 2);

				// loop variables
				$current_personality = $personality_min;
				$current_english = $english_min;
				$current_shift = $shift_min;
				$global_assignments = array();
				
				$shifts = array();
				for ($i = $shift_min; $i <= $shift_max; $i++)
					$shifts[$i] = array();

				$personality_order = array();
				for ($i = $personality_min; $i <= $personality_max; $i++)
					$personality_order[$i] = $shifts;

				$english_order_1 = array();
				$english_order_2 = array();
				for ($i = $english_min; $i <= $english_max; $i++)
					$english_order_1[$i] = $english_order_2[$i] = $shifts;

				// loop 1: shift
				shuffle($applicant_ids);
				foreach ($applicant_ids as $applicant_id) {
					$applicant_id = (int) $applicant_id;

					// current_element
					$assignment = array(
						'applicant_id' => $applicant_id,
						'shift' => $current_shift,
						'personality' => 0,
						'english' => 0
					);

					$global_assignments[$applicant_id] = $assignment;
					$shifts[$current_shift][] = $applicant_id;

					if ($current_shift == $shift_max)
						$current_shift = $shift_min;
					else
						$current_shift++;
				}
				ksort($shifts);
				ksort($global_assignments);

				// loop 2: chambers
				foreach ($shifts as $k => $applicant_ids) {
					foreach ($applicant_ids as $applicant_id) {
						$global_assignments[$applicant_id]['personality'] = $current_personality;
						$global_assignments[$applicant_id]['english'] = $current_english;

						$assignment = $global_assignments[$applicant_id];

						// push assignment into chamber sorting tables
						$current_shift = $assignment['shift'];
						
						$personality_order[$current_personality][$current_shift][] = $assignment;

						if ($current_shift > $shift_halfpoint)
							$english_order_1[$current_english][$current_shift][] = $assignment;
						else
							$english_order_2[$current_english][$current_shift][] = $assignment;

						// iterate chamber iterators

						foreach (array('personality', 'english') as $var) {
							$current_var = 'current_' . $var;
							$var_max = $var . '_max';
							$var_min = $var . '_min';
							if ($$current_var == $$var_max)
								$$current_var = $$var_min;
							else
								$$current_var++;
						}
					}
				}

				// order the interview schedule by shift
				$personality_schedules = array();
				foreach ($personality_order as $chamber => $shifts) {
					ksort($shifts);
					$chamber_schedule = array();
					foreach ($shifts as $assignments)
						foreach ($assignments as $assignment)
							$chamber_schedule[] = $assignment;

					$personality_schedules[$chamber] = $chamber_schedule;
				}
				ksort($personality_schedules);

				// for english chambers, we do something different
				$english_schedules = array();
				foreach ($english_order_1 as $chamber => $earlier_shifts) {
					ksort($earlier_shifts);
					$chamber_schedule = array();
					foreach ($earlier_shifts as $assignments)
						foreach ($assignments as $assignment)
							$chamber_schedule[] = $assignment;
					
					$later_shifts = $english_order_2[$chamber];
					if ($later_shifts) {
						ksort($later_shifts);
						foreach ($later_shifts as $assignments)
							foreach ($assignments as $assignment)
								$chamber_schedule[] = $assignment;
					}

					$english_schedules[$chamber] = $chamber_schedule;
				}
				ksort($english_schedules);

				$assignments = $global_assignments;

				// we have arrays that represent three tables.
				// now push them!
				$queries = array();
				
				// update active_applicants
				foreach ($assignments as $assignment) {
					extract($assignment);
					$queries[] = "UPDATE `active_applicants` SET `selection_1_passed`=1, `personality_chamber_id`=$personality, `english_chamber_id`=$english WHERE `applicant_id`=$applicant_id";
				}

				// insert into personality_interview_order
				$personality_query = 'INSERT INTO `personality_interview_order` (`personality_chamber_id`, `order`, `applicant_id`) VALUES ';
				$personality_rows = array();
				foreach ($personality_schedules as $chamber => $assignments) {
					foreach ($assignments as $order => $assignment) {
						extract($assignment);
						$order_sql = $order + 1;
						$personality_rows[] = "($chamber, $order_sql, $applicant_id)";
					}
				}
				$personality_query .= implode(', ', $personality_rows);
				$queries[] = $personality_query;
				
				// insert into english_interview_order
				$english_query = 'INSERT INTO `english_interview_order` (`english_chamber_id`, `order`, `applicant_id`) VALUES ';
				$english_rows = array();
				foreach ($english_schedules as $chamber => $assignments) {
					foreach ($assignments as $order => $assignment) {
						extract($assignment);
						$order_sql = $order + 1;
						$english_rows[] = "($chamber, $order_sql, $applicant_id)";
					}
				}
				$english_query .= implode(', ', $english_rows);
				$queries[] = $english_query;
				
				foreach ($queries as $query)
					$db->query($query);

				// test vars
				$this['a'] = $global_assignments;
				$this['p'] = $personality_schedules;
				$this['e'] = $english_schedules;
				$this['q'] = $queries;
				break;
			case 'comms':
				// do this later
				break;
			default:
				// default prompt for input variables
				$this['errors'] = $this->session->flash('errors');
				$this['applicants'] = $this->session['applicants'];
				$this['personality_chamber_count'] = $this->session['personality_chamber_count'];
				$this['english_chamber_count'] = $this->session['english_chamber_count'];
		}
	}

	public function view_selection_2_assignments() {
		$db = Helium::db();

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$personality = $_POST['personality'];
			$english = $_POST['english'];
			$queries = array();
			foreach (compact('personality', 'english') as $type => $rows) {
				$table = $type . '_interview_order';

				$latest = $db->get_results("SELECT applicant_id, entered, exited FROM $table WHERE 1");
				$last_values = array();
				foreach ($latest as $row) {
					$last_values[$row->applicant_id] = array('entered' => $row->entered, 'exited' => $row->exited);
				}

				$rows_o = $_POST[$type . '_o'];
				if ($rows_o) {
					foreach ($rows_o as $row => $prev) {
						list($applicant_id, $ed) = explode('|', $row);
						$value = $_POST[$type][$row];
						$prev = (int) (bool) ($prev);
						if ($value != $prev) {
							$applicant_id = addslashes($applicant_id);
							$ed = addslashes($ed);
							if ($ed == 'entered' || $ed == 'exited') {
								$last_value = $last_values[$applicant_id][$ed];
								if ($prev == $last_value)
									$queries[] = "UPDATE $table SET `$ed`='$value' WHERE applicant_id='$applicant_id'";
							}
						}
					}
				}
				foreach ($queries as $q)
					$db->query($q);
			}
			$this->session['last_tab'] = $_POST['last_tab'];
			$this['saved'] = true;
		}

		$this['last_tab'] = $this->session['last_tab'];

		$chambers = array();

		foreach (array('personality', 'english') as $type) {
			$rows = $db->get_results("SELECT *, active_applicants.full_name, active_applicants.school FROM {$type}_interview_order LEFT JOIN active_applicants ON {$type}_interview_order.applicant_id = active_applicants.applicant_id WHERE 1");
			$chamber_list = array();
			$chamber = $type . '_chamber_id';
			if (!$rows)
				Gatotkaca::redirect(array('controller' => 'admin', 'action' => 'prepare_selection_2'));
			foreach ($rows as $row) {
				if (!is_array($chamber_list[$row->$chamber]))
					$chamber_list[$row->$chamber] = array();
				$chamber_list[$row->$chamber][] = $row;
			}
			$chambers[$type] = $chamber_list;
		}

		$this['chambers'] = $chambers;
	}

	public function prepare_selection_3() {
		// 1. Ask for the IDs of applicants that pass selection 2
		// 2. Ask for the number of shifts
		// 3. Assign shifts to said applicants
		// 4. Update active_applicants and set selection_2_passed = 1.

		// this action is divided into several stages in $_POST['stage'].
		// a. input (default/no defined stage)
		// b. confirmation
		// c. comms process (email announcement)

		$stage = $_POST['stage'];
		$this['stage'] = $stage;
		
		$view_assignments = array('controller' => 'admin', 'action' => 'index');
		
		$db = Helium::db();
		$precheck = $db->get_var("SELECT COUNT(*) FROM active_applicants WHERE selection_2_passed=1");
		if ($precheck)
			Gatotkaca::redirect($view_assignments);

		switch ($_POST['stage']) {
			case 'confirm':
				// admin has entered applicant IDs and the number of chambers

				// sanitize applicant IDs
				$applicants = trim($_POST['applicants']);
				$applicants = str_replace(Helium::conf('applicant_prefix'), '', $applicants);
				$applicants = preg_split('/\s+/', $applicants);
				$applicant_ids = array();
				foreach ($applicants as $k => $v) {
					$n = (int) $v;
					if ($n && !in_array($n, $applicant_ids)) // no overlap allowed
						$applicant_ids[$k] = $n;
				}

				if ($applicant_ids) {
					$this['shift_count'] = $shift_count = intval(trim($_POST['shift_count']));

					$range = '(' . implode(', ', $applicant_ids) . ')';
					$active_applicants = ActiveApplicant::find("applicant_id IN $range");
					$active_applicants->set_batch_length(0);
					$this['applicants'] = $active_applicants;

					// sanitize $applicant_ids, only include IDs that exist
					$applicant_ids = array();
					foreach ($active_applicants as $active_applicant) {
						$applicant_ids[] = $active_applicant->applicant_id;
					}
				}

				// validate here please
				$errors = array();
				if (!$applicant_ids)
					$errors[] = 'Invalid applicant IDs.';
				if (!$shift_count)
					$errors[] = 'Invalid shift count.';

				if ($errors) { // did not pass validation
					$this->session['errors'] = $errors;
					$this->session['shift_count'] = $_POST['shift_count'];
					$this->session->save();
					Gatotkaca::redirect(array('controller' => 'admin', 'action' => 'prepare_selection_3'));
				}
				else {
					// validated.
					unset(
						$this->session['applicants'],
						$this->session['personality_chamber_count'],
						$this->session['english_chamber_count']
					);
					$this->session['prepare_selection_3_input'] = compact('applicant_ids', 'shift_count');
				}
				break;
			case 'process':
				// admin has confirmed that the selection of applicants is correct.
				// we shall assign to these applicants their shifts and chambers.
				$input = $this->session->flash('prepare_selection_3_input');
				
				if (!$input)
					Gatotkaca::redirect($view_assignments);
				$applicant_ids = $input['applicant_ids'];
				$applicant_count = count($applicant_ids);
				$shift_count = $input['shift_count'];

				// the number of participants in one particular shift
				$chunk_size = ceil($applicant_count / $shift_count);

				// for loop for each shift
				// the inside the loop, assign shifts
				$applicant_cursor = 0;
				$applicant_assignments = array();
				for ($i = 1; $i <= $shift_count; $i++) {
					$stop = $applicant_cursor + $chunk_size;
					for ($j = $applicant_cursor; $j < $stop; $j++) {
						$applicant_id = $applicant_ids[$j];
						if ($applicant_id)
							$applicant_assignments[$applicant_id] = $i;
					}
					$applicant_cursor = $j;
				}

				// we have arrays that represent three tables.
				// now push them!
				$queries = array();
			
				// update active_applicants
				foreach ($applicant_assignments as $applicant_id => $shift) {
					$queries[] = "UPDATE `active_applicants` SET `selection_2_passed`=1, `selection_3_shift`=$shift WHERE `applicant_id`=$applicant_id";
				}

				foreach ($queries as $query)
					$db->query($query);
				break;
			case 'comms':
				// do this later
				break;
			default:
				// default prompt for input variables
				$this['errors'] = $this->session->flash('errors');
				$this['applicants'] = $this->session['applicants'];
				$this['personality_chamber_count'] = $this->session['personality_chamber_count'];
				$this['english_chamber_count'] = $this->session['english_chamber_count'];
		}
	}
}