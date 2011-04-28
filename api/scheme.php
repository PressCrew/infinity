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
function infinity_scheme_init( $theme = null )
{
	// initialize the scheme
	Pie_Easy_Scheme::instance( $theme )->init( INFINITY_NAME );
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
 * Return absolute path to first image matching path in the scheme stack
 *
 * @param string $path Image file path RELATIVE to your image_root setting
 */
function infinity_image_path( $path )
{
	// locate image in scheme stack
	$image_path = Pie_Easy_Scheme::instance()->locate_image( $path );
	// convert path to url
	return ($image_path) ? Pie_Easy_Files::theme_file_to_url($image_path) : null;
}

/**
 * Return URL to first image matching path in the scheme stack
 *
 * @param string $path Image file path RELATIVE to your image_root setting
 */
function infinity_image_url( $path )
{
	// locate image in scheme stack
	$image_path = infinity_get_image_path( $path );
	// convert path to url
	return ($image_path) ? Pie_Easy_Files::theme_file_to_url($image_path) : null;
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

?>
