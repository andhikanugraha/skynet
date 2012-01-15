<?php

class UserController extends AppController {
	public $role_names = array(1 => 'Peserta', 3 => 'Staf Chapter', 4 => 'Admin Chapter', 5 => 'Admin Nasional');
	

	public function init() {
		$this['role_names'] = $this->role_names;
	}

	private function fill_vars() {
		$roles = $this->role_names;
		foreach ($roles as $k => $v) {
			if ($v > $this->user->role)
				unset($roles[$k]);
		}
		unset($roles[1]);
		$this['roles'] = $roles;
		
		$chapters = Chapter::find('all');
		$chapters_a = array();
		foreach ($chapters as $c) {
			$chapters_a[$c->id] = $c->chapter_name;
		}
		$this['chapters'] = $chapters_a;

		$this['national'] = $this->user->capable_of('national_admin');
	}

	/**
	 * User preferences (from user)
	 */
	public function prefs() {
		$this->require_authentication();

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$validate = array();

			$old_password = $_POST['old_password'];
			$password = $_POST['password'];
			$retype_password = $_POST['retype_password'];

			if (!$error) {
				$old_password_hash = User::hash_password($old_password);
				if ($old_password_hash != $this->session->user->password_hash)
					$error = 'previous_password_incorrect';
			}

			if (!$error && $password != $retype_password)
				$error = 'password_mismatch';

			if (!$error && strlen($password) < 8)
				$error = 'password_too_short';

			if (!$error) {
				$user = $this->session->user;
				$user->set_password($password);
				$password_hash = $user->hash_password($password);
				$this->session->user_password_hash = $password_hash;
				$user->save();
				$this->session['success'] = true;
				$this->http_redirect($this->params);
			}
			else {
				$this['error'] = $error;
			}
		}

		$this['success'] = $this->session->flash('success');
		$this['form'] = new FormDisplay;
	}

	/**
	 * List all users (from admin)
	 */
	public function index() {
		$this->require_role('chapter_admin');
		
		if ($this->user->capable_of('national_admin')) {
			$chapter_id = $this->params['chapter_id'];
			if ($chapter_id && $chapter_id != 1)
				$users = User::find(compact('chapter_id'));
			else
				$users = User::find('all');
		}
		else {
			$chapter_id = $this->user->chapter_id;
			$users = User::find(compact('chapter_id'));
		}

		if ($this->params['volunteers_only'])
			$users->narrow('(role != 1)');
		
		$users->set_order_by('role');
		$users->set_order('DESC');
		
		if (is_numeric($chapter_id)) {
			$chapter = Chapter::find($chapter_id);
			$this['chapter'] = $chapter;
		}
		$this['users'] = $users;
		$this['message'] = $this->session->flash('message');
	}

	/**
	 * User creation (from admin)
	 */
	public function create() {
		$this->require_role('chapter_admin');

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
			if (!$error && $password != $retype_password)
				$error = 'retype_password';
			
			if (!$error && strlen($password) < 8)
				$error = 'password';
			
			$role = $_POST['role'];
			if (!$error && ($role < 2 || $role > $this->user->role))
				$error = 'role';

			// validate email
			if (!$error && !filter_var($email, FILTER_VALIDATE_EMAIL))
				$error = 'email';

			if (!$error && $this->user->capable_of('national_admin')) {
				// Chapter validation
				$chapter_id = $_POST['chapter_id'];
				$chapter = Chapter::find($chapter_id);
				if (!$chapter)
					$error = 'chapter_not_found';
			}

			if (!$error) {
				// everything set to go
				$user = new User;
				$user->username = $_POST['username'];
				$user->chapter_id = $chapter_id ? $chapter_id : $this->user->chapter_id;
				$user->set_password($password);
				$user->email = $email;
				$user->role = $role;
				$user->save();

				$this->session['message'] = 'user_created';
				
				switch ($_POST['afterwards']) {
					case 0: // return to index
						$this->http_redirect(array('controller' => 'user', 'action' => 'index'));
						break;
					case 1: // create new user
						$this['message'] = $this->session->flash['message'];
						break;
					case 2: // login with new user
						$this->auth->process_login($username, $password);
						$this->auth->land();
						break;
				}
			}
			else {
				$this->session['username'] = $username;
				$this->session['email'] = $email;
				$this['error'] = $error;
			}
		}
		
		// Regardless of error
		if (!$this->user->capable_of('national_admin'))
			$this['chapter'] = $this->user->chapter;
			
		$this['form'] = $form = new FormDisplay;

		$roles = $this->role_names;
		foreach ($roles as $k => $v) {
			if ($v > $this->user->role)
				unset($roles[$k]);
		}
		unset($roles[1]);
		$this['roles'] = $roles;

		$chapters = Chapter::find('all');
		$chapters_a = array();
		foreach ($chapters as $c) {
			$chapters_a[$c->id] = $c->chapter_name;
		}
		$this['chapters'] = $chapters_a;

		$this['national'] = $this->user->capable_of('national_admin');
	}

	/**
	 * User editing (from admin)
	 */
	public function edit() {
		$this->require_role('chapter_admin');
		
		$id = $this->params['id'];
		$user = User::find($this->params['id']);
		if (!$id || !$user)
			$error = 'not_found';
		
		if (!$error && (!$this->user->capable_of('national_admin') && $user->chapter_id != $this->user->chapter_id))
			$error = 'forbidden';
		
		if (!$error && $_SERVER['REQUEST_METHOD'] == 'POST') {
			// submitting a form here
			$username = trim($_POST['username']);
			$password = $_POST['password'];
			$retype_password = $_POST['retype_password'];
			$email = trim($_POST['email']);
			
			$proc = new FormProcessor;
			$proc->associate($user);

			// validate username, password and email.
			$validate = array();

			if (!isset($username, $email))
				$error = 'incomplete';

			if (!$error && strtolower($username) != strtolower($user->username)) {
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

			$role = $_POST['role'];
			if (!$error && ($role > $this->user->role))
				$error = 'role';

			// validate email
			if (!$error && !filter_var($email, FILTER_VALIDATE_EMAIL))
				$error = 'email';

			if (!$error && $password) {
				// validate retype password
				if (!$error && $password != $retype_password)
					$error = 'retype_password';

				if (!$error && strlen($password) < 8)
					$error = 'password';
			}
			else {
				$proc->add_uneditables('password_hash');
			}

			if (!$error && $this->user->capable_of('national_admin')) {
				// Chapter validation
				$chapter_id = $_POST['chapter_id'];
				$chapter = Chapter::find($chapter_id);
				if (!$chapter)
					$error = 'chapter_not_found';
			}
			else
				$proc->add_uneditables('chapter_id');

			if (!$error) {
				$proc->commit();
				
				if ($password)
					$user->set_password($password);

				$user->save();

				$this->session['message'] = 'user_edited';
				$this->session['user_edited_id'] = $id;

				if (!$_POST['return'])
					$this->http_redirect(array('controller' => 'user', 'action' => 'index'));
				else
					$message = $this->session->flash['message'];
			}
			else {
				$this['error'] = $error;
			}
		}

		if ($user) {
			$form = new FormDisplay;
			$form->associate($user);
			$this['form'] = $form;
			$this['user'] = $user;

			$roles = $this->role_names;
			foreach ($roles as $k => $v) {
				if ($v > $this->user->role)
					unset($roles[$k]);
			}
			$this['roles'] = $roles;

			$chapters = Chapter::find('all');
			$chapters_a = array();
			foreach ($chapters as $c) {
				$chapters_a[$c->id] = $c->chapter_name;
			}
			$this['chapters'] = $chapters_a;

			$this['national'] = $this->user->capable_of('national_admin');
			
			$this['show_form'] = true;
		}
		else {
			$this['error'] = $error;
		}
	}
}