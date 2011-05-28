<div class="wrap nosubsub">
	<div id="infinity-cpanel">
		<div id="infinity-cpanel-header">
			<div id="infinity-cpanel-header-slogan">
			</div>
			<div id="infinity-cpanel-header-logo">
				<p><?php print INFINITY_VERSION ?></p>
			</div>
		</div>
		<div id="infinity-cpanel-toolbar">
			<a id="infinity-cpanel-toolbar-menu" class="infinity-cpanel-context-menu" title="<?php _e('Infinity', infinity_text_domain) ?>"><?php _e('Infinity', infinity_text_domain) ?></a>
			<?php infinity_dashboard_cpanel_toolbar_menu() ?>
			<?php infinity_dashboard_cpanel_toolbar_buttons() ?>
			<a id="infinity-cpanel-toolbar-refresh" title="<?php _e('Refresh current tab', infinity_text_domain) ?>"><?php _e('Refresh', infinity_text_domain) ?></a>
			<input id="infinity-cpanel-toolbar-scroll" type="checkbox" /><label for="infinity-cpanel-toolbar-scroll" title="<?php _e('Toggle scroll bars on/off', infinity_text_domain) ?>">Scrolling</label>
		</div>
		<div id="infinity-cpanel-tabs">
			<ul><!-- tabs are injected here --></ul>
		</div>
	</div>
</div>
