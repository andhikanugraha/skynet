<?php

/**
 * RegistrationCodeController
 *
 * @author Andhika Nugraha <andhika.nugraha@gmail.com>
 * @package chapter
 */
class RegistrationCodeController extends AppController {
	public function index() {
		$this->require_role('chapter_admin');

		$db = Helium::db();
		$query = "SELECT chapter_id, chapter_code, chapter_name, chapter_timezone, expires_on FROM registration_codes LEFT JOIN chapters ON registration_codes.chapter_id = chapters.id";

		if (!$this->session->user->capable_of('admin'))
			$query .= $db->prepare(" WHERE chapter_id='%s'", $this->session->user->chapter_id);

		$query .= " GROUP BY expires_on, chapter_id ORDER BY chapter_id ASC, expires_on DESC";
		$batches = $db->get_results($query);
		if (!$batches)
			$batches = array();

		array_walk($batches, function (&$el) {
			$wib = new HeliumDateTime($el->expires_on);
			$el->expires_on = clone $wib;
			$el->expires_on->setTimezone($el->chapter_timezone);
			$el->view_link = PathsComponent::build_url(array('controller' => 'registration_code', 'action' => 'view', 'chapter_id' => $el->chapter_id, 'expires_on' => (string) $wib));
		});
		
		if ($this->user->capable_of('national_admin'))
			$chapter = false;
		else
			$chapter = $this->user->chapter;
		
		$this['chapter'] = $chapter;
		
		$this['batches'] = $batches;
	}
	
	public function view() {
		$this->require_role('chadmin');
		$chapter_id = $this->params['chapter_id'];
		$expires_on = $this->params['expires_on'];
		
		$user = $this->session->user;
		
		if (!$chapter_id || !$expires_on) {
			$error = 'incomplete_request';
		}
		
		if (!$error && !$user->capable_of('admin') && $chapter_id != $this->session->user->chapter_id) {
			$error = 'access_forbidden';
		}
		
		if (!$error) {
			$chapter = Chapter::find($chapter_id);
			if (!$chapter)
				$error = 'not_found';
		}

		if (!$error) {
			$codes = RegistrationCode::find();
			$codes->narrow(array('chapter_id' => $chapter_id, 'expires_on' => $expires_on));
			if (!$codes->count_all())
				$error = 'no_codes_found';
		}
		
		if (!$error) {
			$this['codes'] = $codes;
			$this['chapter_name'] = $chapter->chapter_name;
			$exp = new HeliumDateTime($expires_on);
			$exp->setTimezone($chapter->chapter_timezone);
			$this['expires_on'] = $exp;
			$this['timezone'] = __($chapter->chapter_timezone);
		}
		else {
			$this['error'] = $error;
		}
	}

	public function issue() {
		$this->require_role('chadmin');

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			if ($this->session->user->capable_of('admin'))
				$chapter_id = (int) $_POST['chapter_id'];
			else
				$chapter_id = $this->session->user->chapter_id;

			$db = Helium::db();

			$q = $db->prepare("SELECT chapter_timezone FROM chapters WHERE id='%d'", $chapter_id);
			$timezone = $db->get_var($q);

			$q = $db->prepare("SELECT chapter_timezone FROM chapters WHERE id='%d'", $chapter_id);
			$timezone = $db->get_var($q);

			if (!$timezone)
				$error = 'not_found';

			if (!$error) {
				$e = $_POST['expires_on'];
				$datestring = "{$e[year]}-{$e[month]}-{$e[day]} 23:59:59";
				$expires_on = new HeliumDateTime($datestring, $timezone);

				$expires_on->setTimezone(Helium::conf('site_timezone'));

				$number_of_codes = (int) $_POST['number_of_codes'];

				$codes = array();
				for ($i = 1; $i <= $number_of_codes; $i++) {
					$codes[] = RegistrationCode::generate_token();
				}

				$sql = 'INSERT INTO registration_codes (token, chapter_id, expires_on, availability) VALUES ';
				$expires_on = (string) $expires_on;
				foreach ($codes as $i => $code) {
					$sql .= " ('$code', '$chapter_id', '$expires_on', 1)";
					if ($i < (count($codes) - 1))
						$sql .= ',';
				}

				// it should be fairly safe to assume that there are no conflicts
				$db->query($sql);
			}

			if (!$error) {
				$action = 'view';
				$controller = 'registration_code';
				$expires_on = (string) $expires_on;
				$this->http_redirect(compact('controller', 'action', 'chapter_id', 'expires_on'));
			}
		}

		$exp = new HeliumDateTime('now');
		$exp->modify('+7 days');
		$expires_on = array('year' => $exp->format('Y'), 'month' => $exp->format('m'), 'day' => $exp->format('d'));

		$form = new FormDisplay;
		$form->feed(compact('expires_on'));

		$this['form'] = $form;

		$this['timezone'] = __($this->session->user->chapter->chapter_timezone);
		if ($this->session->user->capable_of('admin')) {
			$this['can_choose_chapter'] = true;
			$chapters = Chapter::find();
			$tch = array();
			foreach ($chapters as $c) {
				if (!$c->is_national_office()) {
					$id = $c->id;
					$ch[$id] = $c->chapter_name;
				}
			}

			$this['chapters'] = $ch;
		}
	}
}