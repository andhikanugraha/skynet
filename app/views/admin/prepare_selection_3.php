<?php $this->header('Prepare Selection 2'); ?>
<header class="stage-title">
	<h1>Administration</h1>
	<h2>Prepare Selection 3</h2>
</header>
<div class="container <?php echo $stage ?>">
<?php
switch ($stage):
case 'confirm':
$prefix = Helium::conf('applicant_prefix');
?>
<h2>Confirm Results</h2>
<p>Please verify that the following applicants have indeed passed selection 2.</p>
<table class="applicant_list">
	<?php foreach ($applicants as $applicant): ?>
	<tr>
		<td class="id"><?php echo str_replace($prefix, '<span>' . $prefix . '</span>', Applicant::get_test_id($applicant->applicant_id)); ?></td>
		<td class="name"><?php echo $applicant->full_name ?></td>
		<td class="chamber">P<?php echo str_pad($applicant->personality_chamber_id, 2, '0', STR_PAD_LEFT) ?></td>
	</tr>
	<?php endforeach; ?>
</table>
<p class="plan_summary"><strong><?php echo $applicants->count() ?></strong> applicants will be assigned into <strong><?php echo $shift_count ?></strong> re-registration shifts.</p>
<form action="<?php L($this->params) ?>" method="POST">
	<p>
		<input type="hidden" name="stage" value="process">
		<button type="submit">Continue</button>
	</p>
</form>
<?php
break;
case 'process':
?>
<h2>Success</h2>
<p>Applicants who have passed selection 2 have successfully been assigned shifts for selection 3 re-registration. They may now view the results on this portal.</p>
<?php
break;
default:
?>
<?php if ($errors): ?>
<div class="notice errors">
	<p><strong>Migration failed</strong> because of:</p>
	<ul>
		<?php foreach ($errors as $error): ?>
		<li><?php echo $error; ?></li>
		<?php endforeach; ?>
	</ul>
	<p><a href="#" onclick="$(this.parentNode.parentNode).slideUp()">Hide this message</a></p>
</div>
<?php endif; ?>
<h2>Input Selection 2 Results</h2>
<form action="<?php L($this->params) ?>" method="POST">
	<table class="input_table">
		<tr>
			<td>
				<p>IDs of applicants who have passed selection 1: (one per line)</p>
				<p><textarea name="applicants" class="applicant_id_box" required><?php echo htmlentities($applicants) ?></textarea></p>
			</td>
			<td>
				<p>Number of shifts:</p>
				<p><input type="number" name="shift_count" class="chamber_count" value="<?php echo htmlentities($shift_count) ?>" required></p>
				<p>
					<input type="hidden" name="stage" value="confirm">
					<button type="submit">Continue</button>
				</p>
			</td>
		</tr>
	</table>
</form>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<?php endswitch;?>
</div>
<?php $this->footer(); ?>