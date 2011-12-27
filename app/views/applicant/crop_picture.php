<?php
$this['additional_css'] = array('imgareaselect/imgareaselect-animated');
?>
<?php $this->header() ?>
<header class="page-title">
	<h1>Unggah Foto</h1>
</header>
<div class="container">

	<div class="pic-container">
		<p class="instruction">Lakukan <i>cropping</i> pada foto Adik dengan menekan tombol pada <i>mouse</i> dan menggesernya. <i>(click and drag)</i></p>
		<img id="pic" src="<?php echo $picture->get_original_url() ?>" alt="">
	</div>
	<form action="<?php L($this->params) ?>" method="POST">
		<input name="x" id="x" type="hidden">
		<input name="y" id="y" type="hidden">
		<input name="width" id="width" type="hidden">
		<input name="height" id="height" type="hidden">
		<button type="submit">Simpan</button>
	</form>

</div>
<script src="<?php L('/assets/imgareaselect/jquery.min.js') ?>"></script>
<script src="<?php L('/assets/imgareaselect/jquery.imgareaselect.pack.js') ?>"></script>
<script>
$(document).ready(function () {
	
	o = $('#pic').clone().css('max-width', '');
	rx = o.width() / $('#pic').width();
	ry = o.height() / $('#pic').height();
	
	$('#pic').imgAreaSelect({
		x1: 20, y1: 30, x2: 300, y2: 400,
		aspectRatio: '3:4',
		handles: true,
		onSelectEnd: function (img, selection) {
			$('#x').val(rx * selection.x1);
			$('#y').val(ry * selection.y1);
			$('#width').val(rx * selection.width);
			$('#height').val(ry * selection.height);
	}
})
});
</script>
<?php $this->footer() ?>