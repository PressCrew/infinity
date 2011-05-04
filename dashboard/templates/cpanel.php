<?php infinity_image_url( 'foo.png') ?>
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
			<ul id="infinity-cpanel-toolbar-menu-items">
				<li>
					<a id="infinity-cpanel-toolbar-menu-item-start" href="<?php print infinity_dashboard_route( 'cpanel', 'start' ) ?>#infinity-cpanel-tab-start" title="<?php _e('Start', infinity_text_domain) ?>"><?php _e('Start', infinity_text_domain) ?></a>
				</li>
				<li>
					<a id="infinity-cpanel-toolbar-menu-item-options" href="<?php print infinity_dashboard_route( 'cpanel', 'options' ) ?>#infinity-cpanel-tab-options" title="<?php _e('Options', infinity_text_domain) ?>"><?php _e('Options', infinity_text_domain) ?></a>
				</li>
				<li>
					<a id="infinity-cpanel-toolbar-menu-item-shortcodes" href="<?php print infinity_dashboard_route( 'cpanel', 'shortcodes' ) ?>#infinity-cpanel-tab-shortcodes" title="<?php _e('Shortcodes', infinity_text_domain) ?>"><?php _e('Shortcodes', infinity_text_domain) ?></a>
				</li>
				<li>
					<a id="infinity-cpanel-toolbar-menu-item-widgets" href="<?php print infinity_dashboard_route( 'cpanel', 'widgets' ) ?>#infinity-cpanel-tab-widgets" title="<?php _e('Widgets', infinity_text_domain) ?>"><?php _e('Widgets', infinity_text_domain) ?></a>
				</li>
				<li>
					<a id="infinity-cpanel-toolbar-menu-item-docs" href="<?php print infinity_dashboard_route( 'cpanel', 'docs' ) ?>#infinity-cpanel-tab-docs" title="<?php _e('User Docs', infinity_text_domain) ?>"><?php _e('User Docs', infinity_text_domain) ?></a>
				</li>
				<li>
					<a id="infinity-cpanel-toolbar-menu-item-about" href="<?php print infinity_dashboard_route( 'cpanel', 'about' ) ?>#infinity-cpanel-tab-about" title="<?php _e('About', infinity_text_domain) ?>"><?php _e('About', infinity_text_domain) ?></a>
				</li>
				<li>
					<a id="infinity-cpanel-toolbar-menu-item-devs" class="infinity-cpanel-context-menu" title="<?php _e('Developers', infinity_text_domain) ?>"><?php _e('Developers', infinity_text_domain) ?></a>
					<ul>
						<li>
							<a id="infinity-cpanel-toolbar-menu-item-ddocs" href="<?php print infinity_dashboard_route( 'cpanel', 'ddocs' ) ?>#infinity-cpanel-tab-ddocs" title="<?php _e('Dev Docs', infinity_text_domain) ?>"><?php _e('Documentation', infinity_text_domain) ?></a>
						</li>
						<li>
							<a id="infinity-cpanel-toolbar-menu-item-api" href="<?php print infinity_dashboard_route( 'cpanel', 'api' ) ?>#infinity-cpanel-tab-api" title="<?php _e('API', infinity_text_domain) ?>"><?php _e('API Browser', infinity_text_domain) ?></a>
						</li>
						<li>
							<a id="infinity-cpanel-toolbar-menu-item-repo" href="<?php print infinity_dashboard_route( 'cpanel', 'repo' ) ?>#infinity-cpanel-tab-repo" title="<?php _e('Repo', infinity_text_domain) ?>"><?php _e('Repo Browser', infinity_text_domain) ?></a>
						</li>
					</ul>
				</li>
				<li>
					<a id="infinity-cpanel-toolbar-menu-item-comm" class="infinity-cpanel-context-menu" title="<?php _e('Community', infinity_text_domain) ?>"><?php _e('Community', infinity_text_domain) ?></a>
					<ul>
						<li>
							<a id="infinity-cpanel-toolbar-menu-item-support" href="<?php print infinity_dashboard_route( 'cpanel', 'support' ) ?>#infinity-cpanel-tab-support" title="<?php _e('Support', infinity_text_domain) ?>"><?php _e('Support', infinity_text_domain) ?></a>
						</li>
						<li>
							<a id="infinity-cpanel-toolbar-menu-item-thanks" href="<?php print infinity_dashboard_route( 'cpanel', 'thanks' ) ?>#infinity-cpanel-tab-thanks" title="<?php _e('Thanks', infinity_text_domain) ?>"><?php _e('Thanks', infinity_text_domain) ?></a>
						</li>
					</ul>
				</li>
			</ul>
			<a id="infinity-cpanel-toolbar-start" href="<?php print infinity_dashboard_route( 'cpanel', 'start' ) ?>#infinity-cpanel-tab-start" title="<?php _e('Start', infinity_text_domain) ?>"><?php _e('Start', infinity_text_domain) ?></a>
			<a id="infinity-cpanel-toolbar-options" href="<?php print infinity_dashboard_route( 'cpanel', 'options' ) ?>#infinity-cpanel-tab-options" title="<?php _e('Options', infinity_text_domain) ?>"><?php _e('Options', infinity_text_domain) ?></a>
			<a id="infinity-cpanel-toolbar-shortcodes" href="<?php print infinity_dashboard_route( 'cpanel', 'shortcodes' ) ?>#infinity-cpanel-tab-shortcodes" title="<?php _e('Shortcodes', infinity_text_domain) ?>"><?php _e('Shortcodes', infinity_text_domain) ?></a>
			<a id="infinity-cpanel-toolbar-widgets" href="<?php print infinity_dashboard_route( 'cpanel', 'widgets' ) ?>#infinity-cpanel-tab-widgets" title="<?php _e('Widgets', infinity_text_domain) ?>"><?php _e('Widgets', infinity_text_domain) ?></a>
			<a id="infinity-cpanel-toolbar-docs" href="<?php print infinity_dashboard_route( 'cpanel', 'docs' ) ?>#infinity-cpanel-tab-docs" title="<?php _e('User Docs', infinity_text_domain) ?>"><?php _e('User Docs', infinity_text_domain) ?></a>
			<a id="infinity-cpanel-toolbar-about" href="<?php print infinity_dashboard_route( 'cpanel', 'about' ) ?>#infinity-cpanel-tab-about" title="<?php _e('About', infinity_text_domain) ?>"><?php _e('About', infinity_text_domain) ?></a>
			<a id="infinity-cpanel-toolbar-refresh" title="<?php _e('Refresh current tab', infinity_text_domain) ?>"><?php _e('Refresh', infinity_text_domain) ?></a>
			<input id="infinity-cpanel-toolbar-scroll" type="checkbox" /><label for="infinity-cpanel-toolbar-scroll" title="<?php _e('Toggle scroll bars on/off', infinity_text_domain) ?>">Scrolling</label>
		</div>
		<div id="infinity-cpanel-tabs">
			<ul><!-- tabs are injected here --></ul>
		</div>
	</div>
</div>
