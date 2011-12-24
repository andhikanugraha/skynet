<nav class="actions-nav">
	<ul>
	<?php
		foreach ($actions as $action => $label):
			$is_active = ($this->params['action'] == $action);
	?>
		<li><a href="<?php L(compact('action')) ?>"<?php if ($is_active) { ?> class="active" <?php } ?>><?php echo $label ?></a></li>
	<?php
		endforeach;
	?>

	</ul>
</nav>