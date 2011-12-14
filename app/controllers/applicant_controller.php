<?php

class ApplicantController extends GatotkacaController {

	public $default_action = 'redeem';

	public $applicant;

	public function init() {
		if ($this->is_logged_in()) {
			$user_id = $this->session->user->id;
			$this->applicant = Applicant::find_by_user($user_id);
		}
	}

	private function check_expiry() {
		if ($this->applicant && $this->applicant->expires_on->earlier_than('now'))
			$this->auth->land();
	}
	
	private function check_submitted() {
		if (!$this->applicant->submitted)
			$this->auth->land();
	}

	public function expired() {}

	private function require_finalized() {
		if (!$this->applicant->finalized && Helium::conf('production'))
			Gatotkaca::redirect(array('controller' => 'applicant', 'action' => 'form'));
	}

	public function guide() {
		Gatotkaca::redirect('/uploads/guide.pdf'); exit;
	}

	public function redeem() {
		if ($this->applicant)
			$this->auth->land();

		$enable_recaptcha = $this['enable_recaptcha']= false;

		$this['recaptcha'] = $recaptcha = new RECAPTCHA;

		$this['registration_code_error'] = $this->session['registration_code_error'];

		unset($this->session['registration_code']);

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// submitting a form
			$token = strtoupper(trim($_POST['token']));

			// validation routine
			$validate = array();

			$validate['incomplete'] = isset($token);

			// validate the token
			$code = RegistrationCode::find_by_token($token);
			if (!$code)
				$validate['token_nonexistent'] = false;
			elseif (!$code->validate())
				$validate[$code->validation_error] = false;

			// validate reCAPTCHA
			if ($enable_recaptcha)
				$validate['recaptcha'] = $recaptcha->check_answer();

			$errors = array();
			$valid = true;
			foreach ($validate as $type => $check) {
				if (!$check) {
					$valid = false;
					$errors[] = $type;
				}
			}

			if (!$valid) {
				$this['mode'] = 'fail';
				$this['errors'] = $errors;
			}
			else {
				// save the code into session,
				// then redirect to applicant/create
				$this->session['registration_code'] = $code;
				// $this->session->save();
				Gatotkaca::redirect(array('controller' => 'applicant', 'action' => 'create'));
			}
		}
	}

	public function create() {
		if ($this->applicant)
			$this->auth->land();
	
		// we need a code on hand to get to this form

		$code = $this->session['registration_code'];
		// registration code validation
		// if this doesn't pass, redirect back to applicant/redeem
		if (!$code) {
			Gatotkaca::redirect(array('controller' => 'applicant', 'action' => 'redeem'));
		}
		elseif (!$code->validate()) {
			$this->session['registration_code_error'] = $code->validation_error;
			Gatotkaca::redirect(array('controller' => 'applicant', 'action' => 'redeem'));
		}

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// submitting a form here
			$username = trim($_POST['username']);
			$password = $_POST['password'];
			$retype_password = $_POST['retype_password'];
			$email = trim($_POST['email']);

			// validate username, password and email.
			$validate = array();

			$validate['incomplete'] = isset($username, $password, $retype_password, $email);

			// username validation
			// username can only contain letters, numbers and underscore. min. 3 chars.
			$username_pattern = "/^[a-z0-9_.]{3,}$/i";
			$validate['username_format'] = preg_match($username_pattern, $username);
			
			$username_check = (bool) User::find(array('username' => $username))->first();
			$validate['username_availability'] = !$username_check;

			// validate password
			$validate['password'] = strlen($password) >= 8;

			// validate retype password
			$validate['retype_password'] = ($password == $retype_password);

			// validate email
			$validate['email'] = filter_var($email, FILTER_VALIDATE_EMAIL);

			$errors = array();
			$valid = true;
			foreach ($validate as $type => $check) {
				if (!$check) {
					$valid = false;
					$errors[] = $type;
				}
			}

			if (!$valid) {
				$this->session['username'] = $username;
				$this->session['email'] = $email;
				$this['mode'] = 'fail';
				$this['errors'] = $errors;
			}
			else {
				// everything set to go
				
				// redeem the reg code
				$code->redeem();
				
				// create the user
				$user = new User;
				$user->username = $username;
				$user->set_password($password);
				$user->email = $email;
				$user->role = 'applicant';
				$user->save();
				
				// assign the code to the user
				$code->user_id = $user->id;
				$code->save();

				// create the applicant
				$applicant = new Applicant;
				$applicant->user_id = $user->id;
				$applicant->expires_on = clone $code->expires_on;
				$applicant->save();

				// create applicant detail
				$applicant_detail = new ApplicantDetail;
				$applicant_detail->applicant_id = $applicant->id;
				$applicant_detail->alamat_lengkap['kota'] = 'Bandung';
				$applicant_detail->alamat_lengkap['provinsi'] = 'Jawa Barat';
				$applicant_detail->alamat_lengkap['email'] = $user->email;
				$applicant_detail->save();

				// link everything up
				$applicant_detail->save();
				$applicant->save();
				$user->save();

				// login as the new user
				$this->auth->process_login($username, $password);

				$this['mode'] = 'success';

				unset($this->session['registration_code']);
				Gatotkaca::redirect(array('controller' => 'applicant', 'action' => 'form'));
			}
		}
	}

	public function status() {
		$this->render = false;
		Gatotkaca::redirect(array('controller' => 'applicant', 'action' => 'form'));
	}

	public function form() {
		$this->require_authentication();

		if ($this->session->user->capable_of('admin') && $this->params['id']) {
			if ($this->params['readonly'])
				$readonly = true;
			$id = $this->params['id'];
			$applicant = Applicant::find($id);
			$this['admin'] = true;
		}
		else {
			$this->require_role('applicant');
			$form = $this['form'] = new GatotkacaFormOutput;
			$user_id = $this->session->user->id;
			$applicant = Applicant::find_by_user($user_id);

			if ($applicant->finalized)
				$this->auth->land();

			$this->check_expiry();
		}

		if ($readonly)
			$form = $this['form'] = new GatotkacaFormTranscript;
		else
			$form = $this['form'] = new GatotkacaFormOutput;
		$this['readonly'] = $readonly;

		$applicant_id = $applicant->id;
		$applicant_detail = ApplicantDetail::find(compact('applicant_id'))->first();
		$pictures = Picture::find(compact('applicant_id'));
		$pictures->set_order('DESC');
		$picture = $this['picture'] = $pictures->first();

		/* // DEBUG CODE AKA DATABASE RECONCILIATION
		
		$this['form'] = $form = new GatotkacaFormAnalyzer;
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			echo '<pre>';
			echo <<<EOD
ALTER TABLE applicant_details

EOD;
			$longtext = $_POST['longtext'];
			unset($_POST['longtext']);
			foreach ($_POST as $key => $value) {
				echo 'ADD `'. $key .'` ';
				if (is_array($value) || in_array($key, $longtext))
					echo 'TEXT';
				else
					echo 'VARCHAR(512)';
				echo ',
';
			}
			
			$arrays = array();
			echo "\n";
			
			foreach ($_POST as $key => $value) {
				if (is_array($value)) {
					$arrays[] = $key;
					echo 'public $' . $key . ' = array();';
				}
				else
					echo 'public $' . $key . ' = \'\';';
				echo "\n";
			}
			echo "\n";
			
			foreach ($arrays as $key) {
				echo "\$this->auto_serialize('$key');\n";
			}
			exit;
		}
		
		// END */

		$this['new'] = $this->sessions->flash('just_logged_in');
		$this['errors'] = $this->sessions->flash('form_errors');
		$this['incomplete'] = $this->sessions->flash('incomplete');
		$this['notice'] = $this->sessions->flash('notice');

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// store the form values in the DB
			$checkboxes = $_POST['checkboxes'];
			$applicant_detail->absorb($_POST);
			$applicant_detail->save();
			$this->session['notice'] = 'Data Adik berhasil disimpan sementara. Silakan melanjutkan mengisi formulir.';

			// handle upload, if any.
			if (isset($_FILES['picture']) && $_FILES['picture']['tmp_name']) {
				$file = $_FILES['picture'];
				$pic = new Picture;
				$pic->upload_original($file);
				$this->session['picture'] = $pic;

				Gatotkaca::redirect(array('controller' => 'applicant', 'action' => 'crop_picture'));
				exit;
			}
			
			// finalization process
			if ($_POST['finalize']) {
				// we validate the completeness of the form here first.
				// $applicant->finalized = true;
				// $applicant->save();
				$try = $applicant->finalize();
				if ($try)
					Gatotkaca::redirect(array('controller' => 'applicant', 'action' => 'finalized'));
				else {
					$errors = $applicant->validation_errors;
					$errors = array_map(function($e) {
						switch ($e) {
							case 'incomplete':
								return 'Formulir belum lengkap. Pastikan seluruh bagian formulir ini telah terisi.';
							case 'picture':
								return 'Adik belum mengunggah (upload) foto.';
							case 'birth_date':
								return 'Tanggal lahir Adik harus di antara <strong>1 September 1994</strong> dan <strong>1 April 1996</strong>';
							default:
								return $e;
						}
					}, $errors);
					$this->session['form_errors'] = $errors;
					$this->session['incomplete'] = $applicant->incomplete_fields;
				}
			}

			Gatotkaca::redirect($this->params);
		}
		else
			$form->feed($applicant_detail->form_fields());
	}

	public function crop_picture() {
		$this->require_role('applicant');

		$this->check_expiry();

		if (!$this->session['picture'])
			Gatotkaca::redirect(array('controller' => 'applicant', 'action' => 'form'));
		else
			$this['picture'] = $this->session['picture'];

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			extract($_POST);
			$params = compact('width', 'height', 'x', 'y');
			$pic = $this->session['picture'];
			$crop = $pic->process($params);
			if ($crop) {
				// crop success!
				$user_id = $this->session->user->id;
				$applicant = Applicant::find_by_user($user_id);
				$pic->applicant_id = $applicant->id;
				$pic->save();
				unset($this->session['picture']);

				// back to the form
				Gatotkaca::redirect(array('controller' => 'applicant', 'action' => 'form'));
			}
			else {
				$this->session['error'] = 'Pengunggahan foto gagal.';
			}
			exit;
		}
	}

	public function card() {
		$this->require_role('applicant');
		$this->require_finalized();
		// $this->check_expiry();

		$user_id = $this->session->user->id;
		$applicant = Applicant::find_by_user($user_id);

		// if (!$_POST['username'] || !$_POST['password'])
		// 	Gatotkaca::redirect(array('controller' => 'applicant', 'action' => 'finalized'));

		$applicant_id = $applicant->id;
		$applicant_detail = ApplicantDetail::find(compact('applicant_id'))->first();
		$pictures = Picture::find(compact('applicant_id'));
		$pictures->set_order('DESC');
		$picture = $this['picture'] = $pictures->first();

		$this['det'] = $applicant_detail;
		$this['name'] = $applicant_detail->nama_lengkap;

		// if ($_SERVER['REQUEST_METHOD'] == 'POST')
		// 	$this->render();
		// else
		// 	Gatotkaca::redirect(array('controller' => 'applicant', 'action' => 'finalized'));
	}

	public function finalized() {
		$this->require_role('applicant');
		$this->require_finalized();
		$this->check_expiry();
		
		if ($this->applicant->submitted)
			Gatotkaca::redirect(array('controller' => 'applicant', 'action' => 'submitted'));
	}

	public function transcript() {
		$this->require_role('applicant');
		$this->require_finalized();
		// $this->check_expiry();

		$this->render = false;
		// ob_start();
		$form = $this['form'] = new GatotkacaFormTranscript;

		$user_id = $this->session->user->id;
		$applicant = Applicant::find_by_user($user_id);

		$applicant_id = $applicant->id;
		$applicant_detail = ApplicantDetail::find(compact('applicant_id'))->first();
		$pictures = Picture::find(compact('applicant_id'));
		$pictures->set_order('DESC');
		$picture = $this['picture'] = $pictures->first();
		$this['applicant'] = $applicant;

		$form->feed($applicant_detail->form_fields());
		
		// $this->render();

		$this->render();
		// $html = ob_get_clean();
		// $pdf = true;
		// file_put_contents(HELIUM_PARENT_PATH . '/test.html', $html);
		// $this->session['html'] = $html;
		// if (!$pdf)
		// 	echo $html;
		// else {
		// 	Gatotkaca::redirect(array('controller' => 'applicant', 'action' => 'transcript_pdf'));
		// 	exit;
		// }
	}

	public function transcript_pdf() {
		$this->require_role('applicant');
		$this->render = false;
		try {
			$dompdf = new DOMPDF;
			Helium::$autoload = false;
			$html = $this->session['html'];
			$dompdf->load_html($html);
			$dompdf->set_base_path(HELIUM_PARENT_PATH);
			$dompdf->render();
			unset($this->session['html']);
			$dompdf->stream('transkrip-formulir.pdf');
		}
		catch (DOMPDF_Exception $e) {
			throw new HeliumException((string) $e);
		}
	}

	public function confirm() {
		$this->require_role('volunteer');

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$aid = $_POST['applicant_id'];
			$ap = Applicant::find($aid);
			$ap->confirm();
			$ap->save();
			$this['notice'] = 'Applicant submission confirmed.';
			$this->params['id'] = $aid;
		}

		$id = $this->params['id'];
		if (!$id)
			$this['no_applicant'] = true;
		else {
			$a = $this['a'] = Applicant::find($id);
			$this['d'] = $a->applicant_detail;
			if (!$a)
				$this['error'] = 'Invalid Applicant';
			// elseif (!$a->finalized)
			// 	$this['error'] = 'Applicant not yet finalized';
		}
	}

}