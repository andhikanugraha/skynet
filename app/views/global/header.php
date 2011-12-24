<?php

$the_title = 'Pendaftaran Seleksi Bina Antarbudaya';
if ($page_title)
	$the_title .= ': ' . $page_title;

$controller = $this->params['controller'];

$controller_css = $controller . '/style';
$action_css = $controller . '/' . $this->_action();

?>
<!DOCTYPE html>

<html lang="id">

	<head>
		<meta charset="utf-8">
		<title><?php echo $the_title; ?></title>
		<link rel="icon" href="http://binabudbdg.org/icon.png" type="image/png">
		<link rel="stylesheet" href="<?php L('/assets/css/global/style.css'); ?>">
		<link rel="stylesheet" href="<?php L('/assets/css/' . $controller_css . '.css'); ?>">
		<link rel="stylesheet" href="<?php L('/assets/css/' . $action_css . '.css'); ?>">
		<?php if ($css = $this['additional_css']): foreach ($css as $s): ?>
		<link rel="stylesheet" href="<?php L('/assets/' . $s . '.css'); ?>">
		<?php endforeach; endif; ?>

	</head>

	<body>
		<nav class="global-nav">
			<header class="masthead"><a href="<?php L(); ?>"><img src="<?php L('/assets/css/global/masthead.png'); ?>" alt="Bina Antarbudaya"></a></header>
			<?php if ($this->is_logged_in()): ?>
			<p class="user-controls">
				<span class="username"><?php echo $this->session->user->username; ?></span>
				<?php if ($this->session->user->role == ('applicant')): $applicant = Applicant::find_by_user($this->session->user); if (!$applicant->submitted): ?>
				<span class="expires_on">Tenggat Waktu: <b><?php echo $applicant->expires_on->format('d/m/Y'); ?></b></span>
				<a href="<?php L(array('controller' => 'applicant', 'action' => 'guide')); ?>">Panduan</a>
				<?php endif; endif; ?>
				<a href="<?php L(array('controller' => 'user', 'action' => 'prefs')); ?>">Pengaturan</a>
				<a href="<?php L(array('controller' => 'auth', 'action' => 'logout')); ?>">Keluar</a>
			</p>
			<?php elseif ($controller != 'auth'): ?>
			<p class="user-controls">
				<strong class="activate-link"><a href="<?php L(array('controller' => 'applicant', 'action' => 'redeem')) ?>">Aktivasi PIN pendaftaran</a></strong>
				Sudah memiliki akun? &nbsp;<a href="<?php L(array('controller' => 'auth', 'action' => 'login')); ?>">Masuk &raquo;</a>
			</p>
			<?php endif; ?>
		</nav>
