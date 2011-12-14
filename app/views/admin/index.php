<?php $this->header('Switchboard'); ?>

	<header class="stage-title">
		<h1>Administration</h1>
		<h2>Switchboard</h2>
	</header>
<div class="container">
	<p class="option"><a href="<?php L(array('controller' => 'admin', 'action' => 'stats')) ?>">Registration Statistics</a></p>
	<hr>
	<?php if ($this->is_migratable()): ?>
	<p class="option"><a href="<?php L(array('controller' => 'admin', 'action' => 'migrate_applicants')) ?>">Migrate Applicants</a></p>
	<?php else: ?>
	<p class="option"><a href="<?php L(array('controller' => 'admin', 'action' => 'prepare_selection_3')) ?>">Prepare Selection 3</a></p>
	<hr>
	<p class="option"><a href="<?php L(array('controller' => 'admin', 'action' => 'prepare_selection_2')) ?>">Prepare Selection 2</a></p>
	<p class="option"><a href="<?php L(array('controller' => 'admin', 'action' => 'view_selection_2_assignments')) ?>">Selection 2 Assignments</a></p>
	<hr>
	<p class="option"><a href="<?php L(array('controller' => 'admin', 'action' => 'edit_chamber_info')) ?>">Selection 1 Chambers</a></p>
	<?php endif; ?>
	<hr>
	<p class="option"><a href="<?php L(array('controller' => 'admin', 'action' => 'applicant_list')) ?>">Applicant List</a></p>
	<?php if ($this->is_migratable()): ?>
	<p class="option"><a href="<?php L(array('controller' => 'applicant', 'action' => 'confirm')) ?>">Confirm Applicant</a></p>
	<p class="option"><a href="<?php L(array('controller' => 'admin', 'action' => 'unfinalizer')) ?>">Unfinalizer</a></p>
	<p class="option"><a href="<?php L(array('controller' => 'admin', 'action' => 'issue_registration_code')) ?>">Issue PINs</a></p>
	<?php endif; ?>
	<hr>
	<p class="option"><a href="<?php L(array('controller' => 'admin', 'action' => 'create_account')) ?>">Create Account</a></p>
	<p class="option"><a href="<?php L(array('controller' => 'admin', 'action' => 'login_bypass')) ?>">Login Bypass</a></p>
	<p class="option"><a href="<?php L(array('controller' => 'admin', 'action' => 'session_switcher')) ?>">Session Switcher</a></p>
</div>
<?php $this->footer(); ?>