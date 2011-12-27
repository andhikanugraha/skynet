<?php $this->header('Unfinalizer'); ?>

	<header class="stage-title">
		<h1>Administration</h1>
		<h2>Unfinalizer</h2>
	</header>
<div class="container">
	<form action="<?php L($this->params) ?>" method="POST">
		<p><input type="text" name="applicant_id"> <button type="submit">Unfinalize</button></p>
	</form>
</div>
<?php $this->footer(); ?>