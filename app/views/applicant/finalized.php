<?php $this->header('Pengumpulan Berkas'); ?>
<header class="stage-title">
	<h1>Tahap 5/5</h1>
	<h2>Pengumpulan Berkas</h2>
</header>
<div class="user-create-wrapper">
	<p class="big">Untuk menyelesaikan pendaftaran seleksi Bina Antarbudaya, Adik harus mengumpulkan berkas-berkas berikut ini:</p>
	<ol class="reqs">
		<li><strong>Transkrip Formulir Pendaftaran Seleksi</strong>, yang dicetak melalui halaman ini</li>
		<li><strong>Tanda Peserta Seleksi</strong>, yang dicetak <strong>berwarna</strong> melalui halaman ini</li>
		<?php if ($this->applicant->applicant_detail->akselerasi): ?><li><strong>Surat Pernyataan Siswa Akselerasi</strong> (khusus siswa kelas akselerasi), yang dapat diunduh di halaman <a href="<?php L(array('controller' => 'applicant', 'action' => 'guide')); ?>">panduan</a></li</li><?php endif; ?>
		<li>Surat izin orang tua, yang diunduh dari halaman <a href="<?php L(array('controller' => 'applicant', 'action' => 'guide')); ?>">panduan</a> kemudian dicetak lalu ditulis tangan</li>
		<li>Surat Rekomendasi Kepala Sekolah/Wakil Kepala Sekolah</li>
		<li>2 (dua) buah stofmap-folio berwarna <strong>biru</strong></li>
		<li>2 (dua) buah salinan ijazah (STTB) beserta salinan <strong>nilai</strong> ijazah SMP yang telah disahkan/dilegalisasi (cap asli) oleh Kepala Sekolah</li>
		<li>1 (satu) buah salinan nilai SKHUN SMP dan (bila ada) SKHU UAS SMP.</li>
		<li>1 (satu) buah salinan rapor kelas IX SMP (Semester II) yang telah disahkan/dilegalisasi (cap asli) oleh Kepala Sekolah</li>
		<li>1 (satu) buah salinan rapor kelas X SMA/SMK/MA (Semester I) yang telah disahkan/dilegalisasi (cap asli) oleh Kepala Sekolah</li>
		<li>1 (satu) buah salinan Akte Kelahiran</li>
		<li>3 buah pas foto: 2 berukuran 4x6, 1 berukuran 3x4</li>
		<li>1 (satu) buah salinan paspor (halaman pertama, yang ada fotonya), <strong>bagi yang sudah memiliki paspor.</strong></li>
	</ol>
	<?php
	$now = new HeliumDateTime;
	if ($now->later_than('2011-04-03')):
	?>
	<p class="big">Seluruh berkas dimasukkan ke dalam stofmap folio biru rangkap (poin nomor 2), kemudian dikumpulkan ke <strong>Sekretariat Bina Antarbudaya Chapter Bandung, Jl. Aria Jipang 6</strong> selambat-lambatnya tanggal <strong><?php echo $this->applicant->expires_on->format('d/m/Y'); ?></strong> pada:</p>
	<ul class="when">
		<li>Hari <strong>Jumat</strong> pukul 14.00&ndash;17.00, atau</li>
		<li>Hari <strong>Sabtu</strong> dan <strong>Minggu</strong> pukul 13.00&ndash;17.00.</li>
	</ul>
	<?php else: ?>
	<p class="big">Seluruh berkas dimasukkan ke dalam stofmap folio biru rangkap (poin nomor 2), kemudian dikumpulkan ke <strong>Sekretariat Bina Antarbudaya Chapter Bandung, Jl. Aria Jipang 6</strong> pada:</p>
	<ul class="when">
		<li>hari <strong>Jumat, 1 April 2011</strong> pukul 14.00&ndash;17.00,</li>
		<li>hari <strong>Sabtu, 2 April 2011</strong> pukul 13.00&ndash;17.00,</li>
	</ul>
	<p class="big">atau ke <strong>Open House AFS/Bina Antarbudaya Bandung</strong> pada hari <strong>Minggu, 3 April 2011</strong> di <strong>Common Room Networks Foundation, Jalan Kyai Gede Utama 8 Dago</strong>, pukul 09.00&ndash;16.00.</p>
	<?php endif; ?>
	<!-- <hr>
	<p class="afsglobal">Untuk mengunduh kartu peserta, Adik <strong>wajib</strong> mendaftar dulu di situs <strong><a href="https://www.afsglobal.org/AFSGlobal/OnlineApplication/SignUp/default.aspx?Type=Sending&amp;country=INA&amp;language=" target="_blank">AFS Global</a></strong>. Jika Adik sudah terdaftar, masukkan username dan password AFS Global Adik di bawah ini.</p> -->
	<!-- <form action="<?php L(array('controller' => 'applicant', 'action' => 'card')) ?>" method="POST"> -->
	<!-- <p>
		<label for="username">Username AFS Global</label>
		<input name="username" id="username" required type="text">
	</p>
	<p>
		<label for="password">Password AFS Global</label>
		<input name="password" id="password" required type="password">
	</p> -->
		<!-- </form> -->
	<p>
		<form action="<?php L(array('controller' => 'applicant', 'action' => 'card')) ?>" method="POST">
			<button type="submit" id="card">Cetak Tanda Peserta Seleksi</button>
		</form>
		&nbsp;&nbsp;&nbsp;
		<form action="<?php L(array('controller' => 'applicant', 'action' => 'transcript')) ?>" method="POST">
			<button type="submit" id="transcript">Cetak Transkrip Formulir</button>
		</form>
	</p>

</div>
<?php $this->footer(); ?>