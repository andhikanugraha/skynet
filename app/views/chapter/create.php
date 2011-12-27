<?php $this->header(); ?>
<div class="container">
	<?php if ($error): ?>
	<div class="message error">
		<header>Penambahan chapter gagal</header>
		<p>
		<?php
		
		switch ($error) {
			case 'incomplete_form':
				echo 'Formulir tidak lengkap.';
				break;
			case 'chapter_code_conflict':
				echo "Chapter dengan kode $chapter_code sudah ada. Gunakan kode chapter lain.";
				break;
			case 'chapter_name_conflict':
				echo "Chapter dengan nama $chapter_name sudah ada. Gunakan nama chapter lain.";
				break;
			case 'password_mismatch':
				echo 'Sandilewat tidak sesuai.';
				break;
			case 'password_too_short':
				echo 'Sandilewat kurang panjang.';
				break;
			case 'chapter_addition_failed':
			case 'user_addition_failed':
			default:
				echo 'Coba sekali lagi.';
				break;
		}
		
		?>
		</p>
	</div>
	<?php elseif ($success): ?>
	<div class="message ">
		<header>Penambahan chapter berhasil</header>
	</div>
	<?php endif; ?>
	<form action="<?php L(array('action' => 'create')) ?>" method="POST">
		<fieldset class="chapter-details">
			<legend>Informasi Chapter</legend>
			<div class="fields">
				<table class="form-table">
					<tr>
						<td class="label"><?php $form->label('chapter_code', 'Kode Chapter', 'required')?></td>
						<td class="field"><?php $form->text('chapter_code', 'medium', 3, true) ?></td>
					</tr>
					<tr>
						<td class="label"><?php $form->label('chapter_name', 'Nama Chapter', 'required')?></td>
						<td class="field"><?php $form->text('chapter_name', 'medium', 45, true) ?></td>
					</tr>
					<tr>
						<td class="label"><?php $form->label('chapter_address', 'Alamat Chapter')?></td>
						<td class="field"><?php $form->textarea('chapter_address') ?></td>
					</tr>
					<tr>
						<td class="label"><?php $form->label('chapter_email', 'E-mail Chapter')?></td>
						<td class="field"><?php $form->email('chapter_email' , 'medium') ?></td>
					</tr>
					<tr>
						<td class="label"><?php $form->label('chapter_timezone', 'Zona Waktu Chapter', 'required')?></td>
						<td class="field"><?php $form->select('chapter_timezone', $timezones) ?></td>
					</tr>
					<tr>
						<td class="label"><?php $form->label('chapter_area', 'Daerah Jangkauan', 'required')?></td>
						<td class="field"><?php $form->province('chapter_area', 'medium', '(Menjangkau lebih dari satu provinsi)') ?></td>
					</tr>
					<tr>
						<td class="label"><?php $form->label('facebook_url', 'Alamat Facebook')?></td>
						<td class="field"><?php $form->text('facebook_url', 'medium', 45, null, 'http://facebook.com/') ?> <span class="instruction">Kosongkan apabila tidak ada.</span></td>
					</tr>
					<tr>
						<td class="label"><?php $form->label('twitter_username', 'Twitter @name')?></td>
						<td class="field"><?php $form->text('twitter_username', 'medium', 20) ?> <span class="instruction">Kosongkan apabila tidak ada.</span></td>
					</tr>
					<tr>
						<td class="label"><?php $form->label('site_url', 'Alamat Situs')?></td>
						<td class="field"><?php $form->text('site_url', 'medium', null, null, 'http://') ?> <span class="instruction">Kosongkan apabila tidak ada.</span></td>
					</tr>
				</table>
			</div>
		</fieldset>

		<fieldset class="contacts">
			<legend>Narahubung</legend>
			<div class="fields">
				<table class="form-table">
					<tr>
						<td class="label"><?php $form->label('contact_person_name', 'Nama Narahubung')?></td>
						<td class="field"><?php $form->text('contact_person_name') ?> <span class="instruction">Nama PO Seleksi atau panitia seleksi lainnya yang akan berhubungan dengan peserta seleksi.</span></td>
					</tr>
					<tr>
						<td class="label"><?php $form->label('contact_person_phone', 'Nomor Telepon Narahubung')?></td>
						<td class="field"><?php $form->tel('contact_person_phone', 'medium', null, null) ?></td>
					</tr>
				</table>
			</div>
		</fieldset>

		<fieldset class="chapter-account">
			<legend>Akun Administrasi</legend>
			<div class="fields">
				<table class="form-table">
					<tr>
						<td class="label"><?php $form->label('user[username]', 'Nama Pengguna', 'required')?></td>
						<td class="field"><?php $form->text('user[username]', 'medium', null, true) ?> <span class="instruction">Akun pengguna ini akan digunakan untuk mengelola seleksi pada chapter ini.</span></td>
					</tr>
					<tr>
						<td class="label"><?php $form->label('user[password]', 'Sandilewat', 'required')?></td>
						<td class="field"><?php $form->password('user[password]', 'medium', null, true) ?> <span class="instruction">Terdiri atas paling sedikit delapan karakter.</span></td>
					</tr>
					<tr>
						<td class="label"><?php $form->label('user[confirm_password]', 'Ulang Sandilewat', 'required')?></td>
						<td class="field"><?php $form->password('user[confirm_password]', 'medium', null, true) ?></td>
					</tr>
				</table>
			</div>
		</fieldset>
		
		<fieldset class="save">
			<p class="save-button"><button type="submit">Simpan</button></p>
			<p class="next-action"><?php $form->checkbox('create_again') ?> <?php $form->label('create_again', 'Kembali ke laman ini') ?>
		</fieldset>
	</form>
	<br clear="all">
</div>
<?php $this->footer(); ?>