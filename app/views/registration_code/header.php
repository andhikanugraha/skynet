<header class="page-title">
	<hgroup>
		<h1><a href="<?php L(array('controller' => 'chapter', 'action' => 'view', 'chapter_code' => $applicant->chapter->chapter_code)) ?>"><?php echo $this->user->chapter->get_title() ?></a></h1>
		<h2>Pengelolaan PIN Pendaftaran</h2>
	</hgroup>
</header>
<?php $this->actions_nav(array(	'index' => 'Sudah Terbit',
								'issue' => 'Terbitkan Baru' )) ?>
