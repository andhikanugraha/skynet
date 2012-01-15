<?php
switch ($this->_action()) {
	case 'create':
		$title = 'Chapter Baru';
		$alt = false;
		break;
	case 'edit':
		$title = 'Edit Chapter';
		$alt = true;
		break;
	case 'index':
		$alt = false;
		$title = 'Pengelolaan Chapter';
		break;
	case 'view':
	default:
		$alt = true;
		$title = 'Pengelolaan Chapter';
}

?>
<header class="page-title<?php if ($alt) echo ' alt' ?>">
	<h1><?php echo $title ?></h1>
</header>
<?php if ($this->user->capable_of('national_admin')): ?>
	<?php $this->actions_nav(array(	'index' => 'Seluruh Chapter',
									'create' => 'Chapter Baru' )) ?>
<?php endif; ?>