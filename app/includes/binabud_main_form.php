<?php

class BinabudMainForm {
	public static function render($form) {
		?>

		<fieldset id="pribadi">
			<legend>Data Pribadi</legend>
			<ol>
				<li>
					<label for="nama_lengkap" class="main-point">Nama Lengkap (sesuai akte lahir)</label>
					<?php $form->text('nama_lengkap'); ?>
				</li>
				<li>
					<?php ?>
					<label for="alamat_lengkap" class="main-point">Alamat Lengkap</label>
					<?php $form->address('alamat_lengkap'); ?>
				</li>
				<li>
					<label for="ttl.kota" class="main-point">Tempat &amp; Tanggal Lahir</label>
					<?php $form->text('ttl[kota]', 'medium') ?>
					<br>
					<?php $form->date('ttl[tanggal]'); ?>
				</li>
				<li>
					<label for="jenis_kelamin" class="main-point">Jenis Kelamin</label>
					<?php
					$form->select('jenis_kelamin', array('L' => 'Laki-laki', 'P' => 'Perempuan'), 'short')
					?>
				</li>
				<li>
					<label for="tinggi_badan" class="subpoint">Tinggi Badan</label>
					<?php $form->number('tinggi_badan', 'very-short') ?> cm
					<br>
					<label for="berat_badan" class="subpoint">Berat Badan</label>
					<?php $form->number('berat_badan', 'very-short') ?> kg
					<br>
					<label for="golongan_darah" class="subpoint">Golongan Darah</label>
					<?php $form->select('golongan_darah', array('O' => 'O', 'A' => 'A', 'B' => 'B', 'AB' => 'AB'), 'very-short')?>
				</li>
				<li>
					<label for="kewarganegaraan" class="main-point">Kewarganegaraan</label>
					<?php $form->text('kewarganegaraan', 'medium') ?>
				</li>
				<li>
					<label for="agama" class="main-point">Agama</label>
					<?php $form->text('agama', 'medium') ?>
				</li>
			</ol>
		</fieldset>

		<fieldset id="program">
			<legend>Pilihan Program</legend>
			<!-- poin 20–26 -->
			<ol>
				<li>
					<label class="main-point">Year Program (1 tahun)</label>
					<?php $form->checkbox('program', 'afs') ?> AFS
					<br>
					<?php $form->checkbox('program', 'yes') ?> YES
					<br>
					<?php $form->checkbox('program', 'jenesys') ?> JENESYS
				</li>
			</ol>
		</fieldset>

		<fieldset id="keluarga">
			<!-- poin 9–11 -->
			<legend>Keluarga</legend>
			<?php

			foreach(array('Ayah', 'Ibu') as $parent):
				$n = strtolower($parent);
			?>
			<h1><?php echo $parent; ?></h1>
			<ol>
				<li>
					<label for="nama_lengkap_<?php echo $n; ?>" class="main-point">Nama Lengkap <?php echo $parent; ?></label>
					<?php $form->text('nama_lengkap_' . $n, 'long'); ?>
				</li>
				<li>
					<label for="pendidikan_terakhir_<?php echo $n; ?>" class="main-point">Pendidikan Terakhir</label>
					<?php $form->text('pendidikan_terakhir_' . $n, 'medium') ?>
				</li>
				<li>
					<label for="pekerjaan_<?php echo $n; ?>" class="main-point">Pekerjaan/Jabatan</label>
					<?php $form->text('pekerjaan_' . $n, 'medium') ?>
				</li>
				<li>
					<label for="pangkat_<?php echo $n; ?>" class="main-point">Pangkat/Golongan</label>
					<?php $form->text('pangkat_' . $n, 'medium') ?>
				</li>
				<li>
					<label for="nama_kantor_<?php echo $n; ?>" class="main-point">Nama &amp; Alamat Kantor</label>
					<?php $form->text('nama_kantor_' . $n, 'long'); ?>
					<?php $form->address('alamat_kantor_' . $n, false, false, false, true, false, true, true) ?>
				</li>
			</ol>
			<?php endforeach; ?>

			<h1>Wali <span>(bilamana orang tua telah wafat atau siswa tinggal terpisah dengan orang tua)</h1>
			<ol>
				<li>
					<label for="nama_lengkap_wali" class="main-point">Nama Lengkap Wali</label>
					<?php $form->text('nama_lengkap_wali', 'long'); ?>
				</li>
				<li>
					<label for="hubungan_dengan_wali" class="main-point">Hubungan dengan Anda</label>
					<?php $form->text('hubungan_dengan_wali', 'long'); ?>
				</li>
				<li>
					<label for="pendidikan_terakhir_wali" class="main-point">Pendidikan Terakhir</label>
					<?php $form->text('pendidikan_terakhir_wali', 'long'); ?>
				</li>
				<li>
					<label for="alamat_lengkap_wali" class="main-point">Alamat Lengkap</label>
					<?php $form->address('alamat_lengkap_wali', false, false, false, true, true, false, false) ?>
				</li>
				<li>
					<label for="pekerjaan_wali" class="main-point">Pekerjaan/Jabatan</label>
					<?php $form->text('pekerjaan_wali', 'medium'); ?>
				</li>
				<li>
					<label for="pangkat_wali" class="main-point">Pangkat/Golongan</label>
					<?php $form->text('pangkat_wali', 'medium'); ?>
				</li>
				<li>
					<label for="nama_kantor_wali" class="main-point">Nama &amp; Alamat Kantor</label>
					<?php $form->text('nama_kantor_wali', 'long'); ?>
					<?php $form->address('alamat_kantor_wali', false, false, false, true, false, true, true) ?>
				</li>
			</ol>
			<h1>Saudara</h1>
			<ol>
				<li>
					<label for="jumlah_anak_dalam_keluarga" class="main-point">Jumlah anak dalam keluarga</label>
					<?php $form->text('jumlah_anak_dalam_keluarga', 'very-short'); ?>
					<label for="anak_nomor" class="inline-point">Anda anak nomor</label>
					<?php $form->text('anak_nomor', 'very-short'); ?>
				</li>
				<li>
					<label for="saudara-1--nama" class="main-point">Nama, umur, &amp; sekolah/pekerjaan saudara kandung Anda:</label>
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
			<ol>
		</fieldset>

		<fieldset id="pendidikan">
			<legend>Pendidikan</legend>
			<!-- poin 12–14 -->
			<h1>Sekolah Dasar (SD/MI)</h1>
			<ol>
				<li>
					<label for="pendidikan_sd-nama_sekolah" class="main-point">Nama Sekolah</label>
					<?php $form->text('pendidikan_sd_nama_sekolah', 'long'); ?>
				</li>
				<li>
					<label for="pendidikan_sd-tahun_ijazah" class="main-point">Tahun Ijazah</label>
					<?php $form->select_year('pendidikan_sd_tahun_ijazah', 2009, 2005); ?>
				</li>
				<li>
					<label class="main-point">Data Prestasi</label>
					<table class="academics sd">
						<thead>
							<tr>
								<th rowspan="2" width="60" class="grade">Kelas</th>
								<th width="180" class="term-first">Cawu/Semester I</th>
								<th width="180" class="term-middle">Cawu/Semester II</th>
								<th width="180" class="term-final">Cawu III</th>
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
				</li>
			</ol>
			<h1>Sekolah Lanjutan Tingkat Pertama (SMP/MTs)</h1>
			<ol>
				<li>
					<label for="pendidikan_smp-nama_sekolah" class="main-point">Nama Sekolah</label>
					<?php $form->text('pendidikan_smp_nama_sekolah', 'long'); ?>
				</li>
				<li>
					<label for="pendidikan_smp-tahun_ijazah" class="main-point">Tahun Ijazah</label>
					<?php $form->select_year('pendidikan_smp_tahun_ijazah', 2010, 2008); ?>
				</li>
				<li>
					<label class="main-point">Data Prestasi</label>
					<table class="academics smp">
						<thead>
							<tr>
								<th rowspan="2" width="60" class="grade">Kelas</th>
								<th width="270" class="term-first">Semester I</th>
								<th width="270" class="term-final">Semester II</th>
							</tr>
							<tr>
								<th colspan="3">Ranking ke ... dari ... siswa atau Rata-Rata Nilai (jika tidak ada ranking)</th>
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
			<h1>Sekolah Lanjutan Tingkat Atas (SMA/SMK/MA)</h1>
			<ol>
				<li>
					<label for="pendidikan_sma-nama_sekolah" class="main-point">Nama Sekolah</label>
					<?php $form->text('pendidikan_sma_nama_sekolah', 'long'); ?>
				</li>
				<li>
					<label for="pendidikan_sma-alamat_sekolah-alamat" class="main-point">Alamat Sekolah</label>
					<?php $form->address('pendidikan_sma_alamat_sekolah', false, false, false, $telepon = true, $hp = false, $fax = true, $email = false); ?>
				</li>
				<li>
					<label for="pendidikan_sma-nama_kepala_sekolah" class="main-point">Nama Kepala Sekolah</label>
					<?php $form->text('pendidikan_sma_nama_kepala_sekolah', 'long'); ?>
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
					<label class="main-point">Data Prestasi</label>
					<table class="academics sma">
						<thead>
							<tr>
								<th rowspan="2" width="60" class="grade">Kelas</th>
								<th width="270" class="term-first">Semester I</th>
								<th width="270" class="term-final">Semester II</th>
							</tr>
							<tr>
								<th colspan="3">Ranking ke ... dari ... siswa atau Rata-Rata Nilai (jika tidak ada ranking)</th>
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
				</li>
			</ol>
			<h1>Pengetahuan Bahasa</h1>
			<ol>
				<li>
					<label class="main-point" for="pengetahuan_bahasa_inggris_berapa_lama">Sudah berapa lama Anda belajar Bahasa Inggris?</label>
					<?php $form->text('pengetahuan_bahasa_inggris_berapa_lama', 'short') ?>
				</li>
				<li>
					<label class="main-point" for="pengetahuan_bahasa_lain_apa">Bahasa lain yang Anda kuasai/pelajari</label>
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
					<label class="main-point" for="pengetahuan_bahasa_lain_apa">Cita-cita</label>
					<?php $form->text('pengetahuan_bahasa_lain_apa', 'short') ?>
				</li>
			</ol>
		</fieldset>

		<fieldset id="kegiatan">
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
		
		<fieldset id="referensi">
			<legend>Referensi</legend>
			<ol>
				<li>
					<label class="main-point" for="pernah_pergi_jangka_pendek">Pernahkah Anda melawat/berpergian dalam jangka pendek ke luar negeri?</label>
					<?php $form->select('pernah_pergi_jangka_pendek', array('Tidak pernah', 'Pernah'), 'medium-short') ?>
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
					<label class="main-point" for="pernah_pergi_jangka_panjang">Pernahkah Anda melawat/berpergian dalam jangka panjang ke luar negeri?</label>
					<?php $form->select('pernah_pergi_jangka_panjang', array('Tidak pernah', 'Pernah'), 'medium-short') ?>
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
					<label class="main-point" for="kegiatan_pergi_jangka_panjang">Kegiatan Anda selama di sana?</label>
					<?php $form->text('kegiatan_pergi_jangka_panjang', 'medium') ?>
				</li>
				<li>
					<label class="main-point descriptive">Adakah di antara keluarga besar Anda yang pernah mengikuti program pertukaran yang diselenggarakan oleh Bina Antarbudaya/AFS? Jika iya:</label>
					<br clear="all">
					<label class="main-point" for="nama_relasi_pernah_ikut">Nama</label>
					<?php $form->text('nama_relasi_pernah_ikut', 'medium') ?>
					<br>
					<label class="main-point" for="hubungan_relasi_pernah_ikut">Hubungan dengan Anda</label>
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
					<label class="main-point">Pernahkah Anda/keluarga turut berpartisipasi dalam kegiatan Bina Antarbudaya/AFS?</label>
					<br clear="all">
					<label class="main-point" for="nama_kegiatan_yba_pernah_diikuti">Kegiatan</label>
					<?php $form->text('nama_kegiatan_yba_pernah_diikuti', 'medium') ?>
					<br>
					<label class="main-point" for="tahun_kegiatan_yba_pernah_diikuti">Tahun</label>
					<?php $form->select_year('tahun_kegiatan_yba_pernah_diikuti', 2011, 1970) ?>
				</li>
				<li>
					<label class="main-point" for="referral">Dari mana anda mengetahui program kami?</label>
					<br>
					<?php $form->textarea('referral'); ?>
				</li>
				<li>
					<label class="main-point" for="motivasi">Apa motivasi Anda mengikuti seleksi dan program Bina Antarbudaya?</label>
					<br>
					<?php $form->textarea('motivasi'); ?>
				</li>
			</ol>
		</fieldset>
		<fieldset id="rekomendasi">
			<legend>Rekomendasi</legend>
			<p>Sebutkan nama 3 (tiga) orang di luar keluarga Anda yang mengenal diri Anda secara pribadi untuk menuliskan surat rekomendasi bagi Anda.</p>
			<h1>Lingkungan sekolah (Kepala Sekolah atau Guru) <span>minimal berusia 21 tahun</span></h1>
		</fieldset>
		<?php
	}
}

?>