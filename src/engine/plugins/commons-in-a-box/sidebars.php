<?php

/**
 * Automagically set up sidebars on theme activation.
 */
function cbox_theme_magic_sidebars()
{
	// auto sidebar population
	infinity_sidebars_auto_populate();
}
add_action( 'infinity_dashboard_activated', 'cbox_theme_magic_sidebars' );

/**
 * Set additional sidebars during auto populate sequence.
 *
 * @param string $sidebar_id
 */
function cbox_theme_sidebars_auto_populate( $sidebar_id )
{
	// which sidebar id was just populated?
	switch( $sidebar_id ) {
		// Homepage Top Right
		case 'homepage-top-right':
			// Try to set CAC featured member
			infinity_sidebars_set_cacfc_member( 'homepage-top-right' );
			break;
	}
}
add_action( 'infinity_sidebars_auto_populate', 'cbox_theme_sidebars_auto_populate' );