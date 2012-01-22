<?php $this->header(); ?>
<div class="container">
	<table class="chapters">
		<?php foreach ($chapters as $chapter): ?>
		<tr>
			<td class="chapter-code"><?php echo $chapter->chapter_code ?></td>
			<td class="chapter-name"><a href="<?php L(array('action' => 'view', 'chapter_code' => $chapter->chapter_code)) ?>"><?php echo $chapter->chapter_name ?></a></td>
			<td class="chapter-address"><?php echo nl2br($chapter->chapter_address) ?></td>
			<td class="chapter-email"><?php echo $chapter->chapter_email ?></td>
			<td class="actions">
				<p class="more"><a href="<?php L(array('controller' => 'applicant', 'action' => 'index', 'chapter_id' => $chapter->id)) ?>"><?php echo $chapter->get_applicant_count() ?> pendaftar</a></p>
				<p class="edit"><a href="<?php L(array('controller' => 'applicant', 'action' => 'index', 'chapter_id' => $chapter->id)) ?>">Edit informasi</a></p>
			</td>
		</tr>
			
		<?php endforeach;?>
	</table>
</div>
<?php $this->footer(); ?>