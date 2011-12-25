<?php $this->header(); ?>
<script>document.write('<style>.global-nav, .content {display: none}</style>');</script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<div class="home-index-wrapper">
	<a href="<?php L('/daftar') ?>"><img src="<?php L('/assets/dengar.png'); ?>" alt="Dengar kata dunia. Didengar oleh dunia."></a>
</div>
<script>
$(document).ready(function(){
	$('.global-nav').slideDown('slow', function() {$('.content').fadeIn('slow')});
})
</script>
<?php $this->footer(); ?>