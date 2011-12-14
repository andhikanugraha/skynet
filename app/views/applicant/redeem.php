<?php

$error_messages = array('token_nonexistent' => 'PIN pendaftaran yang Anda masukkan salah.',
						'token_unavailable' => 'PIN pendaftaran yang Anda masukkan sudah terpakai.',
						'token_expired' => 'PIN pendaftaran yang Anda masukkan telah habis masa pakainya.',
						'incomplete' => 'Anda belum memasukkan PIN pendaftaran.',
						'recaptcha' => 'Isian reCAPTCHA tidak cocok.')

?>
<?php $this->header('Aktivasi PIN Pendaftaran'); ?>

	<header class="stage-title">
		<h1>Tahap 1/5</h1>
		<h2>Aktivasi PIN Pendaftaran</h2>
	</header>
<div class="user-create-wrapper">
	<?php if ($errors): ?>
	<section class="errors">
		<p><b>Aktivasi PIN pendaftaran Anda gagal karena:</b></p>
		<ul>
			<?php foreach ($errors as $error): ?>
			<li><?php echo $error_messages[$error]; ?></li>
			<?php endforeach; ?>
		</ul>
		<p>Isilah kembali formulir di bawah ini dengan memerhatikan galat-galat di atas.</p>
	</section>
	<?php endif; ?>

	<form action="<?php L($this->params) ?>" method="POST" class="user-create-form">
		<p>
			<label for="token">Masukkan delapan huruf PIN pendaftaran Anda</label>
			<input type="text" name="token" id="token" width="10" maxlength="10" autofocus required>
		</p>
		<?php if ($enable_recaptcha): ?>
		<p>
			<script>var RecaptchaOptions = { theme : 'white' };</script>
			<?php echo $recaptcha->get_html(); ?>
		</p>
		<?php endif; ?>
		<p>
			<button type="submit">Lanjut</button>
		</p>
	</form>
	<p class="hello"><a href="http://binabudbdg.org/pendaftaran/">Belum punya PIN?</a></p>
</div>
<?php $this->footer(); ?>