<?php $this->header('Session Switcher'); ?>

	<header class="stage-title">
		<h1>Administration</h1>
		<h2>Session Switcher</h2>
	</header>
<div class="container">
	<p>Use this page to switch into another user's session without having to know their password.</p>
	<p>Here is a list of usernames followed by their real names and origin school.</p>
	<p>Your session token is <strong><?php echo $this->session->token ?></strong>.</p>
	<table class="users">
		<?php foreach ($rows as $row): ?>
		<tr>	
			<td><form action="<?php L($this->params) ?>" method="POST"><input type="hidden" name="session_id" value="<?php echo $row->session_id; ?>"><button class="use">Use</button></form></td>
			<td><strong><?php echo $row->username; ?></strong></td>
			<td><?php echo $row->nama_lengkap; ?></td>
			<td><?php echo $row->pendidikan_sma_nama_sekolah; ?></td>
		</tr>
		<?php endforeach; ?>
	</table>
</div>
<?php $this->footer(); ?>