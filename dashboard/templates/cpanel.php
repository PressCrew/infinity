<?php
	// begin rendering by passing our element id prefix
	infinity_dashboard_cpanel_render_begin( 'infinity-cpanel-' );
?>

<div id="infinity-cpanel" class="wrap nosubsub">

	<!-- header -->
	<div id="infinity-cpanel-header" class="ui-widget-header ui-corner-top">
		<?php
			// use load template to make header template overridable
			infinity_dashboard_load_template( 'cpanel_header.php' );
		?>
	</div>

	<!-- toolbar -->
	<div id="infinity-cpanel-toolbar">

		<!-- menu -->
		<a id="infinity-cpanel-menubutton" title="<?php _e( 'infinity', infinity_text_domain ) ?>"><?php _e( 'infinity', infinity_text_domain ) ?></a>
		<ul id="infinity-cpanel-menu">
			<li>
				<a id="infinity-cpanel-menu-item-comm" href="#" title="Community">Community</a>
				<ul>
					<li>
						<a target="_blank" href="http://community.presscrew.com/" title="Visit Us!">Visit Us!</a>
					</li>
					<li>
						<a target="_blank" href="http://community.presscrew.com/discussions/" title="Forums">Forums</a>
					</li>
				</ul>
			</li>
			<li>
				<a id="infinity-cpanel-menu-item-devs" href="#" title="Developers">Developers</a>
				<ul>
					<li>
						<a target="_blank" href="http://infinity.presscrew.com/api/" title="API Browser">API Browser</a>
					</li>
					<li>
						<a target="_blank" href="https://github.com/PressCrew/infinity" title="Repo Browser">Repo Browser</a>
					</li>
				</ul>
			</li>
		</ul>

	</div>

	<!-- tabs -->
	<div id="infinity-cpanel-tabs">
		<?php infinity_dashboard_cpanel_render_tab_list() ?>
		<?php infinity_dashboard_cpanel_render_tab_panels() ?>
	</div>

</div>

<script type="text/javascript">
(function($){

	// its pretty safe to hack this code as its all config/preference.
	// all of the logic code is safely tucked away in cpanel.js

	var menuButton =
			$( 'a#infinity-cpanel-menubutton' ).button({
				icons: { secondary: 'ui-icon-triangle-1-s' }
			});
	var menu =
			$( 'ul#infinity-cpanel-menu' ).buttonmenu({
				button: menuButton
			});
	var toolbar =
			$( 'div#infinity-cpanel-toolbar' ).toolbar();
	var tabs =
			$( 'div#infinity-cpanel-tabs' ).tabs();

	initOptionsPanel( $( '#infinity-cpanel-tab-options' ) );

})(jQuery);
</script>

<?php
	// renders dynamic scripts and performs cleanup
	infinity_dashboard_cpanel_render_end();
?>