<?php $this->header('Pengelolaan Pendaftar');

global $p;
$p = $this->params;
function LL($target) {
	global $p;
	$pi = array_merge($p, $target);
	L($pi);
}
?>
<header class="page-title">
	<hgroup>
		<h1><a href="<?php L(array('controller' => 'chapter', 'action' => 'view', 'chapter_code' => $applicant->chapter->chapter_code)) ?>"><?php echo $this->user->chapter->get_title() ?></a></h1>
		<h2>Pengelolaan <?php echo $post_registration ? 'Peserta' : 'Pendaftar' ?></h2>
	</hgroup>
</header>
<nav class="actions-nav">
	<ul>
		<?php foreach(array('' => $chapter ? 'Chapter ' . $chapter->chapter_name : 'Seluruh Pendaftar', 'confirmed' => 'Terkonfirmasi', 'finalized' => 'Terfinalisasi', 'incomplete' => 'Sedang Mengisi', 'expired' => 'Kadaluarsa') as $i => $j): ?>
		<li><a href="<?php LL(array('stage' => $i)) ?>"<?php if ($i == $current_stage) echo 'class="active"' ?>><?php echo $j ?></a></li>

		<?php endforeach; ?>
		<li><a href="<?php L(array('action' => 'search')) ?>">Pencarian</a></li>
		<li class="page-selector">Halaman
		<?php for ($i = 1; $i <= $total_pages; $i++): ?>
			<a href="<?php LL(array('page' => $i)) ?>"<?php if ($i == $current_page) echo 'class="active"' ?>><?php echo $i ?></a>
		<?php endfor; ?>
		</li>
		<li class="applicant-count"><strong><?php if ($applicants) echo $applicants->count_all(); else echo 0; ?></strong> orang</li>
	</ul>
</nav>

<div class="container">
	<table class="applicants">
		<thead>
			<tr>
				<th class="checkbox"></th>
				<th class="test-id">No. Peserta</th>
				<th class="name">Nama Lengkap</th>
				<th class="school">Asal Sekolah</th>
				<th class="expires-on">Batas Pendaftaran</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($applicants as $a): 
		$classes = '';
		if (!$a->finalized) $classes .= 'unfinalized '; 
		if ($a->confirmed) $classes .= 'submitted '; 
		elseif ($a->is_expired()) $classes .= 'expired ';
		$exp = $a->expires_on;
		?>
			<tr class="<?php echo $classes; ?>">
				<td class="checkbox"><?php $form->checkbox('ids[]', $a->id) ?></td>
				<td class="test-id"><?php echo $a->test_id; ?></td>
				<td class="name"><b><a href="<?php L(array('controller' => 'applicant', 'action' => 'view', 'id' => $a->id)) ?>"><?php echo $a->sanitized_full_name ? $a->sanitized_full_name : '<span class="empty">(Belum diisi)</span>'; ?></a></b></td>
				<td class="school"><?php echo $a->sanitized_high_school_name; ?></td>
				<td class="expires-on"><?php echo $exp->format('j F Y'); ?></td>
			</tr>

		<?php endforeach; ?>
		</tbody>
	</table>
	<!-- <p><a href="<?php LL(array('output' => 'xlsx')) ?>">Download in Excel 2007+ format</a></p> -->
	<!-- Query took <?php echo microtime() - $start; ?> seconds. -->
</div>
<?php $this->footer(); ?>