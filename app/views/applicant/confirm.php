<?php $this->header('Applicant Control'); ?>
<header class="stage-title">
	<h1>Administration</h1>
	<h2>Applicant Control</h2>
</header>
<div class="user-create-wrapper">
	<?php if (!$notice && $error): ?>
	<p><?php echo $error; ?></p>
	<?php elseif (!$no_applicant): ?>
	<p class="picture">
		<?php if ($picture = $a->picture): ?>
		<img src="<?php L('uploads/'); echo $picture->cropped_filename; ?>" width="240" height="320" alt="">
		<?php endif; ?>
	</p>
	<?php if ($notice): ?>
	<p class="notice"><?php echo $notice; ?></p>
	<?php endif; ?>
	<p class="details">
		<?php echo $a->get_test_id($a->id); ?><br>
		<strong><?php echo $d->nama_lengkap; ?></strong><br>
		<strong><?php echo $d->sanitized_school(); ?></strong>
		<br>
		<?php
		$exp = $a->expires_on;
		if ($a->submitted):
		?>
		Already confirmed.
		<?php elseif ($exp->earlier_than('now')): ?>
		<span class="expired">Expired <?php echo $a->expires_on->format('d F Y') ?></span>
		<?php else: ?>
		Expires <?php echo $a->expires_on->format('d F Y') ?>
		<?php endif; ?>
		<?php if (!$a->finalized): ?>
		<br>
		Not yet finalized
		<?php endif; ?>
		<?php if ($this->session->user->capable_of('admin')): ?>
		<br>
		<a href="<?php L(array('controller' => 'applicant', 'action' => 'form', 'id' => $a->id)); ?>">View form</a>
		<?php endif; ?>
	</p>
	<?php if (!$notice && !$a->submitted && $a->finalized): ?>
	<form action="<?php L(array('controller' => 'applicant', 'action' => 'confirm')) ?>" method="POST">
	<p><input type="hidden" name="applicant_id" value="<?php echo $a->id ?>"><button type="submit" autofocus>Confirm</button></p>
	</form>
	<!--form action="<?php L(array('controller' => 'admin', 'action' => 'unfinalizer')) ?>" method="POST">
	<p><input type="hidden" name="applicant_id" value="<?php echo $a->id ?>"><button type="submit">Unfinalize</button></p>
	</form-->
	<?php endif; ?>
	<hr>
	<?php endif; ?>
	<form action="<?php L(array('controller' => 'applicant', 'action' => 'confirm')) ?>" method="GET">
	<p class="selector"><input type="text" name="id" placeholder="Applicant ID" autofocus> <button type="submit">View</button></p>
	</form>
</div>
<?php $this->footer(); ?>