<?php $this->header(); ?>
<div class="container">
	<?php if ($error): ?>
	<div class="message error">
		<header>
			<h1>Pembuatan Akun Pengguna Gagal</h1>
		</header>
		<p><?php echo $error; ?></p>
	</div>
	<?php endif; ?>

	<form action="<?php L(array('action' => 'create')) ?>" method="POST" validate>
		<table class="form-table">
			<tr class="first">
				<td class="label"><?php $form->label('username', 'Nama pengguna', 'required') ?></td>
				<td class="field"><input type="text" name="username" id="username" class="medium" value="<?php echo $this->session->flash('username'); ?>" autofocus required> <span class="instruction">Terdiri atas paling sedikit empat karakter, dan hanya boleh terdiri atas huruf, angka, garisbawah (_), tanda sambung (-). Tidak boleh mengandung spasi.</span></td>
			</tr>
			<tr>
				<td class="label"><?php $form->label('password', 'Sandilewat', 'required')?></td>
				<td class="field"><input type="password" name="password" class="medium" id="password" required> <span class="instruction">Terdiri atas paling sedikit delapan karakter.</span></td>
			</tr>
			<tr>
				<td class="label"><?php $form->label('retype_password', 'Ulang Sandilewat', 'required')?></td>
				<td class="field"><input type="password" name="retype_password" id="retype_password" class="medium" required></td>
			</tr>
			<tr>
				<td class="label">Peran</td>
				<td class="field"><?php $form->select('role', $roles, 'medium') ?></td>
			</tr>
			<?php if ($national): ?>
			<tr>
				<td class="label">Chapter</td>
				<td class="field"><?php $form->select('chapter_id', $chapters, 'medium') ?></select>
			</tr>
			<?php endif; ?>
			<tr>
				<td class="label"><?php $form->label('email', 'Alamat surel', 'required') ?></td>
				<td class="field"><input type="email" name="email" id="email" class="medium" value="<?php echo $this->session->flash('email'); ?>" required></td>
			</tr>
			<tr>
				<td class="label"><?php $form->label('afterwards', 'Setelah menyimpan&hellip;') ?></td>
				<td class="field"><?php $form->select('afterwards', array(0 => 'Kembali ke daftar pengguna', 1 => 'Kembali ke laman ini', 2 => 'Masuk dengan pengguna baru'), 'medium') ?>
			</tr>
			<tr>
				<td class="label"></td>
				<td class="field">
					<button type="submit">Buat Akun</button>
				</td>
			</tr>
		</table>
	</form>
</div>
<?php $this->footer(); ?>