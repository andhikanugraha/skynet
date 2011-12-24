<?php $this->header('Create Account'); ?>

	<header class="stage-title">
		<h1>Administration</h1>
		<h2>Create Account</h2>
	</header>
	<div class="container">
		<?php if ($success): ?>
		<div class="notice">
			User <strong><?php echo $user->username ?></strong> created.
			<form action="<?php L(array('controller' => 'admin', 'action' => 'login_bypass')) ?>" method="POST"><input type="hidden" name="user_id" value="<?php echo $row->user_id; ?>"><button class="use">Use</button></form>
		</div>
		<?php elseif ($error): ?>
		<div class="error">
			User creation failed.
		</div>
		<?php endif; ?>
		<form action="<?php L($this->params) ?>" method="POST">
			<p>
				<label for="username">Username</label>
				<input type="text" name="username" id="username" value="<?php echo $this->session->flash('username'); ?>" autofocus required>
			</p>
			<p class="pw">
				<label for="password">Password</label>
				<input type="password" name="password" id="password" required>
			</p>
			<p>
				<label for="email">E-mail address</label>
				<input type="email" name="email" id="email" value="<?php echo $this->session->flash('email'); ?>" required>
			</p>
			<p>
				<label for="role">Role</label>
				<select name="role" id="role">
					<?php foreach ($roles as $role): ?>
					<option value="<?php echo $role; ?>"><?php echo $role ?></option>
					<?php endforeach; ?>
				</select>
			<p>
				<button type="submit">Create</button>
			</p>
		</form>
	</div>
<?php $this->footer(); ?>