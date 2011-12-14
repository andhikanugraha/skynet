<?php $this->header('Formulir Pendaftaran'); ?>
<header class="stage-title">
	<?php if ($admin): ?>
	<h1>Administrasi</h1>
	<h2><?php echo $readonly ? 'Lihat' : 'Edit' ?> Formulir Peserta</h2>
	<?php else: ?>
	<h1>Tahap 3/5</h1>
	<h2>Formulir Pendaftaran</h2>
	<?php endif; ?>
</header>

<div class="main-form">

<form action="<?php L($this->params) ?>" enctype="multipart/form-data" method="POST">

<nav class="form-nav">
	<ul>
		<li><a href="#pribadi">Data Pribadi</a></li>
		<li><a href="#program">Pilihan Program</a></li>
		<li><a href="#foto">Foto</a></li>
		<li><a href="#keluarga">Keluarga</a></li>
		<li><a href="#pendidikan">Pendidikan</a></li>
		<li><a href="#kegiatan">Kegiatan</a></li>
		<li><a href="#kepribadian">Kepribadian</a></li>
		<li><a href="#referensi">Referensi</a></li>
		<li><a href="#rekomendasi">Rekomendasi</a></li>
		<?php if (!$readonly): ?>
		<li><strong><a href="#finalisasi">Finalisasi</a></strong></li>
		<?php endif; ?>
	</ul>
</nav>

<div class="form-fields">

<script>document.write('<style>.notice {display: none}</style>');</script>

<?php if (!$readonly): ?>
<p class="save-button">	
	<button type="submit">Simpan<?php if (!$admin): ?> Sementara<?php endif; ?></button>
</p>
<?php endif; ?>

<?php if ($new): ?>
<div class="notice">
	<h1>Selamat datang di Formulir Pendaftaran</h1>
	<p>Formulir ini terdiri atas <strong>sembilan bagian</strong> yang dapat diakses melalui tautan pada menu di sebelah kiri. Isilah seluruh formulir ini dengan <strong>lengkap</strong> dan <strong>teliti</strong>. Gunakan tombol <em>Simpan Sementara</em> di sebelah kanan atas ini untuk menyimpan sementara isian formulir untuk diisi kembali.</p>
	<?php if (!$admin): ?><p>Setelah Adik selesai mengisi <strong>seluruh</strong> formulir ini, klik 'Finalisasi' di menu sebelah kiri. Ingat, waktu Adik hanya sampai tanggal <strong><?php echo $this->applicant->expires_on->format('d/m/Y'); ?></strong>.</p><?php endif; ?>
	<p><a href="#" onclick="$(this.parentNode.parentNode).slideUp()">Sembunyikan pesan ini</a></p>
</div>

<?php elseif ($errors): ?>
<div class="notice errors">
	<p><strong>Finalisasi gagal karena:</strong></p>
	<ul>
		<?php foreach ($errors as $error): ?>
		<li><?php echo $error; ?></li>
		<?php endforeach; ?>
	</ul>
	<p><a href="#" onclick="$(this.parentNode.parentNode).slideUp()">Sembunyikan pesan ini</a></p>
</div>

<?php elseif ($notice): ?>
<div class="notice">
	<p><?php echo $notice; ?></p>
	<p><a href="#" onclick="$(this.parentNode.parentNode).slideUp()">Sembunyikan pesan ini</a></p>
</div>

<?php endif; ?>

<!-- begin form -->

<fieldset class="pane" id="pribadi">
	<legend>Data Pribadi</legend>
	<ol>
		<li>
			<label for="full_name" class="main-point">Nama Lengkap</label>
			<?php $form->text('full_name', 'long'); ?>
			<br>
			<span class="instruction">Isi sesuai dengan Akte Kelahiran</span>
		</li>
		<li>
			<?php ?>
			<label for="alamat_lengkap" class="main-point">Alamat Lengkap</label>
			<?php $form->address('applicant'); ?>
			<br>
			<span class="instruction">Isilah dengan lengkap agar tidak terjadi salah pengiriman surat.</span>
		</li>
		<li>
			<label for="ttl.kota" class="main-point">Tempat &amp; Tanggal Lahir</label>
			<?php $form->text('place_of_birth', 'medium') ?>
			<br>
			<?php $form->date('date_of_birth'); ?>
			<br>
			<!-- <span class="instruction">Untuk menjadi peserta Seleksi Bina Antarbudaya YP 2012-2013, tanggal kelahiran Adik harus di antara 1 September 1994 dan 1 April 1996.</span> -->
		</li>
		<li>
			<label for="jenis_kelamin" class="main-point">Jenis Kelamin</label>
			<?php
			$form->select('sex', array( 'F' => 'Perempuan', 'M' => 'Laki-laki'), 'medium-short')
			?>
		</li>
		<li>
			<label for="body_height" class="subpoint">Tinggi Badan</label>
			<?php $form->number('body_height', 'very-short') ?> cm
			<br>
			<label for="body_weight" class="subpoint">Berat Badan</label>
			<?php $form->number('body_weight', 'very-short') ?> kg
			<br>
			<label for="blood_type" class="subpoint">Golongan Darah</label>
			<?php $form->select('blood_type', array('O' => 'O', 'A' => 'A', 'B' => 'B', 'AB' => 'AB'), 'very-short')?>
		</li>
		<li>
			<label for="kewarganegaraan" class="main-point">Kewarganegaraan</label>
			<?php $form->text('kewarganegaraan', 'medium') ?>
			<br>
			<span class="instruction">Contoh: Indonesia</span>
		</li>
		<li>
			<label for="agama" class="main-point">Agama</label>
			<?php $form->text('agama', 'medium') ?>
		</li>		
		<li>
			<label class="main-point">Apakah Adik menggunakan nomor Telkomsel (Halo/Simpati/As)?</label>
			<?php $form->select('telkomsel_menggunakan', array('Tidak', 'Ya'), 'short'); ?>
		</li>
		<li>
			<label class="main-point">Apakah Adik anggota Telkomsel School Community?</label>
			<?php $form->select('telkomsel_school_community', array('Tidak', 'Ya'), 'short'); ?>
		</li>
	</ol>
</fieldset>

<fieldset class="pane" id="program">
	<legend>Pilihan Program</legend>
	<!-- poin 20–26 -->
	<ol>
		<li>
			<label class="main-point">Year Program (1 tahun)</label>
			<?php $form->checkbox('program_afs', 'afs') ?> AFS
			<br>
			<?php $form->checkbox('program_yes', 'yes') ?> YES
			<br>
			<?php $form->checkbox('program_jenesys', 'jenesys') ?> JENESYS
			<br>
			<span class="instruction">Minimal satu program; maksimal ketiga-tiganya.</span>
		</li>
	</ol>
</fieldset>

<fieldset class="pane" id="keluarga">
	<!-- poin 9–11 -->
	<legend>Keluarga</legend>
	<?php

	foreach(array('Ayah', 'Ibu') as $parent):
		$n = strtolower($parent);
	?>
	<h1><?php echo $parent; ?></h1>
	<ol>
		<li>
			<label for="<?php echo $n; ?>_full_name" class="main-point">Nama Lengkap <?php echo $parent; ?></label>
			<?php $form->text($n . '_full_name', 'long'); ?>
			<br>
			<span class="instruction">Isilah dengan nama lengkap.</span>
		</li>
		<li>
			<label for="<?php echo $n; ?>_education" class="main-point">Pendidikan Terakhir</label>
			<?php $form->text($n . '_education', 'medium') ?>
		</li>
		<li>
			<label for="<?php echo $n; ?>_occupation" class="main-point">Pekerjaan/Jabatan</label>
			<?php $form->text($n . '_occupation', 'medium') ?>
			<br>
			<span class="instruction">Isilah dengan rinci &ndash; bila wiraswasta, cantumkan bidangnya; bila swasta, cantumkan jabatan dan nama perusahaannya.</span>
		</li>
		<li>
			<label for="<?php echo $n; ?>_job_title" class="main-point">Pangkat/Golongan</label>
			<?php $form->text($n . '_job_title', 'medium') ?>
		</li>
		<li>
			<label for="<?php echo $n; ?>_office_name" class="main-point">Nama &amp; Alamat Kantor</label>
			<?php $form->text($n . '_office_name', 'long'); ?>
			<?php $form->address($n . '_office', false, false, false, true, true, true, true) ?>
		</li>
	</ol>
	<?php endforeach; ?>

	<h1>Wali <span>(bilamana orang tua telah wafat atau siswa tinggal terpisah dengan orang tua)</h1>
	<ol>
		<li>
			<label for="guardian_full_name" class="main-point">Nama Lengkap Wali</label>
			<?php $form->text('nama_lengkap_wali', 'long'); ?>
			<br>
			<span class="instruction">Isilah dengan nama lengkap.</span>
		</li>
		<li>
			<label for="guardian_hubungan_dengan" class="main-point">Hubungan dengan Adik</label>
			<?php $form->text('hubungan_dengan_wali', 'long'); ?>
		</li>
		<li>
			<label for="guardian_alamat_lengkap" class="main-point">Alamat Lengkap</label>
			<?php $form->address('alamat_lengkap_wali', false, false, false, true, true, false, false) ?>
		</li>
		<li>
			<label for="guardian_occupation" class="main-point">Pekerjaan/Jabatan</label>
			<?php $form->text('pekerjaan_wali', 'medium'); ?>
			<br>
			<span class="instruction">Isilah dengan rinci &ndash; bila wiraswasta, cantumkan bidangnya; bila swasta, cantumkan jabatan dan nama perusahaannya.</span>
		</li>
		<li>
			<label for="guardian_job_title" class="main-point">Pangkat/Golongan</label>
			<?php $form->text('pangkat_wali', 'medium'); ?>
		</li>
		<li>
			<label for="guardian_office_name" class="main-point">Nama &amp; Alamat Kantor</label>
			<?php $form->text('nama_kantor_wali', 'long'); ?>
			<?php $form->address('alamat_kantor_wali', false, false, false, true, false, true, true) ?>
		</li>
	</ol>
	<h1>Saudara</h1>
	<ol>
		<li>
			<label for="number_of_children_in_family" class="main-point">Jumlah anak dalam keluarga</label>
			<?php $form->text('number_of_children_in_family', 'very-short'); ?>
			<label for="nth_child" class="inline-point">Adik anak nomor</label>
			<?php $form->text('nth_child', 'very-short'); ?>
		</li>
		<li>
			<label for="saudara-1--nama" class="main-point">Nama, umur, &amp; sekolah/pekerjaan saudara kandung Adik: (termasuk Adik)</label>
			<table class="siblings">
				<thead>
					<tr>
						<th width="80">Urutan lahir</th>
						<th width="160">Nama Lengkap</th>
						<th width="220">Tanggal Lahir</th>
						<th width="160">Sekolah/Pekerjaan</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach (array('Pertama', 'Kedua', 'Ketiga', 'Keempat', 'Kelima', 'Keenam', 'Ketujuh') as $no => $ke):
						$no++;
					?>
					<tr>
						<td><?php echo $ke; ?></td>
						<td><?php $form->text('saudara[' . $no . '][nama]', 'short') ?></td>
						<td><?php $form->date('saudara[' . $no . '][ttl]', true) ?></td>
						<td><?php $form->text('saudara[' . $no . '][pendidikan]', 'short') ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</li>
	</ol>
</fieldset>

<fieldset class="pane" id="pendidikan">
	<legend>Pendidikan</legend>
	<!-- <p>Seluruh kolom pada halaman ini <strong>wajib diisi</strong>.</p> -->
	<!-- poin 12–14 -->
	<h1>SD/MI</h1>
	<ol>
		<li>
			<label for="pendidikan_sd_nama_sekolah" class="main-point">Nama Sekolah</label>
			<?php $form->text('pendidikan_sd_nama_sekolah', 'long'); ?>
			<br>
			<span class="instruction">Cantumkan kota. Misal: SD Bina Antarbudaya <u>Bandung</u></span>
			<span class="instruction">Jika Adik pernah berpindah sekolah (mutasi), tuliskan secara berurutan nama masing-masing sekolah yang pernah Adik masuki dengan memisahkannya dengan garis miring (/).</span>
		</li>
		<li>
			<label for="pendidikan_sd-tahun_ijazah" class="main-point">Tahun Ijazah</label>
			<?php $form->select_year('pendidikan_sd_tahun_ijazah', 2009, 2005); ?>
		</li>
		<li>
			<label class="main-point">Data Prestasi <strong>(wajib diisi seluruhnya)</strong></label>
			<table class="academics sd">
				<thead>
					<tr>
						<th rowspan="2" width="60" class="grade">Kelas</th>
						<th width="180" class="term-first">Cawu/Semester I</th>
						<th width="180" class="term-middle">Cawu/Semester II</th>
						<th width="180" class="term-final">Cawu III</th>
					</tr>
					<tr>
						<th colspan="3">Ranking ke ... dari ... siswa <strong>atau Rata-Rata Nilai (jika tidak ada ranking)</strong></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$phase = 'sd';
					$grades = explode(' ', 'I II III IV V VI');
					foreach($grades as $i => $g): $i++; ?>
					<tr>
						<td class="grade"><?php echo $g; ?></td>
						<td class="term-first">
							<?php $form->number('pendidikan_' . $phase . '_prestasi[' . $i . '][i][rank]', 'very-short l') ?> /
							<?php $form->number('pendidikan_' . $phase . '_prestasi[' . $i . '][i][total]', 'very-short r') ?>
						</td>
						<td class="term-middle">
							<?php $form->number('pendidikan_' . $phase . '_prestasi[' . $i . '][ii][rank]', 'very-short l') ?> /
							<?php $form->number('pendidikan_' . $phase . '_prestasi[' . $i . '][ii][total]', 'very-short r') ?>
						</td>
						<td class="term-final">
							<?php $form->number('pendidikan_' . $phase . '_prestasi[' . $i . '][iii][rank]', 'very-short l') ?> /
							<?php $form->number('pendidikan_' . $phase . '_prestasi[' . $i . '][iii][total]', 'very-short r') ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</li>
	</ol>
	<h1>SMP/MTs</h1>
	<ol>
		<li>
			<label for="pendidikan_smp_nama_sekolah" class="main-point">Nama Sekolah</label>
			<?php $form->text('pendidikan_smp_nama_sekolah', 'long'); ?>
			<br>
			<span class="instruction">Cantumkan kota. Misal: SMP <u>Negeri</u> 70 <u>Bandung</u>.</span>
			<span class="instruction">Jika Adik pernah berpindah sekolah (mutasi), tuliskan secara berurutan nama masing-masing sekolah yang pernah Adik masuki dengan memisahkannya dengan garis miring (/).</span>			
		</li>
		<li>
			<label for="pendidikan_smp-tahun_ijazah" class="main-point">Tahun Ijazah</label>
			<?php $form->select_year('pendidikan_smp_tahun_ijazah', 2010, 2008); ?>
		</li>
		<li>
			<label class="main-point">Data Prestasi <strong>(wajib diisi seluruhnya)</strong></label>
			<table class="academics smp">
				<thead>
					<tr>
						<th rowspan="2" width="60" class="grade">Kelas</th>
						<th width="270" class="term-first">Semester I</th>
						<th width="270" class="term-final">Semester II</th>
					</tr>
					<tr>
						<th colspan="3">Ranking ke ... dari ... siswa <strong>atau Rata-Rata Nilai (jika tidak ada ranking)</strong></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$phase = 'smp';
					$grades = explode(' ', 'VII VIII IX');
					foreach($grades as $i => $g): $i++; ?>
					<tr>
						<td class="grade"><?php echo $g; ?></td>
						<td class="term-first">
							<?php $form->number('pendidikan_' . $phase . '_prestasi[' . $i . '][i][rank]', 'very-short l') ?> /
							<?php $form->number('pendidikan_' . $phase . '_prestasi[' . $i . '][i][total]', 'very-short r') ?>
						</td>
						<td class="term-final">
							<?php $form->number('pendidikan_' . $phase . '_prestasi[' . $i . '][ii][rank]', 'very-short l') ?> /
							<?php $form->number('pendidikan_' . $phase . '_prestasi[' . $i . '][ii][total]', 'very-short r') ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</li>
	</ol>
	<h1>SMA/SMK/MA</h1>
	<ol>
		<li>
			<label for="pendidikan_sma_nama_sekolah" class="main-point">Nama Sekolah</label>
			<?php $form->text('pendidikan_sma_nama_sekolah', 'long'); ?><br>
			<span class="instruction">Cantumkan kota. Misal: SMA <u>Negeri</u> 70 <u>Bandung</u></span>
			<span class="instruction">Jika Adik pernah berpindah sekolah (mutasi), tuliskan secara berurutan nama masing-masing sekolah yang pernah Adik masuki dengan memisahkannya dengan garis miring (/).</span>	
		</li>
		<li>
			<?php $form->checkbox('pesantren', 'pesantren') ?> Sekolah saya adalah Pesantren/Madrasah
		</li>
		<li>
			<?php $form->checkbox('akselerasi', 'akselerasi') ?> Saya adalah siswa kelas Akselerasi
			<br>
			<span class="instruction"><strong>Jika iya</strong>, pastikan kamu mengisi Surat Pernyataan Siswa Akselerasi yang dapat diunduh di halaman <a href="<?php L(array('controller'=>'applicant', 'action'=>'guide')); ?>">Panduan</a>.</span>
		</li>
		<li>
			<label for="pendidikan_sma-alamat_sekolah-alamat" class="main-point">Alamat Sekolah</label>
			<?php $form->address('pendidikan_sma_alamat_sekolah', false, false, false, $telepon = true, $hp = false, $fax = true, $email = false); ?>
		</li>
		<li>
			<label for="pendidikan_sma_nama_kepala_sekolah" class="main-point">Nama Kepala Sekolah</label>
			<?php $form->text('pendidikan_sma_nama_kepala_sekolah', 'medium'); ?>
		</li>
		<li>
			<label for="pendidikan_sma-tahun_masuk" class="main-point">Masuk SLTA tahun</label>
			<?php $form->select_year('pendidikan_sma_tahun_masuk', 2010, 2008); ?>
		</li>
		<li>
			<label for="pendidikan_sma-bulan_keluar" class="main-point">Akan menamatkan SLTA bulan</label>
			<?php
			$form->select_month('pendidikan_sma_bulan_keluar');
			?>
			<label for="pendidikan_sma-tahun_keluar" class="inline-point">tahun</label>
			<?php $form->select_year('pendidikan_sma_tahun_keluar', 2014, 2011); ?>
		</li>
		<li>
			<label class="main-point">Data Prestasi <strong>(wajib diisi seluruhnya)</strong></label>
			<table class="academics sma">
				<thead>
					<tr>
						<th rowspan="2" width="60" class="grade">Kelas</th>
						<th width="270" class="term-first">Semester I</th>
						<th width="270" class="term-final">Semester II</th>
					</tr>
					<tr>
						<th colspan="3">Ranking ke ... dari ... siswa <strong>atau Rata-Rata Nilai (jika tidak ada ranking)</strong></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$phase = 'sma';
					$grades = explode(' ', 'X');
					foreach($grades as $i => $g): $i++; ?>
					<tr>
						<td class="grade"><?php echo $g; ?></td>
						<td class="term-first">
							<?php $form->number('pendidikan_' . $phase . '_prestasi[' . $i . '][i][rank]', 'very-short l') ?> /
							<?php $form->number('pendidikan_' . $phase . '_prestasi[' . $i . '][i][total]', 'very-short r') ?>
						</td>
						<!-- <td class="term-final">
													<?php $form->number('pendidikan_' . $phase . '_prestasi[' . $i . '][ii][rank]', 'very-short l') ?> /
													<?php $form->number('pendidikan_' . $phase . '_prestasi[' . $i . '][ii][total]', 'very-short r') ?>
												</td> -->
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</li>
	</ol>
	<h1>Pengetahuan Bahasa</h1>
	<ol>
		<li>
			<label class="main-point" for="pengetahuan_bahasa_inggris_berapa_lama">Sudah berapa lama Adik belajar Bahasa Inggris?</label>
			<?php $form->text('pengetahuan_bahasa_inggris_berapa_lama', 'short') ?>
		</li>
		<li>
			<label class="main-point" for="pengetahuan_bahasa_lain_apa">Bahasa lain yang Adik kuasai/pelajari</label>
			<?php $form->text('pengetahuan_bahasa_lain_apa', 'short') ?>
			<label class="inline-point" for="pengetahuan_bahasa_lain_berapa_lama">Berapa lama?</label>
			<?php $form->text('pengetahuan_bahasa_lain_berapa_lama', 'short') ?>
		</li>
	</ol>
	<h1>Pelajaran Favorit &amp; Cita-Cita</h1>
	<ol>
		<li>
			<label class="main-point" for="mata_pelajaran_favorit">Mata pelajaran favorit</label>
			<?php $form->text('mata_pelajaran_favorit', 'medium') ?>
		</li>
		<li>
			<label class="main-point" for="cita_cita">Cita-cita</label>
			<?php $form->text('cita_cita', 'medium') ?>
		</li>
	</ol>
</fieldset>

<fieldset class="pane" id="kegiatan">
	<legend>Kegiatan</legend>
	<!-- poin 15-19 -->
	<h1>Organisasi</h1>
	<ol>
		<li>
			<label class="main-point">Organisasi yang pernah diikuti, baik di lingkungan sekolah maupun di luar lingkungan sekolah</label>
			<table class="activities" width="620">
				<thead>
					<tr>
						<th width="20">No</th>
						<th width="180">Nama Organisasi</th>
						<th width="180">Jenis Kegiatan</th>
						<th width="180">Jabatan</th>
						<th width="40">Tahun</th>
					</tr>
				</thead>
				<tbody>
					<?php for($i=1; $i<=7; $i++): ?>
					<tr>
						<td><?php echo $i ?></td>
						<td><?php $form->text('organisasi[' . $i . '][nama_organisasi]', 'short') ?></td>
						<td><?php $form->text('organisasi[' . $i . '][jenis_kegiatan]', 'short') ?></td>
						<td><?php $form->text('organisasi[' . $i . '][jabatan]', 'short') ?></td>
						<td><?php $form->select_year('organisasi[' . $i . '][tahun]', 2011, 1996) ?></td>
					</tr>
					<?php endfor; ?>
				</tbody>
			</table>
		</li>
	</ol>
	<h1>Kesenian <span>(seni suara, seni musik, tari, teater, dll.)</span></h1>
	<?php $phase = 'kesenian'; ?>
	<ol>
		<li>
			<label class="main-point" for="<?php echo $phase ?>_sekedar_hobi">Sekedar hobi</label>
			<?php $form->text($phase . '_sekedar_hobi', 'long') ?>
		</li>		
		<li>
			<label class="main-point" for="<?php echo $phase ?>_ikut_perkumpulan">Ikut perkumpulan</label>
			<?php $form->text($phase . '_ikut_perkumpulan', 'long') ?>
		</li>
		<li>
			<label class="main-point">Prestasi</label>
			<table class="activities" width="620">
				<thead>
					<tr>
						<th width="20">No</th>
						<th width="180">Jenis</th>
						<th width="180">Kejuaraan</th>
						<th width="180">Prestasi</th>
						<th width="40">Tahun</th>
					</tr>
				</thead>
				<tbody>
					<?php for($i=1; $i<=7; $i++): ?>
					<tr>
						<td><?php echo $i ?></td>
						<td><?php $form->text($phase . '_prestasi[' . $i . '][jenis]', 'short') ?></td>
						<td><?php $form->text($phase . '_prestasi[' . $i . '][kejuaraan]', 'short') ?></td>
						<td><?php $form->text($phase . '_prestasi[' . $i . '][prestasi]', 'short') ?></td>
						<td><?php $form->select_year($phase . '_prestasi[' . $i . '][tahun]', 2011, 1996) ?></td>
					</tr>
					<?php endfor; ?>
				</tbody>
			</table>
		</li>
	</ol>
	<h1>Olahraga</h1>
	<?php $phase = 'olahraga'; ?>
	<ol>
		<li>
			<label class="main-point" for="<?php echo $phase ?>_sekedar_hobi">Sekedar hobi</label>
			<?php $form->text($phase . '_sekedar_hobi', 'long') ?>
		</li>		
		<li>
			<label class="main-point" for="<?php echo $phase ?>_ikut_perkumpulan">Ikut perkumpulan</label>
			<?php $form->text($phase . '_ikut_perkumpulan', 'long') ?>
		</li>
		<li>
			<label class="main-point">Prestasi</label>
			<table class="activities" width="620">
				<thead>
					<tr>
						<th width="20">No</th>
						<th width="360">Kejuaraan</th>
						<th width="180">Pencapaian</th>
						<th width="40">Tahun</th>
					</tr>
				</thead>
				<tbody>
					<?php for($i=1; $i<=7; $i++): ?>
					<tr>
						<td><?php echo $i ?></td>
						<td><?php $form->text($phase . '_prestasi[' . $i . '][kejuaraan]', 'medium') ?></td>
						<td><?php $form->text($phase . '_prestasi[' . $i . '][pencapaian]', 'short') ?></td>
						<td><?php $form->select_year($phase . '_prestasi[' . $i . '][tahun]', 2011, 1996) ?></td>
					</tr>
					<?php endfor; ?>
				</tbody>
			</table>
		</li>
	</ol>
	<?php $phase = 'kegiatan_lain_lain'; ?>
	<ol>
		<li>
			<label class="main-point">Kegiatan lain di luar olahraga dan kesenian</label>
			<table class="activities" width="620">
				<thead>
					<tr>
						<th width="20">No</th>
						<th width="360">Kegiatan</th>
						<th width="180">Prestasi</th>
						<th width="40">Tahun</th>
					</tr>
				</thead>
				<tbody>
					<?php for($i=1; $i<=7; $i++): ?>
					<tr>
						<td><?php echo $i ?></td>
						<td><?php $form->text($phase . '[' . $i . '][kegiatan]', 'medium') ?></td>
						<td><?php $form->text($phase . '[' . $i . '][prestasi]', 'short') ?></td>
						<td><?php $form->select_year($phase . '[' . $i . '][tahun]', 2011, 1996) ?></td>
					</tr>
					<?php endfor; ?>
				</tbody>
			</table>
		</li>
		<?php $phase = 'pengalaman_kerja'; ?>
		<li>
			<label class="main-point">Pengalaman kerja sosial/magang/bekerja (di LSM, Yayasan, kantor, sekolah, koperasi, usaha, dll)</label>
			<table class="activities" width="620">
				<thead>
					<tr>
						<th width="20">No</th>
						<th width="240">Nama dan bidang tempat bekerja/magang</th>
						<th width="240">Tugas dan tanggung jawab yang dijalankan</th>
						<th width="100">Tahun dan lama&nbsp;bekerja</th>
					</tr>
				</thead>
				<tbody>
					<?php for($i=1; $i<=7; $i++): ?>
					<tr>
						<td><?php echo $i ?></td>
						<td><?php $form->text($phase . '[' . $i . '][kegiatan]', 'institution') ?></td>
						<td><?php $form->text($phase . '[' . $i . '][prestasi]', ' position') ?></td>
						<td><?php $form->text($phase . '[' . $i . '][tahun]', ' duration') ?></td>
					</tr>
					<?php endfor; ?>
				</tbody>
			</table>
		</li>
	</ol>
</fieldset>

<fieldset class="pane" id="referensi">
	<legend>Referensi</legend>
	<ol>
		<li>
			<label class="main-point" for="pernah_pergi_jangka_pendek">Pernahkah Adik melawat/berpergian dalam jangka pendek ke luar negeri?</label>
			<?php // $form->select('pernah_pergi_jangka_pendek', array('Tidak pernah', 'Pernah'), 'medium-short') ?>
			<br>
			<label class="main-point" for="tujuan_pergi_jangka_pendek">Jika pernah, ke mana?</label>
			<?php $form->text('tujuan_pergi_jangka_pendek', 'medium') ?>
			<br>
			<label class="main-point" for="kapan_pergi_jangka_pendek">Kapan?</label>
			<?php $form->text('kapan_pergi_jangka_pendek', 'medium') ?>
			<br>
			<label class="main-point" for="rangka_pergi_jangka_pendek">Dalam rangka apa?</label>
			<?php $form->text('rangka_pergi_jangka_pendek', 'medium') ?>
		</li>		
		<li>
			<label class="main-point" for="pernah_pergi_jangka_panjang">Pernahkah Adik melawat/berpergian dalam jangka panjang ke luar negeri?</label>
			<?php // $form->select('pernah_pergi_jangka_panjang', array('Tidak pernah', 'Pernah'), 'medium-short') ?>
			<br>
			<label class="main-point" for="tujuan_pergi_jangka_panjang">Jika pernah, ke mana?</label>
			<?php $form->text('tujuan_pergi_jangka_panjang', 'medium') ?>
			<br>
			<label class="main-point" for="kapan_pergi_jangka_panjang">Kapan dan berapa lama?</label>
			<?php $form->text('kapan_pergi_jangka_panjang', 'medium') ?>
			<br>
			<label class="main-point" for="rangka_pergi_jangka_panjang">Dalam rangka apa?</label>
			<?php $form->text('rangka_pergi_jangka_panjang', 'medium') ?>
			<br>
			<label class="main-point" for="kegiatan_pergi_jangka_panjang">Kegiatan Adik selama di sana?</label>
			<?php $form->text('kegiatan_pergi_jangka_panjang', 'medium') ?>
		</li>
		<li>
			<label class="main-point descriptive">Adakah di antara keluarga besar Adik yang pernah mengikuti program pertukaran yang diselenggarakan oleh Bina Antarbudaya/AFS? Jika iya:</label>
			<br clear="all">
			<label class="main-point" for="nama_relasi_pernah_ikut">Nama</label>
			<?php $form->text('nama_relasi_pernah_ikut', 'medium') ?>
			<br>
			<label class="main-point" for="hubungan_relasi_pernah_ikut">Hubungan dengan Adik</label>
			<?php $form->text('hubungan_relasi_pernah_ikut', 'medium') ?>
			<br>
			<label class="main-point" for="program_relasi_pernah_ikut">Program</label>
			<?php $form->text('program_relasi_pernah_ikut', 'medium-short') ?>
			<?php $form->select('program_relasi_pernah_ikut_jenisnya', array('sending' => 'Sending', 'hosting' => 'Hosting'), 'short') ?>
			<br>
			<label class="main-point" for="tujuan_relasi_pernah_ikut">Tujuan (sending)/Asal (hosting)</label>
			<?php $form->text('tujuan_relasi_pernah_ikut', 'medium') ?>
			<br>
			<label class="main-point" for="alamat_relasi_pernah_ikut">Alamat sekarang</label>
			<?php $form->textarea('alamat_relasi_pernah_ikut')?>
		</li>
		<li>
			<label class="main-point">Pernahkah Adik/keluarga turut berpartisipasi dalam kegiatan Bina Antarbudaya/AFS?</label>
			<br clear="all">
			<label class="main-point" for="nama_kegiatan_yba_pernah_diikuti">Kegiatan</label>
			<?php $form->text('nama_kegiatan_yba_pernah_diikuti', 'medium') ?>
			<br>
			<label class="main-point" for="tahun_kegiatan_yba_pernah_diikuti">Tahun</label>
			<?php $form->select_year('tahun_kegiatan_yba_pernah_diikuti', 2011, 1970) ?>
		</li>
		<li>
			<label class="main-point" for="referral">Dari mana Adik mengetahui program kami?</label>
			<br>
			<?php $form->textarea('referral'); ?>
		</li>
		<li>
			<label class="main-point" for="motivasi">Apa motivasi Adik mengikuti seleksi dan program Bina Antarbudaya?</label>
			<br>
			<?php $form->textarea('motivasi'); ?>
		</li>
		<li>
			<label class="main-point" for="harapan_ikut_binabud">Apa yang diharapkan Adik dengan keikutsertaan Adik dalam seleksi dan program Bina Antarbudaya?</label>
			<br>
			<?php $form->textarea('harapan_ikut_binabud'); ?>
		</li>
	</ol>
</fieldset>

<fieldset class="pane" id="rekomendasi">
	<legend>Rekomendasi</legend>
	<p>Sebutkan nama 3 (tiga) orang <strong>di luar keluarga</strong> Adik yang mengenal diri Adik secara pribadi untuk menuliskan surat rekomendasi bagi Adik. Diharapkan nama orang-orang tersebut tidak akan berganti pada saat Adik harus memintakan rekomendasi dari mereka. <i>Surat rekomendasi tidak perlu dikumpulkan pada saat pendaftaran seleksi.</i></p>
	<h1>Lingkungan sekolah (Guru atau Kepala Sekolah) <span>(minimal berusia 21 tahun)</span></h1>
	<ol>
		<li>
			<label class="main-point" for="rekomendasi_lingkungan_sekolah_nama">Nama</label>
			<?php $form->text('rekomendasi_lingkungan_sekolah_nama'); ?>
		</li>
		<li>
			<label class="main-point" for="rekomendasi_lingkungan_sekolah_alamat">Alamat/Telepon</label>
			<?php $form->text('rekomendasi_lingkungan_sekolah_alamat'); ?>
		</li>
		<li>
			<label class="main-point" for="rekomendasi_lingkungan_sekolah_occupation">Pekerjaan</label>
			<?php $form->text('rekomendasi_lingkungan_sekolah_occupation'); ?>
		</li>
		<li>
			<label class="main-point" for="rekomendasi_lingkungan_sekolah_alamat_occupation">Alamat pekerjaan</label>
			<?php $form->textarea('rekomendasi_lingkungan_sekolah_alamat_occupation'); ?>
		</li>
		<li>
			<label class="main-point" for="rekomendasi_lingkungan_sekolah_hubungan">Hubungan</label>
			<?php $form->text('rekomendasi_lingkungan_sekolah_hubungan'); ?>
		</li>
	</ol>
	<h1>Lingkungan rumah/organisasi di luar sekolah <span>(<strong>bukan keluarga,</strong> minimal berusia 21 tahun)</span></h1>
	<ol>
		<li>
			<label class="main-point" for="rekomendasi_lingkungan_luar_sekolah_nama">Nama</label>
			<?php $form->text('rekomendasi_lingkungan_luar_sekolah_nama'); ?>
		</li>
		<li>
			<label class="main-point" for="rekomendasi_lingkungan_luar_sekolah_alamat">Alamat/Telepon</label>
			<?php $form->text('rekomendasi_lingkungan_luar_sekolah_alamat'); ?>
		</li>
		<li>
			<label class="main-point" for="rekomendasi_lingkungan_luar_sekolah_occupation">Pekerjaan</label>
			<?php $form->text('rekomendasi_lingkungan_luar_sekolah_occupation'); ?>
		</li>
		<li>
			<label class="main-point" for="rekomendasi_lingkungan_luar_sekolah_alamat_occupation">Alamat pekerjaan</label>
			<?php $form->textarea('rekomendasi_lingkungan_luar_sekolah_alamat_occupation'); ?>
		</li>
		<li>
			<label class="main-point" for="rekomendasi_lingkungan_luar_sekolah_hubungan">Hubungan</label>
			<?php $form->text('rekomendasi_lingkungan_luar_sekolah_hubungan'); ?>
		</li>
	</ol>
	<h1>Teman dekat</h1>
	<ol>
		<li>
			<label class="main-point" for="rekomendasi_teman_dekat_nama">Nama</label>
			<?php $form->text('rekomendasi_teman_dekat_nama'); ?>
		</li>
		<li>
			<label class="main-point" for="rekomendasi_teman_dekat_alamat">Alamat/Telepon</label>
			<?php $form->text('rekomendasi_teman_dekat_alamat'); ?>
		</li>
		<li>
			<label class="main-point" for="rekomendasi_teman_dekat_hubungan">Hubungan</label>
			<?php $form->text('rekomendasi_teman_dekat_hubungan'); ?>
		</li>
	</ol>
</fieldset>

<fieldset class="pane" id="kepribadian">
	<legend>Kepribadian</legend>
	<ol>
		<li>
			<label class="main-point" for="kepribadian_sifat_dan_kepribadian">Menurut Adik, seperti apakah sifat dan kepribadian adik?</label>
			<?php $form->textarea('kepribadian_sifat_dan_kepribadian', 'extra-large') ?>
		</li>
		<li>
			<label class="main-point" for="kepribadian_kelebihan_dan_kekurangan">Apakah kelebihan/kekurangan Adik?</label>
			<?php $form->textarea('kepribadian_kelebihan_dan_kekurangan', 'extra-large') ?>
		</li>
		<li>
			<label class="main-point" for="kepribadian_kondisi_membuat_tertekan">Hal-hal apakah yang sering membuat Adik merasa tertekan?</label>
			<?php $form->textarea('kepribadian_kondisi_membuat_tertekan', 'extra-large') ?>
		</li>
		<li>
			<label class="main-point" for="kepribadian_masalah_terberat">Masalah terberat apakah yang pernah Adik hadapi? Bagaimana Adik menyelesaikannya?</label>
			<?php $form->textarea('kepribadian_masalah_terberat', 'extra-large') ?>
		</li>
		<li>
			<label class="main-point" for="kepribadian_rencana">Apakah rencana Adik berkaitan dengan pendidikan dan karir di masa depan?</label>
			<?php $form->textarea('kepribadian_rencana', 'extra-large') ?>
		</li>
	</ol>
</fieldset>

<!-- end form -->

<fieldset class="pane" id="foto">
	<legend>Foto</legend>
	<?php if ($picture): ?>
	<div class="picture-container"><img src="<?php echo $picture->get_cropped_url(); ?>" width="300" height="400"></div>
	<?php endif; ?>
	<?php if (!$admin): ?>
	<ol>
		<li>
			<label class="main-point" for="picture">Unggah foto baru</label>
			<input type="hidden" name="MAX_FILE_SIZE" value="2048000">
			<input type="file" name="picture" id="picture" class="medium">
			Maksimal 2MB
			<br>
			<span class="instruction">Gunakan <strong>pas foto</strong>. Foto jenis lain tidak akan kami terima.</span>
		</li>
		<li>
			<button type="submit">Unggah</button>
		</li>
	</ol>
	<?php endif; ?>
</fieldset>
<?php if (!$admin): ?>
<fieldset class="pane" id="finalisasi">
	<legend>Finalisasi</legend>
	<ol>
		<li>
			<label class="main-point">Untuk menyelesaikan pendaftaran Adik, Adik perlu melakukan finalisasi. Setelah finalisasi, informasi pada formulir ini dikunci dan Adik tidak dapat mengubahnya kembali. Oleh sebab itu, <strong>pastikan seluruh kolom pada formulir ini telah terisi dengan lengkap dan benar sebelum melakukan finalisasi</strong>. Kelalaian dalam mengisi formulir akan mengakibatkan penolakan pengumpulan berkas.</label>
		</li>
		<li class="finalize-checkbox-box">
			<input type="checkbox" name="finalize" value="true" id="finalize"> <label for="finalize"><strong>Formulir ini telah saya isi dengan selengkapnya dan sejujurnya. Saya mengerti.</strong></label>
		</li>
		<li>
			<button type="submit" id="finalize-button">Finalisasi</button>
		</li>
	</ol>
</fieldset>
<?php endif; ?>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>

<script>

$(document).ready(function(){
	uv = function() {
		if ($('#finalize').attr('checked')) {
			$('#finalize-button:parent').fadeIn().focus();
		}
		else
			$('#finalize-button:parent').fadeOut();
	}
	$('#finalize').change(uv)

	uv();

	//When page loads...
	$("fieldset.pane").hide(); //Hide all content
	$(".form-nav ul li:first").addClass("active");
	$("fieldset.pane:first").show(); //Show first tab content

	//On Click Event
	$(".form-nav li a").click(function() {

		$(".form-nav li a").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab

		$("fieldset.pane").hide(); //Hide all tab content

		var activeTab = $(this).attr("href"); //Find the href attribute value to identify the active tab + content
		if ($(this).attr("href") == '#finalisasi')
			$('.save-button').slideUp();
		else {
			$('.save-button').slideDown();
			$('#finalize').removeAttr('checked');
			uv();
		}
		$(activeTab).fadeIn('medium'); //Fade in the active ID content
		return false;
	});
	
	$('.notice').hide().slideDown('slow');
	
	<?php if ($incomplete): ?>
	<?php foreach ($incomplete as $inc): ?>
	$("label[for=<?php echo $inc; ?>]").css('color', '#c00');
	$("#<?php echo $inc; ?>").css('box-shadow', '0 0 5px #f00');
	<?php endforeach; ?>
	<?php endif; ?>

});
</script>

</div>

</form>

</div>
<?php $this->footer(); ?>