<?php $this->header('Seleksi Tahap Pertama'); ?>
<header class="stage-title">
	<h1>Pertukaran Pelajar Bina Antarbudaya INAYPSc 2012-2013</h1>
	<h2>Seleksi Tahap Pertama</h2>
</header>
<div class="container">
	<section class="notice">
		<header>Terima kasih telah mendaftar untuk program kami.</header>
		<p>Seluruh berkas Adik telah kami terima. Informasi selanjutnya mengenai seleksi tahap pertama akan diumumkan melalui halaman ini.</p>
		<?php /* ?><p>Bila Adik diminta oleh relawan kami untuk mencetak ulang Tanda Peserta Seleksi Adik saat menyerahkan berkas pendaftaran, silakan klik tombol di bawah ini.</p>
		<form action="<?php L(array('controller' => 'applicant', 'action' => 'card')) ?>" method="POST">
			<p><button type="submit" id="card">Cetak Ulang Tanda Peserta Seleksi</button></p>
		</form><?php */ ?>
		<p><a href="#" onclick="$(this.parentNode.parentNode).slideUp()">Sembunyikan pesan ini</a></p>
	</section>
	<section class="selection-info">
		<p class="infopart date">
			<span class="desc">Hari, Tanggal</span>
			<strong class="data">Minggu, 1 Mei 2011</strong>
		</p>
		<p class="infopart time">
			<span class="desc">Pukul</span>
			<strong class="data">07.00&ndash;15.00 WIB</strong>
		</p>
		<p class="infopart place">
			<span class="desc">Tempat</span>
			<strong class="data">SMA Negeri 3 &amp; 5 Bandung</strong>
		</p>
		<hr>
	</section>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<?php $this->footer(); ?>