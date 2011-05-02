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
			<a id="infinity-cpanel-toolbar-menu" class="infinity-cpanel-context-menu" title="<?php _e( 'Infinity', INFINITY_TEXT_DOMAIN ) ?>"><?php _e( 'Infinity', INFINITY_TEXT_DOMAIN ) ?></a>
			<ul id="infinity-cpanel-toolbar-menu-items">
				<li>
					<a id="infinity-cpanel-toolbar-menu-item-start" href="<?php print infinity_dashboard_route( 'cpanel', 'start' ) ?>#infinity-cpanel-tab-start" title="<?php _e( 'Start', INFINITY_TEXT_DOMAIN ) ?>"><?php _e( 'Start', INFINITY_TEXT_DOMAIN ) ?></a>
				</li>
				<li>
					<a id="infinity-cpanel-toolbar-menu-item-options" href="<?php print infinity_dashboard_route( 'cpanel', 'options' ) ?>#infinity-cpanel-tab-options" title="<?php _e( 'Options', INFINITY_TEXT_DOMAIN ) ?>"><?php _e( 'Options', INFINITY_TEXT_DOMAIN ) ?></a>
				</li>
				<li>
					<a id="infinity-cpanel-toolbar-menu-item-shortcodes" href="<?php print infinity_dashboard_route( 'cpanel', 'shortcodes' ) ?>#infinity-cpanel-tab-shortcodes" title="<?php _e( 'Shortcodes', INFINITY_TEXT_DOMAIN ) ?>"><?php _e( 'Shortcodes', INFINITY_TEXT_DOMAIN ) ?></a>
				</li>
				<li>
					<a id="infinity-cpanel-toolbar-menu-item-widgets" href="<?php print infinity_dashboard_route( 'cpanel', 'widgets' ) ?>#infinity-cpanel-tab-widgets" title="<?php _e( 'Widgets', INFINITY_TEXT_DOMAIN ) ?>"><?php _e( 'Widgets', INFINITY_TEXT_DOMAIN ) ?></a>
				</li>
				<li>
					<a id="infinity-cpanel-toolbar-menu-item-docs" href="<?php print infinity_dashboard_route( 'cpanel', 'docs' ) ?>#infinity-cpanel-tab-docs" title="<?php _e( 'User Docs', INFINITY_TEXT_DOMAIN ) ?>"><?php _e( 'User Docs', INFINITY_TEXT_DOMAIN ) ?></a>
				</li>
				<li>
					<a id="infinity-cpanel-toolbar-menu-item-about" href="<?php print infinity_dashboard_route( 'cpanel', 'about' ) ?>#infinity-cpanel-tab-about" title="<?php _e( 'About', INFINITY_TEXT_DOMAIN ) ?>"><?php _e( 'About', INFINITY_TEXT_DOMAIN ) ?></a>
				</li>
				<li>
					<a id="infinity-cpanel-toolbar-menu-item-devs" class="infinity-cpanel-context-menu" title="<?php _e( 'Developers', INFINITY_TEXT_DOMAIN ) ?>"><?php _e( 'Developers', INFINITY_TEXT_DOMAIN ) ?></a>
					<ul>
						<li>
							<a id="infinity-cpanel-toolbar-menu-item-ddocs" href="<?php print infinity_dashboard_route( 'cpanel', 'ddocs' ) ?>#infinity-cpanel-tab-ddocs" title="<?php _e( 'Dev Docs', INFINITY_TEXT_DOMAIN ) ?>"><?php _e( 'Documentation', INFINITY_TEXT_DOMAIN ) ?></a>
						</li>
						<li>
							<a id="infinity-cpanel-toolbar-menu-item-api" href="<?php print infinity_dashboard_route( 'cpanel', 'api' ) ?>#infinity-cpanel-tab-api" title="<?php _e( 'API', INFINITY_TEXT_DOMAIN ) ?>"><?php _e( 'API Browser', INFINITY_TEXT_DOMAIN ) ?></a>
						</li>
						<li>
							<a id="infinity-cpanel-toolbar-menu-item-repo" href="<?php print infinity_dashboard_route( 'cpanel', 'repo' ) ?>#infinity-cpanel-tab-repo" title="<?php _e( 'Repo', INFINITY_TEXT_DOMAIN ) ?>"><?php _e( 'Repo Browser', INFINITY_TEXT_DOMAIN ) ?></a>
						</li>
					</ul>
				</li>
				<li>
					<a id="infinity-cpanel-toolbar-menu-item-comm" class="infinity-cpanel-context-menu" title="<?php _e( 'Community', INFINITY_TEXT_DOMAIN ) ?>"><?php _e( 'Community', INFINITY_TEXT_DOMAIN ) ?></a>
					<ul>
						<li>
							<a id="infinity-cpanel-toolbar-menu-item-support" href="<?php print infinity_dashboard_route( 'cpanel', 'support' ) ?>#infinity-cpanel-tab-support" title="<?php _e( 'Support', INFINITY_TEXT_DOMAIN ) ?>"><?php _e( 'Support', INFINITY_TEXT_DOMAIN ) ?></a>
						</li>
						<li>
							<a id="infinity-cpanel-toolbar-menu-item-thanks" href="<?php print infinity_dashboard_route( 'cpanel', 'thanks' ) ?>#infinity-cpanel-tab-thanks" title="<?php _e( 'Thanks', INFINITY_TEXT_DOMAIN ) ?>"><?php _e( 'Thanks', INFINITY_TEXT_DOMAIN ) ?></a>
						</li>
					</ul>
				</li>
			</ul>
			<a id="infinity-cpanel-toolbar-start" href="<?php print infinity_dashboard_route( 'cpanel', 'start' ) ?>#infinity-cpanel-tab-start" title="<?php _e( 'Start', INFINITY_TEXT_DOMAIN ) ?>"><?php _e( 'Start', INFINITY_TEXT_DOMAIN ) ?></a>
			<a id="infinity-cpanel-toolbar-options" href="<?php print infinity_dashboard_route( 'cpanel', 'options' ) ?>#infinity-cpanel-tab-options" title="<?php _e( 'Options', INFINITY_TEXT_DOMAIN ) ?>"><?php _e( 'Options', INFINITY_TEXT_DOMAIN ) ?></a>
			<a id="infinity-cpanel-toolbar-shortcodes" href="<?php print infinity_dashboard_route( 'cpanel', 'shortcodes' ) ?>#infinity-cpanel-tab-shortcodes" title="<?php _e( 'Shortcodes', INFINITY_TEXT_DOMAIN ) ?>"><?php _e( 'Shortcodes', INFINITY_TEXT_DOMAIN ) ?></a>
			<a id="infinity-cpanel-toolbar-widgets" href="<?php print infinity_dashboard_route( 'cpanel', 'widgets' ) ?>#infinity-cpanel-tab-widgets" title="<?php _e( 'Widgets', INFINITY_TEXT_DOMAIN ) ?>"><?php _e( 'Widgets', INFINITY_TEXT_DOMAIN ) ?></a>
			<a id="infinity-cpanel-toolbar-docs" href="<?php print infinity_dashboard_route( 'cpanel', 'docs' ) ?>#infinity-cpanel-tab-docs" title="<?php _e( 'User Docs', INFINITY_TEXT_DOMAIN ) ?>"><?php _e( 'User Docs', INFINITY_TEXT_DOMAIN ) ?></a>
			<a id="infinity-cpanel-toolbar-about" href="<?php print infinity_dashboard_route( 'cpanel', 'about' ) ?>#infinity-cpanel-tab-about" title="<?php _e( 'About', INFINITY_TEXT_DOMAIN ) ?>"><?php _e( 'About', INFINITY_TEXT_DOMAIN ) ?></a>
			<a id="infinity-cpanel-toolbar-refresh" title="<?php _e( 'Refresh current tab', INFINITY_TEXT_DOMAIN ) ?>"><?php _e( 'Refresh', INFINITY_TEXT_DOMAIN ) ?></a>
		</div>
		<div id="infinity-cpanel-tabs">
			<ul><!-- tabs are injected here --></ul>
		</div>
	</div>
</div>
