<header class="page-title alt">
	<h1>Pengelolaan Chapter</h1>
</header>
<?php if ($this->user->capable_of('national_admin')): ?>
	<?php $this->actions_nav(array(	'index' => 'Seluruh Chapter',
									'create' => 'Chapter Baru' )) ?>
<?php endif; ?>