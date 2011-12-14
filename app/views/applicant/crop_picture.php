<?php
$this['additional_css'] = array('imgareaselect/imgareaselect-animated');
?>
<?php $this->header() ?>
<div class="user-create-wrapper">

	<header class="stage-title">
		<h1>Formulir Pendaftaran</h1>
		<h2>Unggah Foto</h2>
	</header>

	<div class="pic-container">
		<p class="instruction">Lakukan <i>cropping</i> pada foto Adik dengan menekan tombol pada <i>mouse</i> dan menggesernya. <i>(click and drag)</i></p>
		<img id="pic" src="<?php echo $picture->get_original_url() ?>" alt="">
	</div>
	<form action="<?php L($this->params) ?>" method="POST">
		<input name="x" id="x" type="hidden">
		<input name="y" id="y" type="hidden">
		<input name="width" id="width" type="hidden">
		<input name="height" id="height" type="hidden">
		<button type="submit">Unggah</button>
	</form>

</div>
<script src="<?php L('/assets/imgareaselect/jquery.min.js') ?>"></script>
<script src="<?php L('/assets/imgareaselect/jquery.imgareaselect.pack.js') ?>"></script>
<script>
$(document).ready(function () { $('#pic').imgAreaSelect({
	aspectRatio: '3:4',
	handles: true,
	onSelectEnd: function (img, selection) {
		$('#x').val(selection.x1);
		$('#y').val(selection.y1);
		$('#width').val(selection.width);
		$('#height').val(selection.height);
	}
})
});
</script>
<?php $this->footer() ?>