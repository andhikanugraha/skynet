<?php

// HeliumConfiguration
// Edit this class to set global configuration variables for your application

define('PICTURE_UPLOAD_PATH', HELIUM_PARENT_PATH . '/uploads');

class HeliumConfiguration extends HeliumDefaults {

	/* Application configuration */
	public $app_name = 'skynet';	// name of application
	public $production = false;		// set to true to disable debugging
	// public $enable_reactor = false;	// true to enable Reactor
	
	public $base_uri = 'http://skynet.bina-antarbudaya.info';

	public $session_cookie_name = 'gatotkaca';
	public $session_length = '1 week';
	public $session_check_password_hash = true;
	public $session_check_user_agent = true;
	public $session_check_ip_address = false;
	
	public $applicant_prefix = "INAYPSc/13-14/";
	public $programs = array('afs', 'yes');
	
	public $program_year = 2014;
	
	public $dates = array(
		'registration_deadline' => '2011-04-24',
		'selection_1' => '2011-05-01',
		'selection_1_announcement' => '2011-05-20 07:25',
		'selection_2_rereg' => '2011-05-27 14:00',
		'selection_2_rereg_end' => '2011-05-27 17:00',
		'selection_2' => '2011-05-29 07:00',
		'selection_2_end' => '2011-05-29 16:00',
		'selection_2_announcement' => '2011-06-05 01:01',
		'selection_3_rereg' => '2011-06-12 13:00',
		'selection_3_rereg_end' => '2011-06-12 16:00',
		'selection_3' => '2011-06-29 07:00', // update plz
		'selection_3_end' => '2011-06-29 16:00'
	);

	public $site_timezone = 'Asia/Jakarta';

	public $recaptcha_public_key = '6LeuJ8ISAAAAAI8CHltLLYj6i-SuNOaYOd9hgfVc';
	public $recaptcha_private_key = '6LeuJ8ISAAAAANw4cl3_OSQUG5sDrEs1fmQPv0qh';
	
	public $picture_upload_path = PICTURE_UPLOAD_PATH;
	public $picture_public_path = 'http://skynet.bina-antarbudaya.info/uploads';

	/* MySQL Database configuration – optional if database is not being used */

	public $db_user = 'username';	// username
	public $db_pass = 'password';	// password
	public $db_name = 'skynet';	// database name – optional; defaults to $db_user
	public $db_host = 'localhost';	// database server – optional; defaults to localhost

}