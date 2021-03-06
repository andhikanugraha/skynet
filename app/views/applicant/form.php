<?php $this->header('Formulir Pendaftaran'); ?>
<?php if ($new) { ?><script>document.write('<style>.global-header, .content {display: none}</style>');</script><?php } ?>
<script src="<?php L('/assets/js/jquery-1.6.2.min.js') ?>"></script>
<?php if ($admin): ?>
<header class="page-title">
	<hgroup>
		<h1><a href="<?php L(array('controller' => 'chapter', 'action' => 'view', 'chapter_code' => $applicant->chapter->chapter_code)) ?>"><?php echo $this->user->chapter->get_title() ?></a></h1>
		<h2>Pengelolaan <?php echo $applicant->confirmed ? 'Peserta' : 'Pendaftar' ?></h2>
	</hgroup>
</header>
<nav class="actions-nav">
	<ul>
		<li><a href="<?php L($this->session->flash('applicant_back_to')) ?>" class="return">Kembali</a></li>
	</ul>
</nav>
<?php else: ?>
<header class="page-title">
	<hgroup>
		<h1>Tahap 3/4</h1>
		<h2>Formulir Pendaftaran</h2>
	</hgroup>
</header>
<nav class="actions-nav expleft">
	<ul>
		<li><a href="<?php L(array('action' => 'guide')) ?>">Panduan Pendaftaran</a></li>
		<li class="expires-on">Batas waktu pendaftaran: <span><?php echo $expires_on->format('l, j F Y') ?></span></li>
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

	<?php elseif ($upload_error): ?>
	<div class="message error">
		<header>
			<h1>Upload Foto Gagal</h1>
		</header>
		<p><?php
			switch ($upload_error) {
				case 'invalid_format':
					echo 'Format foto salah. Gunakan foto berbentuk JPG, PNG, atau GIF.';
					break;
				default:
					echo 'Cobalah sekali lagi.';
			}
		?></p>
		<p class="hide"><a href="#">Sembunyikan pesan ini</a></p>
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
		<script>console.log('Skynet: Finalization failed because the following fields were not filled in:', <?php echo json_encode ($incomplete) ?>)</script>
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
	<p class="save"><button type="submit">Simpan<?php if (!$admin): ?> Sementara<?php endif; ?></button></p>
	<p class="next"><a href="#_next">Halaman berikutnya &raquo;</a></p>
</nav>

<nav class="form-nav">
	<header>
		<h1>Pilih Halaman</h1>
	</header>
	<ol>
		<li><a href="#pribadi">Data Pribadi</a></li>
		<li><a href="#program">Pilihan Program</a></li>
		<li><a href="#countryprefs">Pilihan Negara</a></li>
		<li><a href="#keluarga">Keluarga</a></li>
		<li><a href="#pendidikan">Pendidikan</a></li>
		<li><a href="#kegiatan">Kegiatan</a></li>
		<li><a href="#persona">Kepribadian</a></li>
		<li><a href="#travel">Riwayat Perjalanan</a></li>
		<li><a href="#reference">Referensi</a></li>
		<li><a href="#rekomendasi">Rekomendasi</a></li>
		<li><a href="#foto">Foto</a></li>
		<?php if (!$readonly && !$admin): ?>
		<li class="finalize"><a href="#finalisasi">Finalisasi</a></li>
		<?php endif; ?>
	</ol>
</nav>

<div class="form-fields">

<!-- begin form -->

<fieldset class="pane" id="pribadi">
	<legend>Data Pribadi</legend>
	<table class="form-table">
		<!-- <tr>
			<td class="label"><?php $form->label('full_name', 'Nama Lengkap', 'required') ?></td>
			<td class="field"><?php $form->text('full_name', 'long'); ?> <span class="instruction">Isi sesuai dengan Akte Kelahiran.</span></td>
		</tr> -->
		<tr>
			<td class="label"><?php $form->label('first_name', 'Nama Depan', 'required') ?></td>
			<td class="field"><?php $form->text('first_name', 'medium'); ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('middle_name', 'Nama Tengah') ?></td>
			<td class="field"><?php $form->text('middle_name', 'medium'); ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('last_name', 'Nama Belakang') ?></td>
			<td class="field"><?php $form->text('last_name', 'medium'); ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('place_of_birth', 'Tempat dan Tanggal Lahir', 'required') ?></td>
			<td class="field">
				<?php $form->text('place_of_birth', 'medium') ?>
				<br>
				<?php $form->date('date_of_birth', 17, 15); ?>
				<br>
				<?php
				$this_year = (int) date('Y');
				$min = new HeliumDateTime;
				$min->setDate($this_year - 15, 4, 1);
				$max = new HeliumDateTime;
				$max->setDate($this_year - 17, 9, 1);
				?>
				<span class="instruction">Untuk mengikuti program pertukaran pelajar Bina Antarbudaya, Adik harus berusia antara 15 tahun hingga 16 tahun 8 bulan (lahir antara tanggal <?php echo $max->format('j F Y') ?> dan <?php echo str_replace(' ', '&nbsp;', $min->format('j F Y')) ?>)</span>
			</td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('applicant_email', 'Alamat E-mail', 'required') ?></td>
			<td class="field"><?php $form->email('applicant_email', 'long'); ?> <span class="instruction">Seluruh pengumuman mengenai seleksi akan dikirim ke alamat ini.</span></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('applicant_address_street', 'Alamat Lengkap', 'required') ?></td>
			<td class="field"><?php $form->address('applicant', true, true, true, true, true, true, false); ?> <span class="instruction">Isilah dengan lengkap agar tidak terjadi salah pengiriman surat.</span></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('sex', 'Jenis Kelamin', 'required') ?></td>
			<td class="field">
				<?php $form->radio('sex', 'F') ?> <label for="sex-F">Perempuan</label>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
				<?php $form->radio('sex', 'M') ?> <label for="sex-M">Laki-laki</label>
			</td>
		</tr>
		<tr>
			<td class="label"></td>
			<td class="field">
				<?php $form->label('body_height', 'Tinggi Badan', 'subpoint required') ?>
				<?php $form->number('body_height', 'very-short') ?> cm
				<br>
				<?php $form->label('body_weight', 'Berat Badan', 'subpoint required') ?>
				<?php $form->number('body_weight', 'very-short') ?> kg
				<br>
				<?php $form->label('blood_type', 'Gol. Darah', 'subpoint required') ?>
				<?php $form->select('blood_type', array('' => '',
					'O+' => 'O+', 'A+' => 'A+', 'B+' => 'B+', 'AB+' => 'AB+',
					'O-' => 'O-', 'A-' => 'A-', 'B-' => 'B-', 'AB-' => 'AB-'), 'very-short')?>
			</td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('citizenship', 'Kewarganegaraan', 'required') ?></td>
			<td class="field">
				<?php $form->text('citizenship', 'long') ?>
				<br>
				<span class="instruction">Contoh: Indonesia</span>
			</td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('religion', 'Agama', 'required') ?></td>
			<td class="field">
				<?php $form->text('religion', 'long') ?>
			</td>
		</tr>
	</table>
</fieldset>

<fieldset class="pane" id="program">
	<legend>Pilihan Program</legend>
	<!-- poin 20–26 -->
	<table class="programs-table">
		<colgroup width="20%">
		<colgroup width="40%">
		<colgroup width="40%">
		<tr class="program-name">
			<th class="label">Program</th>
			<td class="afs"><?php $form->checkbox('program_afs') ?> <?php $form->label('program_afs', 'AFS Year Program') ?></td>
			<td class="yes"><?php $form->checkbox('program_yes') ?> <?php $form->label('program_yes', 'Kennedy-Lugar YES') ?></td>
		</tr>
		<tr class="program-length">
			<th class="label">Lama Program</th>
			<td class="afs">
				<span class="length">11 bulan</span>
				<br>
				Agustus <?php echo $program_year - 1?> &ndash; Juni <?php echo $program_year ?> (AS, Eropa)
				<br>
				Maret <?php echo $program_year - 1?> &ndash; Februari <?php echo $program_year ?> (Jepang)
			</td>
			<td class="yes">
				<span class="length">11 bulan</span>
				<br>
				Agustus <?php echo $program_year - 1?> &ndash; Juni <?php echo $program_year ?>
			</td>
		</tr>
		<tr class="program-destination">
			<th class="label">Negara Tujuan</th>
			<td class="afs">
				<ul>
					<li>Amerika Serikat</li>
					<li>Belanda</li>
					<li>Belgia</li>
					<li>Perancis</li>
					<li>Jerman</li>
					<li>Italia</li>
					<li>Jepang</li>
					<li>Norwegia</li>
					<li>Swiss</li>
				</ul>
			</td>
			<td class="yes">
				<ul>
					<li>Amerika Serikat</li>
				</ul>
			</td>
		</tr>
		<tr class="program-info">
			<th class="label"></th>
			<td class="afs">
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
			</td>
			<td class="yes">
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
			</td>
		</tr>
	</table>	
</fieldset>

<fieldset class="pane" id="countryprefs">
	<legend>Pilihan Negara (AFS)</legend>
	<table class="form-table country-preference">
		<tr>
			<td class="label">Preferensi Negara</th>
			<td class="field" id="country-pref-td" colspan="2">
				<ol>
				<?php
				for ($i = 1; $i <= 10; $i++) {
					$name = 'country_preference_' . $i;
					$countries = array( '' => '(Pilih Negara)',
										'USA' => 'Amerika Serikat',
										'NED' => 'Belanda',
										'BFL' => 'Belgia (Flanders)',
										'BFR' => 'Belgia (Wallonia)',
										'FRA' => 'Perancis',
										'GER' => 'Jerman',
										'ITA' => 'Italia',
										'JPN' => 'Jepang',
										'NOR' => 'Norwegia',
										'SUI' => 'Swiss' );
					ksort($countries);
					echo "<li>";
					$form->select($name, $countries, 'medium-short countrypref');
					echo "</li>\n";
				}
				?>
				</ol>
				<span class="instruction">Pilih urutan negara tujuan yang Adik kehendaki <em>jika</em> Adik diterima di AFS Year Program.</span>
			</td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('country_preference_other', 'Pilihan Negara Lainnya') ?></td>
			<td class="field">
			<?php $form->text('country_preference_other', 'medium') ?>
			<br>
			<span class="instruction">Isilah dengan negara lain yang ingin Adik kunjungi untuk pertukaran pelajar di luar pilihan negara di atas, bila ada.</span>
			</td>
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
			<td class="label"><?php $form->label($n . '_full_name', "Nama Lengkap $parent", 'required') ?></td>
			<td class="field">
				<?php $form->text($n . '_full_name', 'long'); ?>
				<br>
				<span class="instruction">Isilah dengan nama lengkap. Apabila telah wafat, cantumkan (Alm).</span>
			</td>
		</tr>
		<tr>
			<td class="label"><?php $form->label($n . '_office_email', 'Alamat E-mail ' . $parent) ?></td>
			<td class="field"><?php $form->text($n . '_office_email', 'long') ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label($n . '_office_mobilephone', 'Nomor Ponsel ' . $parent) ?></td>
			<td class="field"><?php $form->tel($n . '_office_mobilephone', 'long') ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label($n . '_education', 'Pendidikan Terakhir ' . $parent) ?></td>
			<td class="field"><?php $form->text($n . '_education', 'long'); ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label($n . '_occupation', 'Pekerjaan/Jabatan ' . $parent) ?></td>
			<td class="field">
				<?php $form->text($n . '_occupation', 'long'); ?>
				<br>
				<span class="instruction long">Isilah dengan rinci &ndash; bila wiraswasta, cantumkan bidangnya; bila swasta, cantumkan nama perusahaannya.</span>
			</td>
		</tr>
		<tr>
			<td class="label"><?php $form->label($n . '_job_title', 'Pangkat/Golongan ' . $parent) ?></td>
			<td class="field">
				<?php $form->text($n . '_job_title', 'long'); ?>
				<br>
				<span class="instruction long">Isilah dengan rinci &ndash; bila TNI, cantumkan pangkatnya; bila PNS, cantumkan golongannya; bila swasta, cantumkan jabatannya.</span>
			</td>
		</tr>
		<tr>
			<td class="label"><?php $form->label($n . '_office_name', 'Instansi/Perusahaan ' . $parent) ?></td>
			<td class="field"><?php $form->text($n . '_office_name', 'long'); ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label($n . '_office_address_street', 'Alamat Kantor ' . $parent) ?></td>
			<td class="field">
				<?php $form->address($n . '_office', true, true, false, true, false, true, false) ?>
			</td>
		</tr>
	</table>
	<?php endforeach; ?>

	<h1>Wali <span>(apabila orang tua telah wafat atau Adik tinggal terpisah dengan orang tua)</span></h1>
	<table class="form-table">
		<tr>
			<td class="label"><?php $form->label('guardian_full_name', "Nama Lengkap Wali") ?></td>
			<td class="field">
				<?php $form->text('guardian_full_name', 'long'); ?>
				<br>
				<span class="instruction">Isilah dengan nama lengkap.</span>
			</td>
		</tr>
		
		<tr>
			<td class="label"><?php $form->label('guardian_relationship_to_applicant', 'Hubungan dengan Adik') ?></td>
			<td class="field"><?php $form->text('guardian_relationship_to_applicant', 'long'); ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('guardian_email', 'Alamat E-mail Wali') ?></td>
			<td class="field"><?php $form->text('guardian_email', 'long') ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('guardian_address_street', 'Alamat Wali') ?></td>
			<td class="field"><?php $form->address('guardian', true, true, false, true, true, false, false) ?></td>
		</tr>
		<!-- The field below is in the DB schema but not the original form -->
		<!-- <tr>
			<td class="label"><?php $form->label('guardian_education', 'Pendidikan Terakhir') ?></td>
			<td class="field"><?php $form->text('guardian_education', 'long'); ?></td>
		</tr> -->
		<tr>
			<td class="label"><?php $form->label('guardian_occupation', 'Pekerjaan/Jabatan Wali') ?></td>
			<td class="field">
				<?php $form->text('guardian_occupation', 'long'); ?>
				<br>
				<span class="instruction long">Isilah dengan rinci &ndash; bila wiraswasta, cantumkan bidangnya; bila swasta, cantumkan jabatan dan nama perusahaannya.</span>
			</td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('guardian_job_title', 'Pangkat/Golongan Wali') ?></td>
			<td class="field"><?php $form->text('guardian_job_title', 'long'); ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('guardian_office_name', 'Nama dan Alamat Kantor') ?></td>
			<td class="field">
				<?php $form->text('guardian_office_name', 'long'); ?>
				<br>
				<?php $form->address('guardian_office', true, true, false, true, false, true, false) ?>
			</td>
		</tr>
	</table>

	<h1>Saudara Kandung</h1>
	<table class="form-table siblings">
		<tr>
			<td class="label noc"><?php $form->label('number_of_children_in_family', 'Jumlah anak dalam keluarga', 'required') ?></td>
			<td class="field noc"><?php $form->number('number_of_children_in_family', 'very-short'); ?></td>
			<td class="label nth"><?php $form->label('nth_child', 'Adik anak nomor', 'required') ?></td>
			<td class="field nth"><?php $form->number('nth_child', 'very-short'); ?></td>
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
			foreach ($sibling_forms as $s):
			?>
			<tr class="notempty">
				<td class="sibling-name"><?php $s->text('full_name', 'short') ?></td>
				<td class="sibling-dob"><?php $s->date('date_of_birth', 50) ?></td>
				<td class="sibling-job"><?php $s->text('occupation', 'short') ?></td>
			</tr>
			<?php endforeach; ?>
			<?php for ($i = 0; $i < ($applicant->number_of_children_in_family - count($sibling_forms) - 1); $i++): $s = new FormDisplay; $s->make_subform('siblings[' . ($i + 1024) . ']') ?>
			<tr class="phpengineered">
				<td class="sibling-name"><?php $s->text('full_name', 'short') ?></td>
				<td class="sibling-dob"><?php $s->date('date_of_birth', 50) ?></td>
				<td class="sibling-job"><?php $s->text('occupation', 'short') ?></td>
			</tr>
			<?php endfor; $s = new FormDisplay; $s->make_subform('siblings[#]'); ?>
			<tr class="prototype">
				<td class="sibling-name"><?php $s->text('full_name', 'short') ?></td>
				<td class="sibling-dob"><?php $s->date('date_of_birth', 50) ?></td>
				<td class="sibling-job"><?php $s->text('occupation', 'short') ?></td>
			</tr>
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
			<td class="label"><?php $form->label('high_school_name', 'Nama Sekolah', 'required') ?></td>
			<td class="field">
				<?php $form->text('high_school_name', 'long'); ?><br>
				<span class="instruction">Cantumkan kota. Misal: SMA <u>Negeri</u> 70 <u>Bandung</u></span>
				<span class="instruction">Jika Adik pernah berpindah sekolah (mutasi), tuliskan secara berurutan nama masing-masing sekolah yang pernah Adik masuki dengan memisahkannya dengan garis miring (/).</span>	
			</td>
			<tr>
				<td class="label"></td>
				<td class="field">
					<?php $form->checkbox('in_pesantren') ?> Sekolah saya adalah Pesantren/Madrasah
				</td>
			</tr>
			<tr>
				<td class="label"><?php $form->label('high_school_address_street', 'Alamat Sekolah') ?></td>
				<td class="field"><?php $form->address('high_school', false, false, false, true, false, true, false); ?></td>
			</tr>
			<tr>
				<td class="label"><?php $form->label('high_school_admission_year', 'Tahun Masuk', 'required') ?></td>
				<td class="field"><?php $form->select_year('high_school_admission_year', date('Y') - 2, date('Y') - 1, false); ?></td>
			</tr>
			<tr>
				<td class="label"><?php $form->label('high_school_graduation_year', 'Tahun Keluar', 'required') ?></td>
				<td class="field"><?php $form->select_year('high_school_graduation_year', date('Y') + 1, date('Y') + 2); ?></td>
			</tr>
				<tr>
					<td class="label"></td>
					<td class="field">
						<?php $form->checkbox('in_acceleration_class') ?> Saya adalah siswa kelas Akselerasi
						<br>
						<span class="instruction">Program YES tidak tersedia bagi siswa kelas akselerasi.</span>
					</td>
				</tr>

		</tr>
	</table>
	
	<table class="academics sma subform">
		<caption>
			<?php $form->label('grades_y10t1_average', 'Data prestasi', 'required') ?>
			<span class="instruction">Gunakan skala 0&ndash;100 untuk rata-rata nilai, <em>atau</em> indeks abjad sesuai rapor asli bila tidak ada nilai numerik.</span>
		</caption>
		<thead>
			<tr>
				<th rowspan="2" class="grade">Kelas</th>
				<th colspan="2" class="term term-initial">Semester I</th>
			</tr>
			<tr>
				<th class="term-initial average">Rata-Rata Nilai</th>
				<th class="term-initial subjects">Jumlah Mata Pelajaran</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="grade">X</td>
				<td class="term-initial average"><?php $form->text('grades_y10t1_average', 'very-short l') ?></td>
				<td class="term-initial subjects"><?php $form->text('grades_y10t1_subjects', 'very-short r') ?></td>
			</tr>
		</tbody>
	</table>
	
	<h1>SMP/MTs</h1>
	<table class="form-table">
		<tr>
			<td class="label"><?php $form->label('junior_high_school_name', 'Nama Sekolah', 'required') ?></td>
			<td class="field">
				<?php $form->text('junior_high_school_name', 'long'); ?><br>
				<span class="instruction">Cantumkan kota. Misal: SMP <u>Negeri</u> 70 <u>Bandung</u></span>
				<span class="instruction">Jika Adik pernah berpindah sekolah (mutasi), tuliskan secara berurutan nama masing-masing sekolah yang pernah Adik masuki dengan memisahkannya dengan garis miring (/).</span>	
			</td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('junior_high_school_graduation_year', 'Tahun Ijazah', 'required') ?></td>
			<td class="field"><?php $form->select_year('junior_high_school_graduation_year', date('Y') - 2, date('Y') - 1); ?></td>
		</tr>
	</table>

	<table class="academics smp subform">
		<caption>
			<?php $form->label('grades_y7t1_average', 'Data prestasi', 'required') ?>
			<span class="instruction">Gunakan skala 0&ndash;100 untuk rata-rata nilai, <em>atau</em> indeks abjad sesuai rapor asli bila tidak ada nilai numerik.</span>
		</caption>
		<thead>
			<tr>
				<th rowspan="2" width="60" class="grade">Kelas</th>
				<th colspan="2" class="term term-initial">Semester I</th>
				<th colspan="2" class="term term-final">Semester II</th>
			</tr>
			<tr>
				<th class="term-initial average">Rata-Rata Nilai</th>
				<th class="term-initial subjects">Jumlah Mata Pelajaran</th>
				<th class="term-final average">Rata-Rata Nilai</th>
				<th class="term-final subjects">Jumlah Mata Pelajaran</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$grades = array(7 => 'VII', 8 => 'VIII', 9 => 'IX');
			foreach($grades as $i => $g): ?>
			<tr>
				<td class="grade"><?php echo $g; ?></td>
				<td class="term-initial average"><?php $form->text('grades_y' . $i . 't1_average', 'very-short l') ?></td>
				<td class="term-initial subjects"><?php $form->text('grades_y' . $i . 't1_subjects', 'very-short r') ?></td>
				<td class="term-final average"><?php $form->text('grades_y' . $i . 't2_average', 'very-short l') ?></td>
				<td class="term-final subjects"><?php $form->text('grades_y' . $i . 't2_subjects', 'very-short r') ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	
	<h1>SD/MI</h1>
	<table class="form-table">
		<tr>
			<td class="label"><?php $form->label('elementary_school_name', 'Nama Sekolah', 'required') ?></td>
			<td class="field">
				<?php $form->text('elementary_school_name', 'long'); ?><br>
				<span class="instruction">Cantumkan kota. Misal: SD <u>Negeri</u> 70 <u>Bandung</u></span>
				<span class="instruction">Jika Adik pernah berpindah sekolah (mutasi), tuliskan secara berurutan nama masing-masing sekolah yang pernah Adik masuki dengan memisahkannya dengan garis miring (/).</span>	
			</td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('elementary_school_graduation_year', 'Tahun Ijazah', 'required') ?></td>
			<td class="field"><?php $form->select_year('elementary_school_graduation_year', date('Y') - 5, date('Y') - 3); ?></td>
		</tr>
	</table>

	<table class="academics sd subform">
		<caption>
			<?php $form->label('grades_y1t1_average', 'Data prestasi', 'required') ?>
			<span class="instruction">Gunakan skala 0&ndash;100 untuk rata-rata nilai, <em>atau</em> indeks abjad sesuai rapor asli bila tidak ada nilai numerik.</span>
		</caption>
		<thead>
			<tr>
				<th rowspan="2" width="60" class="grade">Kelas</th>
				<th colspan="2" class="term term-initial">Semester I</th>
				<th colspan="2" class="term term-final">Semester II</th>
			</tr>
			<tr>
				<th class="term-initial average">Rata-Rata Nilai</th>
				<th class="term-initial subjects">Jumlah Mata Pelajaran</th>
				<th class="term-final average">Rata-Rata Nilai</th>
				<th class="term-final subjects">Jumlah Mata Pelajaran</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$grades = array(1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI');
			foreach($grades as $i => $g): ?>
			<tr>
				<td class="grade"><?php echo $g; ?></td>
				<td class="term-initial average"><?php $form->text('grades_y' . $i . 't1_average', 'very-short l') ?></td>
				<td class="term-initial subjects"><?php $form->text('grades_y' . $i . 't1_subjects', 'very-short r') ?></td>
				<td class="term-final average"><?php $form->text('grades_y' . $i . 't2_average', 'very-short l') ?></td>
				<td class="term-final subjects"><?php $form->text('grades_y' . $i . 't2_subjects', 'very-short r') ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<h1>Pengetahuan Bahasa</h1>
	<table class="form-table">
		<tr>
			<td class="label"><?php $form->label('years_speaking_english', 'Sudah berapa lama Adik belajar Bahasa Inggris?', 'required') ?></td>
			<td class="field"><?php $form->text('years_speaking_english', 'long') ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('other_languages', 'Bahasa lain yang Adik kuasai/pelajari') ?></td>
			<td class="field"><?php $form->text('other_languages', 'long') ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('years_speaking_other_languages', 'Berapa lama?') ?></td>
			<td class="field"><?php $form->text('years_speaking_other_languages', 'long') ?></td>
		</tr>
	</table>
	<h1>Pelajaran Favorit dan Cita-Cita</h1>
	<table class="form-table">
		<tr>
			<td class="label"><?php $form->label('favorite_subject', 'Mata pelajaran favorit', 'required') ?></td>
			<td class="field"><?php $form->text('favorite_subject', 'long') ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('dream', 'Cita-cita', 'required') ?></td>
			<td class="field"><?php $form->text('dream', 'long') ?></td>
		</tr>
	</table>
</fieldset>

<fieldset class="pane" id="kegiatan">
	<?php $levels = array(
		'' => '(Tingkat)',
		'school' => 'Sekolah',
		'neighborhood' => 'RT/RW',
		'district' => 'Kecamatan',
		'city' => 'Kabupaten/Kota',
		'province' => 'Provinsi',
		'national' => 'Nasional',
		'international' => 'Internasional'
	); ?>
	<legend>Kegiatan</legend>
	<!-- poin 15-19 -->
	<h1>Organisasi</h1>
	<table class="achievements subform">
		<caption>Organisasi yang pernah diikuti, baik di lingkungan sekolah maupun di luar lingkungan sekolah</caption>
		<thead>
			<tr>
				<th class="name">Nama Organisasi</th>
				<th class="kind">Jenis Kegiatan</th>
				<th class="level">Tingkat</th>
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
				<td class="level"><?php $s->select('level', array(
					'' => '(Tingkat)',
					'school' => 'Sekolah',
					'neighborhood' => 'RT/RW',
					'district' => 'Kecamatan',
					'city' => 'Kabupaten/Kota',
					'province' => 'Provinsi',
					'national' => 'Nasional',
					'international' => 'Internasional'
				), 'short')?></td>
				<td class="achv"><?php $s->text('position', 'short') ?></td>
				<td class="year"><?php $s->select_year('year', date('Y') - 12, date('Y')) ?></td>
			</tr>
			<?php endforeach; ?>
			<?php for($i=1; $i<=5; $i++):
			$s = new FormDisplay;
			$s->make_subform("applicant_organizations[$i]"); ?>
			<tr>
				<td class="name"><?php $s->text('name', 'short') ?></td>
				<td class="kind"><?php $s->text('kind', 'short') ?></td>
				<td class="level"><?php $s->select('level', array(
					'' => '(Tingkat)',
					'school' => 'Sekolah',
					'neighborhood' => 'RT/RW',
					'district' => 'Kecamatan',
					'city' => 'Kabupaten/Kota',
					'province' => 'Provinsi',
					'national' => 'Nasional',
					'international' => 'Internasional'
				), 'short')?></td>
				<td class="achv"><?php $s->text('position', 'short') ?></td>
				<td class="year"><?php $s->select_year('year', date('Y') - 12, date('Y')) ?></td>
			</tr>
			<?php endfor; ?>
		</tbody>
	</table>
	<h1>Kesenian <span>(seni suara, seni musik, tari, teater, dll.)</span></h1>
	<?php $phase = 'kesenian'; ?>
	<table class="form-table">
		<tr>
			<td class="label"><?php $form->label('arts_hobby', 'Sekedar hobi', 'required') ?></td>
			<td class="field"><?php $form->text('arts_hobby', 'long') ?></td>
		</tr>		
		<tr>
			<td class="label"><?php $form->label('arts_organized', 'Ikut perkumpulan') ?></td>
			<td class="field"><?php $form->text('arts_organized', 'long') ?></td>
		</tr>
	</table>

	<table class="achievements subform" width="620">
		<caption>Prestasi</caption>
		<thead>
			<tr>
				<th class="name">Jenis</th>
				<th class="kind">Kejuaraan</th>
				<th class="level">Tingkat</th>
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
				<td class="level"><?php $s->select('level', $levels, 'short')?></td>
				<td class="achv"><?php $s->text('achievement', 'short') ?></td>
				<td class="year"><?php $s->select_year('year', date('Y') - 12, date('Y')) ?></td>
			</tr>
			<?php endforeach; ?>
			<?php for($i=1; $i<=5; $i++):
			$s = new FormDisplay;
			$s->make_subform("applicant_arts_achievements[$i]"); ?>
			<tr>
				<td class="name"><?php $s->text('championship', 'short') ?></td>
				<td class="kind"><?php $s->text('kind', 'short') ?></td>
				<td class="level"><?php $s->select('level', $levels, 'short')?></td>
				<td class="achv"><?php $s->text('achievement', 'short') ?></td>
				<td class="year"><?php $s->select_year('year', date('Y') - 12, date('Y')) ?></td>
			</tr>
			<?php endfor; ?>
		</tbody>
	</table>

	<h1>Olahraga</h1>
	<?php $phase = 'olahraga'; ?>
	<table class="form-table">
		<tr>
			<td class="label"><?php $form->label('sports_hobby', 'Sekedar hobi', 'required') ?></td>
			<td class="field"><?php $form->text('sports_hobby', 'long') ?></td>
		</tr>		
		<tr>
			<td class="label"><?php $form->label('sports_organized', 'Ikut perkumpulan') ?></td>
			<td class="field"><?php $form->text('sports_organized', 'long') ?></td>
		</tr>
	</table>
	<table class="achievements subform" width="620">
		<caption>Prestasi</caption>
		<thead>
			<tr>
				<th class="chmp">Kejuaraan</th>
				<th class="level">Tingkat</th>
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
				<td class="level"><?php $s->select('level', $levels, 'short')?></td>
				<td class="achv"><?php $s->text('achievement', 'short') ?></td>
				<td class="year"><?php $s->select_year('year', date('Y') - 12, date('Y')) ?></td>
			</tr>
			<?php endforeach; ?>
			<?php for($i=1; $i<=5; $i++):
			$s = new FormDisplay;
			$s->make_subform("applicant_sports_achievements[$i]"); ?>
			<tr>
				<td class="chmp"><?php $s->text('championship', 'short') ?></td>
				<td class="level"><?php $s->select('level', $levels, 'short')?></td>
				<td class="achv"><?php $s->text('achievement', 'short') ?></td>
				<td class="year"><?php $s->select_year('year', date('Y') - 12, date('Y')) ?></td>
			</tr>
			<?php endfor; ?>
		</tbody>
	</table>

	<h1>Lain-lain</h1>
	<?php $phase = 'kegiatan_lain_lain'; ?>
	<table class="achievements subform">
		<caption>Kegiatan lain di luar olahraga dan kesenian</caption>
		<thead>
			<tr>
				<th class="chmp">Kegiatan</th>
				<th class="level">Tingkat</th>
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
				<td class="level"><?php $s->select('level', $levels, 'short')?></td>
				<td class="achv"><?php $s->text('achievement', 'short') ?></td>
				<td class="year"><?php $s->select_year('year', date('Y') - 12, date('Y')) ?></td>
			</tr>
			<?php endforeach; ?>
			<?php for($i=1; $i<=5; $i++):
			$s = new FormDisplay;
			$s->make_subform("applicant_other_achievements[$i]"); ?>
			<tr>
				<td class="chmp"><?php $s->text('activity', 'short') ?></td>
				<td class="level"><?php $s->select('level', $levels, 'short')?></td>
				<td class="achv"><?php $s->text('achievement', 'short') ?></td>
				<td class="year"><?php $s->select_year('year', date('Y') - 12, date('Y')) ?></td>
			</tr>
			<?php endfor; ?>
		</tbody>
	</table>

	<?php $phase = 'pengalaman_kerja'; ?>
	<table class="achievements subform">
		<caption>Pengalaman kerja sosial/magang/bekerja (di LSM, Yayasan, kantor, sekolah, koperasi, usaha, dll)</caption>
		<thead>
			<tr>
				<th class="ngo">Nama dan bidang tempat bekerja/magang</th>
				<th class="level">Tingkat</th>
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
				<td class="level"><?php $s->select('level', $levels, 'short')?></td>
				<td class="ngo"><?php $s->text('position', 'short') ?></td>
				<td class="period"><?php $form->text('period') ?></td>
			</tr>
			<?php endforeach; ?>
			<?php for($i=1; $i<=5; $i++):
			$s = new FormDisplay;
			$s->make_subform("applicant_work_experiences[$i]"); ?>
			<tr>
				<td class="ngo"><?php $s->text('organization', 'short') ?></td>
				<td class="level"><?php $s->select('level', $levels, 'short')?></td>
				<td class="ngo"><?php $s->text('position', 'short') ?></td>
				<td class="period"><?php $form->text('period') ?></td>
			</tr>
			<?php endfor; ?>
		</tbody>
	</table>
</fieldset>

<fieldset class="pane" id="travel">
	<legend>Riwayat Perjalanan</legend>
	<h1>Pernahkah Adik melawat/berpergian dalam jangka pendek ke luar negeri?</h1>
	<p class="single-bool"><?php $form->checkbox('short_term_travel_has') ?> <?php $form->label('short_term_travel_has', 'Pernah') ?></p>
	<table class="form-table" data-toggle="short_term_travel_has">
		<tr>
			<td class="label"><?php $form->label('short_term_travel_destination', 'Ke mana?') ?></td>
			<td class="field"><?php $form->text('short_term_travel_destination', 'long') ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('short_term_travel_when', 'Kapan?') ?></td>
			<td class="field"><?php $form->text('short_term_travel_when', 'long') ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('short_term_travel_purpose', 'Dalam rangka apa?') ?></td>
			<td class="field"><?php $form->text('short_term_travel_purpose', 'long') ?></td>
		</tr>
	</table>
	<h1>Pernahkah Adik tinggal di luar negeri?</h1>
	<p class="single-bool"><?php $form->checkbox('long_term_travel_has') ?> <?php $form->label('long_term_travel_has', 'Pernah') ?></p>
	<table class="form-table" data-toggle="long_term_travel_has">
		<tr>
			<td class="label"><?php $form->label('long_term_travel_destination', 'Ke mana?') ?></td>
			<td class="field"><?php $form->text('long_term_travel_destination', 'long') ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('long_term_travel_when', 'Kapan dan berapa lama?') ?></td>
			<td class="field"><?php $form->text('long_term_travel_when', 'long') ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('long_term_travel_purpose', 'Dalam rangka apa?') ?></td>
			<td class="field"><?php $form->text('long_term_travel_purpose', 'long') ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('long_term_travel_activities', 'Kegiatan Adik selama di sana?') ?></td>
			<td class="field"><?php $form->text('long_term_travel_activities', 'long') ?></td>
		</tr>
	</table>
</fieldset>

<fieldset class="pane" id="reference">
	<legend>Referensi</legend>
	<h1>Adakah di antara keluarga besar Adik yang pernah mengikuti program pertukaran yang diselenggarakan oleh Bina Antarbudaya/AFS?</h1>
	<p class="single-bool"><?php $form->checkbox('relative_returnee_exists') ?> <?php $form->label('relative_returnee_exists', 'Pernah') ?></p>
	<table class="form-table" data-toggle="relative_returnee_exists">
		<tr>
			<td class="label"><?php $form->label('relative_returnee_name', 'Nama') ?></td>
			<td class="field"><?php $form->text('relative_returnee_name', 'long') ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('relative_returnee_relationship', 'Hubungan dengan Adik') ?></td>
			<td class="field"><?php $form->text('relative_returnee_relationship', 'long') ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('relative_returnee_program', 'Program') ?></td>
			<td class="field">
			<?php $form->text('relative_returnee_program', 'medium');
			$form->select('relative_returnee_program_type', array(' ' => '', 'sending' => 'Sending', 'hosting' => 'Hosting'), 'short') ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('relative_returnee_destination', 'Tujuan (sending)/Asal (hosting)') ?></td>
			<td class="field"><?php $form->text('relative_returnee_destination', 'long')  ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('relative_returnee_address_street', 'Alamat sekarang') ?></td>
			<td class="field"><?php $form->address('relative_returnee', true, false, false, false, false, false, true) ?></td>
		</tr>
	</table>
	<h1>Pernahkah Adik atau keluarga Adik berpartisipasi dalam kegiatan Bina Antarbudaya/AFS sebelumnya?</h1>
	<p class="single-bool"><?php $form->checkbox('past_binabud_has') ?> <?php $form->label('past_binabud_has', 'Pernah') ?></p>
	<table class="form-table" data-toggle="past_binabud_has">
		<tr>
			<td class="label"><?php $form->label('past_binabud_activities_who', 'Nama') ?></td>
			<td class="field"><?php $form->text('past_binabud_activities_who', 'long')  ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('past_binabud_activities_relationship', 'Hubungan dengan Adik') ?></td>
			<td class="field"><?php $form->text('past_binabud_activities_relationship', 'long')  ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('past_binabud_activities', 'Kegiatan') ?></td>
			<td class="field"><?php $form->text('past_binabud_activities', 'long')  ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('past_binabud_activities_year', 'Tahun') ?></td>
			<td class="field"><?php $form->select_year('past_binabud_activities_year', date('Y') - 50, date('Y'))  ?></td>
		</tr>
	</table>
	
	<h1>Referensi</h1>
	<p class="field">
		<?php $form->label('referrer', 'Dari mana Adik mengetahui program kami?', 'required') ?><br>
		<?php $form->textarea('referrer');  ?>
	</p>
	<p class="field">
		<?php $form->label('motivation', 'Apa motivasi Adik mengikuti seleksi dan program Bina Antarbudaya?', 'required') ?><br>
		<?php $form->textarea('motivation', 'extra-large');  ?>
	</p>
	<p class="field">
		<?php $form->label('hopes', 'Apa yang diharapkan Adik dengan keikutsertaan Adik dalam seleksi dan program Bina Antarbudaya?', 'required') ?><br>
		<?php $form->textarea('hopes', 'extra-large');  ?>
	</p>
</fieldset>

<fieldset class="pane" id="rekomendasi">
	<legend>Rekomendasi</legend>
	<p>Sebutkan nama 3 (tiga) orang <u>di luar keluarga</u> Adik yang mengenal diri Adik secara pribadi untuk menuliskan surat rekomendasi bagi Adik. Diharapkan nama orang-orang tersebut tidak akan berganti pada saat Adik harus memintakan rekomendasi dari mereka. <i>Surat rekomendasi tidak perlu dikumpulkan pada saat pendaftaran seleksi.</i></p>
	<h1>Lingkungan sekolah (Guru atau Kepala Sekolah) <span>(berusia sekurang-kurangnya 21 tahun)</span></h1>
	<table class="form-table">
		<tr>
			<td class="label"><?php $form->label('recommendations_school_name', 'Nama', 'required') ?></td>
			<td class="field"><?php $form->text('recommendations_school_name', 'long') ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('recommendations_school_address', 'Alamat/Telepon', 'required') ?></td>
			<td class="field"><?php $form->textarea('recommendations_school_address') ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('recommendations_school_occupation', 'Pekerjaan', 'required') ?></td>
			<td class="field"><?php $form->text('recommendations_school_occupation', 'long') ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('recommendations_school_work_address', 'Alamat pekerjaan', 'required') ?></td>
			<td class="field"><?php $form->textarea('recommendations_school_work_address') ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('recommendations_school_relationship', 'Hubungan', 'required') ?></td>
			<td class="field"><?php $form->text('recommendations_school_relationship', 'long') ?></td>
		</tr>
	</table>
	<h1>Lingkungan rumah/organisasi di luar sekolah <span>(<strong>bukan keluarga,</strong> berusia sekurang-kurangnya 21 tahun)</span></h1>
	<table class="form-table">
		<tr>
			<td class="label"><?php $form->label('recommendations_nonschool_name', 'Nama', 'required') ?></td>
			<td class="field"><?php $form->text('recommendations_nonschool_name', 'long') ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('recommendations_nonschool_address', 'Alamat/Telepon', 'required') ?></td>
			<td class="field"><?php $form->textarea('recommendations_nonschool_address') ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('recommendations_nonschool_occupation', 'Pekerjaan', 'required') ?></td>
			<td class="field"><?php $form->text('recommendations_nonschool_occupation', 'long') ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('recommendations_nonschool_work_address', 'Alamat pekerjaan', 'required') ?></td>
			<td class="field"><?php $form->textarea('recommendations_nonschool_work_address') ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('recommendations_nonschool_relationship', 'Hubungan', 'required') ?></td>
			<td class="field"><?php $form->text('recommendations_nonschool_relationship', 'long') ?><br>
				<span class="instruction">Pastikan yang bersangkutan tidak memiliki hubungan keluarga dengan Adik.</span></td>
		</tr>
	</table>
	<h1>Teman dekat</h1>
	<table class="form-table">
		<tr>
			<td class="label"><?php $form->label('recommendations_close_friend_name', 'Nama', 'required') ?></td>
			<td class="field"><?php $form->text('recommendations_close_friend_name', 'long') ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('recommendations_close_friend_address', 'Alamat/Telepon', 'required') ?></td>
			<td class="field"><?php $form->textarea('recommendations_close_friend_address') ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('recommendations_close_friend_relationship', 'Hubungan', 'required') ?></td>
			<td class="field"><?php $form->text('recommendations_close_friend_relationship', 'long') ?></td>
		</tr>
	</table>
</fieldset>

<fieldset class="pane" id="persona">
	<legend>Kepribadian</legend>
	<p class="field">
		<?php $form->label('personality', 'Menurut Adik, seperti apakah sifat dan kepribadian adik?', 'required') ?>
		<br>
		<?php $form->textarea('personality', 'extra-large') ?>
	</p>
	<p class="field">
		<?php $form->label('strengths_and_weaknesses', 'Apakah kelebihan/kekurangan Adik?', 'required') ?>
		<br>
		<?php $form->textarea('strengths_and_weaknesses', 'extra-large') ?>
	</p>
	<p class="field">
		<?php $form->label('stressful_conditions', 'Hal-hal apakah yang sering membuat Adik merasa tertekan?', 'required') ?>
		<br>
		<?php $form->textarea('stressful_conditions', 'extra-large') ?>
	</p>
	<p class="field">
		<?php $form->label('biggest_life_problem', 'Masalah terberat apakah yang pernah Adik hadapi? Bagaimana Adik menyelesaikannya?', 'required') ?>
		<br>
		<?php $form->textarea('biggest_life_problem', 'extra-large') ?>
	</p>
	<p class="field">
		<?php $form->label('plans', 'Apakah rencana Adik berkaitan dengan pendidikan dan karir di masa depan?', 'required') ?>
		<br>
		<?php $form->textarea('plans', 'extra-large') ?>
	</p>
</fieldset>

<!-- end form -->

<fieldset class="pane" id="foto">
	<legend>Foto</legend>
	<?php if ($picture): ?>
	<div class="picture-container"><img src="<?php echo $picture->get_cropped_url(); ?>" width="300" height="400"></div>
	<?php endif; ?>
	<table class="form-table">
		<tr>
			<td class="label"><?php $form->label('picture', 'Unggah foto'  . ($picture ? ' baru' : ''),  ($picture ? '' : ' required')) ?></td>
			<td class="field">
				<input type="hidden" name="MAX_FILE_SIZE" value="2048000">
				<input type="file" name="picture" id="picture" class="medium">
				<br>
				<span class="instruction">Gunakan <strong>pas foto berwarna</strong>. Ukuran berkas maksimal 2MB. </span>
			</td>
		</tr>
		<tr>
			<td class="label"></td>
			<td class="field">
				<button type="submit">Unggah</button>
			</td>
		</tr>
	</table>
</fieldset>
<?php if (!$admin): ?>
<fieldset class="pane" id="finalisasi">
	<legend>Finalisasi</legend>
	<p>
		Untuk melanjutkan pendaftaran Adik, Adik perlu melakukan finalisasi. Setelah finalisasi, informasi pada formulir ini dikunci dan Adik tidak dapat mengubahnya kembali. Oleh sebab itu, <em>pastikan seluruh kolom pada formulir ini telah terisi dengan lengkap dan benar sebelum melakukan finalisasi</em>. Kelalaian dalam mengisi formulir akan mengakibatkan penolakan pengumpulan berkas.
	</p>
	<p>
		Dengan finalisasi, Adik juga menyatakan bahwa seluruh informasi yang Adik isi dalam formulir ini adalah benar dan apa adanya, serta dibuat tanpa paksaan dari pihak manapun.
	</p>
	<p class="recheck">
		Adik belum dapat melakukan finalisasi karena Adik belum mengisi formulir dengan lengkap. Lengkapi bagian-bagian  formulir yang ditandai dengan warna merah sebelum kembali ke laman ini. Adik masih bisa menyimpan sementara formulir ini jika Adik perlu.
	</p>
	<p class="recheck">
		Tekan tombol 'Simpan Sementara' untuk menonaktifkan penandaan kolom-kolom.
	</p>
	<p class="finalize-checkbox">
		<input type="checkbox" name="finalize" value="true" id="finalize"> <label for="finalize"><strong>Saya mengerti.</strong></label>
	</p>
	<p data-toggle="finalize">
		<button type="submit" id="finalize-button">Finalisasi</button>
	</p>
</fieldset>
<?php endif; ?>

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
	<p class="save"><input type="hidden" name="last_pane" id="lastpane" value="#pribadi"><button type="submit">Simpan<?php if (!$admin): ?> Sementara<?php endif; ?></button></p>
	<p class="next"><a href="#_next">Halaman berikutnya &raquo;</a></p>
</nav>

</div>

</form>
<br clear="all">

<?php endif;?>

</div>

<?php $this->footer(); ?>