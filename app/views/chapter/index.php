<?php $this->header(); ?>
<div class="container">
	<table class="chapters">
		<?php foreach ($chapters as $chapter): ?>
		<tr>
			<td class="chapter-code"><?php echo $chapter->chapter_code ?></td>
			<td class="chapter-name"><?php echo $chapter->chapter_name ?></td>
			<td class="chapter-address"><?php echo $chapter->chapter_address ?></td>
			<td class="chapter-email"><?php echo $chapter->chapter_email ?></td>
			<td class="chapter-applicant-count"><span><?php echo $chapter->get_applicant_count() ?></span> applicants</td>
			<td class="chapter-user-count"><span><?php echo $chapter->get_user_count() - $chapter->get_applicant_count() ?></span> volunteer users</td>
		</tr>
			
		<?php endforeach;?>
	</table>
</div>
<?php $this->footer(); ?>