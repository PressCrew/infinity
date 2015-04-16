<?php
	// begin rendering by passing our element id prefix
	infinity_dashboard_cpanel_render_begin( 'infinity-cpanel-' );
?>

<div id="infinity-cpanel" class="wrap nosubsub">

	<!-- header -->
	<?php
		// use load template to make header template overridable
		get_template_part( 'templates/dashboard/cpanel-header', get_template() );
	?>

	<!-- tabs -->
	<div id="infinity-cpanel-tabs">
		<?php infinity_dashboard_cpanel_render_tab_list() ?>
		<?php infinity_dashboard_cpanel_render_tab_panels() ?>
	</div>

</div>

<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function($){

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
	var tabs =
			$( 'div#infinity-cpanel-tabs' ).tabs();

	initOptionsPanel( $( '#infinity-cpanel-tab-options' ) );

});
//]]>
</script>

<?php
	// renders dynamic scripts and performs cleanup
	infinity_dashboard_cpanel_render_end();
