<?php

// Routes file
//
// Routes are in the form of
// $route($path[, $params, $formats]);

$route('/panduan', array('controller' => 'applicant', 'action' => 'guide'));
$route('/daftar', array('controller' => 'applicant', 'action' => 'redeem'));
$route('/daftar/akun', array('controller' => 'applicant', 'action' => 'create'));
$route('/daftar/berkas', array('controller' => 'applicant', 'action' => 'finalized'));
$route('/daftar/kartu-peserta.pdf', array('controller' => 'applicant', 'action' => 'card'));
$route('/daftar/transkrip', array('controller' => 'applicant', 'action' => 'transcript'));
$route('/admin/applicant/%id%', array('controller' => 'applicant', 'action' => 'form'));
$route('/daftar/formulir', array('controller' => 'applicant', 'action' => 'form'));

$route('/ic', array('controller' => 'admin', 'action' => 'issue_registration_code'));
$route('/ac/%id%', array('controller' => 'applicant', 'action' => 'confirm'));
$route('/ac', array('controller' => 'applicant', 'action' => 'confirm'));

$route('/applicant/confirm', array('controller' => 'applicant', 'action' => 'confirm'));

// $route('/seleksi/hasil', array('controller' => 'selection', 'action' => 'results'));
$route('/hasil', array('controller' => 'selection', 'action' => 'results'));
$route('/seleksi/satu', array('controller' => 'selection', 'action' => 'index'));
$route('/seleksi/%action%', array('controller' => 'selection'));

$route('/%controller%/%action%/%id%');
$route('/%controller%/%action%');
$route('/%controller%');
$route('/', array('controller' => 'home'));