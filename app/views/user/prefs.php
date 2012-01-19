<?php $this->header('Pengaturan'); ?>
<!-- <header class="page-title">
	<h1>Pengaturan</h1>
</header> -->
<div class="container">
	<?php if ($error): ?>
	<div class="message error">
		<header>Pengubahan sandilewat gagal</header>
		<p><?php
		
		switch ($error) {
			case 'old_password_incorrect':
				echo 'Sandilewat lama yang Anda masukkan salah.';
				break;
			case 'password_mismatch':
				echo 'Sandilewat tidak cocok.';
				break;
			case 'password_too_short':
				echo 'Sandilewat yang Anda pilih terlalu pendek.';
				break;
		}
		
		?></p>
	</div>
	<?php elseif ($success): ?>
	<div class="message">
		<header>Pengubahan sandilewat berhasil</header>
		<p>Gunakan sandilewat yang baru untuk memasuki situs ini.</p>
	</div>
	<?php endif; ?>

	<form action="<?php L(array('controller' => 'user', 'action' => 'prefs')) ?>" method="POST">
		<h1>Ubah sandilewat</h1>
		<p>
			<label for="old_password">Sandilewat lama</label>
			<?php $form->password('old_password', 'medium', null, true) ?>
		</p>
		<p>
			<label for="password">Sandilewat baru</label>
			<?php $form->password('password', 'medium', null, true) ?>
			<br>
			<span class="instruction">Sandi terdiri atas paling sedikit delapan karakter.</span>
		</p>
		<p>
			<label for="retype_password">Ulangi sandilewat baru</label>
			<?php $form->password('retype_password', 'medium', null, true) ?>
		</p>
		<p>
			<button type="submit">Simpan</button>
		</p>
	</form>
</div>
<?php $this->footer(); ?>