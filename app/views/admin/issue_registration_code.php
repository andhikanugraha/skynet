<?php $this->header(); ?>
<header class="stage-title">
	<h1>Administration</h1>
	<h2>PIN Creation</h2>
</header>
<div class="container">
	<?php if ($codes): ?>
	<p class="expires_on">Berikut adalah 500 PIN yang kadaluarsa pada <strong><?php echo $expires_on; ?></strong></p>
	<table class="codes">
	<tr>
	<?php
		$newrow = false;
		foreach ($codes as $code) {
			echo '<td>' . $code . '</td>';
			if ($newrow) {
				echo '</tr><tr>
				';
				$newrow = false;
			}
			else
				$newrow = true;
		}
	?>
	</tr>
	</table>
	<?php endif; ?>
	<form action="<?php L($this->params); ?>" method="POST">
		<button type="submit">Buat 500 PIN yang kadaluarsa tanggal <?php echo $expires_on->format('Y-m-d'); ?></button>
	</form>
</div>
<?php $this->footer(); ?>