<?php $this->header('Pengumpulan Berkas'); ?>
<header class="page-title alt">
	<h1>Seleksi</h1>
</header>
<nav class="actions-nav expleft">
	<ul>
		<li><a href="<?php L(array('action' => 'details')) ?>">Lihat Formulir Pendaftaran</a></li>
	</ul>
</nav>
<div class="container">
	<div class="message">
		<header>
			<h1>Selamat!</h1>
		</header>
		<p>Adik telah menyelesaikan seluruh proses pendaftaran.</p>
	</div>
	<header class="applicant-header">
		<p class="applicant-test-id"><?php echo $applicant->test_id ?></h1>
		<h1 class="applicant-name"><?php echo $applicant->sanitized_full_name ?>&nbsp;</h1>
	</header>
	
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