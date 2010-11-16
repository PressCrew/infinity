<div class="wrap nosubsub">
	<div class="icon32"><img src="<?php print bp_tasty_dashboard_image( 'tasty_32x32.png' ) ?>" /></div>
	<h2><?php _e( 'Tasty Theme', BP_TASTY_TEXT_DOMAIN ) ?></h2>
	<?php bp_tasty_dashboard_cpanel_navigation() ?>
	<div class="bp-tasty-cpanel-content">
		<?php do_action( 'bp_tasty_dashboard_cpanel_content' ) ?>
	</div>
</div>
