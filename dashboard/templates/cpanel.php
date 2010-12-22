<div class="wrap nosubsub">
	<div class="icon32"><img src="<?php print infinity_dashboard_image( 'icon_32.png' ) ?>" /></div>
	<h2><?php _e( 'Infinity Theme', INFINITY_TEXT_DOMAIN ) ?></h2>
	<?php
		$actions = infinity_dashboard_cpanel_actions();
		$current_action = infinity_dashboard_cpanel_action();
	?>
	<div id="infinity-cpanel">
		<span id="infinity-cpanel-toolbar" class="ui-widget-header ui-corner-all">
			<?php foreach ( $actions as $action_slug => $action_title ): ?>
			<a id="infinity-cpanel-toolbar-<?php print $action_slug ?>" href="<?php print infinity_dashboard_route( 'cpanel', $action_slug ) ?>"><?php print $action_title ?></a>
			<?php endforeach; ?>
		</span>
		<div class="infinity-cpanel-content" id="infinity-cpanel-<?php print infinity_dashboard_cpanel_action() ?>">
			<?php do_action( 'infinity_dashboard_cpanel_content' ) ?>
		</div>
	</div>
</div>
