<?php
/**
 * Tasty Theme scheme initilization file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://bp-tricks.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package api
 * @subpackage localization
 * @since 1.0
 */

Pie_Easy_Loader::load( 'scheme' );

/**
 * Initialize and load the scheme for the active theme
 *
 * @return boolean
 */
function tasty_scheme_init()
{
	// try to load this active theme as a scheme
	if ( current_theme_supports( TASTY_SUPPORT_INFINITE_CHILDREN ) ) {
		return Pie_Easy_Scheme::init( 'config', TASTY_NAME );
	}

	return false;
}

/**
 * Check if template exists anywhere in the scheme
 *
 * @param string|array $template_names
 * @param boolean $load Auto load template if set to true
 * @return string
 */
function tasty_locate_template( $template_name, $load = false )
{	
	return Pie_Easy_Scheme::instance()->locate_template( $template_name );
}

/**
 * Load a template
 *
 * @param string|array $template_name
 * @return string
 */
function tasty_load_template( $template_name )
{
	return tasty_locate_template( $template_name, true );
}

/**
 * Filter the template that WP is trying to load to see if the scheme
 * wants to completely override it.
 *
 * @param string $template
 * @return string
 */
function tasty_filter_template( $template )
{
	return Pie_Easy_Scheme::instance()->filter_template( $template );
}
add_filter( 'template_include', 'tasty_filter_template', 10 );

?>
