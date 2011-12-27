<?php $this->header('Aktivasi PIN Pendaftaran'); $this->fx('staggered_load'); ?>

<header class="page-title">
	<p>Tahap 1 dari 5</p>
	<h1>Pengaktifan PIN Pendaftaran</h1>
</header>
<nav class="actions-nav">
	<ul>
		<li><a href="<?php L(array('controller' => 'home', 'action' => 'index')) ?>">Kembali ke Beranda</a></li>
		<li><a href="<?php L(array('action' => 'guide')) ?>">Panduan Pendaftaran</a></li>
	</ul>
</nav>
<div class="container">
	<?php
	if ($error):
	$error_messages = array('token_nonexistent' => 'PIN pendaftaran yang Adik masukkan salah.',
							'token_unavailable' => 'PIN pendaftaran yang Adik masukkan sudah terpakai.',
							'token_expired' => 'PIN pendaftaran yang Adik masukkan telah habis masa pakainya.',
							'incomplete' => 'Adik belum memasukkan PIN pendaftaran.',
							'recaptcha' => 'Isian reCAPTCHA tidak cocok.')

	?>
	<div class="message error">
		<header>Pengaktifan PIN pendaftaran gagal</header>
		<p><?php echo $error_messages[$error] ?></p>
	</div>
	<?php endif; ?>

	<section class="token-entry">
		<form action="<?php L($this->params) ?>" method="POST" class="user-create-form">
			<?php if ($enable_recaptcha): ?>
			<p>
				<script>var RecaptchaOptions = { theme : 'clean' };</script>
				<?php echo $recaptcha->get_html(); ?>
			</p>
			<?php endif; ?>
			<p>
				<label for="token">Masukkan enam belas huruf PIN pendaftaran Adik</label>
				<span class="token-box">
					<input type="text" name="token" id="token" width="16" maxlength="16" autofocus required>
					<button type="submit">Lanjut</button>
				</span>
			</p>
		</form>
	</section>
	<section class="faqs">
		<header>
			<h1>FAQs</h1>
		</header>
		<dl>
			<dt>Apa itu PIN pendaftaran?</dt>
			<dd>PIN pendaftaran adalah sebuah kode yang terdiri dari enam belas huruf (tanpa angka, tanpa spasi) yang digunakan untuk mendaftar untuk seleksi pertukaran pelajar Bina Antarbudaya.</dd>
			<dt>Dari mana saya bisa mendapatkan PIN pendaftaran?</dt>
			<dd>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</dd>
			<dt>Saya ingin mendaftar untuk pertukaran pelajar Bina Antarbudaya, tapi rumah saya jauh dari seluruh chapter Bina Antarbudaya. Apa yang harus saya lakukan?</dt>
			<dd>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</dd>
		</dl>
	</section>
	<section class="chapters">
		<header>
			<h1>Kantor-Kantor Bina Antarbudaya</h1>
		</header>
		<dl>
			<?php foreach ($chapters as $c): ?>
			<dt><?php if (!$c->is_national_office()) { echo 'Chapter '; } echo $c->chapter_name ?></dt>
			<dd>
				<ul>
					<li><?php echo nl2br($c->chapter_address) ?></li>
					<li><?php echo $c->chapter_email ?></li>
					<?php if ($u = $c->site_url) { ?><li><a href="<?php echo $u ?>"><?php echo $u ?></a></li><?php } ?>
				</ul>
			</dd>
			<?php endforeach; ?>
		</dl>
	</section>
</div>
<script>
	$('.token-box input').focus(function(){$(this).parent().addClass('focus')})
	$('.token-box input').blur(function(){$(this).parent().removeClass('focus')})
</script>
<?php $this->footer(); ?>