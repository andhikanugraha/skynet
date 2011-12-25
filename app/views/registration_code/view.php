<?php $this->header(); ?>
<div class="container">
	<?php if ($error): ?>
	<div class="message error">
		<header>Pembukaan PIN gagal</header>
		<p><?php echo $error ?></p>
	</div>
	<?php else: ?>
	<div class="message">
		<header>Perhatian</header>
		<p>Pastikan tidak ada PIN pendaftaran yang tercetak dua kali.</p>
		<p><a href="#" onclick="window.print()">Cetak laman ini</a></p>
	</div>
<?php

$expires_on->set_locale('id');
$exp = $expires_on->format('l, j F Y, H.i.s ') . $timezone;

?>
	<table class="codes">
		<thead>
			<tr>
				<td colspan="2">Chapter <strong><?php echo $chapter_name ?></strong> - Berlaku sampai <strong><?php echo $exp ?></strong></td>
			</tr>
		</thead>
		<?php
		foreach ($codes as $i => $b):
		?>
		<?php if ($i % 12 == 0 && $i != 0): ?>
		</tbody>
	</table>
	
	<table class="codes">
		<thead>
			<tr>
				<td colspan="2">Chapter <strong><?php echo $chapter_name ?></strong> - Berlaku sampai <strong><?php echo $exp ?></strong></td>
			</tr>
		</thead>
		<tbody>
		<?php endif; if ($i % 2 == 0) echo '<tr>' ?>

			<td class="chapter_name">
				<span class="header">PIN Pendaftaran Seleksi Bina Antarbudaya</span>
					<img src="<?php L('/assets/dove.png') ?>" alt="">
				<span class="chapter-name">Chapter <strong><?php echo $chapter_name ?></strong></span>
				<span class="token"><?php echo $b->token ?></span>
				<span class="expires-on">Berlaku sampai <strong><?php echo $exp ?></strong></span>
				<span class="footer"><?php L('/') ?></span>
			</td>
		<?php if ($i % 2 == 1) echo '</tr>' ?>

		<?php endforeach; ?>
		</tbody>
	</table>
	
	<?php endif; ?>
</div>
<?php $this->footer(); ?>