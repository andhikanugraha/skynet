<?php

class ApplicantController extends AppController {

	public $default_action = 'redeem';

	public $applicant;

	public function init() {
		if ($this->is_logged_in())
			$this->applicant = $this->session->user->applicant;
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

		$enable_recaptcha = $this['enable_recaptcha'] = false;

		$this['recaptcha'] = $recaptcha = new RECAPTCHA;

		unset($this->session['registration_code']);

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// submitting a form
			$token = strtoupper(trim($_POST['token']));

			if (!isset($token))
				$error = 'incomplete';

			// validate the token
			if (!$error) {
				$code = RegistrationCode::find_by_token($token);
				if (!$code)
					$error = 'token_nonexistent';
			}
			
			if (!$error && !$code->is_available())
				$error = 'token_unavailable';
				
			if (!$error && $code->is_expired())
				$error = 'token_expired';

			// validate reCAPTCHA
			if ($enable_recaptcha && !$error && !$recaptcha->check_answer()) {
				$error = 'recaptcha';
			}

			if (!$error) {
				// Everything went alright

				// save the code into session,
				// then redirect to applicant/create
				$this->session['registration_code_token'] = $code->token;
				$this->http_redirect(array('controller' => 'applicant', 'action' => 'create'));
			}
			else {
				$this->session['error'] = $error;
				$this->http_redirect(array('controller' => 'applicant', 'action' => 'redeem'));
			}
		}

		$this['error'] = $this->session->flash('error');
		$this['chapters'] = Chapter::find('all');
	}

	public function create() {
		if ($this->applicant)
			$this->auth->land();
	
		// we need a code on hand to get to this form

		$token = $this->session['registration_code_token'];
		$code = RegistrationCode::find_by_token($token);
		// registration code validation
		// if this doesn't pass, redirect back to applicant/redeem
		if (!$code) {
			Gatotkaca::redirect(array('controller' => 'applicant', 'action' => 'redeem'));
		}
		elseif (!$code->validate()) {
			$this->session['registration_code_error'] = $code->validation_error;
			Gatotkaca::redirect(array('controller' => 'applicant', 'action' => 'redeem'));
		}

		$this['expires_on'] = $code->expires_on;
		$this['form'] = new FormDisplay;
		$this['chapter_name'] = $code->chapter->chapter_name;

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// submitting a form here
			$username = trim($_POST['username']);
			$password = $_POST['password'];
			$retype_password = $_POST['retype_password'];
			$email = trim($_POST['email']);

			// validate username, password and email.
			$validate = array();

			if (!isset($username, $password, $retype_password, $email))
				$error = 'incomplete';

			if (!$error) {
				$username_check = (bool) User::find(array('username' => $username))->first();
				if ($username_check)
					$error = 'username_availability';
			}

			if (!$error) {
				// username validation
				// username can only contain letters, numbers and underscore. min. 3 chars.
				$username_pattern = "/^[a-z0-9_\-]{4,}$/i";
				if (!preg_match($username_pattern, $username))
					$error = 'username_format';
			}

			// validate retype password
			if ($password != $retype_password)
				$error = 'retype_password';
			
			if (strlen($password) < 8)
				$error = 'password';

			// validate email
			if (!filter_var($email, FILTER_VALIDATE_EMAIL))
				$error = 'email';

			if (!$error) {
				// everything set to go

				$db = Helium::db();
				
				try {
					$db->autocommit(false);

					// redeem the reg code
					$code->redeem();

					$chapter = $code->chapter;

					// create the user
					$user = new User;
					$user->username = $username;
					$user->set_password($password);
					$user->email = $email;
					$user->role = 1;
					$user->chapter_id = $code->chapter_id;
					$user->save();

					// create the applicant
					$applicant = new Applicant;
					$applicant->map_vertical_partitions();
					$applicant->expires_on = clone $code->expires_on;
					$applicant->chapter_id = $code->chapter_id;
					$applicant->program_year = $code->program_year;
					$applicant->citizenship = 'Indonesia';
					
					$applicant->user_id = $user->id;
					$applicant->applicant_email = $email;

					$province = $chapter->chapter_area;
					$city = $chapter->chapter_name;
					$address_keys = array('applicant', 'high_school');
					foreach ($address_keys as $k) {
						$p = $k . '_address_province';
						$applicant->$p = $province;
					}
					if ($city != $province) {
						foreach ($address_keys as $k) {
							$c = $k . '_address_city';
							$applicant->$c = $city;
						}
					}

					$applicant->save();

					$db->commit();

					// assign the code to the user
					$code->applicant_id = $applicant->id;
					$code->save();

					// link everything up
					$applicant->save();
					$user->save();

					// login as the new user
					$this->auth->process_login($username, $password);
					$this->session->is_persistent = $_POST['remember'];
					$this->session['just_logged_in'] = true;
					$this->session->save();
					
					$db->commit();
					
					$db->autocommit(true);
				}
				catch (HeliumException $e) {
					$db->rollback();

					$error = 'db_fail';
				}

				if (!$error) {
					$this['mode'] = 'success';

					$this->session['registration_code'] = '';
					$this->http_redirect(array('controller' => 'applicant', 'action' => 'form'));
				}
			}
			if ($error) {
				$this->session['username'] = $username;
				$this->session['email'] = $email;
				$this['error'] = $error;
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
			$user_id = $this->session->user->id;
			$applicant = $this->session->user->applicant;

			// if ($applicant->finalized)
			// 	$this->auth->land();

			// $this->check_expiry();
		}

		$applicant_id = $applicant->id;

		$pictures = Picture::find(compact('applicant_id'));
		$pictures->set_order('DESC');
		$picture = $this['picture'] = $pictures->first();

		$this['new'] = $this->session->flash('just_logged_in');
		$this['errors'] = $this->session->flash('form_errors');
		$this['incomplete'] = $this->session->flash('incomplete');
		$this['notice'] = $this->session->flash('notice');

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// store the form values in the DB
			$proc = new FormProcessor;
			$proc->add_uneditables('id', 'applicant_id', 'user_id', 'chapter_id', 'program_year', 'expires_on', 'confirmed', 'finalized');
			$proc->associate($applicant);
			$proc->commit();
			$applicant->save();

			$this->session['notice'] = 'Data Adik berhasil disimpan sementara. Silakan melanjutkan mengisi formulir.';
			$this->session['last_pane'] = $_POST['last_pane'];

			$old_siblings = $applicant->applicant_siblings;
			$old_siblings->delete_all();
			$new_siblings = $_POST['siblings'];
			if ($new_siblings) {
				foreach ($new_siblings as $sib) {
					if ($sib['full_name']) {
						$sp = new FormProcessor($sib);
						$sb = new ApplicantSibling;
						$sb->applicant_id = $applicant->id;
						$sp->add_uneditables('id', 'applicant_id');
						$sp->associate($sb);
						$sp->commit();
						$sb->save();
					}
				}
			}

			// // handle upload, if any.
			if (isset($_FILES['picture']) && $_FILES['picture']['tmp_name']) {
				$file = $_FILES['picture'];
				$pic = new Picture;
				$pic->upload_original($file);
				$this->session['picture'] = $pic;
			
				Gatotkaca::redirect(array('controller' => 'applicant', 'action' => 'crop_picture'));
				exit;
			}

			// // finalization process
			// 			if ($_POST['finalize']) {
			// 				// we validate the completeness of the form here first.
			// 				// $applicant->finalized = true;
			// 				// $applicant->save();
			// 				$try = $applicant->finalize();
			// 				if ($try)
			// 					Gatotkaca::redirect(array('controller' => 'applicant', 'action' => 'finalized'));
			// 				else {
			// 					$errors = $applicant->validation_errors;
			// 					$errors = array_map(function($e) {
			// 						switch ($e) {
			// 							case 'incomplete':
			// 								return 'Formulir belum lengkap. Pastikan seluruh bagian formulir ini telah terisi.';
			// 							case 'picture':
			// 								return 'Adik belum mengunggah (upload) foto.';
			// 							case 'birth_date':
			// 								return 'Tanggal lahir Adik harus di antara <strong>1 September 1994</strong> dan <strong>1 April 1996</strong>';
			// 							default:
			// 								return $e;
			// 						}
			// 					}, $errors);
			// 					$this->session['form_errors'] = $errors;
			// 					$this->session['incomplete'] = $applicant->incomplete_fields;
			// 				}
			// 			}

			$this->http_redirect($this->params);
			// @header('Location: ' . PathsComponent::build_url($this->params) . $_POST['last_pane']);
		}

		$form = new FormDisplay;
		$form->associate($applicant);
		$this['form'] = $form;
		$this['expires_on'] = $applicant->expires_on;
		
		$this['program_year'] = $applicant->program_year;
		
		$this['last_pane'] = substr($this->session->flash('last_pane'), 1);
		
		$applicant_siblings = $applicant->applicant_siblings;
		$applicant_siblings->set_order_by('date_of_birth');
		$applicant_siblings->set_order('ASC');
		$sforms = array();
		$i = 0;
		foreach ($applicant_siblings as $s) {
			$d = new FormDisplay;
			$d->associate($s);
			$d->make_subform("siblings[$i]");
			$i++;
			$sforms[] = $d;
		}
		while ($i < ($applicant->number_of_children_in_family - 1)) {
			$d = new FormDisplay;
			$d->make_subform("siblings[$i]");
			$i++;
			$sforms[] = $d;
		}

		$this['sibling_forms'] = $sforms;
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