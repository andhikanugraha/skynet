<?php

/**
 * ChapterController
 *
 * @author Andhika Nugraha <andhika.nugraha@gmail.com>
 * @package chapter
 */
class ChapterController extends AppController {
	public function index() {
		$this->require_role('admin');

		$this['chapters'] = Chapter::find('id != 1');
	}

	public function create() {
		$this->require_role('admin');

		$this['form'] = $form = new FormDisplay;
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// Validate input
			
			// Check 1: Required chapter fields
			if (!$error) {
				$required = array('chapter_code', 'chapter_name', 'chapter_email');
				foreach ($required as $r) {
					if (!trim($_POST[$r]) || !trim($_POST['user']['username'])) {
						$errors = 'incomplete_form';
						break;
					}
				}
			}

			// Check 2: Existing chapter code?
			if (!$error) {
				$chapter_code = strtoupper(trim($_POST['chapter_code']));
				$check = Chapter::find(compact('chapter_code'));
				if ($check->count_all()) {
					$error = 'chapter_code_conflict';
					$this['chapter_code'] = $chapter_code;
				}	
			}

			// Check 3: Existing chapter name?
			if (!$error) {
				$chapter_name = trim($_POST['chapter_name']);
				$check = Chapter::find(compact('chapter_name'));
				if ($check->count_all()) {
					$error = 'chapter_name_conflict';
					$this['chapter_name'] = $chapter_name;
				}
			}
		
			// Check 4: Password match
			if (!$error) {
				if ($_POST['user']['password'] != $_POST['user']['confirm_password']) {
					$error = 'password_mismatch';
				}
			}
			
			// Check 5: Password length
			if (!$error) {
				if (strlen($_POST['user']['password']) < 8) {
					$error = 'password_too_short';
				}
			}

			$db = Helium::db();

			try {
				$db->autocommit(false);

				// Validation passed
				if (!$error) {
					$chapter = new Chapter;
					$proc = new FormProcessor;
					$proc->associate($chapter);
					$proc->commit();
					
					if ($chapter->facebook_url == 'http://facebook.com/')
						$chapter->facebook_url = '';
					if ($chapter->site_url == 'http://')
						$chapter->site_url = '';
					if ($chapter->twitter_username{0} == '@')
						$chapter->twitter_username = ltrim($chapter->twitter_username, '@');

					$save = $chapter->save();
					$db->commit();
					if (!$save) {
						$error = 'chapter_addition_failed';
					}
				}

				// Chapter addition succeeded
				if (!$error) {
					$user = new User;
					$user->chapter_id = $chapter->id;
					$user->username = trim($_POST['user']['username']);
					$user->email = $chapter->chapter_email;
					$user->set_password($_POST['user']['password']);
					$user->role = 4;
					$save = $user->save();
					$db->commit();
					if (!$save) {
						$error = 'user_addition_failed';
						$chapter->destroy();
					}
				}
			}
			catch (HeliumException $e) {
				$db->rollback();
				$db->autocommit(true);

				$error = 'user_addition_failed';
			}

			if (!$error) {
				// Everything went well
				if (!$_POST['create_again'])
					$this->http_redirect(array('controller' => 'chapter', 'action' => 'index'));
				else {
					$this['success'] = true;
				}
			}

			// Something wrong happened
			else {
				$this['error'] = $error;

				// Restore form values
				unset($_POST['user']['password'], $_POST['user']['confirm_password']);
				$form->feed($_POST);
			}
		}

		$this['timezones'] = array('Asia/Jakarta' => 'WIB', 'Asia/Ujung_Pandang' => 'WITA', 'Asia/Jayapura' => 'WIT');
	}
}