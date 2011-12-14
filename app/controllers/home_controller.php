<?php

class HomeController extends GatotkacaController {

	public function index() {
		echo '<pre>';
		print_r($this->session->user->applicant);
		print_r($this->session->user->applicant->_columns());
		$this->session->user->applicant->something = $this->params['w'];
		$this->session->user->applicant->save();
		$this->session->user->applicant->destroy();
		exit;
		// if ($this->is_logged_in())
		// 	$this->auth->land();
		// else
		// 	$this->auth->redirect_to_login_page();
	}

}