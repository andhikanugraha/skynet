<?php $this->header('Pengumpulan Berkas'); ?>
<header class="page-title">
	<p>Tahap 4 dari 4</p>
	<h1>Pengumpulan Surat Pernyataan</h1>
</header>
<nav class="actions-nav expleft">
	<ul>
		<li><a href="<?php L(array('action' => 'details')) ?>">Lihat Formulir Pendaftaran</a></li>
		<?php if (!$applicant->is_expired()): ?>
		<li class="expires-on">Batas waktu pendaftaran: <span><?php echo $applicant->expires_on->format('l, j F Y') ?></span></li>
		<?php endif; ?>
	</ul>
</nav>
<div class="container">
	<!-- <header class="applicant-header">
		<p class="applicant-test-id"><?php echo $applicant->test_id ?></h1>
		<h1 class="applicant-name"><?php echo $applicant->sanitized_full_name ?>&nbsp;</h1>
	</header> -->
	
	<section class="what">
		<p>Untuk menyelesaikan pendaftaran seleksi Bina Antarbudaya, Adik harus mengumpulkan berkas-berkas berikut ini:</p>
		<dl class="files">
			<dt class="card">Tanda Peserta Seleksi</dt>
				<dd class="download-link"><a href="<?php L(array('controller' => 'applicant', 'action' => 'card')) ?>">Unduh</a></dd>
				<dd class="details">
					<ul>
						<li>Dicetak di kertas HVS A4 dan digunting pada garis yang ditentukan</li>
						<li>Tidak boleh dilaminating</li>
						<li>Ditandatangan dan distempel oleh panitia, kemudian dikembalikan kepada peserta</li>
						<li>Tidak berlaku jika tidak ditandatangan dan distempel oleh panitia</li>
					</ul>
				</dd>
			<dt class="parents-statement">Surat Pernyataan Orang Tua</dt>
				<dd class="download-link"><a href="<?php L(array('controller' => 'applicant', 'action' => 'file', 'file' => 'parents_statement')) ?>">Unduh</a></dd>
				<dd class="details">
					<ul>
						<li>Dicetak di kertas HVS A4</li>
						<li>Ditulis tangan serta ditanda tangan asli oleh orang tua siswa</li>
						<li>Dikumpulkan kepada panitia</li>
					</ul>
				</dd>
			<?php if ($applicant->in_acceleration_class): ?>
			<dt class="acceleration-statement">Surat Pernyataan Siswa Akselerasi</dt>
				<dd class="download-link"><a href="<?php L(array('controller' => 'applicant', 'action' => 'file', 'file' => 'acceleration_statement')) ?>">Unduh</a></dd>
				<dd class="details">
					<ul>
						<li>Khusus untuk siswa kelas akselerasi</li>
						<li>Dicetak di kertas HVS A4</li>
						<li>Ditulis tangan serta ditanda tangan asli oleh siswa</li>
						<li>Dikumpulkan kepada panitia</li>
					</ul>
				</dd>
			<?php endif; ?>
		</dl>
	</section>
	<section class="how">
		<p>Berkas-berkas tersebut dikumpulkan paling lambat <time datetime="<?php echo $applicant->expires_on->format(DateTime::W3C) ?>"><?php echo $applicant->expires_on->format('l, j F Y') ?></time> ke:</p>
		<dl class="depots">
			<?php
			$first = true;
			
			// dummy
			$dummy = new StdClass;
			$dummy->depot_address = $applicant->chapter->chapter_address;
			$dummy->mon_open = $dummy->mon_close = 
			$dummy->tue_open = $dummy->tue_close = 
			$dummy->wed_open = $dummy->wed_close = 
			$dummy->thu_open = $dummy->thu_close = 
				'';
			$dummy->fri_open = '14:00';
			$dummy->fri_close = '18:00';
			$dummy->sat_open = $dummy->sun_open = '13:00';
			$dummy->sat_close = $dummy->sun_close = '17:00';
			$dummy->is_default = true;
			
			$depots = array($dummy);
			$first = true;
			foreach ($depots as $depot):
				$depot_address = $depot->is_default ? $applicant->chapter->chapter_address : $depot->depot_address;
				$depot_name = $depot->is_default ? 'Sekretariat Chapter ' . $applicant->chapter->chapter_name : $depot->depot_name;
			?>
			<dt class="<?php echo $first ? 'primary' : 'secondary'; $first = false; ?>"><?php echo $depot_name ?></dt>
				<dd class="map">
					<?php $params = array('markers' => $depot->depot_address . ', Indonesia', 'size' => '458x120', 'sensor' => 'false', 'scale' => 2); $params_enc = http_build_query($params, '', '&amp;') ?>
					<a href="http://maps.google.com/maps?q=<?php echo urlencode($depot_address) ?>,%20Indonesia" title="Peta menuju <?php echo htmlspecialchars($depot_name) ?>"><img src="http://maps.googleapis.com/maps/api/staticmap?<?php echo $params_enc ?>" alt="Peta menuju <?php echo htmlspecialchars($depot_name) ?>" width="458" height="120"></a>
				</dd>
				<dd class="address">
					<h3>Alamat</h3>
					<address><?php echo nl2br($depot->depot_address) ?></address>
				</dd>
				<dd class="schedule">
					<h3>Jadwal Pengumpulan</h3>
					<ul>
						<?php
						$days = array('mon' => 'Senin', 'tue' => 'Selasa', 'wed' => 'Rabu', 'thu' => 'Kamis', 'fri' => 'Jumat', 'sat' => 'Sabtu', 'sun' => 'Minggu');
						foreach ($days as $d => $day):
							$o = $d . '_open';
							$c = $d . '_close';
							if ($depot->$o != $depot->$c):
								$open = str_replace(':', '.', $depot->$o);
								$close = str_replace(':', '.', $depot->$c);
						?>
						<li><strong><?php echo $day ?></strong> pukul <strong><?php echo $open ?>&ndash;<?php echo $close ?></strong></li>
						
						<?php endif; endforeach; ?>
					</ul>
				</dd>
			<?php endforeach; ?>
		</dl>
	</section>
	<section class="then">
		<p>Kembali ke laman ini jika Adik sudah mengumpulkan berkas-berkas tersebut.</p>
	</section>
</div>

<div class="user-create-wrapper" style="display: none">
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