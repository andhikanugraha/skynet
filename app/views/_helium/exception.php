<?php
if (!function_exists('L')) {
	function L($u) {
		echo Helium::conf('base_uri') . $u;
	}
}
?>
<!DOCTYPE html>

<html lang="id">

	<head>
		<meta charset="utf-8">
		<title>Pendaftaran Seleksi Bina Antarbudaya</title>
		<link rel="stylesheet" href="<?php L('/assets/css/global/style.css'); ?>">
		<link rel="stylesheet" href="<?php L('/assets/css/global/error.css'); ?>">
	</head>

	<body>
		<nav class="global-nav">
			<header class="masthead"><a href="<?php L('/'); ?>"><img src="<?php L('/assets/css/global/masthead.png'); ?>" alt="Bina Antarbudaya"></a></header>
		</nav>
		<div class="content">
			<header class="page-title">
				<h1>:o</h1>
			</header>
			<p>Terjadi sebuah kesalahan teknis. Kami akan segera memeriksanya.</p>
			<p><strong><a href="<?php echo $_SERVER['HTTP_REFERER'] ?>">Kembali ke halaman sebelumnya</a></strong></p>
		</div>
	</body>
</html>