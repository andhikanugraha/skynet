<?php $this->header(); ?>
<div class="container">
	<!-- <div class="message">
		<header>Perhatian</header>
		<p>Pastikan tidak ada PIN pendaftaran yang tercetak dua kali.</p>
	</div> -->
	<header>
		<h1>PIN Pendaftaran<?php if ($chapter) echo 'Chapter ' . $chapter->chapter_name; ?></h1>
	</header>
	<dl class="batches">
		<?php $pc = ''; foreach ($batches as $b): ?>
		<?php if ($pc != $b->chapter_name): ?><dt>Chapter <?php echo $b->chapter_name ?></dt><?php $pc = $b->chapter_name; endif; ?>
		<dd><a href="<?php echo $b->view_link ?>"><?php echo $b->expires_on->format('l, j F Y') ?></a></dd>

		<?php endforeach; ?>
	</dl>
</div>
<?php $this->footer(); ?>