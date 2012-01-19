<?php
	$index = $chapter ?
				'Chapter ' . $chapter->chapter_name :
				'Seluruh Pengguna';

	switch ($this->_action()) {
		case 'create':
			$title = 'Pengguna Baru';
			break;
		case 'prefs':
			$title = 'Pengaturan';
			break;
		case 'index':
		default:
			$title = 'Pengelolaan Pengguna';
	}
?>
<header class="page-title">
	<h1><?php echo $title ?></h1>
</header>
<?php 
if ($this->user->capable_of('chapter_admin') && $this->_action() != 'prefs')
	$this->actions_nav(array(	'index' => $index,
								'create' => 'Pengguna Baru' )); ?>