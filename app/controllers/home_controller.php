<?php

class HomeController extends GatotkacaController {

	public function index() {
		echo '<pre>';
		
		$db = Helium::db();
		
		$tables = $db->get_col("SHOW TABLES FROM skynet LIKE 'applicant_%'");
		foreach ($tables as $tab)
			echo '$this->add_vertical_partition(\'' . $tab .'\');' . "\n";
		print_r($tables);
		
		
		/* test partitions */
		$app = new Applicant;
		print_r($app->_columns());
		
		for ($g = 1; $g <= 10; $g++) {
		
		echo "`grades_y{$g}t1_rank` VARCHAR(6) NULL , \n";
		echo "`grades_y{$g}t1_total` VARCHAR(6) NULL , \n";
		echo "`grades_y{$g}t2_rank` VARCHAR(6) NULL , \n";
		echo "`grades_y{$g}t2_total` VARCHAR(6) NULL , \n";
		
		}
		
		exit;
		// if ($this->is_logged_in())
		// 	$this->auth->land();
		// else
		// 	$this->auth->redirect_to_login_page();
	}

}