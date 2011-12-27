<?php $this->header('Switchboard'); ?>

	<header class="stage-title">
		<h1>Volunteer</h1>
		<h2>Switchboard</h2>
	</header>
<div class="container">
	<p class="option"><a href="<?php L(array('controller' => 'admin', 'action' => 'view_selection_2_assignments')) ?>">Selection 2 Assignments</a></p>
	<hr>
	<p class="option"><a href="<?php L(array('controller' => 'admin', 'action' => 'stats')) ?>">Statistics</a></p>
	<hr>
	<p class="option"><a href="<?php L(array('controller' => 'applicant', 'action' => 'confirm')) ?>">Confirm Applicant</a></p>
	<p class="option"><a href="<?php L(array('controller' => 'admin', 'action' => 'unfinalizer')) ?>">Unfinalizer</a></p>
</div>
<?php $this->footer(); ?>