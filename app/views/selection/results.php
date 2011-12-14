<?php

$selection_titles = array(
	'selection_1' => 'Seleksi Tahap Pertama',
	'selection_2' => 'Seleksi Tahap Kedua',
	'selection_3' => 'Seleksi Tahap Ketiga'
);

$selection_title = $selection_titles[$selection_announced];
$page_title = 'Hasil Seleksi';

$next_selection_title = $selection_titles[$next_selection];
$announcement_prefix = 'Pengumuman ';

$rereg_venue_address = 'Jalan Aria Jipang 6, Bandung';

$rereg_date = new HeliumDateTime($dates[$next_selection . '_rereg']);
$rereg_date->set_locale('id');

$announcement_date = new HeliumDateTime($dates[$selection_announced . '_announcement']);
$announcement_date->set_locale('id');

$next_selection_date = new HeliumDateTime($dates[$next_selection]);
$next_selection_date->set_locale('id');
$next_selection_venue = 'SMA Negeri 3 Bandung';

$next_selection_end_date = new HeliumDateTime($dates[$next_selection . '_end']);

$this->header($page_title);

?>
<header class="stage-title">
	<h1>Pertukaran Pelajar Bina Antarbudaya INAYPSc 2012-2013</h1>
	<h2><?php echo $page_title ?></h2>
</header>
<div class="container">
<?php if ($results_ready): ?>
	<?php if ($the_result): ?>
	<h1 class="announcement">Selamat <?php echo $applicant_name ?>, kamu <strong>lulus</strong> <?php echo $selection_title; ?>!</h1>
	<!--p>Untuk mengikuti <?php echo $next_selection_title ?>, harap mendaftar ulang pada <strong><?php echo $rereg_date->format('l, j F Y') ?></strong> di <strong><?php echo $rereg_venue_address ?></strong>.</p-->
	
	<?php 
	switch ($next_selection):
	case 'selection_3':
	
	$shift_number = $applicant->selection_3_shift;
	$shift_start = clone $rereg_date;
	$increment = $shift_number - 1;
	$shift_start->modify('+' . $increment . ' hour');
	$shift_end = clone $shift_start;
	$shift_end->modify('+1 hour');

	?>
	
	<div class="technical_announcements">
		<p>Kami ucapkan selamat atas kelulusan Adik. Selanjutnya, ada beberapa hal yang harus diperhatikan dan dilakukan sebelum <?php echo $next_selection_title ?>:</p>
		
		<ol>
			<li>Adik harus mendaftar ulang di <strong>Jalan Aria Jipang 6, Bandung</strong> pada hari <strong>Minggu, 12 Juni 2011</strong>.</li>
			<li>Daftar ulang dilaksanakan secara bershift, dan Adik masuk ke dalam <strong>shift ke-<?php echo $shift_number ?></strong>. Harap datang pukul <strong><?php echo $shift_start->format('H.i') ?> WIB</strong>.</li>
			<li>Selain pada jam tersebut, <strong>Adik tidak akan kami layani</strong>.</li>
			<li>Ketika daftar ulang, Adik <strong>wajib</strong> membawa kartu peserta.</li>
			<li>Pendaftaran ulang tidak dapat diwakilkan, kecuali bagi yang berada di luar kota harap menghubungi Adinda.</li>
			<li>Pengumuman selanjutnya tentang Seleksi Tahap Ketiga akan diumumkan saat pendaftaran ulang dan melalui blog <a href="http://binabudbdg.org/">binabudbdg.org</a>.</li>
			<li>Silakan hubungi Adinda di 08179273033 apabila ada hal-hal yang <strong>perlu dan penting</strong> untuk ditanyakan. SMS tidak akan dilayani.</li>
		</ol>
	</div>
	<?php
	break;
	case 'selection_2':
	?>
	
	<div class="technical_announcements">
		<p>Kami ucapkan selamat atas kelulusan Adik. Selanjutnya, ada beberapa hal yang harus diperhatikan dan dilakukan sebelum <?php echo $next_selection_title ?>:</p>

		<!--ol>
			<li>Adik harus mendaftar ulang di <strong><?php echo $rereg_venue_address ?></strong> pada hari <strong><?php echo $rereg_date->format('l, j F Y') ?></strong>. Sekretariat dibuka pukul <strong>14.00–17.00</strong>, di luar jam tersebut Adik tidak akan kami layani.</li>
			<li>Ketika daftar ulang, Adik harus membawa kartu peserta dan 3 (tiga) surat rekomendasi yang sudah diisi. Ketiga surat rekomendasi tersebut dapat diunduh di <a href="http://binabudbdg.org/wp-content/uploads/2011/05/Surat-Rekomendasi.zip">sini</a>. Adik juga harus membawa cetak ulang transkrip formulir pendaftaran yang dapat diunduh di <a href="<?php L(array('controller' => 'applicant', 'action' => 'transcript')) ?>">sini</a>.</li>
			<li>Jika Adik tidak membawa kartu peserta dan/atau surat rekomendasi, Adik dinyatakan <strong>MENGUNDURKAN DIRI</strong> dari <?php echo $next_selection_title ?> Bina Antarbudaya Bandung.</li>
			<li>Pendaftaran ulang tidak dapat diwakilkan. Adik harus melakukan keseluruhan prosedur dan pastikan yang melayani Adik benar-benar volunteer kami (tidak diperkenankan hanya menitipkan persyaratan di atas pada penghuni Jalan Aria Jipang 6).</li>
			<li><?php echo $next_selection_title ?> akan diselenggarakan hari <strong><?php echo $next_selection_date->format('l, j F Y') ?></strong> di <strong><?php echo $next_selection_venue; ?></strong>. Harap datang pukul <strong><?php echo $next_selection_date->format('H.i') ?></strong> untuk pendataan ulang, pemberian name tag, dan briefing awal. Seleksi diperkirakan selesai pukul <strong><?php echo $next_selection_end_date->format('H.i') ?></strong>. Silakan membawa buku atau benda-benda lain yang bisa menghibur Adik ketika menunggu giliran wawancara.</li>
			<li>Informasi selengkapnya mengenai format Seleksi Tahap Kedua dapat dilihat di blog binabudbdg.org.</li>
			<li>Silakan hubungi Adinda di 08179273033 apabila ada hal-hal yang <strong>perlu dan penting</strong> untuk ditanyakan. SMS tidak akan dilayani.</li>
		</ol-->
		
		<ol>
			<li>Adik harus mendaftar ulang di <strong>Jalan Aria Jipang 6, Bandung</strong> pada hari <strong>Jumat, 27 Mei 2011</strong>. Sekretariat dibuka pukul <strong>14.00–17.00</strong>, di luar jam tersebut Adik tidak akan kami layani.</li>
			<li>Ketika daftar ulang, Adik <strong>wajib</strong> membawa:
		a. Kartu peserta
		b. 3 (tiga) surat rekomendasi yang sudah diisi. Ketiga surat rekomendasi tersebut dapat diunduh di <a href="http://binabudbdg.org/wp-content/uploads/2011/05/Surat-Rekomendasi.zip">sini</a>.
		c. Cetak ulang transkrip formulir pendaftaran yang dapat diunduh di <a href="http://seleksi.binabudbdg.org/daftar/transkrip">sini</a> (login dengan username/password pendaftaran)</li>
			<li>Jika Adik tidak membawa salah satu dari tiga poin di atas, Adik dinyatakan <strong>MENGUNDURKAN DIRI</strong> dari Seleksi Tahap Kedua Bina Antarbudaya Bandung.</li>
			<li>Pendaftaran ulang tidak dapat diwakilkan. Adik harus melakukan keseluruhan prosedur dan pastikan yang melayani Adik benar-benar volunteer kami (tidak diperkenankan hanya menitipkan persyaratan di atas pada penghuni Jalan Aria Jipang 6).</li>
			<li>Seleksi Tahap Kedua akan diselenggarakan hari <strong>Minggu, 29 Mei 2011</strong> di <strong>SMA Negeri 3 Bandung</strong>. Harap datang pukul <strong>07.00</strong> untuk pendataan ulang, pemberian name tag, dan briefing awal. Seleksi diperkirakan selesai pukul <strong>16.00</strong>. Silakan membawa buku atau benda-benda lain yang bisa menghibur Adik ketika menunggu giliran wawancara.</li>
			<li>Silakan hubungi Adinda di 08179273033 apabila ada hal-hal yang <strong>perlu dan penting</strong> untuk ditanyakan. SMS tidak akan dilayani.</li>
		</ol>
	</div>
	
	<?php break; endswitch; ?>
	
	<?php else: ?>
	<h1 class="announcement">:(</h1>
	<p>Maaf <?php echo $applicant_name ?>, kamu tidak lulus <?php echo $selection_title; ?>. <!-- Tapi ini bukan berarti akhir dari perjuanganmu &ndash; masih banyak jalan menuju Roma :) --></p>
	<?php endif; ?>
	<p><a href="<?php L(array('controller' => 'applicant', 'action' => 'card')); ?>">Cetak ulang kartu peserta</a></p>
	<p>Untuk informasi selengkapnya, kunjungi terus <strong><a href="http://binabudbdg.org">binabudbdg.org</a></strong>, follow <strong><a href="http://twitter.com/afsbandung">@afsbandung</a></strong>, atau email <strong>seleksi@binabudbdg.org</strong>.</p>
<?php else: ?>
	<h1 class="announcement"><em>Patience is virtue.</em></h1>
	<p>Hasil seleksi akan diumumkan pada <strong><?php echo $announcement_date->format('l, j F Y') ?></strong> pukul <strong><?php echo $announcement_date->format('H.i') ?> WIB</strong>.</p>
<?php endif; ?>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<?php $this->footer(); ?>