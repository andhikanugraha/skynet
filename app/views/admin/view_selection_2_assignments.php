<?php $this->header('Selection 2 Assignments'); $prefix = Helium::conf('applicant_prefix'); ?>
<header class="stage-title">
	<h1>Administration</h1>
	<h2>Selection 2 Assignments</h2>
</header>
<div class="container">
	<form class="context" action="<?php L($this->params) ?>" method="POST">
		<?php if ($saved): ?>
		<div class="notice" id="notice">
			<strong>Saved.</strong>
		</div>
		<?php endif; ?>

		<?php foreach (array('personality', 'english') as $type): ?>
		<?php $first_letter = strtoupper($type[0]); $title = ucfirst($type) ?>
		<section id="<?php echo $type; ?>">
			<header><?php echo $title ?></header>
			<div class="chambers">
				<?php foreach ($chambers[$type] as $chamber => $rows): ?>
				<article>
					<header>Chamber <strong><?php echo $first_letter . str_pad($chamber, 2, '0', STR_PAD_LEFT); ?></strong></header>
					<table class="interview_order">
						<?php foreach ($rows as $row): ?>
						<tr>
							<td class="order"><?php echo $row->order ?></td>
							<td class="applicant_id"><?php echo str_replace($prefix, '<span>' . $prefix . '</span>', Applicant::get_test_id($row->applicant_id));  ?></td>
							<td class="full_name"><a href="<?php L(array('controller' => 'applicant', 'action' => 'confirm', 'id' => $row->applicant_id)) ?>"><?php echo $row->full_name ?></a></td>
							<td class="checkbox entered">
								<input type="hidden" name="<?php echo $type . "_o[{$row->applicant_id}|entered]" ?>" value="<?php echo (int) $row->entered ?>">
								<label><?php $checked = $row->entered ? ' checked' : ''; ?>
								<input type="checkbox" name="<?php echo $type . "[{$row->applicant_id}|entered]" ?>"<?php echo $checked ?> value="1"> Entered</label>
							</td>
							<td class="checkbox exited">
								<input type="hidden" name="<?php echo $type . "_o[{$row->applicant_id}|exited]" ?>" value="<?php echo (int) $row->exited ?>">
								<label><?php $checked = $row->exited ? ' checked' : ''; ?>
								<input type="checkbox" name="<?php echo $type . "[{$row->applicant_id}|exited]" ?>"<?php echo $checked ?> value="1"> Exited</label>
							</td>
						</tr>
						<?php endforeach; ?>
					</table>
					<p class="save-refresh">
						<button type="submit">Save</button> <button type="reset" class="refresh">Refresh</button>
					</p>
				</article>
				<?php endforeach; ?>
			</div>
		</section>
		<?php endforeach; ?>
		<input type="hidden" name="last_tab" id="last_tab">
	</form>
</div>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script>
	$(document).ready(function(){
		$('.context').addClass('jsenabled');
		hide = '<?php echo ($last_tab == 'english') ? '#personality' : '#english' ?>';
		$(hide).addClass('hidden');
		$('#english, #personality').click(function() {
			other = (this.id == 'english') ? $('#personality') : $('#english');
			if ($(this).hasClass('hidden')) {
				other.addClass('hidden');
				$('.chambers', $(this)).hide();
				$(this).removeClass('hidden');
				$('.chambers', other).fadeOut('slow');
				$('.chambers', $(this)).fadeIn('slow');
			}
			$(this).blur();
			$('#last_tab').attr('value', this.id);
		})
		
		checkboxes_changed = false;
		$('td.checkbox input').change(function() {
			checkboxes_changed = true;
		})
		$('td.checkbox input').click(function() { $('form').submit(); });
		$('.refresh').click(function() { window.location.href = window.location.href });
		<?php if ($saved): ?>
		$('#notice').click(function(){$(this).slideUp()})
		window.setTimeout(function() { $('#notice').slideUp(); }, 3000)
		<?php endif; ?>
	})
</script>
<?php $this->footer(); ?>