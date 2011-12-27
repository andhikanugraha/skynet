<?php $this->header('Switchboard'); ?>
<header class="page-title">
	<h1>National Administration</h1>
</header>
<div class="container">
	<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
	<p class="option"><a href="<?php L(array('controller' => 'chapter', 'action' => 'index')) ?>">Chapters</a></p>
	<p class="option"><a href="<?php L(array('controller' => 'registration_code', 'action' => 'index')) ?>">PIN Pendaftaran</a></p>
</div>
<?php $this->footer(); ?>