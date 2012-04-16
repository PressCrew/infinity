<?php
/**
 * Infinity Theme: Dashboard options screen, menu section template
 *
 * Variables:
 *		$section	The current ICE_Section object being rendered
 *		$children	Array of ICE_Section objects that are children of the section object
 *		$options	Array of ICE_Option objects that are assigned to the section object
 *
 * @package Infinity
 * @subpackage dashboard-templates
 */
?>
<div id="menu___<?php print esc_attr( $section->name ) ?>">
	<a><?php print esc_html( $section->title ) ?></a>
	<a id="section___<?php print esc_attr( $section->name ) ?>" class="infinity-cpanel-options-menu-show infinity-cpanel-options-menu-showall" href="#"><?php _e('Show All', infinity_text_domain) ?></a>
</div>

<?php
	if ( $children ):
		// render all children sections ?>
		<div><div id="submenu___<?php print esc_attr( $section->name ) ?>" class="infinity-cpanel-options-menu infinity-cpanel-options-submenu">
			<?php
				foreach ( $children as $child ):
					infinity_options_render_menu_section( $child );
				endforeach; ?>
		</div></div>
<?php
	else:
		// render this section's options
		infinity_options_render_menu_options( $options );
	endif;
?>
