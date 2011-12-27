<!DOCTYPE html>

<html lang="id">

	<head>
		<meta charset="utf-8">
		<title>Formulir Pendaftaran Pendaftaran Seleksi Bina Antarbudaya</title>
		<base href="http://seleksi.binabudbdg.org/">
		<!-- <link rel="stylesheet" href="assets/css/applicant/transcript.css"> -->
		<style>
		<?php readfile(HELIUM_PARENT_PATH . '/assets/css/applicant/transcript.css'); ?>
		</style>
	</head>
	<body>

<!-- begin form -->

<div id="cover" style="page-break-after: always">
	<div class="instructions">
		Cetaklah transkrip ini dengan orientasi <strong>portrait</strong> pada kertas <strong>HVS A4</strong>.
		<br>
		Gunakan <strong>Firefox/Chrome/Internet Explorer</strong> untuk mencetak, jangan gunakan Word.
		<br>
		Untuk mencetak transkrip ini <em>sekarang</em>, <a href="javascript:window.print()" onclick="">klik di sini</a>.
		<br>
		Untuk menyimpan transkrip ini, tekan Ctrl+S (atau Cmd+S di Mac) dan pilih jenis 'Web page, complete'.
		<br>
		Saat mencetak, pastikan tulisan pada formulir ini terbaca seluruhnya (tidak terlalu kecil).
		<br>
		Petunjuk ini tidak akan ditampilkan saat mencetak.
	</div>
	<img src="assets/logo.png" alt="Bina Antarbudaya" style="font-size: 18pt">
	<h1>FORMULIR PENDAFTARAN SELEKSI</h1>
	<?php if ($picture): ?>
	<img src="uploads/<?php echo $picture->cropped_filename; ?>" width="240" height="320" alt="Foto 4x6">
	<?php else: ?>
	<br><br><br><br>(Foto 4x6)<br><br><br><br>
	<?php endif; ?>
	<p class="name">
		<strong><?php echo ApplicantDetail::sanitize_name($form->values['nama_lengkap']) ?></strong>
		<br>
		<?php echo Applicant::get_test_id($applicant->id); ?> &ndash; Chapter Bandung
	</p>
	<p class="programs">
		Program yang diinginkan:<br>
		<strong><?php
		$p = array();
		$v = array('program_afs' => 'AFS', 'program_yes' => 'YES', 'program_jenesys' => 'JENESYS');
		foreach ($v as $i => $j) {
			if ($form->values[$i])
				$p[] = $j;
		}
		echo implode(', ', $p);
		?></strong>
	</p>
	<p class="school">
		<strong>Informasi Sekolah:</strong>
		<br>
		<span><?php echo Gatotkaca::sanitize_school($form->values['pendidikan_sma_nama_sekolah']); ?></span>
		<br>
		<?php if ($form->values['pesantren']): ?>
		(Pesantren)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<?php endif; ?>
		<?php if ($form->values['akselerasi']): ?>
		(Akselerasi)
		<?php endif; ?>
	</p>
	<p class="telkomsel">
		Apakah Adik menggunakan nomor Telkomsel? &nbsp;&nbsp;&nbsp;<strong><?php if ($form->values['telkomsel_menggunakan']) echo 'Ya'; else echo 'Tidak'; ?></strong>
		<br>
		Apakah Adik anggota Telkomsel School Community? &nbsp;&nbsp;&nbsp;<strong><?php if ($form->values['telkomsel_school_community']) echo 'Ya'; else echo 'Tidak'; ?></strong>
	</p>
</div>

<!-- page break -->

<div id="pribadi">
	<ol>
		<p>
			<label for="nama_lengkap" class="main-point">1. Nama Lengkap:</label>
			<?php $form->text('nama_lengkap'); ?>
		</p>
		<p>
			<?php ?>
			<label for="alamat_lengkap" class="main-point">2. Alamat Lengkap:</label>
			<?php $form->address('alamat_lengkap'); ?>
		</p>
		<p>
			<label for="ttl.kota" class="main-point">3. Tempat &amp; Tanggal Lahir:</label>
			<?php $form->text('ttl[kota]', 'medium') ?>, <?php $form->date('ttl[tanggal]'); ?>
		</p>
		<p>
			<label for="jenis_kelamin" class="main-point">4. Jenis Kelamin:</label>
			<?php
			$form->select('jenis_kelamin', array('L' => 'Laki-laki', 'P' => 'Perempuan'), 'short')
			?>
		</p>
		<p>
			<label for="tinggi_badan" class="subpoint">5. Tinggi/Berat:</label>
			<?php $form->number('tinggi_badan', 'very-short') ?> cm / <?php $form->number('berat_badan', 'very-short') ?> kg
			<br>
			<label for="golongan_darah" class="subpoint">6. Golongan Darah:</label>
			<?php $form->select('golongan_darah', array('O' => 'O', 'A' => 'A', 'B' => 'B', 'AB' => 'AB'), 'very-short')?>
		</p>
		<p>
			<label for="kewarganegaraan" class="main-point">7. Kewarganegaraan:</label>
			<?php $form->text('kewarganegaraan', 'medium') ?>
		</p>
		<p>
			<label for="agama" class="main-point">8. Agama:</label>
			<?php $form->text('agama', 'medium') ?>
		</p>
	<?php

	foreach(array('a' => 'Ayah', 'b' => 'Ibu') as $i => $parent):
		$n = strtolower($parent);
	?>
		<p>9. Orangtua</p>
		<p>
			<label for="nama_lengkap_<?php echo $n; ?>" class="main-point tabbed"><?php echo $i ?>. Nama Lengkap <?php echo $parent; ?>:</label>
			<?php $form->text('nama_lengkap_' . $n, 'long'); ?>
		</p>
		<p>
			<label for="pendidikan_terakhir_<?php echo $n; ?>" class="main-point tabtabbed">Pendidikan Terakhir:</label>
			<?php $form->text('pendidikan_terakhir_' . $n, 'medium') ?>
		</p>
		<p>
			<label for="pekerjaan_<?php echo $n; ?>" class="main-point tabtabbed">Pekerjaan/Jabatan:</label>
			<?php $form->text('pekerjaan_' . $n, 'medium') ?>
		</p>
		<p>
			<label for="pangkat_<?php echo $n; ?>" class="main-point tabtabbed">Pangkat/Golongan:</label>
			<?php $form->text('pangkat_' . $n, 'medium') ?>
		</p>
		<p>
			<label for="nama_kantor_<?php echo $n; ?>" class="main-point tabtabbed">Nama &amp; Alamat Kantor:</label>
			<?php $form->text('nama_kantor_' . $n, 'long'); ?>
			<br clear="all">
			<label class="main-point tabbed">&nbsp;</label>
			<?php $form->address('alamat_kantor_' . $n, false, false, false, true, false, true, true) ?>
		</p>
	<?php endforeach; ?>
		<p>10. Wali <span>(bilamana orang tua telah wafat atau siswa tinggal terpisah dengan orang tua)</p>
		<p>
			<label for="nama_lengkap_wali" class="main-point tabbed">a. Nama Lengkap Wali:</label>
			<?php $form->text('nama_lengkap_wali', 'long'); ?>
		</p>
		<p>
			<label for="hubungan_dengan_wali" class="main-point tabbed">b. Hubungan dengan Anda:</label>
			<?php $form->text('hubungan_dengan_wali', 'long'); ?>
		</p>
		<p>
			<label for="alamat_lengkap_wali" class="main-point tabbed">c. Alamat Lengkap:</label>
			<?php $form->address('alamat_lengkap_wali', false, false, false, true, true, false, false) ?>
		</p>
		<p>
			<label for="pekerjaan_wali" class="main-point tabbed">d. Pekerjaan/Jabatan:</label>
			<?php $form->text('pekerjaan_wali', 'medium'); ?>
		</p>
		<p>
			<label for="pangkat_wali" class="main-point tabtabbed">Pangkat/Golongan:</label>
			<?php $form->text('pangkat_wali', 'medium'); ?>
		</p>
		<p>
			<label for="nama_kantor_wali" class="main-point tabtabbed">Nama &amp; Alamat Kantor:</label>
			<?php $form->text('nama_kantor_wali', 'long'); ?>
			<br clear="all">
			<label class="main-point tabbed">&nbsp;</label>
			<?php $form->address('alamat_kantor_wali', false, false, false, true, false, true, true) ?>
		</p>
		<p>11. Keluarga</p>
		<p>
			<label class="tabbed">a. Jumlah anak dalam keluarga: <?php $form->text('jumlah_anak_dalam_keluarga', 'very-short'); ?>
			<label for="anak_nomor" class="inline-point">Anda anak nomor:</label>
			<?php $form->text('anak_nomor', 'very-short'); ?>
		</p>
		<p>
			<label for="saudara-1--nama" class="main-point tabbed descriptive">b. Nama, umur, &amp; sekolah/pekerjaan saudara kandung Anda:</label>
			<table class="siblings">
				<thead>
					<tr>
						<th >Urutan lahir</th>
						<th >Nama Lengkap</th>
						<th >Tanggal Lahir</th>
						<th >Sekolah/Pekerjaan</th>
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
		</p>
		<p class="pagebreak"></p>
		<p>12. Pendidikan</p>
		<p>
			<label class="tabbed">a. SD</label>
		</p>
		<p>
			<label for="pendidikan_sd-nama_sekolah" class="main-point tabtabbed">Nama Sekolah:</label>
			<?php $form->text('pendidikan_sd_nama_sekolah', 'long'); ?>
		</p>
		<p>
			<label for="pendidikan_sd-tahun_ijazah" class="main-point tabtabbed">Tahun Ijazah:</label>
			<?php $form->select_year('pendidikan_sd_tahun_ijazah', 2009, 2005); ?>
		</p>
		<p>
			<label class="main-point tabtabbed descriptive">Data Prestasi:</label>
			<table class="academics sd">
				<thead>
					<tr>
						<th rowspan="2"  class="grade">Kelas</th>
						<th  class="term-first">Cawu/Semester I</th>
						<th  class="term-middle">Cawu/Semester II</th>
						<th  class="term-final">Cawu III</th>
					</tr>
					<tr>
						<th colspan="3">Ranking ke ... dari ... siswa</th>
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
		</p>
		<p>
			<label class="tabbed">b. SMP/MTs</label>
		</p>
		<p>
			<label for="pendidikan_smp-nama_sekolah" class="main-point tabtabbed">Nama Sekolah:</label>
			<?php $form->text('pendidikan_smp_nama_sekolah', 'long'); ?>
		</p>
		<p>
			<label for="pendidikan_smp-tahun_ijazah" class="main-point tabtabbed">Tahun Ijazah:</label>
			<?php $form->select_year('pendidikan_smp_tahun_ijazah', 2010, 2008); ?>
		</p>
		<p>
			<label class="main-point tabtabbed descriptive">Data Prestasi:</label>
			<table class="academics smp">
				<thead>
					<tr>
						<th rowspan="2"  class="grade">Kelas</th>
						<th  class="term-first">Semester I</th>
						<th  class="term-final">Semester II</th>
					</tr>
					<tr>
						<th colspan="2">Ranking ke ... dari ... siswa atau Rata-Rata Nilai (jika tidak ada ranking)</th>
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
		</p>
		<p>
			<label class="tabbed">c. SMA/SMK/MA</label>
		</p>
		<p>
			<label for="pendidikan_sma-nama_sekolah" class="main-point tabtabbed">Nama Sekolah:</label>
			<?php $form->text('pendidikan_sma_nama_sekolah', 'long'); ?>
			<br>
			<!-- <?php $form->checkbox('pesantren', 'pesantren') ?> Pesantren/Madrasah -->
		</p>
		<p>
			<label for="pendidikan_sma-alamat_sekolah-alamat" class="main-point tabtabbed">Alamat Sekolah:</label>
			<?php $form->address('pendidikan_sma_alamat_sekolah', false, false, false, $telepon = true, $hp = false, $fax = true, $email = false); ?>
		</p>
		<p>
			<label for="pendidikan_sma-nama_kepala_sekolah" class="main-point tabtabbed">Nama Kepala Sekolah:</label>
			<?php $form->text('pendidikan_sma_nama_kepala_sekolah', 'long'); ?>
		</p>
		<p>
			<label for="pendidikan_sma-tahun_masuk" class="main-point tabtabbed">Masuk SLTA tahun:</label>
			<?php $form->select_year('pendidikan_sma_tahun_masuk', 2010, 2008); ?>
		</p>
		<!-- <p>
			<?php $form->checkbox('akselerasi', 'akselerasi') ?> Saya adalah siswa kelas Akselerasi
		</p> -->
		<p>
			<label for="pendidikan_sma-bulan_keluar" class="main-point tabtabbed">Akan menamatkan SLTA bulan:</label>
			<?php
			$form->select_month('pendidikan_sma_bulan_keluar');
			?>
			<label for="pendidikan_sma-tahun_keluar" class="inline-point">tahun:</label>
			<?php $form->select_year('pendidikan_sma_tahun_keluar', 2014, 2011); ?>
		</p>
		<p>
			<label class="main-point tabtabbed descriptive">Data Prestasi:</label>
			<table class="academics sma">
				<thead>
					<tr>
						<th rowspan="2"  class="grade">Kelas</th>
						<th  class="term-first">Semester I</th>
						<th  class="term-final">Semester II</th>
					</tr>
					<tr>
						<th colspan="2">Ranking ke ... dari ... siswa atau Rata-Rata Nilai (jika tidak ada ranking)</th>
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
						<td class="term-final">
							<?php $form->number('pendidikan_' . $phase . '_prestasi[' . $i . '][ii][rank]', 'very-short l') ?> /
							<?php $form->number('pendidikan_' . $phase . '_prestasi[' . $i . '][ii][total]', 'very-short r') ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</p>
		<p>13. Pengetahuan Bahasa</p>
		<p class="tabbed">
			a. Sudah berapa lama Anda belajar Bahasa Inggris? &nbsp;
			<?php $form->text('pengetahuan_bahasa_inggris_berapa_lama', 'short') ?>
		</p>
		<p class="tabbed">
			b. Bahasa lain yang Anda kuasai/pelajari?&nbsp;
			<?php $form->text('pengetahuan_bahasa_lain_apa', 'short') ?>
			&nbsp;&nbsp;&nbsp;&nbsp;Berapa lama?</label>
			<?php $form->text('pengetahuan_bahasa_lain_berapa_lama', 'short') ?>
		</p>
		<p>14. Pelajaran Favorit &amp; Cita-cita</p>
		<p>
			<label class="main-point tabbed" for="mata_pelajaran_favorit">a. Mata pelajaran favorit Anda:</label>
			<?php $form->text('mata_pelajaran_favorit', 'medium') ?>
		</p>
		<p>
			<label class="main-point tabbed" for="cita_cita">b. Cita-cita Anda:</label>
			<?php $form->text('cita_cita', 'short') ?>
		</p>
		<p>
			<label class="main-point descriptive">15. Organisasi yang pernah diikuti, baik di lingkungan sekolah maupun di luar lingkungan sekolah:</label>
			<table class="activities">
				<thead>
					<tr>
						<th >No</th>
						<th >Nama Organisasi</th>
						<th >Jenis Kegiatan</th>
						<th >Jabatan</th>
						<th >Tahun</th>
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
		</p>
		<p>16. Kesenian <span>(seni suara, seni musik, tari, teater, dll.)</span></p>
	<?php $phase = 'kesenian'; ?>
		<p>
			<label class="main-point tabbed" for="<?php echo $phase ?>_sekedar_hobi">Sekedar hobi:</label>
			<?php $form->text($phase . '_sekedar_hobi', 'long') ?>
		</p>		
		<p>
			<label class="main-point tabbed" for="<?php echo $phase ?>_ikut_perkumpulan">Ikut perkumpulan:</label>
			<?php $form->text($phase . '_ikut_perkumpulan', 'long') ?>
		</p>
		<p>
			<label class="main-point tabbed descriptive">Prestasi:</label>
			<table class="activities">
				<thead>
					<tr>
						<th >No</th>
						<th >Jenis</th>
						<th >Kejuaraan</th>
						<th >Prestasi</th>
						<th >Tahun</th>
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
		</p>
		<p class="pagebreak"></p>
		<p>17. Olahraga</p>
	<?php $phase = 'olahraga'; ?>
		<p>
			<label class="main-point tabbed" for="<?php echo $phase ?>_sekedar_hobi">Sekedar hobi:</label>
			<?php $form->text($phase . '_sekedar_hobi', 'long') ?>
		</p>		
		<p>
			<label class="main-point tabbed" for="<?php echo $phase ?>_ikut_perkumpulan">Ikut perkumpulan:</label>
			<?php $form->text($phase . '_ikut_perkumpulan', 'long') ?>
		</p>
		<p>
			<label class="main-point tabbed descriptive">Prestasi:</label>
			<table class="activities">
				<thead>
					<tr>
						<th >No</th>
						<th >Kejuaraan</th>
						<th >Pencapaian</th>
						<th >Tahun</th>
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
		</p>
	<?php $phase = 'kegiatan_lain_lain'; ?>
		<p>
			<label class="main-point descriptive">18. Kegiatan lain di luar olahraga dan kesenian:</label>
			<table class="activities">
				<thead>
					<tr>
						<th >No</th>
						<th >Kegiatan</th>
						<th >Prestasi</th>
						<th >Tahun</th>
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
		</p>
		<?php $phase = 'pengalaman_kerja'; ?>
		<p>
			<label class="main-point descriptive">19. Pengalaman kerja sosial/magang/bekerja (di LSM, Yayasan, kantor, sekolah, koperasi, usaha, dll):</label>
			<table class="activities">
				<thead>
					<tr>
						<th >No</th>
						<th >Nama dan bidang tempat bekerja/magang</th>
						<th >Tugas dan tanggung jawab yang dijalankan</th>
						<th >Tahun dan lama&nbsp;bekerja</th>
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
		</p>
		<p class="pagebreak"></p>
		<p>
			<label class="main-point descriptive" for="pernah_pergi_jangka_panjang">20. Pernahkah Anda melawat/berpergian dalam jangka pendek ke luar negeri?<l/abel><br>
			<label class="main-point tabbed" for="tujuan_pergi_jangka_pendek">a. Ke mana?</label>
			<?php $form->text('tujuan_pergi_jangka_pendek', 'medium') ?>
			<label class="inline-point" for="kapan_pergi_jangka_pendek">b. Kapan?</label>
			<?php $form->text('kapan_pergi_jangka_pendek', 'medium') ?>
			<br>
			<label class="main-point tabbed" for="rangka_pergi_jangka_pendek">c. Dalam rangka apa?</label>
			<?php $form->text('rangka_pergi_jangka_pendek', 'medium') ?>
		</p>		
		<p>
			<label class="main-point descriptive" for="pernah_pergi_jangka_panjang">21. Pernahkah Anda melawat/berpergian dalam jangka panjang ke luar negeri?</label>
			<?php $form->select('pernah_pergi_jangka_panjang', array('Tidak pernah', 'Pernah'), 'medium-short') ?>
			<br>
			<label class="main-point tabbed" for="tujuan_pergi_jangka_panjang">a. Ke mana?</label>
			<?php $form->text('tujuan_pergi_jangka_panjang', 'medium') ?>
			<label class="inline-point" for="kapan_pergi_jangka_panjang">b. Kapan dan berapa lama?</label>
			<?php $form->text('kapan_pergi_jangka_panjang', 'medium') ?>
			<br>
			<label class="main-point tabbed" for="rangka_pergi_jangka_panjang">c. Dalam rangka apa?</label>
			<?php $form->text('rangka_pergi_jangka_panjang', 'medium') ?>
			<br>
			<label class="main-point tabbed" for="kegiatan_pergi_jangka_panjang">d. Kegiatan Anda selama di sana?</label>
			<?php $form->text('kegiatan_pergi_jangka_panjang', 'medium') ?>
		</p>
		<p>
			<label class="main-point descriptive">22. Adakah di antara keluarga besar Anda yang pernah mengikuti program pertukaran yang diselenggarakan oleh Bina Antarbudaya/AFS? Jika iya:</label>
			<br clear="all">
			<label class="main-point tabbed" for="nama_relasi_pernah_ikut">Nama:</label>
			<?php $form->text('nama_relasi_pernah_ikut', 'medium') ?>
			<br>
			<label class="main-point tabbed" for="hubungan_relasi_pernah_ikut">Hubungan dengan Anda:</label>
			<?php $form->text('hubungan_relasi_pernah_ikut', 'medium') ?>
			<br>
			<label class="main-point tabbed"><?php $form->select('program_relasi_pernah_ikut_jenisnya', array('sending' => 'Sending', 'hosting' => 'Hosting'), 'short') ?> program:</label>
			<?php $form->text('program_relasi_pernah_ikut', 'medium-short') ?>
			<br>
			<label class="main-point tabbed" for="tujuan_relasi_pernah_ikut">Tujuan (sending)/Asal (hosting):</label>
			<?php $form->text('tujuan_relasi_pernah_ikut', 'medium') ?>
			<br>
			<label class="main-point tabbed" for="alamat_relasi_pernah_ikut">Alamat sekarang:</label>
			<?php $form->textarea('alamat_relasi_pernah_ikut')?>
		</p>
		<p>
			<label class="main-point descriptive">23. Pernahkah Anda/keluarga turut berpartisipasi dalam kegiatan Bina Antarbudaya/AFS?</label>
			<br>
			<label class="main-point tabbed" for="nama_kegiatan_yba_pernah_diikuti">Kegiatan:</label>
			<?php $form->text('nama_kegiatan_yba_pernah_diikuti', 'medium') ?>
			<br>
			<label class="main-point tabbed" for="tahun_kegiatan_yba_pernah_diikuti">Tahun:</label>
			<?php $form->select_year('tahun_kegiatan_yba_pernah_diikuti', 2011, 1970) ?>
		</p>
		<p>
			<label class="main-point descriptive" for="referral">24. Sebutkan dari mana Anda mengetahui program kami:</label>
		</p>
		<p class="tabbed">
			<?php $form->textarea('referral'); ?>
		</p>
		<p>
			<label class="main-point descriptive" for="motivasi">25. Apa motivasi Anda mengikuti seleksi dan program Bina Antarbudaya?</label>
		</p>
		<p class="tabbed">
			<?php $form->textarea('motivasi'); ?>
		</p>
		<p>
			<label class="main-point descriptive" for="motivasi">26. Apa yang diharapkan Anda dengan keikutsertaan Anda dalam seleksi dan program Bina Antarbudaya?</label>
		</p>
		<p class="tabbed">
			<?php $form->textarea('harapan_ikut_binabud'); ?>
		</p>
		<p class="pagebreak"></p>
		<p>27. Sebutkan nama 3 (tiga) orang di luar keluarga Anda yang mengenal diri Anda secara pribadi untuk menuliskan surat rekomendasi bagi Anda.</p>
		<p class="tabbed">1. Lingkungan sekolah (Kepala Sekolah atau Guru) <span>(minimal berusia 21 tahun)</span></p>
		<p>
			<label class="main-point tabtabbed">a. Nama:</label>
			<?php $form->text('rekomendasi_lingkungan_sekolah_nama'); ?>
		</p>
		<p>
			<label class="main-point tabtabbed">b. Alamat/Telepon:</label>
			<?php $form->text('rekomendasi_lingkungan_sekolah_alamat'); ?>
		</p>
		<p>
			<label class="main-point tabtabbed">c. Pekerjaan:</label>
			<?php $form->text('rekomendasi_lingkungan_sekolah_pekerjaan'); ?>
		</p>
		<p>
			<label class="main-point tabtabbed">d. Alamat pekerjaan:</label>
			<?php $form->textarea('rekomendasi_lingkungan_sekolah_alamat_pekerjaan'); ?>
		</p>
		<p>
			<label class="main-point tabtabbed">e. Hubungan:</label>
			<?php $form->text('rekomendasi_lingkungan_sekolah_hubungan'); ?>
		</p>
		<p class="tabbed">2. Lingkungan rumah/organisasi di luar sekolah <span>(minimal berusia 21 tahun)</span></p>
		<p>
			<label class="main-point tabtabbed">a. Nama:</label>
			<?php $form->text('rekomendasi_lingkungan_luar_sekolah_nama'); ?>
		</p>
		<p>
			<label class="main-point tabtabbed">b. Alamat/Telepon:</label>
			<?php $form->text('rekomendasi_lingkungan_luar_sekolah_alamat'); ?>
		</p>
		<p>
			<label class="main-point tabtabbed">c. Pekerjaan:</label>
			<?php $form->text('rekomendasi_lingkungan_luar_sekolah_pekerjaan'); ?>
		</p>
		<p>
			<label class="main-point tabtabbed">d. Alamat pekerjaan:</label>
			<?php $form->textarea('rekomendasi_lingkungan_luar_sekolah_alamat_pekerjaan'); ?>
		</p>
		<p>
			<label class="main-point tabtabbed">e. Hubungan:</label>
			<?php $form->text('rekomendasi_lingkungan_luar_sekolah_hubungan'); ?>
		</p>
		<p class="tabbed">3. Teman dekat</p>
		<p>
			<label class="main-point tabtabbed">a. Nama:</label>
			<?php $form->text('rekomendasi_teman_dekat_nama'); ?>
		</p>
		<p>
			<label class="main-point tabtabbed">b. Alamat/Telepon:</label>
			<?php $form->text('rekomendasi_teman_dekat_alamat'); ?>
		</p>
		<p>
			<label class="main-point tabtabbed">c. Hubungan:</label>
			<?php $form->text('rekomendasi_teman_dekat_hubungan'); ?>
		</p>
	</ol>
	<ol style="page-break-before: always" id="kepribadian">
		<p>
			<label class="main-point" for="kepribadian_sifat_dan_kepribadian">Menurut Adik, seperti apakah sifat dan kepribadian adik?</label>
			<br>
			<?php $form->textarea('kepribadian_sifat_dan_kepribadian', 'extra-large') ?>
		</p>
		<p>
			<label class="main-point" for="kepribadian_kelebihan_dan_kekurangan">Apakah kelebihan/kekurangan Adik?</label>
			<br>
			<?php $form->textarea('kepribadian_kelebihan_dan_kekurangan', 'extra-large') ?>
		</p>
		<p>
			<label class="main-point" for="kepribadian_kondisi_membuat_tertekan">Hal-hal apakah yang sering membuat Adik merasa tertekan?</label>
			<br>
			<?php $form->textarea('kepribadian_kondisi_membuat_tertekan', 'extra-large') ?>
		</p>
		<p>
			<label class="main-point" for="kepribadian_masalah_terberat">Masalah terberat apakah yang pernah Adik hadapi? Bagaimana Adik menyelesaikannya?</label>
			<br>
			<?php $form->textarea('kepribadian_masalah_terberat', 'extra-large') ?>
		</p>
		<p>
			<label class="main-point" for="kepribadian_rencana">Apakah rencana Adik berkaitan dengan pendidikan dan karir di masa depan?</label>
			<br>
			<?php $form->textarea('kepribadian_rencana', 'extra-large') ?>
		</p>
	</ol>
</div>

<div class="footer">
	<b>Formulir ini telah saya isi dengan selengkapnya dan sejujurnya.</b>
	<br><br>
	Bandung, <?php $now = new HeliumDateTime; $now->set_locale('id'); echo $now->format('d F Y'); ?>
	<br><br><br><br><br><br>
	<span><?php $form->v('nama_lengkap'); ?></span>
</div>

<!-- end form -->
</body>