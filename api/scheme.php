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
	// initialize the scheme
	Pie_Easy_Scheme::instance()->init( 'config', TASTY_NAME );
	Pie_Easy_Scheme::instance()->load_options( Tasty_Options_Registry::instance(), 'Tasty_Options_Section', 'Tasty_Options_Option' );
	return true;
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

/**
 * Get a theme image url
 *
 * @param string $image Relative path to image from the theme's images directory
 * @param string $theme Return an image from a theme other than the active theme
 * @return string
 */
function tasty_theme_image( $image, $theme = null )
{
	return Pie_Easy_Scheme::instance()->images_url( $theme ) . '/' . $image;
}

?>
