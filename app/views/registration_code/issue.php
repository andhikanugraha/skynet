<?php $this->header();
$timezone_r = $can_choose_chapter ? 'waktu setempat' : $timezone; ?>
<div class="container">
	<header>
		<h1>Terbitkan PIN Pendaftaran</h1>
	</header>
	<form action="<?php L(array('action' => 'issue')) ?>" method="POST">
		<table class="form-table">
			<tr>
				<td class="label"><?php $form->label('chapter_id', 'Chapter') ?></td>
				<td class="field">
					<?php
					if ($can_choose_chapter)
						$form->select('chapter_id', $chapters, 'medium-short');
					else
						echo "<strong>{$this->session->user->chapter->chapter_name}</strong>";
					?>
				</td>
			</tr>
			<tr>
				<td class="label"><?php $form->label('number_of_codes', 'Jumlah PIN yang ingin diterbitkan') ?></td>
				<td class="field"><?php $form->number('number_of_codes', 'short count', 4, true, '100') ?></td>
			</tr>
			<tr>
				<td class="label"><?php $form->label('expires_on', 'Berlaku sampai ') ?></td>
				<td class="field">
					<?php $form->date('expires_on', 0, -1) ?>
					<span class="instruction">pukul <strong>23.59.59 <span id="timezone"><?php echo $timezone_r ?></span></strong></span>
				</td>
			</tr>
			<tr>
				<td class="label"></td>
				<td class="field"><button type="submit">Terbitkan</button></td>
			</tr>
		</table>
	</form>
</div>
<?php $this->footer(); ?>