<div class="wrap nosubsub">
	<?php
		$actions = infinity_dashboard_cpanel_actions();
		$current_action = infinity_dashboard_cpanel_action();
	?>
	<div id="infinity-cpanel">
		<div id="infinity-cpanel-toolbar" class="ui-corner-all">
			<a id="infinity-cpanel-toolbar-menu" title="<?php _e( 'Infinity', INFINITY_TEXT_DOMAIN ) ?>"><?php _e( 'Infinity', INFINITY_TEXT_DOMAIN ) ?></a>
			<?php foreach ( $actions as $action_slug => $action_title ): ?>
				<a id="infinity-cpanel-toolbar-<?php print $action_slug ?>" href="?page=<?php print INFINITY_ADMIN_PAGE ?>&route=cpanel/<?php print $action_slug ?>#infinity-cpanel-tab-<?php print $action_slug ?>" title="<?php print $action_title ?>"></a>
			<?php endforeach; ?>
			<a id="infinity-cpanel-toolbar-refresh" title="<?php _e( 'Refresh current tab', INFINITY_TEXT_DOMAIN ) ?>"><?php _e( 'Refresh', INFINITY_TEXT_DOMAIN ) ?></a>
		</div>
		<ul id="infinity-cpanel-tabs">
		</ul>
	</div>
</div>
