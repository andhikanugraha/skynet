<?php $this->header('Prepare Selection 2'); ?>
<header class="stage-title">
	<h1>Administration</h1>
	<h2>Prepare Selection 2</h2>
</header>
<div class="container <?php echo $stage ?>">
<?php
switch ($stage):
case 'confirm':
$prefix = Helium::conf('applicant_prefix');
?>
<h2>Confirm Results</h2>
<p>Please verify that the following applicants have indeed passed selection 1.</p>
<table class="applicant_list">
	<?php foreach ($applicants as $applicant): ?>
	<tr>
		<td class="id"><?php echo str_replace($prefix, '<span>' . $prefix . '</span>', Applicant::get_test_id($applicant->applicant_id)); ?></td>
		<td class="name"><?php echo $applicant->full_name ?></td>
		<td class="chamber"><?php echo $applicant->selection1_chamber_id ?></td>
	</tr>
	<?php endforeach; ?>
</table>
<p class="plan_summary"><strong><?php echo $applicants->count() ?></strong> applicants will be assigned into <strong><?php echo $personality_chamber_count ?></strong> personality chambers and <strong><?php echo $english_chamber_count ?></strong> English chambers.</p>
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
<p>Applicants who have passed selection 1 have successfully been assigned chambers for selection 2. They may now view the results on this portal.</p>
<p><a href="<?php L(array('controller' => 'admin', 'action' => 'view_selection_2_assignments')); ?>">View chamber assignments</a></p>
<?php /*
<h2>Process Test</h2>
<table class="applicant_list">
	<?php foreach ($a as $k => $v): ?>
	<tr>
		<td class="id"><?php echo str_replace($prefix, '<span>' . $prefix . '</span>', Applicant::get_test_id($k)); ?></td>
		<td class="chamber">Shift <?php echo $v['shift'] ?> / P<?php echo $v['personality']?> / E<?php echo $v['english'] ?></td>
	</tr>
	<?php endforeach; ?>
</table>

<?php foreach (array('Personality' => $p, 'English' => $e) as $label => $x): ?>
<?php foreach ($x as $c => $a): ?>
<h2><?php echo $label; ?> Chamber <?php echo $c ?></h2>
<p>
	<?php foreach ($a as $k => $v): ?>
	Turn <?php echo $k ?>: Applicant <?php echo $v['applicant_id'] ?> / Shift <?php echo $v['shift']?><br>
	<?php endforeach; ?>
</p>
<?php endforeach; ?>
<?php endforeach; ?>

<pre>
<?php echo implode("\n", $q); ?>
<?php
*/
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
<h2>Input Selection 1 Results</h2>
<form action="<?php L($this->params) ?>" method="POST">
	<table class="input_table">
		<tr>
			<td>
				<p>IDs of applicants who have passed selection 1: (one per line)</p>
				<p><textarea name="applicants" class="applicant_id_box" required><?php echo htmlentities($applicants) ?></textarea></p>
			</td>
			<td>
				<p>Number of personality chambers:</p>
				<p><input type="number" name="personality_chamber_count" class="chamber_count" value="<?php echo htmlentities($personality_chamber_count) ?>" required></p>
				<p>Number of English chambers:</p>
				<p><input type="number" name="english_chamber_count" class="chamber_count" value="<?php echo htmlentities($english_chamber_count) ?>" required></p>
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