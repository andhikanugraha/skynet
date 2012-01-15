<?php $this->header();
?>
<div class="container">
	<?php if ($message): ?>
	<div class="message">
		<p><?php
		
		switch ($message) {
			case 'user_created':
				echo 'Pembuatan akun pengguna berhasil.';
				break;
			case 'user_edited':
				echo 'Akun pengguna berhasil diubah.';
				break;
			default:
				echo $message;
		}
		?>
	</div>
	<?php endif; ?>

	<table class="users">
		<thead>
			<tr>
				<th class="username">Nama Pengguna</th>
				<th class="chapter">Chapter</th>
				<th class="role">Peran</th>
				<th class="email">Surel</th>
				<th class="edit"></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($users as $u): ?>
			<tr class="role<?php echo $u->role ?>">
				<td class="username"><a href="<?php L(array('action' => 'edit', 'id' => $u->id)) ?>"><?php echo $u->username ?></a></td>
				<td class="chapter"><?php echo $u->chapter->chapter_name ?></td>
				<td class="role"><?php echo $role_names[$u->role] ?></td>
				<td class="email"><?php echo $u->email ?></td>
			</tr>
		
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php $this->footer(); ?>