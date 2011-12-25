<?php

class FxComponent extends HeliumComponent {

	public $effects = array();
	public $header_js;
	public $footer_js;
	public $headers = array();
	public $footers = array();

	public function init() {
		if (strpos($_SERVER['HTTP_REFERER'], PathsComponent::build_url('')) !== false) {
			$headers['staggered_load'] = $headers['fade_all_load'] = <<<'EOS'
document.write('<style>.content {display: none}</style>');

EOS;
			$footers['staggered_load'] = <<<'EOS'
$('.content').fadeIn('slow')

EOS;
		}
		else {
			$headers['staggered_load'] = $headers['fade_all_load'] = <<<'EOS'
document.write('<style>.global-nav, .content {display: none}</style>');

EOS;
			$footers['staggered_load'] = <<<'EOS'
$('.global-nav').slideDown('slow', function() {$('.content').fadeIn('slow')});

EOS;
		}

		$footers['fade_all_load'] = <<<'EOS'
$('.global-nav, .content').fadeIn('fast');

EOS;

		$footers['fade_content_unload'] = <<<'EOS'
$('body').setAttr('onunload', ' ')
$('a[href]').click(function() { $('.content').fadeOut('fast') })

EOS;

		$this->headers = $headers;
		$this->footers = $footers;
	}

	public function register_effect($effect) {
		$this->header_js .= $this->headers[$effect];
		$this->footer_js .= $this->footers[$effect];
	}

	public function load_jquery() {
?><script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script><?php
	}
	
	public function __invoke() {
		foreach (func_get_args() as $e)
			$this->register_effect($e);

		if ($this->header_js) {
		$this->load_jquery();
		?>
<script>
<?php echo $this->header_js ?>
</script>

		<?php
		}
	}
	
	public function footer() {
		$this->load_jquery();
		
		if ($this->footer_js) {
		?>
<script>
$(document).ready(function() {
<?php echo $this->footer_js ?>	
})
</script>

		<?php
		}
	}
}

?>