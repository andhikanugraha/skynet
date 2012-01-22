<?php
$show = true;

switch ($this->_action()) {
	case 'index':
		$title = 'Pengelolaan Chapter';
		break;
	case 'create':
		$title = 'Chapter Baru';
		break;
	default:
		$show = false;
}

if ($show):
?>
<header class="page-title">
	<hgroup>
		<h1><a href="<?php L(array('action' => 'view', 'id' => 1)) ?>">Kantor Nasional</a></h1>
		<h2><?php echo $title ?></h2>
	</hgroup>
</header>
<?php $this->actions_nav(array(	'index' => 'Daftar Chapter',
								'create' => 'Chapter Baru' ));

endif;