<?php

class HomeController extends GatotkacaController {

	public function index() {
		header('Content-type: text/plain');
		
		$db = Helium::db();
		
		$tables = $db->get_col('SHOW TABLES FROM skynet');

		foreach ($tables as $tab) {
			if (strpos($tab, 'applicant') !== false)
				continue;

			$class = Inflector::classify($tab);
			$text = <<<EOF
<?php

/**
 * $class
 *
 * @author Andhika Nugraha <andhika.nugraha@gmail.com>
 * @package applicant
 */
class $class extends HeliumRecord {

EOF;

			$cols = $db->get_col('SHOW COLUMNS IN ' . $tab);
			foreach ($cols as $col) {
				$text .= "	public \$$col;\n";
			}

		$text .= <<<EOF

	// public init() {
	// 	\$this->belongs_to('applicant');
	// }
}


EOF;
			$file = HELIUM_APP_PATH . '/models/' . Inflector::singularize($tab) . '.php';
			// 
			if (!file_exists($file)) {
				file_put_contents($file, $text);
				echo "$file:\n$text\n";
			}
		}
		
		exit;
		// if ($this->is_logged_in())
		// 	$this->auth->land();
		// else
		// 	$this->auth->redirect_to_login_page();
	}

}