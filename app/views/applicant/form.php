<?php $this->header('Formulir Pendaftaran'); ?>
<script>document.write('<style>.global-nav, .content {display: none}</style>');</script>
<script src="<?php L('/assets/js/jquery-1.6.2.min.js') ?>"></script>
<header class="page-title">
	<p>Tahap 3/5</p>
	<h1>Formulir Pendaftaran</h1>
</header>
<nav class="actions-nav">
	<ul>
		<li><a href="<?php L(array('action' => 'guide')) ?>">Panduan Pendaftaran</a></li>
		<li class="expires-on">Batas waktu pendaftaran: <span><?php echo $expires_on->format('l, j F Y') ?></span></li>
	</ul>
</nav>
<div class="container">

	<?php if ($new): ?>
	<div class="message extended">
		<header>
			<h1>Selamat datang di Formulir Pendaftaran</h1>
		</header>
		<p>Formulir ini terdiri atas <strong>sembilan bagian</strong> yang dapat diakses melalui tautan pada menu di sebelah kiri. Isilah seluruh formulir ini dengan <strong>lengkap</strong> dan <strong>teliti</strong>. Gunakan tombol <em>Simpan Sementara</em> di sebelah kanan atas ini untuk menyimpan sementara isian formulir untuk diisi kembali.</p>
		<?php if (!$admin): ?><p>Setelah Adik selesai mengisi <strong>seluruh</strong> formulir ini, klik 'Finalisasi' di menu sebelah kiri.<br>Ingat, waktu Adik hanya sampai <strong><?php echo $this->applicant->expires_on->format('l, j F Y'); ?></strong>.</p><?php endif; ?>
		<p><a href="#" onclick="$(this.parentNode.parentNode).slideUp()">Sembunyikan pesan ini</a></p>
	</div>

	<?php elseif ($errors): ?>
	<div class="message errors">
		<p><strong>Finalisasi gagal karena:</strong></p>
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
		<p><a href="#" onclick="$(this.parentNode.parentNode).slideUp()">Sembunyikan pesan ini</a></p>
	</div>

	<?php endif; ?>

	<script>document.write('<style>.message {display: none}</style>');</script>


<form action="<?php L($this->params) ?>" enctype="multipart/form-data" method="POST">

<nav class="form-nav">
	<header>
		<h1>Pilih Halaman</h1>
	</header>
	<ol>
		<li><a href="#pribadi">Data Pribadi</a></li>
		<li><a href="#program">Pilihan Program</a></li>
		<li><a href="#keluarga">Keluarga</a></li>
		<li><a href="#pendidikan">Pendidikan</a></li>
		<li><a href="#kegiatan">Kegiatan</a></li>
		<li><a href="#kepribadian">Kepribadian</a></li>
		<li><a href="#referensi">Referensi</a></li>
		<li><a href="#rekomendasi">Rekomendasi</a></li>
		<li><a href="#foto">Foto</a></li>
		<?php if (!$readonly): ?>
		<li class="finalize"><strong><a href="#finalisasi">Finalisasi</a></strong></li>
		<?php endif; ?>
	</ol>
</nav>

<div class="form-fields">


<?php if (!$readonly): ?>
<p class="save-button">	
	<input type="hidden" name="last_pane" id="lastpane" value="#pribadi">
	<button type="submit">Simpan<?php if (!$admin): ?> Sementara<?php endif; ?></button>
</p>
<?php endif; ?>

<!-- begin form -->

<fieldset class="pane" id="pribadi">
	<legend>Data Pribadi</legend>
	<table class="form-table">
		<tr>
			<td class="label"><?php $form->label('full_name', 'Nama Lengkap', 'required') ?></td>
			<td class="field"><?php $form->text('full_name', 'long'); ?> <span class="instruction">Isi sesuai dengan Akte Kelahiran.</span></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('applicant_address_street', 'Alamat Lengkap', 'required') ?></td>
			<td class="field"><?php $form->address('applicant'); ?> <span class="instruction">Isilah dengan lengkap agar tidak terjadi salah pengiriman surat.</span></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('place_of_birth', 'Tempat dan Tanggal Lahir', 'required') ?></td>
			<td class="field">
				<?php $form->text('place_of_birth', 'medium') ?>
				<br>
				<?php $form->date('date_of_birth'); ?>
			</td>
		</tr>
		<tr>
			<td class="label"><?php $form->label('sex', 'Jenis Kelamin', 'required') ?></td>
			<td class="field">
				<?php $form->radio('sex', 'F') ?> Perempuan
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
				<?php $form->radio('sex', 'M') ?> Laki-laki
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
				<?php $form->select('blood_type', array('O' => 'O', 'A' => 'A', 'B' => 'B', 'AB' => 'AB'), 'very-short')?>
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
		<tr>
			<td class="combined" colspan="2"><?php $form->label('is_telkomsel_customer', 'Apakah Adik menggunakan nomor Telkomsel (Halo/Simpati/As)?', 'inline required') ?> <?php $form->select('is_telkomsel_customer', array('Tidak', 'Ya'), 'short'); ?></td>
		</tr>
		<tr>
			<td class="combined" colspan="2"><?php $form->label('is_telkomsel_school_community_member', 'Apakah Adik anggota Telkomsel School Community?', 'inline required') ?> <?php $form->select('is_telkomsel_school_community_member', array('Tidak', 'Ya'), 'short'); ?></td>
		</tr>
		<!--
		<tr>
			<td class="label"><?php $form->label('n', 'A', 'required') ?></td>
			<td class="field">
				
			</td>
		</tr>
		-->
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
				<p>For more than 90 years in the world and 55 years in Indonesia, AFS Program has offered young people exciting and dynamic learning opportunities through intercultural programs. At its core are programs that include physical exchanges, service learning, and volunteerism that directly impact youth engagement and create opportunities that build long-lasting understanding and respect for differences.</p>
				<p>AFS Program gives you opportunities to develop yourself to become young Indonesian future leaders by having a worthwhile exchanges experience in another country, where you would learn about yourselves, learning how we perceive others who are especially different from us, learn how to build a good relationship, positive communication and ability to work together in fostering respect, empathy and understanding towards others.</p>
				<p>You will experience living with a host family and attend school in a foreign country. Of course, you will definitely experience a totally new way of life, new culture, lifestyle and family values, new language and finally gain intercultural skills.</p>
				<p>To make the most of your experience, create good relationship with your host family, get involved in the many curricular and extracurricular activities available at your local school and local community. AFS also organizes activities so that you can meet other participants from over 50 AFS countries to provide you with the ultimate intercultural experience!</p>
				<p>If you are interested in experiencing a “new world”, having an one year experience abroad as a young Indonesian ambassador, sign up for the AFS Year program!</p>
			</td>
			<td class="yes">
				<p>The Kennedy-Lugar Youth Exchange and Study (YES) Program was established in October, 2002 and sponsored by The Bureau of Educational and Cultural Affairs (ECA) to provide scholarships for high school students from countries with significant Muslim populations to spend up to one academic year in the U.S.</p>
				<p>Students will spend one year in United States of America. Students live with host families, attend high school, and engage in activities to learn about American society and values, acquire leadership skills, and help educate Americans about Indonesia and our cultures.</p>
				<p>Upon their return the students will apply their leadership skills in Indonesia. In addition, alumni groups will help participants continue to be involved with many community service activities including: mentoring younger children, and much more.</p>
				<p>YES can support students with disabilities and encourages their participation. The Bureau of Educational and Cultural Affairs (ECA) works with a separate organization to provide students with disabilities with leadership-building workshops and appropriate information and support as needed to enhance their year in the United States.</p>
				<p>The YES program is administered by the YES Consortium and its partners in each country.</p>
			</td>
		</tr>
	</table>
</fieldset>

<fieldset class="pane" id="keluarga">
	<!-- poin 9–11 -->
	<legend>Keluarga</legend>
	<?php

	foreach(array('father' => 'Ayah', 'mother' => 'Ibu') as $n => $parent):
		$n = strtolower($parent);
	?>
	<h1><?php echo $parent; ?></h1>
	<table class="form-table">
		<tr>
			<td class="label"><?php $form->label($n . '_full_name', "Nama Lengkap $parent", 'required') ?></td>
			<td class="field">
				<?php $form->text($n . '_full_name', 'long'); ?>
				<br>
				<span class="instruction">Isilah dengan nama lengkap.</span>
			</td>
		</tr>
		<tr>
			<td class="label"><?php $form->label($n . '_education', 'Pendidikan Terakhir') ?></td>
			<td class="field"><?php $form->text($n . '_education', 'long'); ?></td>
		</tr>
		<tr>
			<td class="label"><?php $form->label($n . '_occupation', 'Pekerjaan/Jabatan') ?></td>
			<td class="field">
				<?php $form->text($n . '_occupation', 'long'); ?>
				<br>
				<span class="instruction long">Isilah dengan rinci &ndash; bila wiraswasta, cantumkan bidangnya; bila swasta, cantumkan jabatan dan nama perusahaannya.</span>
			</td>
		</tr>
		<!-- Remove Pangkat/Golongan for now; it's a bit absurd -->
		<!-- <tr>
			<td class="label"><?php $form->label($n . '_job_title', 'Pangkat/Golongan') ?></td>
			<td class="field"><?php $form->text($n . '_job_title', 'long'); ?></td>
		</tr> -->
		<tr>
			<td class="label"><?php $form->label($n . '_office_name', 'Nama dan Alamat Kantor') ?></td>
			<td class="field">
				<?php $form->text($n . '_office_name', 'long'); ?>
				<br>
				<?php $form->address($n . '_office', false, false, false, true, true, true, true) ?>
			</td>
		</tr>
	</table>
	<?php endforeach; ?>

	<?php

	foreach(array('guardian' => 'Wali') as $n => $parent):
		$n = strtolower($parent);
	?>
	<h1>Wali <span>(bilamana orang tua telah wafat atau siswa tinggal terpisah dengan orang tua)</span></h1>
	<table class="form-table">
		<tr>
			<td class="label"><?php $form->label('guardian_full_name', "Nama Lengkap $parent", 'required') ?></td>
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
			<td class="label"><?php $form->label('guardian_address_street', 'Pendidikan Terakhir') ?></td>
			<td class="field"><?php $form->address('guardian', false, false, false, true, true, false, false) ?></td>
		</tr>
		<!-- The field below is in the DB schema but not the original form -->
		<!-- <tr>
			<td class="label"><?php $form->label('guardian_education', 'Pendidikan Terakhir') ?></td>
			<td class="field"><?php $form->text('guardian_education', 'long'); ?></td>
		</tr> -->
		<tr>
			<td class="label"><?php $form->label('guardian_occupation', 'Pekerjaan/Jabatan') ?></td>
			<td class="field">
				<?php $form->text('guardian_occupation', 'long'); ?>
				<br>
				<span class="instruction long">Isilah dengan rinci &ndash; bila wiraswasta, cantumkan bidangnya; bila swasta, cantumkan jabatan dan nama perusahaannya.</span>
			</td>
		</tr>
		<!-- Remove Pangkat/Golongan for now; it's a bit absurd -->
		<!-- <tr>
			<td class="label"><?php $form->label('guardian_job_title', 'Pangkat/Golongan') ?></td>
			<td class="field"><?php $form->text('guardian_job_title', 'long'); ?></td>
		</tr> -->
		<tr>
			<td class="label"><?php $form->label('guardian_office_name', 'Nama dan Alamat Kantor') ?></td>
			<td class="field">
				<?php $form->text('guardian_office_name', 'long'); ?>
				<br>
				<?php $form->address('guardian_office', false, false, false, true, false, true, true) ?>
			</td>
		</tr>
	</table>
	<?php endforeach; ?>

	<h1>Saudara</h1>
	<table class="form-table siblings">
		<tr>
			<td class="label noc"><?php $form->label('number_of_children_in_family', 'Jumlah anak dalam keluarga', 'required') ?></td>
			<td class="field noc"><?php $form->number('number_of_children_in_family', 'very-short'); ?></td>
			<td class="label nth"><?php $form->label('nth_child', 'Adik anak nomor', 'required') ?></td>
			<td class="field nth"><?php $form->number('nth_child', 'very-short'); ?></td>
		</tr>
		<tr>
			<td colspan="4" class="combined">
				<span>Nama, umur, dan sekolah/pekerjaan saudara kandung (selain Adik sendiri)</span>
				<br>
				<table class="siblings-table">
					<thead>
						<tr>
							<th>Nama Lengkap</th>
							<th>Tanggal Lahir</th>
							<th>Sekolah/Pekerjaan</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($sibling_forms as $s):
						?>
						<tr>
							<td class="sibling-name"><?php $s->text('full_name', 'short') ?></td>
							<td class="sibling-dob"><?php $s->date('date_of_birth', 50) ?></td>
							<td class="sibling-job"><?php $s->text('occupation', 'short') ?></td>
						</tr>
						<?php endforeach; ?>
						<?php $s = new FormDisplay; $s->make_subform('siblings[#]'); ?>
						<tr class="prototype">
							<td class="sibling-name"><?php $s->text('full_name', 'short') ?></td>
							<td class="sibling-dob"><?php $s->date('date_of_birth', 50) ?></td>
							<td class="sibling-job"><?php $s->text('occupation', 'short') ?></td>
						</tr>
					</tbody>
				</table>
<script>
	$(document).ready(function() {
		fac = function() {
			$('td.sibling-name input').each(function() {
				t = $(this);
				if (!t.parent().parent().hasClass('prototype')) {
					if (t.val())
						t.parent().parent().removeClass('engineered').addClass('notempty');
					else
						t.parent().parent().addClass('engineered').removeClass('notempty');
				}
			})
			
			v = parseInt($(this).val());
			o = $('.siblings-table tbody tr').length - 1;
			if (v > o) {
				d = v - o - 1;
				for (i=0; i<d; i++) {
					$('.siblings-table tbody').append($('.prototype').clone().removeClass('prototype'));
				}
			}
			if (v <= o) {
				d = o - v + 1;
				for (i=0; i<d; i++) {
					$('tr.engineered').first().detach();
				}
			}
		}
		$('#number_of_children_in_family').click(fac);
		$('#number_of_children_in_family').change(fac);
		$('#number_of_children_in_family').keyup(fac);
	})
</script>
			</td>
		</tr>
	</table>
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
			<?php $form->checkbox('pesantren') ?> Sekolah saya adalah Pesantren/Madrasah
		</li>
		<li>
			<?php $form->checkbox('akselerasi') ?> Saya adalah siswa kelas Akselerasi
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

<script>

$(document).ready(function(){
	$('span.phone-number input').focus(function(){$(this.parentNode).addClass('focus')});
	$('span.phone-number input').blur(function(){$(this.parentNode).removeClass('focus')});

	uv = function() {
		if ($('#finalize').attr('checked')) {
			$('#finalize-button:parent').fadeIn('fast').focus();
		}
		else
			$('#finalize-button:parent').fadeOut();
	}
	$('#finalize').change(uv)

	uv();
	
	function switchToTab(activeTab, direct) {
		if ($(activeTab).hasClass('pane')) {
			$(".form-nav li a").removeClass("active"); //Remove any "active" class
			$(".form-nav li a[href='" + activeTab + "']").addClass("active"); //Add "active" class to selected tab

			$("fieldset.pane").removeClass('active').hide(); //Hide all tab content
	
			$("#lastpane").val(activeTab);
		
			if (activeTab == '#finalisasi')
				$('.save-button').slideUp();
			else {
				$('.save-button').slideDown();
				$('#finalize').removeAttr('checked');
				uv();
			}
			if (direct)
				$(activeTab).addClass('active').show();
			else
				$(activeTab).addClass('active').fadeIn('medium'); //Fade in the active ID content
		}
	}

	<?php if (!$last_pane) $last_pane = 'pribadi'; ?>

	switchToTab('<?php echo '#' . $last_pane ?>', true)

	//On Click Event
	$(".form-nav li a").click(function() {
		var activeTab = $(this).attr("href"); //Find the href attribute value to identify the active tab + content

		switchToTab(activeTab);
		
		if (history.pushState)
			history.pushState(activeTab, $(this).text(), activeTab);

		return false;
	});

	if (history.pushState) {
		window.onpopstate = function(e) {
			if (e.state)
				switchToTab(e.state);
			else if (window.location.hash)
				switchToTab(window.location.hash, true);
			else
				switchToTab('<?php echo '#' . $last_pane ?>');
		}
	}

	<?php if ($new): ?>
	$('.message').hide();
	$('.global-nav').slideDown('slow', function() {$('.content').fadeIn('slow', function() { $('.message').slideDown() })});
	<?php else: ?>
	$('.global-nav, .content').fadeIn('fast', function() { $('.message').slideDown() })
	<?php endif; ?>
	
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
<br clear="all">
</div>
<?php $this->footer(); ?>