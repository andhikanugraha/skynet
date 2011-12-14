<?php $this->header('Applicant List');

global $p;
$p = $this->params;
function LL($target) {
	global $p;
	$pi = array_merge($p, $target);
	L($pi);
}
?>

	<header class="stage-title">
		<h1>Administration</h1>
		<h2>Applicant List</h2>
	</header>
<div class="container">
	<div class="search">
		<p><?php $start = microtime(); ?></p>
		<p class="page-selector filter">
			<strong><?php if ($applicants) echo $applicants->count_all(); else echo 0; ?></strong> applicants &middot; Go to page
		<?php for ($i = 1; $i <= $applicants->get_number_of_batches(); $i++): ?>
			<a href="<?php LL(array('page' => $i)) ?>"<?php if ($i == $this->params['page']) echo 'class="active"' ?>><?php echo $i ?></a>
		<?php endfor; ?>
		</p>
		<p class="stage-selector filter">
			<?php

			foreach ($stages as $stage => $label):
			?>
			<a href="<?php LL(array('stage' => $stage, 'page' => 1)) ?>"<?php if ($stage == $this->params['stage']) echo 'class="active"' ?>><?php echo $label ?></a>
			<?php endforeach; ?>
		</p>
	</div>
	<table class="applicants">
		<?php foreach ($applicants as $a): 
		$classes = '';
		if (!$a->finalized) $classes .= 'unfinalized '; 
		if ($a->submitted) $classes .= 'submitted '; 
		elseif ($a->expired) $classes .= 'expired ';
		$exp = $a->expires_on;
		?>
		<tr class="<?php echo $classes; ?>">
			<td width="10%"><?php echo $a->get_test_id($a->id); ?></td>
			<td width="30%"><b><a href="<?php L(array('controller' => 'applicant', 'action' => 'confirm', 'id' => $a->id)) ?>"><?php echo $a->applicant_detail->sanitized_name(); ?></a></b></td>
			<td width="30%"><?php echo $a->applicant_detail->sanitized_school(); ?></td>
			<td width="20%">
			Expire<?php echo ($a->expired) ? 'd' : 's' ?> <?php echo $exp->format('d F Y'); ?>
			</td>
			<td width="10%">
			<?php if ($a->submitted): ?>
			Confirmed
			<?php elseif ($a->finalized): ?>
			<form action="<?php L($this->params) ?>" method="POST"><input type="hidden" name="applicant_id" value="<?php echo $a->id; ?>"><button class="use">Unfinalize</button></form>
			<?php endif; ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	<p><a href="<?php LL(array('output' => 'xlsx')) ?>">Download in Excel 2007+ format</a></p>
	<!-- Query took <?php echo microtime() - $start; ?> seconds. -->
</div>
<?php $this->footer(); ?>