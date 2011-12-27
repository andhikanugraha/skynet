<?php $this->header('Telkomsel - Penerbitan PIN'); ?>
<header class="stage-title">
	<h1>Telkomsel&ndash;Bina Antarbudaya</h1>
	<h2>Control Panel</h2>
</header>
<div class="container">
	<?php if ($code): ?>
	<p class="token">
		PIN pendaftaran Seleksi Pertukaran Pelajar Bina Antarbudaya (hanya berlaku untuk satu orang):
		<span><?php echo $code->token;?></span>
	</p>
	<p>PIN ini berlaku hingga hari Minggu, 17 April 2011. Sampaikan PIN ini kepada vendor Telkomsel yang memintanya untuk memberikannya kepada pendaftar.</p>
	<?php endif; ?>
	<p><strong>Seluruh PIN yang diterbitkan melalui halaman ini tercatat oleh Bina Antarbudaya dan tidak dapat ditarik kembali.</strong></p>
	<form action="<?php L($this->params); ?>" method="POST">
		<button type="submit">Buat 1 PIN yang kadaluarsa tanggal <?php echo $expires_on->format('d/m/Y'); ?></button>
	</form>
	<p class="stats">
		<?php echo $count ?> buah PIN telah diterbitkan oleh Telkomsel.
	</p>
</div>
<?php $this->footer(); ?>