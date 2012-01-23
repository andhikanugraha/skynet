<?php

class ApplicantController extends AppController {

	public $default_action = 'redeem';

	public $applicant;

	/**
	 *
	 */
	public function init() {
		if ($this->is_logged_in())
			$this->applicant = $this->session->user->applicant;
	}

	/**
	 * @deprecated
	 */
	private function check_expiry() {
		if ($this->applicant && $this->applicant->expires_on->earlier_than('now'))
			$this->auth->land();
	}

	/**
	 * @deprecated
	 */
	private function check_submitted() {
		if (!$this->applicant->submitted)
			$this->auth->land();
	}

	/**
	 *
	 */
	public function expired() {}

	/**
	 * @deprecated
	 */
	private function require_finalized() {
		if (!$this->applicant->finalized && Helium::conf('production'))
			Gatotkaca::redirect(array('controller' => 'applicant', 'action' => 'form'));
	}

	/**
	 *
	 */
	public function guide() {
		Gatotkaca::redirect('/uploads/guide.pdf'); exit;
	}

	/**
	 *
	 */
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
		$this['chapters'] = Chapter::find('id != 1');
	}

	/**
	 *
	 */
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

	/**
	 * @deprecated
	 */
	public function status() {
		$this->render = false;
		Gatotkaca::redirect(array('controller' => 'applicant', 'action' => 'form'));
	}

	/**
	 * List applicants
	 */
	public function index() {
		$this->require_role('chapter_staff');
		
		$applicants = Applicant::find();

		$db = Helium::db();
		
		// -- Filtering --
		
		// Filter by chapter
		// This can only be used by national admin
		if ($this->user->capable_of('national_admin')) {
			if ($this->params['chapter_id'] && $this->params['chapter_id'] != 1) {
				$applicants->narrow(array('chapter_id' => $this->params['chapter_id']));
				$is_search = true;
				$chapter = Chapter::find($this->params['chapter_id']);
			}
		}
		// Otherwise, only list applicants from user's chapter
		else {
			$applicants->narrow(array('chapter_id' => $this->user->chapter_id));
			$chapter = $this->user->chapter;
		}
		
		// Filter by stage
		$applicants->add_additional_column('expired', "expires_on < '" . (new HeliumDateTime('now')) . "'");
		switch ($this->params['stage']) {
			case 'expired':
				$applicants->narrow(array('expired' => true, 'confirmed' => false));
				break;
			case 'unexpired':
				$applicants->narrow(array('expired' => false));
				$applicants->widen(array('confirmed' => true));
				break;
			case 'confirmed':
				$applicants->narrow(array('confirmed' => true));
				break;
			case 'finalized':
				$applicants->narrow(array('finalized' => true));
				break;
			case 'anomaly':
				$applicants->narrow(array('confirmed' => false, 'expired' => true, 'finalized' => true));
				break;
			case 'incomplete':
				$applicants->narrow(array('confirmed' => false, 'expired' => false, 'finalized' => false));
				break;
		}
		
		// Filter by school
		if ($this->params['school_name']) {
			$applicants->narrow(array('sanitized_high_school_name' => $this->params['school_name']));
			$is_search = true;
		}

		// Filter by name
		if ($this->params['name']) {
			$criteria = $db->prepare("`sanitized_full_name` LIKE '%%%s%%'", str_replace(' ', '%', $this->params['name']));
			$applicants->narrow($criteria);
			$is_search = true;
		}

		// TODO
		// Filter by POB
		// Filter by DOB
		
		// -- Ordering --
		
		switch ($this->params['order_by']) {
			case 'school':
				$order_by = 'sanitized_high_school_name';
				break;
			case 'name':
				$order_by = 'sanitized_full_name';
				break;
			case 'test_id':
			default:
				$order_by = 'test_id';
		}
		$applicants->set_order_by($order_by);
		
		if (strtoupper($this->params['order']) == 'DESC')
			$applicants->set_order('DESC');
		else
			$applicants->set_order('ASC');

		// -- Pagination --
		$batch_length = 100;
		$applicants->set_batch_length($batch_length);
		if (!$this->params['page'])
			$this->params['page'] = 1;
		$page = $this->params['page'];
		$count_all = $applicants->count_all();
		$applicants->set_batch_number($page);
		$first = (($page - 1) * $batch_length) + 1;
		$last = ($first + $batch_length - 1) > $count_all ? $count_all : ($first + $batch_length - 1);

		// Applicants is now ready for listing.
		$this['applicants'] = $applicants;
		$this['chapter'] = $chapter;
		$this['total_pages'] = $applicants->get_number_of_batches();
		$this['current_page'] = $page;
		$this['first'] = $first;
		$this['last'] = $last;
		$this['count_all'] = $count_all;
		$this['current_stage'] = $this->params['stage'];

		if ($this->user->capable_of('national_admin'))
			$this['schools'] = $this->get_schools();
		else
			$this['schools'] = $this->get_schools($this->user->chapter_id);
			
		$this['form'] = new FormDisplay;
		
		$this->session['back_to'] = $this->params;
	}

	/**
	 *
	 */
	public function get_schools($chapter_id = null) {
		$db = Helium::db();

		$q = "SELECT DISTINCT sanitized_high_school_name FROM applicants";
		if ($chapter_id)
			$q .= $db->prepare(" WHERE chapter_id = '%s'", $chapter_id);

		return $db->get_col($q);
	}

	/**
	 * Edit an applicant's application form.
	 *
	 * Accessible either as an applicant or as an admin, with slight UI differences.
	 */
	public function form() {
		$this->require_authentication();
		
		if ($this->session->user->capable_of('chapter_admin')) {
			$this['admin'] = true;

			if ($this->params['readonly'])
				$readonly = true;

			$id = $this->params['id'];
			if (!$id)
				$error = 'not_found';
			else {
				$applicant = Applicant::find($id);
				if (!$applicant)
					$error = 'not_found';

				if (!$error && !$this->user->capable_of('national_admin') && ($this->user->chapter_id != $applicant->chapter_id))
					$error = 'forbidden';
			
				if (!$error && $applicant->finalized)
					$error = 'applicant_finalized';
			}
		}
		else {
			$this->require_role('applicant');
			$user_id = $this->session->user->id;
			$applicant = $this->session->user->applicant;

			if ($applicant->finalized || $applicant->is_expired())
				$this->auth->land();
		}

		if (!$error) {

			$applicant_id = $applicant->id;

			$pictures = Picture::find(compact('applicant_id'));
			$pictures->set_order('DESC');
			$picture = $this['picture'] = $pictures->first();

			$this['new'] = $this->session->flash('just_logged_in');
			$this['errors'] = $this->session->flash('form_errors');
			$this['incomplete'] = $this->session->flash('incomplete');
			$this['notice'] = $this->session->flash('notice');

			$subforms = array(	'siblings' => 'applicant_siblings',
								'applicant_organizations' => 'applicant_organizations',
								'applicant_arts_achievements' => 'applicant_arts_achievements',
								'applicant_sports_achievements' => 'applicant_sports_achievements',
								'applicant_other_achievements' => 'applicant_other_achievements',
								'applicant_work_experiences' => 'applicant_work_experiences');

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				// store the form values in the DB
				$proc = new FormProcessor;
				$proc->add_uneditables('id', 'applicant_id', 'user_id', 'chapter_id', 'program_year', 'expires_on', 'confirmed', 'finalized');
				$proc->associate($applicant);
				$proc->commit();
				$applicant->save();

				$this->session['notice'] = 'Data Adik berhasil disimpan sementara. Silakan melanjutkan mengisi formulir.';
				$this->session['last_pane'] = $_POST['last_pane'];

				foreach ($subforms as $f => $d) {
					$old = $applicant->$d;
					$old->delete_all();
					$new = $_POST[$f];

					if ($new) {
						foreach ($new as $node) {
							if ($node) {
								foreach ($node as $n) {
									if ($not_empty)
										break;

									if (is_array($n)) {
										foreach ($n as $o)
											if ($o)
												$not_empty = true;
									}
									elseif ($n)
										$not_empty = true;
								}
								if ($not_empty) {
									$sp = new FormProcessor($node);
									$class_name = Inflector::classify($d);
									$sb = new $class_name;
									$sb->applicant_id = $applicant->id;
									$sp->add_uneditables('id', 'applicant_id');
									$sp->associate($sb);
									$sp->commit();
									$sb->save();
								}
							}

							$not_empty = false;
						}
					}
				}

				// // handle upload, if any.
				if (isset($_FILES['picture']) && $_FILES['picture']['tmp_name']) {
					$file = $_FILES['picture'];
					$pic = new Picture;
					$pic->upload_original($file);
					$this->session['picture'] = $pic;
			
					$this->http_redirect(array('controller' => 'applicant', 'action' => 'crop_picture'));

					exit;
				}

				// finalization process
				if ($_POST['finalize']) {
					// we validate the completeness of the form here first.
					// $applicant->finalized = true;
					// $applicant->save();
					$try = $applicant->finalize();
					if ($try) {
						$applicant->save();
						$this->http_redirect(array('controller' => 'applicant', 'action' => 'finalized'));
					}
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

				$this->http_redirect($this->params);
				// @header('Location: ' . PathsComponent::build_url($this->params) . $_POST['last_pane']);
			}

			$form = new FormDisplay;
			$form->associate($applicant);
			$this['form'] = $form;
			$this['expires_on'] = $applicant->expires_on;
		
			$this['applicant'] = $applicant;
		
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

			$this['sibling_forms'] = $sforms;
		
			$subform_forms = array();
			foreach ($subforms as $f => $d) {
				$nodes = $applicant->$d;
				$i = 1;
				$forms = array();
				if ($nodes) {
					foreach ($nodes as $s) {
						$d = new FormDisplay;
						$d->associate($s);
						$d->make_subform($f . '[' . $i . ']');
						$i++;
						$forms[] = $d;
					}
				}

				$subform_forms[$f] = $forms;
			}
		
			$this['subforms'] = $subform_forms;
		}
		else {
			$this['error'] = $error;
		}
	}


	/**
	 * View a read-only, complete version of an applicant's application form.
	 *
	 * Accessible either as an applicant or as an admin, with slight UI differences.
	 */
	public function details() {
		$this->require_authentication();
		
		if ($this->session->user->capable_of('chapter_admin')) {
			$this['admin'] = true;

			if ($this->params['readonly'])
				$readonly = true;

			$id = $this->params['id'];
			if (!$id)
				$error = 'not_found';
			else {
				$applicant = Applicant::find($id);
				if (!$applicant)
					$error = 'not_found';

				if (!$error && !$this->user->capable_of('national_admin') && ($this->user->chapter_id != $applicant->chapter_id))
					$error = 'forbidden';
			}
		}
		else {
			$this->require_role('applicant');
			$user_id = $this->session->user->id;
			$applicant = $this->session->user->applicant;
		}

		if (!$error) {

			$applicant_id = $applicant->id;

			$pictures = Picture::find(compact('applicant_id'));
			$pictures->set_order('DESC');
			$picture = $this['picture'] = $pictures->first();

			$subforms = array(	'siblings' => 'applicant_siblings',
								'applicant_organizations' => 'applicant_organizations',
								'applicant_arts_achievements' => 'applicant_arts_achievements',
								'applicant_sports_achievements' => 'applicant_sports_achievements',
								'applicant_other_achievements' => 'applicant_other_achievements',
								'applicant_work_experiences' => 'applicant_work_experiences');

			$this['a'] = $applicant;

			foreach ($subforms as $k => $sf)
				$this[$k] = $applicant->$sf;

			$form = new FormTranscript;
			$form->associate($applicant);
			$this['form'] = $form;
			$this['expires_on'] = $applicant->expires_on;
		
			$this['applicant'] = $applicant;
		
			$this['program_year'] = $applicant->program_year;
		
			$this['last_pane'] = substr($this->session->flash('last_pane'), 1);

			$applicant_siblings = $applicant->applicant_siblings;
			$applicant_siblings->set_order_by('date_of_birth');
			$applicant_siblings->set_order('ASC');
			$sforms = array();
			$i = 0;
			foreach ($applicant_siblings as $s) {
				$d = new FormTranscript;
				$d->associate($s);
				$d->make_subform("siblings[$i]");
				$i++;
				$sforms[] = $d;
			}

			$this['sibling_forms'] = $sforms;
		
			$subform_forms = array();
			foreach ($subforms as $f => $d) {
				$nodes = $applicant->$d;
				$i = 0;
				$forms = array();
				if ($nodes) {
					foreach ($nodes as $s) {
						$d = new FormTranscript;
						$d->associate($s);
						$d->make_subform($f . '[' . $i . ']');
						$i++;
						$forms[] = $d;
					}
				}

				$subform_forms[$f] = $forms;
			}
		
			$this['subforms'] = $subform_forms;
		}
		else {
			$this['error'] = $error;
		}
	}

	/**
	 * View applicant
	 */
	public function view() {
		$this->require_role('chapter_staff');

		$id = $this->params['id'];
		$applicant = Applicant::find($id);
		
		$this->session['applicant_back_to'] = $this->params;
		
		if (!$applicant)
			$error = 'not_found';
		else {
			$this['applicant'] = $applicant;
			$this['picture'] = $applicant->picture;
		}
		
		if (!$error && !$this->user->capable_of('national_admin') && ($applicant->chapter_id != $this->user->chapter_id))
			$error = 'forbidden';
		
		if (!$error && $_SERVER['REQUEST_METHOD'] == 'POST') {
			$applicant->finalized = $_POST['finalized'];
			$applicant->confirmed = $applicant->finalized ? $_POST['confirmed'] : false;
			$applicant->save();
		}
		
		if (!$error) {
			$back_to = $this->session['back_to'];
			if (!$back_to)
				$back_to = array('controller' => 'applicant', 'action' => 'index');

			$this['back_to'] = $back_to;

			$this['can_edit'] = $this->user->capable_of('chapter_admin') && !$applicant->finalized;
		}
		else
			$this['error'] = $error;
	}

	/**
	 *
	 */
	public function crop_picture() {
		$this->require_role('applicant');

		$this->check_expiry();

		if (!$this->session['picture'])
			$this->http_redirect(array('controller' => 'applicant', 'action' => 'form'));
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
				$this->http_redirect(array('controller' => 'applicant', 'action' => 'form'));
			}
			else {
				$this->session['error'] = 'Pengunggahan foto gagal.';
			}
			exit;
		}
	}

	/**
	 *
	 */
	public function card() {
		if ($this->session->user->capable_of('chapter_admin')) {
			$this['admin'] = true;

			if ($this->params['readonly'])
				$readonly = true;

			$id = $this->params['id'];
			if (!$id)
				$error = 'not_found';
			else {
				$applicant = Applicant::find($id);
				if (!$applicant)
					$error = 'not_found';

				if (!$error && !$this->user->capable_of('national_admin') && ($this->user->chapter_id != $applicant->chapter_id))
					$error = 'forbidden';
			}
		}
		else {
			$this->require_role('applicant');
			$user_id = $this->session->user->id;
			$applicant = $this->session->user->applicant;
		}

		if ($error)
			$this->render = false;

		$applicant_id = $applicant->id;
		$picture = $applicant->picture;

		$this['name'] = $applicant->sanitized_full_name;
		$this['applicant'] = $applicant;
		$this['picture'] = $picture;
		
		if (!$applicant->finalized)
			$this->render = false;
	}

	/**
	 *
	 */
	public function finalized() {
		$this->require_role('applicant');

		$applicant = $this['applicant'] = $this->applicant;
		
		if (!$applicant->finalized || $applicant->confirmed)
			$this->auth->land();
	}

	/**
	 *
	 */
	public function confirmed() {
		$this->require_role('applicant');

		$applicant = $this['applicant'] = $this->applicant;

		if (!$applicant->confirmed)
			$this->auth->land();
	}

	/**
	 * 
	 */
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

	/**
	 * @deprecated
	 */
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

	/**
	 * @deprecated
	 */
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