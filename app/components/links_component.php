<?php

class LinksComponent extends HeliumComponent {
	
	public function init() {
		function L($path = '') {
			echo Gatotkaca::build_url($path);
		}
	}
	
}