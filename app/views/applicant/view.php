<?php $this->header(); ?>
<header class="page-title alt">
	<h1>Pengelolaan Pendaftar</h1>
</header>
<nav class="actions-nav">
	<ul>
		<li><a href="<?php L($back_to) ?>">Kembali ke daftar</a></li>
	</ul>
</nav>

<div class="container">
	<?php if ($error): ?>
	<div class="message error">
		<header>Pendaftar tidak ditemukan</header>
		<p>Pendaftar yang dimaksud tidak ditemukan.</p>
	</div>
	<?php else: ?>
	
	<header class="applicant-header">
		<p class="applicant-test-id"><?php echo $applicant->finalized ? $applicant->test_id : 'Chapter ' . $applicant->chapter->chapter_name ?></h1>
		<h1 class="applicant-name"><?php echo $applicant->sanitized_full_name ?>&nbsp;</h1>
	</header>

	<div class="picture-container">
		<?php if ($picture): ?>
		<img src="<?php echo $picture->get_cropped_url(); ?>" width="300" height="400">
		<?php endif; ?>
	</div>
	
	<?php echo new HeliumDateTime('0000-00-00', 'Asia/Jakarta') ?><div class="form-preview">
		<h2>Tentang <?php echo $applicant->sanitized_full_name ?></h2>
		<table>
			<?php if ($applicant->finalized): ?>
			<tr>
				<td class="label">Chapter</td>
				<td class="field"><?php echo $applicant->chapter->chapter_name ?></td>
			</tr>
			
			<?php endif; ?>
			<tr>
				<td class="label">Asal Sekolah</td>
				<td class="field"><?php echo $applicant->sanitized_high_school_name ?></td>
			</tr>
			<tr>
				<td class="label">Tempat, Tgl Lahir</td>
				<td class="field"><?php echo $applicant->place_of_birth ?>, <?php echo $applicant->date_of_birth->format('j F Y') ?></td>
			</tr>
			<tr>
				<td class="label">Alamat Surel</td>
				<td class="field"><?php echo $applicant->applicant_email ?></td>
			</tr>
			<tr>
				<td class="label">No. Ponsel</td>
				<td class="field"><?php echo $applicant->applicant_mobilephone ?></td>
			</tr>
			<tr>
				<td class="label">Alamat Rumah</td>
				<td class="field">
					<?php echo nl2br($applicant->applicant_address_street) ?>
					<br>
					<?php
					echo $applicant->applicant_address_city;
					echo $applicant->applicant_address_postcode ? ' ' . $applicant->applicant_address_postcode : '';
					echo $applicant->applicant_address_city ? ', ' . $applicant->applicant_address_province : $applicant->applicant_address_province; ?>
				</td>
			</tr>
		</table>
		<p class="more"><a href="<?php L(array('controller' => 'applicant', 'action' => 'details', 'id' => $applicant->id)) ?>">Lihat formulir selengkapnya</a></p>
		<?php if ($can_edit): ?><p class="edit"><a href="<?php L(array('controller' => 'applicant', 'action' => 'form', 'id' => $applicant->id)) ?>">Edit formulir</a></p><?php endif; ?>
	</div>

	<div class="application-status">
		<?php
		$f = $applicant->finalized;
		$c = $applicant->confirmed;
		if ($f && $c):
		elseif ($f && !$c):
		?>
		<table>
			<tr>
				<td class="label">Status Pendaftaran</td>
				<td class="field"><strong>Belum konfirmasi</strong></td>
			</tr>
			<tr>
				<td class="label">Batas Pendaftaran</td>
				<td class="field"><?php echo $applicant->expires_on->format('j F Y') ?></td>
			</tr>
			<tr>
				<td class="label">Tanda Peserta</td>
				<td class="field"><a href="<?php L(array('controller' => 'applicant', 'action' => 'card', 'id' => $applicant->id)) ?>">Cetak</a></td>
			</tr>
		</table>

		<form action="<?php L(array('controller' => 'applicant', 'action' => 'view', 'id' => $applicant->id)) ?>" method="POST" class="confirm-form">
			<p>
				<input type="hidden" name="id" value="<?php echo $applicant->id ?>">
				<input type="hidden" name="finalized" value="1">
				<input type="hidden" name="confirmed" value="1">
				<button type="submit" class="confirm-button">Konfirmasi pendaftaran</button>
				<br>
				<span class="instruction">Lakukan konfirmasi hanya jika <?php echo $applicant->sanitized_full_name ?> telah melengkapi seluruh persyaratan pendaftaran.</span>
			</p>
		</form>
		<?php if ($this->user->capable_of('chapter_admin')): ?>
		<form action="<?php L(array('controller' => 'applicant', 'action' => 'view', 'id' => $applicant->id)) ?>" method="POST" class="confirm-form">
			<p>
				<input type="hidden" name="id" value="<?php echo $applicant->id ?>">
				<input type="hidden" name="finalized" value="0">
				<input type="hidden" name="confirmed" value="0">
				<button type="submit" class="unfinalize-button">Batalkan finalisasi</button>
				<br>
				<span class="instruction">Pembatalan finalisasi dilakukan hanya jika <?php echo $applicant->sanitized_full_name ?> salah mengisi formulir pendaftarannya.</span>
			</p>
		</form>
		<?php endif; ?>

		<?php
		elseif (!$f && $c):
			// Anomaly. Let's fix it while we can.
			$applicant->confirmed = false;
			$applicant->save();
		else:
		?>
		<table>
			<tr>
				<td class="label">Status Pendaftaran</td>
				<td class="field"><strong><?php echo $applicant->is_expired() ? 'Kadaluarsa' : 'Belum finalisasi' ?></strong></td>
			</tr>
			<tr>
				<td class="label">Batas Pendaftaran</td>
				<td class="field"><?php echo $applicant->expires_on->format('j F Y') ?></td>
			</tr>
		</table>

		<?php
		endif;
		?>
	</div>

	<?php endif; ?>
</div>