<?php $this->header(); ?>
<div class="container">
	<form action="<?php L(array('action' => 'create')) ?>" method="POST">
		<fieldset class="chapter-details">
			<legend>Chapter Information</legend>
			<table class="form-table">
				<tr>
					<td class="label"><?php $form->label('chapter_code', 'Chapter Code')?></td>
					<td class="field"><?php $form->text('chapter_code', 'medium', 3, true) ?></td>
				</tr>
				<tr>
					<td class="label"><?php $form->label('chapter_name', 'Chapter Name')?></td>
					<td class="field"><?php $form->text('chapter_name', 'medium', null, true) ?></td>
				</tr>
				<tr>
					<td class="label"><?php $form->label('chapter_address', 'Chapter Address')?></td>
					<td class="field"><?php $form->textarea('chapter_address') ?></td>
				</tr>
				<tr>
					<td class="label"><?php $form->label('facebook_url', 'Facebook URL')?></td>
					<td class="field"><?php $form->text('chapter_facebook_url', 'medium', null, null, 'http://facebook.com/') ?></td>
				</tr>
				<tr>
					<td class="label"><?php $form->label('twitter_username', 'Twitter @name')?></td>
					<td class="field"><?php $form->text('chapter_twitter_username') ?></td>
				</tr>
				<tr>
					<td class="label"><?php $form->label('site_url', 'Website URL')?></td>
					<td class="field"><?php $form->text('site_url', 'medium', null, null, 'http://') ?></td>
				</tr>
			</table>
		</fieldset>
	</form>
</div>
<?php $this->footer(); ?>