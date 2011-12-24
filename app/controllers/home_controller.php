<?php

class HomeController extends GatotkacaController {

	public function firstrun_check() {
		$check = User::find()->count_all();
		if (!$check) {
			// First Run!
			$user = new User;
			$user->username = 'admin';
			$user->set_password('admin');
			$user->role = 5;
			$user->save();
		}
	}

	public function index() {
	}

}