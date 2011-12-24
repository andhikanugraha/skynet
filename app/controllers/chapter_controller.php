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
	}

	public function create() {
		$this->require_role('admin');
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$proc = new FormProcessor;
		}
		
		$this['form'] = $form = new FormDisplay;
	}
}