<?php $this->header('Edit Chamber Info'); ?>

<header class="stage-title">
	<h1>Administration</h1>
	<h2>Edit Selection 1 Chambers</h2>
</header>
<div class="container">
	<?php if ($success): ?>
	<p class="notice">Chambers have been updated.</p>
	<?php endif; ?>
	<form action="<?php echo L($this->params) ?>" method="POST">
	<table>
		<thead>
			<tr>
				<th></th>
				<th>Chamber Name</th>
				<th>Venue</th>
				<th>Subvenue</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($chambers as $chamber):
		$base = 'chambers[' . $chamber->id . ']'; ?>
		<tr>
			<td class="id"><?php echo $chamber->id; ?>
			<td class="chamber_name"><input type="text" name="<?php echo $base; ?>[chamber_name]" value="<?php echo $chamber->chamber_name; ?>" placeholder="Chamber Name"></td>
			<td class="venue"><input type="text" name="<?php echo $base; ?>[venue]" value="<?php echo $chamber->venue; ?>" placeholder="Venue"></td>
			<td class="subvenue"><input type="text" name="<?php echo $base; ?>[subvenue]" value="<?php echo $chamber->subvenue; ?>" placeholder="Subvenue"></td>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<p><button type="submit">Save</button></p>
	</form>
	<p><a href="<?php L(array('controller' => 'admin', 'action' => 'output_chambers_list_xlsx')) ?>">Download chamber list in XLSX format</a></p>
</div>
<script>
el = document.getElementById('calc');
function calculate(inp) { el.innerHTML = Math.ceil(<?php echo $eligible_applicants_count; ?> / inp.value) }
</script>
<?php $this->footer(); ?>