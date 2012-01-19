<?php $this->header() ?>
<div class="container">
	<header class="chapter-header">
		<?php if (!$national) : ?><p class="chapter-code"><?php echo $chapter_code ?></p><?php endif; ?>
		<h1><?php if (!$national) echo 'Chapter '; echo $chapter_name ?></h1>
	</header>
	<section class="primary">
		<article class="registration-codes">
			<header>
				<h1>PIN Pendaftaran</h1>
			</header>
			<ul class="counts codes">
				<li class="issued"><strong><?php echo $code_count ?></strong> tercetak</li>
				<li class="activated"><strong><?php echo $activated_code_count ?></strong> terpakai</li>
				<li class="activated"><strong><?php echo $available_code_count ?></strong> tersedia</li>
				<li class="activated"><strong><?php echo $expired_code_count ?></strong> kadaluarsa</li>
			</ul>
			<p class="more"><a href="<?php L(array('controller' => 'registration_code', 'action' => 'issue', 'chapter_id' => $id)) ?>">Terbitkan PIN pendaftaran</a></p>
			<p class="more"><a href="<?php L(array('controller' => 'registration_code', 'action' => 'index', 'chapter_id' => $id)) ?>">Lihat daftar selengkapnya</a></p>
		</article>
		<article class="applicants-summary">
			<header>
				<h1>Jumlah Pendaftar</h1>
			</header>
			<ul class="counts applicants">
				<li class="total"><strong><?php echo $total_applicant_count ?></strong> total</li>
				<li class="active"><strong><?php echo $active_applicant_count ?></strong> aktif
					<?php if (!$applicant_tipping_point): ?>
					<ul>
						<li class="total"><strong><?php echo $confirmed_applicant_count ?></strong> terkonfirmasi</li>
						<li class="active"><strong><?php echo $finalized_applicant_count ?></strong> terfinalisasi</li>
						<li class="expired"><strong><?php echo $incomplete_applicant_count ?></strong> masih mengisi</li>
					</ul>
					<?php endif; ?>
				</li>
				<li class="expired"><strong><?php echo $expired_applicant_count ?></strong> kadaluarsa</li>
				<li class="expired"><strong><?php echo $anomalous_applicant_count ?></strong> anomali</li>
			</ul>
		</article>
		<article>
			<header>
				<h1>Pendaftar Terbaru</h1>
			</header>
			<table class="summary applicants">
				<?php
				foreach ($na as $a):
				?>
				<tr>
					<td class="field"><a href="<?php L(array('controller' => 'applicant', 'action' => 'view', 'id' => $a->id)) ?>"><?php echo $a->sanitized_full_name ?></a></td>
					<th class="label"><?php echo $a->get_short_test_id() ?></th>
				</tr>
				<?php endforeach; ?>
			</table>
			<p class="more"><a href="<?php L(array('controller' => 'applicant', 'action' => 'index', 'chapter_id' => $id)) ?>">Lihat daftar selengkapnya</a></p>
		</article>
	</section>
	<section class="secondary">
		<article class="chapter-info">
			<header>
				<h1>Informasi Chapter</h1>
			</header>
			<table class="summary">
				<tr>
					<th class="label">Alamat</th>
					<td class="field"><?php echo nl2br($chapter_address) ?></td>
				</tr>
				<tr>
					<th class="label">Jangkauan</th>
					<td class="field"><?php echo nl2br($chapter_area) ?></td>
				</tr>
				<?php if ($contact_person_name): ?>
				<tr>
					<th class="label">Narahubung</th>
					<td class="field"><?php
						echo nl2br($contact_person_name);
						if ($contact_person_phone)
							echo '<br>' . $contact_person_phone	?></td>
				</tr>
				<?php endif; ?>
				<?php if ($facebook_url || $twitter_username): ?>
				<tr>
					<th class="label">Jejaring Sosial</th>
					<td class="field"><?php
						if ($facebook_url)
							printf('<a href="%s">Facebook</a>', $facebook_url);
						if ($facebook_url && $twitter_username)
							echo '<br>';
						if ($twitter_username)
							printf('<a href="http://twitter.com/%s">@%1$s</a>', $twitter_username);
					?>
				</tr>
				<?php endif; ?>
				<?php if ($site_url): ?>
				<tr>
					<th class="label">Situs web</th>
					<td class="field"><?php
						if ($facebook_url)
							printf('<a href="%s">%s</a>', $site_url);
					?>
				</tr>
				<?php endif; ?>
				<?php if ($chapter_email): ?>
				<tr>
					<th class="label">Alamat Surel</th>
					<td class="field"><?php
						if ($facebook_url)
							printf('<a href="mailto:%s">%s</a>', $chapter_email);
					?>
				</tr>
				<?php endif; ?>
			</table>
			<p class="edit"><a href="<?php L(array('controller' => 'chapter', 'action' => 'edit', 'id' => $id)) ?>">Edit informasi chapter</a></p>
		</article>
		<article class="users">
			<header>
				<h1>Akun Pengguna</h1>
			</header>
			<p class="summary">
				Akun volunteer <?php if (!$national) echo 'Chapter '; echo $chapter_name ?> yang terdaftar:<br>
				<?php foreach ($volunteers as $v): ?>
				<a href="<?php L(array('controller' => 'user', 'action' => 'edit', 'id' => $v->id)) ?>"><?php echo $v->username ?></a>
				
				<?php endforeach; ?>
			</p>
			<p class="more"><a href="<?php L(array('controller' => 'user', 'action' => 'index')) ?>">Lihat daftar selengkapnya</a></p>
			<p class="more"><a href="<?php L(array('controller' => 'auth', 'action' => 'login')) ?>">Masuk sebagai pengguna lain</a></p>
			<p class="more"><a href="<?php L(array('controller' => 'user', 'action' => 'create')) ?>">Tambahkan akun pengguna baru</a></p>
		</article>
	</section>
	<!--
	<section class="tertiary">
		<article class="event-calendar">
			<header>
				<h1>Jadwal Kegiatan</h1>
			</header>
			<dl>
				<dt class="current">Pendaftaran</dt>
				<dd class="current">21 Jan - 22 Mar</dd>
				<dt>Seleksi Tahap Pertama</dt>
				<dd>27 Mar</dd>
				<dt>Seleksi Tahap Kedua</dt>
				<dd>19 Apr</dd>
			</dl>
		</article>
	</section>
	-->
</div>
<?php $this->footer() ?>