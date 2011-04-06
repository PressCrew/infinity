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

Pie_Easy_Loader::load( 'schemes' );

/**
 * Initialize and load the scheme for the active theme
 *
 * @return boolean
 */
function infinity_scheme_init()
{
	// initialize the scheme
	Pie_Easy_Scheme::instance()->init( INFINITY_NAME, 'config' );
	Pie_Easy_Scheme::instance()->load_options( Infinity_Options_Registry::instance() );
	return true;
}

/**
 * Get a scheme directive value
 *
 * @param string $name
 * @return mixed
 */
function infinity_scheme_directive( $name )
{
	return Pie_Easy_Scheme::instance()->get_directive( $name )->value;
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
 * Load a header from the scheme stack
 *
 * @param string $name
 * @return boolean
 */
function infinity_get_header( $name = null )
{
	return Pie_Easy_Scheme::instance()->get_header( $name );
}

/**
 * Load a footer from the scheme stack
 *
 * @param string $name
 * @return boolean
 */
function infinity_get_footer( $name = null )
{
	return Pie_Easy_Scheme::instance()->get_footer( $name );
}

/**
 * Load a sidebar from the scheme stack
 *
 * @param string $name
 * @return boolean
 */
function infinity_get_sidebar( $name = null )
{
	return Pie_Easy_Scheme::instance()->get_sidebar( $name );
}

/**
 * Load the search form from the scheme stack
 *
 * @param string $echo
 * @return boolean
 */
function infinity_get_search_form( $echo = true )
{
	return Pie_Easy_Scheme::instance()->get_search_form( $echo );
}

/**
 * Load a template part from the scheme stack
 *
 * @param string $slug The slug name for the generic template.
 * @param string $name The name of the specialised template.
 * @return boolean
 */
function infinity_get_template_part( $slug, $name = null )
{
	return Pie_Easy_Scheme::instance()->get_template_part( $slug, $name );
}

/**
 * Load comments template from the scheme stack (wrapper)
 *
 * @param string $file Optional, default '/comments.php'. The file to load
 * @param bool $separate_comments Optional, whether to separate the comments by comment type. Default is false.
 */
function infinity_comments_template( $file = null, $separate_comments = false )
{
	if ( $file === null ) {
		$file = DIRECTORY_SEPARATOR . 'comments.php';
	}
	
	// this is just a wrapper to avoid confusion
	return comments_template( $file, $separate_comments );
}

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
