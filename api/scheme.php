<?php
/**
 * Infinity Theme scheme initilization file
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
function infinity_scheme_init()
{
	// initialize the scheme
	Pie_Easy_Scheme::instance()->init( 'config', INFINITY_NAME );
	Pie_Easy_Scheme::instance()->load_options( Infinity_Options_Registry::instance(), 'Infinity_Options_Section', 'Infinity_Options_Option' );
	return true;
}

/**
 * Check if template exists anywhere in the scheme
 *
 * @param string|array $template_names
 * @param boolean $load Auto load template if set to true
 * @return string
 */
function infinity_locate_template( $template_name, $load = false )
{	
	return Pie_Easy_Scheme::instance()->locate_template( $template_name );
}

/**
 * Load a template
 *
 * @param string|array $template_name
 * @return string
 */
function infinity_load_template( $template_name )
{
	return infinity_locate_template( $template_name, true );
}

/**
 * Filter the template that WP is trying to load to see if the scheme
 * wants to completely override it.
 *
 * @param string $template
 * @return string
 */
function infinity_filter_template( $template )
{
	return Pie_Easy_Scheme::instance()->filter_template( $template );
}
add_filter( 'template_include', 'infinity_filter_template', 10 );

/**
 * Get a theme image url
 *
 * @param string $image Relative path to image from the theme's images directory
 * @param string $theme Return an image from a theme other than the active theme
 * @return string
 */
function infinity_theme_image( $image, $theme = null )
{
	return Pie_Easy_Scheme::instance()->images_url( $theme ) . '/' . $image;
}

?>
