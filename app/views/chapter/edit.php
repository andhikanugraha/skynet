<?php $this->header(); ?>

<?php if ($chapter): ?>
<header class="page-title">
	<hgroup>
		<h1><a href="<?php L(array('controller' => 'chapter', 'action' => 'view', 'chapter_code' => $chapter->chapter_code)) ?>"><?php echo $chapter->get_title() ?></a></h1>
		<h2>Edit Informasi Chapter</h2>
	</hgroup>
</header>
<nav class="actions-nav">
	<ul>
		<li><a href="<?php L(array('controller' => 'chapter', 'action' => 'view', 'chapter_code' => $chapter->chapter_code)) ?>" class="return">Kembali</a></li>
	</ul>
</nav>
<?php endif; ?>

<div class="container">
	<?php if ($error): ?>
	<div class="message error">
		<header>Penyuntingan chapter gagal</header>
		<p>
		<?php
		
		switch ($error) {
			case 'forbidden':
				echo 'Akses ditolak.';
				break;
			case 'not_found':
				echo 'Chapter tidak ditemukan.';
				break;
			default:
				echo 'Coba sekali lagi.';
				break;
		}
		
		?>
		</p>
	</div>
	<?php elseif ($success): ?>
	<div class="message ">
		<header>Informasi chapter berhasil disimpan.</header>
	</div>
	<?php endif; ?>
	<form action="<?php L(array('action' => 'edit', 'id' => $chapter->id)) ?>" method="POST">
		<fieldset class="chapter-details">
			<legend>Informasi Chapter</legend>
			<div class="fields">
				<table class="form-table">
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
		
		<fieldset class="save">
			<p class="save-button"><button type="submit">Simpan</button></p>
			<p class="next-action"><?php $form->checkbox('create_again') ?> <?php $form->label('create_again', 'Kembali ke laman ini') ?>
		</fieldset>
	</form>
	<br clear="all">
</div>
<?php $this->footer(); ?>