<?php

class HomeController extends GatotkacaController {

	public function firstrun_check() {
		$check = User::find()->count_all();
		if (!$check) {
			// First Run!
			$chapter = new Chapter;
			$chapter->chapter_code = 'INA';
			$chapter->chapter_name = 'Kantor Nasional';
			$chapter->chapter_timezone = 'Asia/Jakarta';
			$chapter->save();

			$user = new User;
			$user->username = 'admin';
			$user->set_password('admin');
			$user->role = 5;
			$user->chapter_id = $chapter->id;
			$user->save();
		}
	}

	public function index() {
		$this->firstrun_check();
	}

}