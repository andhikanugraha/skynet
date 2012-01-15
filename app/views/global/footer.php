		</div>
		<footer class="global-footer">
			<!-- Untuk bantuan, baca <strong><a href="<?php L(array('controller' => 'applicant', 'action' => 'guide')); ?>">panduan</a></strong>, mention/DM <strong>@afsbandung</strong> atau e-mail <strong>seleksi@binabudbdg.org</strong>. -->
			<!-- <address class="afs-partner"><img src="<?php L('/assets/images/AFS_Partner.png') ?>" alt="A Partner of AFS Intercultural Programs"></address> -->
		</footer>
		<?php if (Helium::conf('production')): // UserVoice ?>
		<script type="text/javascript">
		  var uvOptions = {};
		  (function() {
		    var uv = document.createElement('script'); uv.type = 'text/javascript'; uv.async = true;
		    uv.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'widget.uservoice.com/n3xOhVkFkHQSKYQiPLSeag.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(uv, s);
		  })();
		</script>
		<?php endif; ?>
	</body>

</html>