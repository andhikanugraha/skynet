<?php

class LinksComponent extends HeliumComponent {
	
	public function init() {
		function L($path = '') {
			if (is_array($path)) {
				$router = Helium::router();
				if (!$path['controller'])
					$path['controller'] = $router->controller;
			}
			echo PathsComponent::build_url($path);
		}
	}
	
}