<?php $this->header('Formulir Pendaftaran'); ?>
<?php

function print_address($a, $name, $kota = true, $provinsi = true, $kodepos = true, $telepon = true, $hp = true, $fax = true, $email = true) {
	?>
	<p class="value block"><?php $v = $name . '_address_street'; echo $a->$v; ?>
	<?php
	$k = $name . '_address_city';
	if ($kota && $a->$k) {
		echo '<br>' . $a->$k;

		$v = $name . '_address_postcode';
		if ($kodepos && $a->$v)
			echo ' ' . $a->$v;
	}

	$v = $name . '_address_province';
	if ($provinsi && $a->$v)
		echo ($a->$k ? ', ' : '<br>') . $a->$v;

	?>
	</p>
	<?php
	$v = $name . '_mobilephone';
	if ($hp && $a->$v):
	?>
	<label class="subpoint">HP</label>
	<span class="value"><?php echo $a->$v; ?></span>
	<br>
	<?php
	endif;
	$v = $name . '_phone_number';
	if ($telepon && $a->$v):
	?>
	<label class="subpoint">Telepon</label>
	<span class="value"><?php $v = $name . '_phone_areacode'; if ($a->$v) echo '(' . $a->$v . ') '; ?><?php $v = $name . '_phone_number'; echo $a->$v; ?></span>
	<?php
	endif;
	$v = $name . '_fax_number';
	if ($fax && $a->$v):
	?>
	<br>
	<label class="subpoint">Faks</label>
	<span class="value"><?php $v = $name . '_fax_areacode'; if ($a->$v) echo '(' . $a->$v . ') '; ?><?php $v = $name . '_fax_number'; echo $a->$v; ?></span>
	<?php
	endif;
	$v = $name . '_email';
	if ($email && $a->$v):
	?>
	<br>
	<label class="subpoint">E-mail</label>
	<span class="value"><?php echo $a->$v; ?></span>
	<?php endif;
}

?>
<script src="<?php L('/assets/js/jquery-1.6.2.min.js') ?>"></script>
<?php if ($admin): ?>
<header class="page-title alt">
	<h1>Pengelolaan Pendaftar</h1>
</header>
<nav class="actions-nav">
	<ul>
		<li><a href="<?php L($this->session->flash('applicant_back_to')) ?>">Kembali</a></li>
	</ul>
</nav>
<?php else: ?>
<header class="page-title alt">
	<h1>Transkrip Formulir Pendaftaran</h1>
</header>
<nav class="actions-nav">
	<ul>
		<li><a href="<?php L($this->user->get_landing_page()) ?>">Kembali</a></li>
	</ul>
</nav>
<?php endif; ?>
<div class="container">

	<?php if ($new && !$admin): ?>
	<div class="message extended">
		<header>
			<h1>Selamat datang di Formulir Pendaftaran</h1>
		</header>
		<p>Formulir ini terdiri atas <strong>sembilan bagian</strong> yang dapat diakses melalui tautan pada menu di sebelah kiri. Isilah seluruh formulir ini dengan <strong>lengkap</strong> dan <strong>teliti</strong>. Gunakan tombol <em>Simpan Sementara</em> di sebelah kanan atas ini untuk menyimpan sementara isian formulir untuk diisi kembali.</p>
		<p>Setelah Adik selesai mengisi <strong>seluruh</strong> formulir ini, klik 'Finalisasi' di menu sebelah kiri.<br>Ingat, waktu Adik hanya sampai <strong><?php echo $this->applicant->expires_on->format('l, j F Y'); ?></strong>.</p>
		<p class="hide"><a href="#">Sembunyikan pesan ini</a></p>
	</div>

	<?php elseif ($admin && $error): ?>
	<div class="message error">
		<header>
			<h1>Peserta tidak dapat diubah</h1>
		</header>
		<p><?php $messages = array('not_found' => 'Peserta tidak ditemukan.', 'applicant_finalized' => 'Peserta sudah melakukan finalisasi.', 'forbidden' => 'Anda tidak boleh mengakses laman ini.'); echo $messages[$error]; ?></p>
	</div>

	<?php elseif ($errors): ?>
	<div class="message error">
		<header>
			<h1>Finalisasi Gagal</h1>
		</header>
		<ul>
			<?php foreach ($errors as $error): ?>
			<li><?php echo $error; ?></li>
			<?php endforeach; ?>
		</ul>
		<p><a href="#" onclick="$(this.parentNode.parentNode).slideUp()">Sembunyikan pesan ini</a></p>
	</div>

	<?php elseif ($message = $notice): ?>
	<div class="message">
		<p><?php echo $message; ?></p>
		<p class="hide"><a href="#">Sembunyikan pesan ini</a></p>
	</div>
	<?php endif; ?>
	
	<script>$(document).ready(function(){$('.message .hide a').click(function(e){e.preventDefault(); $(this).parent().parent().slideUp()})})</script>

	<?php if (!$admin) { ?><script>document.write('<style>.message {display: none}</style>');</script><?php } ?>


<?php if (!$admin || !$error): ?>

<form action="<?php L($this->params) ?>" enctype="multipart/form-data" method="POST" id="appform">

<nav class="form-page-nav above">
	<p class="prev"><a href="#_prev">&laquo; Halaman sebelumnya</a></p>
	<p class="next"><a href="#_next">Halaman berikutnya &raquo;</a></p>
</nav>

<nav class="form-nav">
	<header>
		<h1>Pilih Halaman</h1>
	</header>
	<ol>
		<li><a href="#pribadi">Data Pribadi</a></li>
		<li><a href="#keluarga">Keluarga</a></li>
		<li><a href="#pendidikan">Pendidikan</a></li>
		<li><a href="#kegiatan">Kegiatan</a></li>
		<li><a href="#persona">Kepribadian</a></li>
		<li><a href="#travel">Riwayat Perjalanan</a></li>
		<li><a href="#reference">Referensi</a></li>
		<li><a href="#rekomendasi">Rekomendasi</a></li>
	</ol>
</nav>

<div class="form-fields">

<!-- begin form -->

<fieldset class="pane" id="pribadi">
	<legend>Data Pribadi</legend>
	
	<?php if ($picture): ?>
	<div class="picture-container"><img src="<?php echo $picture->get_cropped_url(); ?>" width="300" height="400"></div>
	<?php endif; ?>
	<table class="form-table">
		<tr>
			<td class="label">Nama Lengkap</td>
			<td class="field"><span class="value"><?php echo $a->full_name ?></span></td>
		</tr>
		<tr>
			<td class="label">Pilihan Program</td>
			<td class="field"><span class="value"><?php
			if ($a->program_afs)
				echo 'AFS';
			if ($a->program_afs && $a->program_yes)
				echo ', ';
			if ($a->program_yes)
				echo 'YES';
			?></span></td>
		<tr>
			<td class="label">Tempat dan Tanggal Lahir</td>
			<td class="field"><span class="value"><?php echo $a->place_of_birth . ', ' . $a->date_of_birth->format('j F Y') ?></td>
		</tr>
		<tr>
			<td class="label">Alamat Surel (E-mail)</td>
			<td class="field"><span class="value"><?php echo $a->applicant_email ?></span></td>
		</tr>
		<tr>
			<td class="label">Nomor Ponsel</td>
			<td class="field"><span class="value"><?php echo $a->applicant_mobilephone ?></span></td>
		</tr>
		<tr>
			<td class="label">Alamat Lengkap</td>
			<td class="field"><?php print_address($a, 'applicant', true, true, true, true, false, true, false); ?> </td>
		</tr>
		<tr>
			<td class="label">Jenis Kelamin</td>
			<td class="field">
				<span class="value"><?php $map = array('F' => 'Perempuan', 'M' => 'Laki-laki'); echo $map[$a->sex] ?></span>
			</td>
		</tr>
		<tr>
			<td class="label"></td>
			<td class="field">
				<label class="subpoint">Tinggi Badan</label> <span class="value"><?php echo $a->body_height ?></span> cm
				<br>
				<label class="subpoint">Berat Badan</label> <span class="value"><?php echo $a->body_weight ?></span> kg
				<br>
				<label class="subpoint">Gol. Darah</label>
				<span class="value"><?php $map = array('' => '', 'O' => 'O', 'A' => 'A', 'B' => 'B', 'AB' => 'AB'); echo $map[$a->blood_type] ?></span>
			</td>
		</tr>
		<tr>
			<td class="label">Kewarganegaraan</td>
			<td class="field"><span class="value"><?php echo $a->citizenship ?></span><br>
				
			</td>
		</tr>
		<tr>
			<td class="label">Agama</td>
			<td class="field"><span class="value"><?php echo $a->religion ?></span></td>
		</tr>
	</table>
</fieldset>

<fieldset class="pane" id="keluarga">
	<!-- poin 9–11 -->
	<legend>Keluarga</legend>
	<?php

	foreach(array('father' => 'Ayah', 'mother' => 'Ibu') as $n => $parent):
	?>
	<h1><?php echo $parent; ?></h1>
	<table class="form-table">
		<tr>
			<td class="label"><?php echo "Nama Lengkap $parent" ?></td>
			<td class="field"><span class="value"><?php $v = $n . '_full_name'; echo $a->$v; ?></a></td>
		</tr>
		<tr>
			<td class="label">Pendidikan Terakhir</td>
			<td class="field"><?php $v = $n . '_education'; echo $a->$v; ?></td>
		</tr>
		<tr>
			<td class="label">Pekerjaan/Jabatan</td>
			<td class="field"><?php $v = $n . '_occupation'; echo $a->$v; ?></td>
		</tr>
		<tr>
			<td class="label">Alamat Surel (E-mail)</td>
			<td class="field"><?php $v = $n . '_office_email'; echo $a->$v; ?></td>
		</tr>
		<tr>
			<td class="label">Nomor Ponsel</td>
			<td class="field"><?php $v = $n . '_office_mobilephone'; echo $a->$v; ?></td>
		</tr>
		<tr>
			<td class="label">Nama dan Alamat Kantor</td>
			<td class="field">
				<span class="value"><?php $v = $n . '_office_name'; echo $a->$v ?></span>
				<br>
				<?php print_address($a, $n . '_office', true, true, false, true, false, true, false) ?>
			</td>
		</tr>
	</table>
	<?php endforeach; ?>

	<h1>Wali <span>(apabila orang tua telah wafat atau Adik tinggal terpisah dengan orang tua)</span></h1>
	<table class="form-table">
		<tr>
			<td class="label"><?php $form->label('guardian_full_name', "Nama Lengkap Wali") ?></td>
			<td class="field"><span class="value"><?php echo $a->guardian_full_name ?></span><br>
				
			</td>
		</tr>
		
		<tr>
			<td class="label"><?php $form->label('guardian_relationship_to_applicant', 'Hubungan dengan Adik') ?></td>
			<td class="field"><span class="value"><?php echo $a->guardian_relationship_to_applicant ?></span></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('guardian_email', 'Alamat Surel (E-mail)') ?></td>
			<td class="field"><span class="value"><?php echo $a->guardian_email ?></span></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('guardian_address_street', 'Alamat Wali') ?></td>
			<td class="field"><?php print_address($a, 'guardian', true, true, false, true, true, false, false) ?></td>
		</tr>
		<!-- The field below is in the DB schema but not the original form -->
		<!-- <tr>
			<td class="label"><?php $form->label('guardian_education', 'Pendidikan Terakhir') ?></td>
			<td class="field"><span class="value"><?php echo $a->guardian_education ?></span></td>
		</tr> -->
		<tr>
			<td class="label"><?php $form->label('guardian_occupation', 'Pekerjaan/Jabatan') ?></td>
			<td class="field"><span class="value"><?php echo $a->guardian_occupation ?></span></td>
		</tr>
		<!-- Remove Pangkat/Golongan for now; it's a bit absurd -->
		<!-- <tr>
			<td class="label"><?php $form->label('guardian_job_title', 'Pangkat/Golongan') ?></td>
			<td class="field"><span class="value"><?php echo $a->guardian_job_title ?></span></td>
		</tr> -->
		<tr>
			<td class="label"><?php $form->label('guardian_office_name', 'Nama dan Alamat Kantor') ?></td>
			<td class="field"><span class="value"><?php echo $a->guardian_office_name ?></span><br>
				<?php print_address($a, 'guardian_office', true, true, false, true, false, true, false) ?>
			</td>
		</tr>
	</table>

	<h1>Saudara Kandung</h1>
	<table class="form-table siblings">
		<tr>
			<td class="label noc">Jumlah anak dalam keluarga</td>
			<td class="field noc"><span class="value"><?php echo $a->number_of_children_in_family ?></span></td>
			<td class="label nth">Adik anak nomor</td>
			<td class="field nth"><span class="value"><?php echo $a->nth_child ?></span></td>
		</tr>
	</table>
	<table class="siblings-table subform">
		<caption>
			<span>Nama, umur, dan sekolah/pekerjaan saudara kandung (selain Adik sendiri)</span>
		</caption>
		<thead>
			<tr>
				<th class="sibling-name">Nama Lengkap</th>
				<th class="sibling-dob">Tanggal Lahir</th>
				<th class="sibling-job">Sekolah/Pekerjaan</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($siblings as $s):
			?>
			<tr class="notempty">
				<td class="sibling-name"><?php echo $s->full_name ?></td>
				<td class="sibling-dob"><?php echo $s->date_of_birth ?></td>
				<td class="sibling-job"><?php echo $s->occupation ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</fieldset>

<fieldset class="pane" id="pendidikan">
	<legend>Pendidikan</legend>
	<!-- <p>Seluruh kolom pada halaman ini <strong>wajib diisi</strong>.</p> -->
	<!-- poin 12–14 -->

	<h1>SMA/SMK/MA</h1>
	<table class="form-table">
		<tr>
			<td class="label">Nama Sekolah</td>
			<td class="field"><span class="value"><?php echo $a->high_school_name ?></span><br>
				
					
			</td>
			<tr>
				<td class="label">Pesantren/Madrasah</td>
				<td class="field"><span class="value"><?php echo $a->in_pesantren ? 'Ya' : 'Tidak'; ?></span></td>
			</tr>
			<tr>
				<td class="label">Akselerasi</td>
				<td class="field"><span class="value"><?php echo $a->is_acceleration_class ? 'Ya' : 'Tidak'; ?></span></td>
			</tr>
			<tr>
				<td class="label">Alamat Sekolah</td>
				<td class="field"><?php print_address($a, 'high_school', false, false, false, true, false, true, false); ?></td>
			</tr>
			<tr>
				<td class="label">Tahun Masuk</td>
				<td class="field"><span class="value"><?php echo $a->high_school_admission_year ?></span></td>
			</tr>
			<tr>
				<td class="label">Bulan Keluar</td>
				<td class="field"><span class="value"><?php
				$months = array(1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
				7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember');
				echo $months[$a->high_school_graduation_month] . ' ' . $a->high_school_graduation_year; ?></span></td>
			</tr>
		</tr>
	</table>
	
	<table class="academics sma subform">
		<caption>
			Data prestasi
		</caption>
		<thead>
			<tr>
				<th rowspan="2" class="grade">Kelas</th>
				<th>Ranking ke ... dari ... siswa <strong>atau Rata-Rata Nilai (jika tidak ada ranking)</strong></th>
			</tr>
			<tr>
				<th class="term-first">Semester I</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="grade">X</td>
				<td class="term-first">
					<?php $form->text('grades_y10t1_rank', 'very-short l') ?>
					dari
					<?php $form->text('grades_y10t1_total', 'very-short r') ?>
				</td>
			</tr>
		</tbody>
	</table>
	
	<h1>SMP/MTs</h1>
	<table class="form-table">
		<tr>
			<td class="label">Nama Sekolah</td>
			<td class="field">
				<span class="value"><?php echo $a->junior_high_school_name ?></span><br>
				
					
			</td>
		</tr>
		<tr>
			<td class="label">Tahun Ijazah</td>
			<td class="field"><?php $form->select_year('junior_high_school_graduation_year', date('Y') - 2, date('Y') - 1); ?></td>
		</tr>
	</table>

	<table class="academics smp subform">
		<caption>
			Data prestasi
		</caption>
		<thead>
			<tr>
				<th rowspan="2" width="60" class="grade">Kelas</th>
				<th colspan="2">Ranking ke ... dari ... siswa <strong>atau Rata-Rata Nilai (jika tidak ada ranking)</strong></th>
			</tr>
			<tr>
				<th class="term-first">Semester I</th>
				<th class="term-final">Semester II</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$grades = array(7 => 'VII', 8 => 'VIII', 9 => 'IX');
			foreach($grades as $i => $g): ?>
			<tr>
				<td class="grade"><?php echo $g; ?></td>
				<td class="term-first">
					<?php $form->text('grades_y' . $i . 't1_rank', 'very-short l') ?> dari
					<?php $form->text('grades_y' . $i . 't1_total', 'very-short r') ?>
				</td>
				<td class="term-final">
					<?php $form->text('grades_y' . $i . 't2_rank', 'very-short l') ?> dari
					<?php $form->text('grades_y' . $i . 't2_total', 'very-short r') ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	
	<h1>SD/MI</h1>
	<table class="form-table">
		<tr>
			<td class="label">Nama Sekolah</td>
			<td class="field"><span class="value"><?php echo $a->elementary_school_name ?></span><br>
				
					
			</td>
		</tr>
		<tr>
			<td class="label">Tahun Ijazah</td>
			<td class="field"><?php $form->select_year('elementary_school_graduation_year', date('Y') - 5, date('Y') - 3); ?></td>
		</tr>
	</table>

	<table class="academics sd subform">
		<caption>
			Data prestasi
		</caption>
		<thead>
			<tr>
				<th rowspan="2" width="60" class="grade">Kelas</th>
				<th colspan="2">Ranking ke ... dari ... siswa <strong>atau Rata-Rata Nilai (jika tidak ada ranking)</strong></th>
			</tr>
			<tr>
				<th class="term-first">Semester I</th>
				<th class="term-final">Semester II</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$grades = array(1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI');
			foreach($grades as $i => $g): ?>
			<tr>
				<td class="grade"><?php echo $g; ?></td>
				<td class="term-first">
					<?php $form->text('grades_y' . $i . 't1_rank', 'very-short l') ?> dari
					<?php $form->text('grades_y' . $i . 't1_total', 'very-short r') ?>
				</td>
				<td class="term-final">
					<?php $form->text('grades_y' . $i . 't2_rank', 'very-short l') ?> dari
					<?php $form->text('grades_y' . $i . 't2_total', 'very-short r') ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<h1>Pengetahuan Bahasa</h1>
	<table class="form-table">
		<tr>
			<td class="label">Sudah berapa lama Adik belajar Bahasa Inggris?</td>
			<td class="field"><span class="value"><?php echo $a->years_speaking_english ?></span></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('other_languages', 'Bahasa lain yang Adik kuasai/pelajari') ?></td>
			<td class="field"><span class="value"><?php echo $a->other_languages ?></span></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('years_speaking_other_languages', 'Berapa lama?') ?></td>
			<td class="field"><span class="value"><?php echo $a->years_speaking_other_languages ?></span></td>
		</tr>
	</table>
	<h1>Pelajaran Favorit dan Cita-Cita</h1>
	<table class="form-table">
		<tr>
			<td class="label">Mata pelajaran favorit</td>
			<td class="field"><span class="value"><?php echo $a->favorite_subject ?></span></td>
		</tr>
		<tr>
			<td class="label">Cita-cita</td>
			<td class="field"><span class="value"><?php echo $a->dream ?></span></td>
		</tr>
	</table>
</fieldset>

<fieldset class="pane" id="kegiatan">
	<legend>Kegiatan</legend>
	<!-- poin 15-19 -->
	<h1>Organisasi</h1>
	<table class="achievements subform">
		<caption>Organisasi yang pernah diikuti, baik di lingkungan sekolah maupun di luar lingkungan sekolah</caption>
		<thead>
			<tr>
				<th class="name">Nama Organisasi</th>
				<th class="kind">Jenis Kegiatan</th>
				<th class="achv">Jabatan</th>
				<th class="year">Tahun</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($subforms['applicant_organizations'] as $s):
			?>
			<tr class="notempty">
				<td class="name"><?php $s->text('name', 'short') ?></td>
				<td class="kind"><?php $s->text('kind', 'short') ?></td>
				<td class="achv"><?php $s->text('position', 'short') ?></td>
				<td class="year"><?php $s->select_year('year', date('Y') - 12, date('Y')) ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<h1>Kesenian <span>(seni suara, seni musik, tari, teater, dll.)</span></h1>
	<?php $phase = 'kesenian'; ?>
	<table class="form-table">
		<tr>
			<td class="label">Sekedar hobi</td>
			<td class="field"><span class="value"><?php echo $a->arts_hobby ?></span></td>
		</tr>		
		<tr>
			<td class="label"><?php $form->label('arts_organized', 'Ikut perkumpulan') ?></td>
			<td class="field"><span class="value"><?php echo $a->arts_organized ?></span></td>
		</tr>
	</table>

	<table class="achievements subform" width="620">
		<caption>Prestasi</caption>
		<thead>
			<tr>
				<th class="name">Jenis</th>
				<th class="kind">Kejuaraan</th>
				<th class="achv">Prestasi</th>
				<th class="year">Tahun</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($subforms['applicant_arts_achievements'] as $s):
			?>
			<tr class="notempty">
				<td class="name"><?php $s->text('championship', 'short') ?></td>
				<td class="kind"><?php $s->text('kind', 'short') ?></td>
				<td class="achv"><?php $s->text('achievement', 'short') ?></td>
				<td class="year"><?php $s->select_year('year', date('Y') - 12, date('Y')) ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<h1>Olahraga</h1>
	<?php $phase = 'olahraga'; ?>
	<table class="form-table">
		<tr>
			<td class="label">Sekedar hobi</td>
			<td class="field"><span class="value"><?php echo $a->sports_hobby ?></span></td>
		</tr>		
		<tr>
			<td class="label"><?php $form->label('sports_organized', 'Ikut perkumpulan') ?></td>
			<td class="field"><span class="value"><?php echo $a->sports_organized ?></span></td>
		</tr>
	</table>
	<table class="achievements subform" width="620">
		<caption>Prestasi</caption>
		<thead>
			<tr>
				<th class="chmp">Kejuaraan</th>
				<th class="achv">Pencapaian</th>
				<th class="year">Tahun</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($subforms['applicant_sports_achievements'] as $s):
			?>
			<tr class="notempty">
				<td class="chmp"><?php $s->text('championship', 'short') ?></td>
				<td class="achv"><?php $s->text('achievement', 'short') ?></td>
				<td class="year"><?php $s->select_year('year', date('Y') - 12, date('Y')) ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<h1>Lain-lain</h1>
	<?php $phase = 'kegiatan_lain_lain'; ?>
	<table class="achievements subform">
		<caption>Kegiatan lain di luar olahraga dan kesenian</caption>
		<thead>
			<tr>
				<th class="chmp">Kegiatan</th>
				<th class="achv">Prestasi</th>
				<th class="year">Tahun</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($subforms['applicant_other_achievements'] as $s):
			?>
			<tr class="notempty">
				<td class="chmp"><?php $s->text('activity', 'short') ?></td>
				<td class="achv"><?php $s->text('achievement', 'short') ?></td>
				<td class="year"><?php $s->select_year('year', date('Y') - 12, date('Y')) ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php $phase = 'pengalaman_kerja'; ?>
	<table class="achievements subform">
		<caption>Pengalaman kerja sosial/magang/bekerja (di LSM, Yayasan, kantor, sekolah, koperasi, usaha, dll)</caption>
		<thead>
			<tr>
				<th class="ngo">Nama dan bidang tempat bekerja/magang</th>
				<th class="ngo">Tugas dan tanggung jawab yang dijalankan</th>
				<th class="period">Tahun dan lama&nbsp;bekerja</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($subforms['applicant_work_experiences'] as $s):
			?>
			<tr class="notempty">
				<td class="ngo"><?php $s->text('organization', 'short') ?></td>
				<td class="ngo"><?php $s->text('position', 'short') ?></td>
				<td class="period"><?php $form->text('period') ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</fieldset>

<fieldset class="pane" id="travel">
	<legend>Riwayat Perjalanan</legend>
	<h1>Pernahkah Adik melawat/berpergian dalam jangka pendek ke luar negeri?</h1>
	<table class="form-table">
		<tr>
			<td class="label"><?php $form->label('short_term_travel_destination', 'Jika pernah, ke mana?') ?></td>
			<td class="field"><span class="value"><?php echo $a->short_term_travel_destination ?></span></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('short_term_travel_when', 'Kapan?') ?></td>
			<td class="field"><span class="value"><?php echo $a->short_term_travel_when ?></span></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('short_term_travel_purpose', 'Dalam rangka apa?') ?></td>
			<td class="field"><span class="value"><?php echo $a->short_term_travel_purpose ?></span></td>
		</tr>
	</table>
	<h1>Pernahkah Adik melawat/berpergian dalam jangka panjang ke luar negeri?</h1>
	<table class="form-table">
		<tr>
			<td class="label"><?php $form->label('long_term_travel_destination', 'Jika pernah, ke mana?') ?></td>
			<td class="field"><span class="value"><?php echo $a->long_term_travel_destination ?></span></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('long_term_travel_when', 'Kapan dan berapa lama?') ?></td>
			<td class="field"><span class="value"><?php echo $a->long_term_travel_when ?></span></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('long_term_travel_purpose', 'Dalam rangka apa?') ?></td>
			<td class="field"><span class="value"><?php echo $a->long_term_travel_purpose ?></span></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('long_term_travel_activities', 'Kegiatan Adik selama di sana?') ?></td>
			<td class="field"><span class="value"><?php echo $a->long_term_travel_activities ?></span></td>
		</tr>
	</table>
</fieldset>

<fieldset class="pane" id="reference">
	<legend>Referensi</legend>
	<h1>Adakah di antara keluarga besar Adik yang pernah mengikuti program pertukaran yang diselenggarakan oleh Bina Antarbudaya/AFS?</h1>
	<table class="form-table">
		<tr>
			<td class="label"><?php $form->label('relative_returnee_name', 'Nama') ?></td>
			<td class="field"><span class="value"><?php echo $a->relative_returnee_name ?></span></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('relative_returnee_relationship', 'Hubungan dengan Adik') ?></td>
			<td class="field"><span class="value"><?php echo $a->relative_returnee_relationship ?></span></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('relative_returnee_program', 'Program') ?></td>
			<td class="field"><span class="value"><?php echo $a->relative_returnee_program . ' (' . ucfirst($a->relative_returnee_program_type) . ')'; ?></span></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('relative_returnee_destination', 'Tujuan (sending)/Asal (hosting)') ?></td>
			<td class="field"><?php $form->text('relative_returnee_destination', 'long')  ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('relative_returnee_address_street', 'Alamat sekarang') ?></td>
			<td class="field"><?php print_address($a, 'relative_returnee', true, false, false, false, false, false, true) ?></td>
		</tr>
	</table>
	<h1>Pernahkah Adik atau keluarga Adik berpartisipasi dalam kegiatan Bina Antarbudaya/AFS sebelumnya?</h1>
	<table class="form-table">
		<tr>
			<td class="label"><?php $form->label('past_binabud_activities', 'Kegiatan') ?></td>
			<td class="field"><?php $form->text('past_binabud_activities', 'long')  ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('past_binabud_activities_year', 'Tahun') ?></td>
			<td class="field"><?php echo $a->past_binabud_activities_year; ?></td>
		</tr>
	</table>
	
	<h1>Referensi</h1>
	<p class="field">
		<?php $form->label('referrer', 'Dari mana Adik mengetahui program kami?') ?><br>
		<?php $form->text('referrer');  ?>
	</p>
	<p class="field">
		Apa motivasi Adik mengikuti seleksi dan program Bina Antarbudaya?<br>
		<?php $form->text('motivation', 'extra-large');  ?>
	</p>
	<p class="field">
		Apa yang diharapkan Adik dengan keikutsertaan Adik dalam seleksi dan program Bina Antarbudaya?<br>
		<?php $form->text('hopes', 'extra-large');  ?>
	</p>
</fieldset>

<fieldset class="pane" id="rekomendasi">
	<legend>Rekomendasi</legend>
	<p>Sebutkan nama 3 (tiga) orang <u>di luar keluarga</u> Adik yang mengenal diri Adik secara pribadi untuk menuliskan surat rekomendasi bagi Adik. Diharapkan nama orang-orang tersebut tidak akan berganti pada saat Adik harus memintakan rekomendasi dari mereka. <i>Surat rekomendasi tidak perlu dikumpulkan pada saat pendaftaran seleksi.</i></p>
	<h1>Lingkungan sekolah (Guru atau Kepala Sekolah) <span>(berusia sekurang-kurangnya 21 tahun)</span></h1>
	<table class="form-table">
		<tr>
			<td class="label">Nama</td>
			<td class="field"><span class="value"><?php echo $a->recommendations_school_name ?></span></td>
		</tr>
		<tr>
			<td class="label">Alamat/Telepon</td>
			<td class="field"><?php $form->textarea('recommendations_school_address') ?></td>
		</tr>
		<tr>
			<td class="label">Pekerjaan</td>
			<td class="field"><span class="value"><?php echo $a->recommendations_school_occupation ?></span></td>
		</tr>
		<tr>
			<td class="label">Alamat pekerjaan</td>
			<td class="field"><?php $form->textarea('recommendations_school_work_address') ?></td>
		</tr>
		<tr>
			<td class="label">Hubungan</td>
			<td class="field"><span class="value"><?php echo $a->recommendations_school_relationship ?></span></td>
		</tr>
	</table>
	<h1>Lingkungan rumah/organisasi di luar sekolah <span>(<strong>bukan keluarga,</strong> berusia sekurang-kurangnya 21 tahun)</span></h1>
	<table class="form-table">
		<tr>
			<td class="label">Nama</td>
			<td class="field"><span class="value"><?php echo $a->recommendations_nonschool_name ?></span></td>
		</tr>
		<tr>
			<td class="label">Alamat/Telepon</td>
			<td class="field"><?php $form->textarea('recommendations_nonschool_address') ?></td>
		</tr>
		<tr>
			<td class="label">Pekerjaan</td>
			<td class="field"><span class="value"><?php echo $a->recommendations_nonschool_occupation ?></span></td>
		</tr>
		<tr>
			<td class="label">Alamat pekerjaan</td>
			<td class="field"><?php $form->textarea('recommendations_nonschool_work_address') ?></td>
		</tr>
		<tr>
			<td class="label">Hubungan</td>
			<td class="field"><span class="value"><?php echo $a->recommendations_nonschool_relationship ?></span><br>
				</td>
		</tr>
	</table>
	<h1>Teman dekat</h1>
	<table class="form-table">
		<tr>
			<td class="label">Nama</td>
			<td class="field"><span class="value"><?php echo $a->recommendations_close_friend_name ?></span></td>
		</tr>
		<tr>
			<td class="label">Alamat/Telepon</td>
			<td class="field"><?php $form->textarea('recommendations_close_friend_address') ?></td>
		</tr>
		<tr>
			<td class="label">Hubungan</td>
			<td class="field"><span class="value"><?php echo $a->recommendations_close_friend_relationship ?></span></td>
		</tr>
	</table>
</fieldset>

<fieldset class="pane" id="persona">
	<legend>Kepribadian</legend>
	<p class="field">
		Menurut Adik, seperti apakah sifat dan kepribadian adik?
		<br>
		<?php $form->text('personality', 'extra-large') ?>
	</p>
	<p class="field">
		Apakah kelebihan/kekurangan Adik?
		<br>
		<?php $form->text('strengths_and_weaknesses', 'extra-large') ?>
	</p>
	<p class="field">
		Hal-hal apakah yang sering membuat Adik merasa tertekan?
		<br>
		<?php $form->text('stressful_conditions', 'extra-large') ?>
	</p>
	<p class="field">
		Masalah terberat apakah yang pernah Adik hadapi? Bagaimana Adik menyelesaikannya?
		<br>
		<?php $form->text('biggest_life_problem', 'extra-large') ?>
	</p>
	<p class="field">
		Apakah rencana Adik berkaitan dengan pendidikan dan karir di masa depan?
		<br>
		<?php $form->text('plans', 'extra-large') ?>
	</p>
</fieldset>

<script>
	last_pane = '<?php echo $last_pane ? $last_pane : '' ?>';
	firstTime = <?php echo $new ? 'true' : 'false' ?>;
	incomplete = <?php echo $incomplete ? 'true' : 'false' ?>;
	programYear = <?php echo $program_year ?>;
</script>
<!-- <?php var_dump($incomplete) ?> -->
<script src="<?php L('/assets/js/form.js') ?>"></script>

<nav class="form-page-nav below">
	<p class="prev"><a href="#_prev">&laquo; Halaman sebelumnya</a></p>
	<p class="next"><a href="#_next">Halaman berikutnya &raquo;</a></p>
</nav>

</div>

</form>
<br clear="all">

<?php endif;?>

</div>

<?php $this->footer(); ?>