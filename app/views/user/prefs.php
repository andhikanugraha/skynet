<?php
$error_messages = array('wrong_old_password' => 'Sandi lama yang Anda masukkan salah.',
						'password' => 'Panjang sandi yang Anda pilih kurang dari delapan karakter.',
						'retype_password' => 'Kedua isian sandi Anda tidak saling cocok.',
						'incomplete' => 'Isian pada formulir tidak lengkap.',
						'recaptcha' => 'Isian reCAPTCHA tidak cocok.')
?>
<?php $this->header('Pengaturan'); ?>
<header class="stage-title">
	<h2>Pengaturan</h2>
</header>
<div class="user-prefs-wrapper">
	<?php if ($errors): ?>
	<section class="errors">
		<p><b>Pengubahan sandi akun Anda gagal karena:</b></p>
		<ul>
			<?php foreach ($errors as $error): ?>
			<li><?php echo $error_messages[$error]; ?></li>
			<?php endforeach; ?>
		</ul>
		<p>Isilah kembali formulir di bawah ini dengan memerhatikan galat-galat di atas.</p>
	</section>
	<?php elseif ($success): ?>
	<section class="success">
		<p>Pengubahan sandi akun Anda berhasil.</p>
	</section>
	<?php endif; ?>
	<form action="<?php L(array('controller' => 'user', 'action' => 'prefs')) ?>" method="POST">
		<h1>Ubah sandi</h1>
		<p>
			<label for="old_password">Sandi lama</label>
			<input type="password" name="old_password" id="old_password" required>
		</p>
		<p>
			<label for="password">Sandi baru</label>
			<input type="password" name="password" id="password" required>
			<span class="description">Sandi terdiri atas paling sedikit delapan karakter.</span>
		</p>
		<p>
			<label for="retype_password">Ulangi sandi baru</label>
			<input type="password" name="retype_password" id="retype_password" required>
		</p>
		<p>
			<button type="submit">Ubah sandi</button>
		</p>
	</form>
</div>
<?php $this->footer(); ?>