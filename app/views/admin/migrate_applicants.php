<?php $this->header('Migrate Applicants'); ?>

<header class="stage-title">
	<h1>Administration</h1>
	<h2>Migrate Applicants</h2>
</header>
<div class="container">
	<?php if ($success): ?>
	<p><strong><?php echo $count; ?></strong> applicants have been migrated. Now you may <a href="<?php L(array('controller' => 'admin', 'action' => 'edit_chamber_info')) ?>">edit chamber info</a>.</p>
	<?php elseif ($migratable): ?>
	<p>Once all registration procedures have finished, you may migrate the applicant data in preparation for selections. During migration, selection 1 chambers will also be automatically assigned. This process cannot be redone.</p>
	<p>There are <strong><?php echo $eligible_applicants_count ?></strong> applicants eligible for selections.</p>
	<form action="<?php L($this->params) ?>" method="POST">
		<p><input type="number" name="capacity" value="20" width="4" required onkeyup="calculate(this)"> people/chamber = <span class="calculator" id="calc"><?php echo ceil($eligible_applicants_count / 20) ?></span><span class="calculator"> chambers</span></p>
		<p><button type="submit">Migrate</button></p>
	</form>
	<?php else: ?>
	<p>Migration has been executed and cannot be redone.</p>
	<?php endif; ?>
</div>
<script>
el = document.getElementById('calc');
function calculate(inp) { el.innerHTML = Math.ceil(<?php echo $eligible_applicants_count; ?> / inp.value) }
</script>
<?php $this->footer(); ?>