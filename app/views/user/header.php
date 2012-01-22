<?php
	$index = $chapter ?
				'Chapter ' . $chapter->chapter_name :
				'Seluruh Pengguna';

	switch ($this->_action()) {
		case 'create':
			$title = 'Pengguna Baru';
			break;
		case 'prefs':
			$hide_h1 = true;
			$title = 'Pengaturan Akun';
			break;
		case 'index':
		default:
			$title = 'Pengelolaan Pengguna';
	}
?>
<header class="page-title">
	<hgroup>
		<?php if (!$hide_h1): ?><h1><a href="<?php L(array('controller' => 'chapter', 'action' => 'view', 'chapter_code' => $applicant->chapter->chapter_code)) ?>"><?php echo $this->user->chapter->get_title() ?></a></h1><?php endif; ?>
		<h2><?php echo $title ?></h2>
	</hgroup>
</header>
<?php 
if ($this->user->capable_of('chapter_admin') && $this->_action() != 'prefs')
	$this->actions_nav(array(	'index' => 'Daftar Pengguna',
								'create' => 'Pengguna Baru' )); ?>