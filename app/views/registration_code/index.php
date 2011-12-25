<?php $this->header(); ?>
<div class="container">
	<!-- <div class="message">
		<header>Perhatian</header>
		<p>Pastikan tidak ada PIN pendaftaran yang tercetak dua kali.</p>
	</div> -->
	<table class="batches">
		<?php foreach ($batches as $b): ?>
		<tr>
			<td class="chapter_name"><?php echo $b->chapter_name ?></td>
			<td class="expires_on"><?php echo $b->expires_on->format('l, j F Y, H.i.s ') . __($b->expires_on->getTimezone()->getName()) ?></td>
			<td class="view"><a href="<?php echo $b->view_link ?>">view</a></td>
		</tr>

		<?php endforeach; ?>
	</table>
</div>
<?php $this->footer(); ?>