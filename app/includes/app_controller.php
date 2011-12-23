<?php

abstract class AppController extends HeliumController {
	public $components = array('cookies', 'sessions', 'auth', 'links', 'locale', 'paths');

	protected function header($title = '') {
		$this['page_title'] = $title;
		$this->render('global/header');

		try {
			$controller_name = $this->params['controller'];
			$this->render($controller_name . '/header');
		}
		catch (HeliumException $e) {
			// do nothing
		}
	}

	protected function footer() {
		$this->render('global/footer');
		
		try {
			$controller_name = $this->params['controller'];
			$this->render($controller_name . '/footer');
		}
		catch (HeliumException $e) {
			// do nothing
		}

	}
}