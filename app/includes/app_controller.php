<?php

abstract class AppController extends HeliumController {
	public $components = array('cookies', 'sessions', 'auth', 'links', 'locale', 'paths', 'fx');

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
		$this->fx->footer();
		
		try {
			$controller_name = $this->params['controller'];
			$this->render($controller_name . '/footer');
		}
		catch (HeliumException $e) {
			// do nothing
		}

		$this->render('global/footer');
	}
	
	protected function actions_nav(array $actions = array()) {
		$this['actions'] = $actions;
		$this->render('global/actions_nav');
	}
}