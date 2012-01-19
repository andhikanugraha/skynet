<?php $this->header('Masuk'); $this->fx('staggered_load'); ?>
<?php

$destination_titles = array(
	'selection::results' => 'Pengumuman Hasil Seleksi',
	'selection' => 'Informasi Seleksi',
	'admin::stats' => 'Registration Statistics',
	'admin' => 'Switchboard',
	'applicant::confirm' => 'Switchboard'
);
$destination_title = $destination_titles[$destination_name];
if (!$destination_title)
	$destination_title = $destination_titles[$destination_controller];

?>

<div class="container">
	<header class="stage-title">
		<h1>Masuk</h1>
		<?php if ($destination_title): ?>
		<p>menuju <strong><?php echo $destination_title ?></strong></p>
		<?php endif; ?>
	</header>
	<?php if ($mode == 'fail'): ?>
	<div class="message error">
		<p>Perpaduan nama pengguna dan sandi yang Anda masukkan tidak cocok.</p>
	</div>
	<script>$(document).ready(function(){
		$('input').click(function() { $('.message').slideUp() });
	})</script>
	<?php endif; ?>
	
	<form action="<?php L(array('controller' => 'auth', 'action' => 'login')) ?>" method="POST" class="auth-login-form">
		<p>
			<label for="username">Nama pengguna</label>
			<input type="text" class="medium" name="username" id="username" value="<?php echo $this->session->flash('username'); ?>" autofocus required>
		</p>
		<p>
			<label for="password">Sandilewat</label>
			<input type="password" class="medium" name="password" id="password" required>
		</p>
		<p>
			<input type="checkbox" name="remember" id="remember">
			<label for="remember">Ingat saya</label>
		</p>
		<p>
			<button type="submit">Masuk</button>
		</p>
		<p>
			<a href="<?php L(array('controller' => 'user', 'action' => 'forgot')) ?>">Saya lupa sandilewat saya</a>
		</p>
		<p>
			<a href="<?php L(array('controller' => 'applicant', 'action' => 'create')) ?>">Saya belum punya akun</a>
		</p>
	</form>
</div>
<?php $this->footer(); ?>