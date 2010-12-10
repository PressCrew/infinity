<div class="wrap nosubsub">
	<div class="icon32"><img src="<?php print tasty_dashboard_image( 'tasty_32x32.png' ) ?>" /></div>
	<h2><?php _e( 'Tasty Theme', TASTY_TEXT_DOMAIN ) ?></h2>
	<?php tasty_dashboard_cpanel_navigation() ?>
	<div class="tasty-cpanel-content">
		<?php do_action( 'tasty_dashboard_cpanel_content' ) ?>
	</div>
</div>
