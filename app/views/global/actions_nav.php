<nav class="actions-nav">
	<ul>
	<?php
		foreach ($actions as $action => $label):
			$is_active = ($this->_action() == $action);
	?>
		<li><a href="<?php L(compact('action')) ?>"<?php if ($is_active) { ?> class="active" <?php } ?>><?php echo $label ?></a></li>
	<?php
		endforeach;
		
		if ($back_to):
	?>
		<li><a href="<?php L($back_to) ?>">Kembali</a></li>
	<?php endif; ?>
	</ul>
</nav>