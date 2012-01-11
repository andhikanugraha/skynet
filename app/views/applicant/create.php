<?php

$error_messages = array('username_availability' => 'Nama pengguna yang Adik pilih tidak tersedia.',
						'username_format' => 'Pada nama pengguna yang Adik pilih terdapat karakter selain huruf, angka, dan garis bawah, atau panjangnya kurang dari tiga karakter.',
						'password' => 'Panjang sandi yang Adik pilih kurang dari delapan karakter.',
						'retype_password' => 'Kedua isian sandi Adik tidak saling cocok.',
						'email' => 'Alamat surel yang Adik masukkan tidak sah.',
						'incomplete' => 'Isian pada formulir tidak lengkap.',
						'recaptcha' => 'Isian reCAPTCHA tidak cocok.',
						'db_fail' => 'Cobalah sesaat lagi.')

?>
<?php $this->header('Buat Akun'); $this->fx('fade_all_load'); ?>


<header class="page-title">
	<p>Tahap 2 dari 4</p>
	<h1>Pembuatan Akun</h1>
</header>
<nav class="actions-nav">
	<ul>
		<li><a href="<?php L(array('action' => 'redeem')) ?>">Aktifkan PIN lainnya</a></li>
		<li><a href="<?php L(array('controller' => 'home', 'action' => 'index')) ?>">Kembali ke Beranda</a></li>
		<li><a href="<?php L(array('action' => 'guide')) ?>">Panduan Pendaftaran</a></li>
		<li class="expires-on">Batas waktu pendaftaran: <span><?php echo $expires_on->format('l, j F Y') ?></span></li>
	</ul>
</nav>
<div class="container">	
	<?php if ($error): ?>
	<div class="message error">
		<header>
			<h1>Pembuatan akun gagal</h1>
		</header>
		<p><?php echo $error_messages[$error]; ?></p>
	</div>
	<?php endif; ?>

	<section class="intro">		
		<header>
			<h1>PIN pendaftaran berhasil dimasukkan</h1>
		</header>
		<p class="hello">Untuk melanjutkan proses pendaftaran, Adik perlu membuat akun. Akun ini digunakan untuk mengisi formulir pendaftaran dan mengubah isian formulir tersebut sebelum finalisasi.</p>
		<p>Adik akan terdaftar pada Bina Antarbudaya Chapter <strong><?php echo $chapter_name ?></strong>. Jika Adik memperoleh PIN pendaftaran selain dari Chapter <?php echo $chapter_name ?>, hubungi panitia chapter tempat Adik memperoleh PIN pendaftaran Adik.</p>
	</section>

	<section class="user-form">		
		<header>
			<h1>Informasi Akun</h1>
		</header>
		<form action="<?php L(array('controller' => 'applicant', 'action' => 'create')) ?>" method="POST" validate>
			<table class="form-table">
				<tr>
					<td class="label"><?php $form->label('username', 'Nama pengguna', 'required') ?></td>
					<td class="field"><input type="text" name="username" id="username" class="medium" value="<?php echo $this->session->flash('username'); ?>" autofocus required> <span class="instruction">Terdiri atas paling sedikit empat karakter, dan hanya boleh terdiri atas huruf, angka, garisbawah (_), tanda sambung (-). Tidak boleh mengandung spasi.</span></td>
				</tr>
				<tr>
					<td class="label"><?php $form->label('password', 'Sandilewat', 'required')?></td>
					<td class="field"><input type="password" name="password" class="medium" id="password" required> <span class="instruction">Terdiri atas paling sedikit delapan karakter.</span></td>
				</tr>
				<tr>
					<td class="label"><?php $form->label('retype_password', 'Ulang Sandilewat', 'required')?></td>
					<td class="field"><input type="password" name="retype_password" id="retype_password" class="medium" required></td>
				</tr>
				<tr>
					<td class="label">Chapter</td>
					<td class="field"><select class="medium" name="_" disabled><option><?php echo $chapter_name ?></option></select> <span class="instruction">Jika Adik membeli PIN pendaftaran selain dari Chapter <?php echo $chapter_name ?>, hubungi panitia chapter tempat Adik memperoleh PIN pendaftaran Adik.</span></td>
				</tr>
				<tr>
					<td class="label"><?php $form->label('email', 'Alamat surel (e-mail)', 'required') ?></td>
					<td class="field"><input type="email" name="email" id="email" class="medium" value="<?php echo $this->session->flash('email'); ?>" required></td>
				</tr>
				<tr>
					<td class="label"></td>
					<td class="field">
						<button type="submit">Buat Akun</button>
						&nbsp;&nbsp;&nbsp;
						<?php $form->checkbox('remember') ?> <?php $form->label('remember', 'Ingat saya') ?>
					</td>
				</tr>
			</table>
		</form>
	</section>
</div>
<?php $this->footer(); ?>