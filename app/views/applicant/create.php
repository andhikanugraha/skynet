<?php

$error_messages = array('username_availability' => 'Nama pengguna yang Adik pilih tidak tersedia.',
						'username_format' => 'Pada nama pengguna yang Adik pilih terdapat karakter selain huruf, angka, dan garis bawah, atau panjangnya kurang dari tiga karakter.',
						'password' => 'Panjang sandi yang Adik pilih kurang dari delapan karakter.',
						'retype_password' => 'Kedua isian sandi Adik tidak saling cocok.',
						'email' => 'Alamat surel yang Adik masukkan tidak sah.',
						'incomplete' => 'Isian pada formulir tidak lengkap.',
						'recaptcha' => 'Isian reCAPTCHA tidak cocok.')

?>
<?php $this->header('Buat Akun'); ?>


	<header class="stage-title">
		<h1>Tahap 2/5</h1>
		<h2>Buat Akun</h2>
	</header>
<div class="user-create-wrapper">	
	<?php if ($errors): ?>
	<section class="errors">
		<p><b>Pembuatan akun Adik gagal karena:</b></p>
		<ul>
			<?php foreach ($errors as $error): ?>
			<li><?php echo $error_messages[$error]; ?></li>
			<?php endforeach; ?>
		</ul>
		<p>Isilah kembali formulir di bawah ini dengan memerhatikan galat-galat di atas.</p>
	</section>
	<?php endif; ?>

		<p class="hello"><strong>PIN pendaftaran Adik berhasil dimasukkan.</strong> Untuk melanjutkan proses pendaftaran, Adik perlu membuat akun. Akun ini digunakan untuk mengisi formulir pendaftaran dan mengubah isian formulir tersebut sebelum finalisasi.</p>

	<form action="<?php L(array('controller' => 'applicant', 'action' => 'create')) ?>" method="POST" class="user-create-form">
		<p>
			<label for="username">Nama pengguna</label>
			<input type="text" name="username" id="username" value="<?php echo $this->sessions->flash('username'); ?>" autofocus required>
			<span class="description">Nama pengguna hanya boleh terdiri dari huruf, angka, dan garis bawah. <strong>Tidak boleh menggunakan spasi.</strong></span>
		</p>
		<p class="pw">
			<label for="password">Sandi</label>
			<input type="password" name="password" id="password" required>
			<span class="description">Sandi terdiri atas paling sedikit delapan karakter.</span>
		</p>
		<p class="pw">
			<label for="retype_password">Ulangi sandi</label>
			<input type="password" name="retype_password" id="retype_password" required>
		</p>
		<p>
			<label for="email">Alamat surel (e-mail)</label>
			<input type="email" name="email" id="email" value="<?php echo $this->sessions->flash('email'); ?>" required>
		</p>
		<p>
			<button type="submit">Buat</button>
		</p>
	</form>
</div>
<?php $this->footer(); ?>