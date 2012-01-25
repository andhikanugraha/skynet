<?php $this->header(); ?>
<div class="container">
	<?php if ($this->user->capable_of('national_admin')): ?>
	<section class="batches verbose">
		<header>
			<h1>PIN Pendaftaran Nasional</h1>
		</header>
		<table class="batches">
			<thead>
				<tr>
					<th class="number">No.</th>
					<th class="chapter">Chapter</th>
					<th class="created-at">Tanggal Penerbitan</th>
					<th class="expires-on">Tanggal Kadaluarsa</th>
					<th class="generated-by">Akun Penerbit</th>
					<th class="total-count">Jumlah</th>
					<th class="available-count">Tersedia</th>
					<th class="view"></th>
					<th class="print"></th>
				</tr>
			</thead>
			<tbody>
				<?php $i = 0; foreach ($batches as $b): ?>
				<tr>
					<td class="number"><?php echo ++$i; ?></td>
					<td class="chapter"><?php echo $b->chapter->chapter_name ?></td>
					<td class="created-at"><?php echo $b->created_at->format('l, j F Y') ?></td>
					<td class="expires-on"><?php echo $b->expires_on->format('l, j F Y') ?></td>
					<td class="generated-by"><?php echo $b->get_generator()->username ?></td>
					<td class="total-count"><?php echo $b->code_count ?></td>
					<td class="available-count"><?php echo $b->get_available_code_count() ?></td>
					<td class="view"><a href="<?php echo $b->get_view_link() ?>">Lihat</a></td>
					<td class="print"><a href="<?php echo $b->get_print_link() ?>">Cetak</a></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</section>
	<?php else: ?>
		<section class="batches">
			<header>
				<h1>PIN Pendaftaran <?php echo $chapter->get_title() ?></h1>
			</header>
			<table class="batches">
				<thead>
					<tr>
						<th class="number">No.</th>
						<th class="created-at">Tanggal Penerbitan</th>
						<th class="expires-on">Tanggal Kadaluarsa</th>
						<th class="total-count">Jumlah</th>
						<th class="available-count">Tersedia</th>
						<th class="view"></th>
						<th class="print"></th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 0; foreach ($batches as $b): ?>
					<tr>
						<td class="number"><?php echo ++$i; ?></td>
						<td class="created-at"><?php echo $b->created_at->format('l, j F Y') ?></td>
						<td class="expires-on"><?php echo $b->expires_on->format('l, j F Y') ?></td>
						<td class="total-count"><?php echo $b->code_count ?></td>
						<td class="available-count"><?php echo $b->get_available_code_count() ?></td>
						<td class="view"><a href="<?php echo $b->get_view_link() ?>">Lihat</a></td>
						<td class="print"><a href="<?php echo $b->get_print_link() ?>">Cetak</a></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</section>
		<section class="help">
			<header>
				<h1>Tentang PIN Pendaftaran</h1>
			</header>
			<dl class="help">
				<dt>Apa itu PIN Pendaftaran?</dt>
				<dd>PIN Pendaftaran adalah kode berupa 16 huruf yang digunakan untuk pendaftaran seleksi Bina Antarbudaya. Bagi pendaftar, mengaktifkan PIN Pendaftaran adalah langkah awal pendaftaran seleksi. Tanpa PIN Pendaftaran, pendaftar tidak dapat membuat akun dan mendaftar untuk proses seleksi Bina Antarbudaya. Bagi chapter, PIN Pendaftaran menjadi mekanisme pendapatan, dengan cara chapter menjual PIN Pendaftaran sebagai biaya administrasi seleksi. Namun, sistem pendaftaran online ini tidak dapat mencatat berapa PIN yang <em>terjual</em>, hanya berapa PIN yang <em>diaktifkan</em>, karena PIN Pendaftaran dapat dibeli namun tidak digunakan.</dd>
			</dl>
		</section>
	<?php endif; ?>
</div>
<?php $this->footer(); ?>