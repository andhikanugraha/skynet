<?php

abstract class GatotkacaController extends HeliumController {
	public $components = array('sessions', 'auth', 'links', 'locale');

	protected function header($title = '') {
		$this['page_title'] = $title;
		$this->render('global/header');
	}
	
	protected function footer() {
		$this->render('global/footer');
	}
}