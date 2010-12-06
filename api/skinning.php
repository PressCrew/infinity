<?php
/**
 * BP Tasty theme API templating functions
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://bp-tricks.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package api
 * @subpackage templating
 * @since 1.0
 */

Pie_Easy_Loader::load( 'enqueue' );

define( 'BP_TASTY_SKIN_DEFAULT', 'minimal' );
define( 'BP_TASTY_SKIN_NAME', bp_tasty_skins_active_name() );
define( 'BP_TASTY_SKIN_DIR', BP_TASTY_EXTRAS_SKIN_DIR . '/' . BP_TASTY_SKIN_NAME );
define( 'BP_TASTY_SKIN_URL', BP_TASTY_EXTRAS_SKIN_URL . '/' . BP_TASTY_SKIN_NAME );
define( 'BP_TASTY_SKIN_ASSETS_DIR', BP_TASTY_SKIN_DIR . '/assets' );
define( 'BP_TASTY_SKIN_ASSETS_URL', BP_TASTY_SKIN_URL . '/assets' );
define( 'BP_TASTY_SKIN_ASSETS_CSS_DIR', BP_TASTY_SKIN_ASSETS_DIR . '/css' );
define( 'BP_TASTY_SKIN_ASSETS_CSS_URL', BP_TASTY_SKIN_ASSETS_URL . '/css' );
define( 'BP_TASTY_SKIN_ASSETS_IMAGES_DIR', BP_TASTY_SKIN_ASSETS_DIR . '/images' );
define( 'BP_TASTY_SKIN_ASSETS_IMAGES_URL', BP_TASTY_SKIN_ASSETS_URL . '/images' );
define( 'BP_TASTY_SKIN_ASSETS_JS_DIR', BP_TASTY_SKIN_ASSETS_DIR . '/js' );
define( 'BP_TASTY_SKIN_ASSETS_JS_URL', BP_TASTY_SKIN_ASSETS_URL . '/js' );
define( 'BP_TASTY_SKIN_CONFIG_DIR', BP_TASTY_SKIN_DIR . '/config' );

// initialize skin options in registry
bp_tasty_skins_registry_init();

/**
 * Initialize the skin registry options
 *
 * @return boolean
 */
function bp_tasty_skins_registry_init()
{
	// path to skin ini
	$skin_ini = BP_TASTY_SKIN_CONFIG_DIR . '/options.ini';

	// load the skin config if it exists
	if ( is_readable( $skin_ini ) ) {
		return
			BP_Tasty_Options_Registry::instance()
				->load_config_file(
					$skin_ini,
					'BP_Tasty_Options_Section',
					'BP_Tasty_Options_Skin_Option'
				);
	} else {
		return;
	}
}

/**
 * Get the name of the active skin
 *
 * @return string
 */
function bp_tasty_skins_active_name()
{
	// make sure global registry has been initialized
	bp_tasty_options_registry_init();
	
	// try to get name from the options
	$skin_name = bp_tasty_option( 'active_skin' );

	// did we get a name?
	if ( empty( $skin_name ) ) {
		// nope, use default
		return BP_TASTY_SKIN_DEFAULT;
	} else {
		return $skin_name;
	}
}

/**
 * Return a list of the available skins in the skins dir
 *
 * @return array
 */
function bp_tasty_skins_available()
{
	// load dirs in skins directory
	$skin_dirs = Pie_Easy_Files::list_filtered( BP_TASTY_EXTRAS_SKIN_DIR, '/^\w+$/', true );

	// dirs to actually return
	$skins = array();

	// loop through and add only directories
	foreach ( $skin_dirs as $skin_name => $skin_dir ) {
		// is it a directory?
		if ( is_dir($skin_dir) ) {
			// yep, key and value are the skin name
			$skins[$skin_name] = $skin_name;
		}
	}

	return $skins;
}

/**
 * Return the prefix used for skin option names and section name
 *
 * @return string
 */
function bp_tasty_skins_options_name_prefix()
{
	return sprintf( '%s_%s_', 'skin', BP_TASTY_SKIN_NAME );
}

/**
 * Enqueue all of the active skin's assets
 */
function bp_tasty_skin_enqueue_assets()
{
	// all stylesheets
	Pie_Easy_Enqueue::styles(
		BP_TASTY_SKIN_ASSETS_CSS_DIR,
		BP_TASTY_SKIN_ASSETS_CSS_URL,
		sprintf( '%s-%s-', BP_TASTY_NAME, BP_TASTY_SKIN_NAME )
	);
	// all javascript
	Pie_Easy_Enqueue::scripts(
		BP_TASTY_SKIN_ASSETS_JS_DIR,
		BP_TASTY_SKIN_ASSETS_JS_URL,
		sprintf( '%s-%s-', BP_TASTY_NAME, BP_TASTY_SKIN_NAME )
	);
}

/**
 * Load the active skin's functions
 */
function bp_tasty_skin_load_functions()
{
	// path to functions file
	$filename = BP_TASTY_SKIN_DIR . DIRECTORY_SEPARATOR . 'functions.php';

	// does it exist?
	if ( file_exists( $filename ) ) {
		require_once $filename;
	}
}

/**
 * Get a skin option value
 *
 * @param string $option_name
 * @return mixed
 */
function bp_tasty_skin_option( $option_name )
{
	return bp_tasty_option( bp_tasty_skins_options_name_prefix() . $option_name );
}

/**
 * Get a skin option value
 *
 * @param string $size
 * @param string $option_name
 * @return mixed
 */
function bp_tasty_skin_option_image_url( $option_name, $size = 'thumbnail' )
{
	return bp_tasty_option_image_url( bp_tasty_skins_options_name_prefix() . $option_name, $size );
}

/**
 * Get a skin image url
 *
 * @param string $image Relative path to image from skin images directory
 * @return string
 */
function bp_tasty_skin_image( $image )
{
	return BP_TASTY_SKIN_ASSETS_IMAGES_URL . '/' . $image;
}

/**
 * Get a skin script url
 *
 * @param string $handle
 * @return string
 */
function bp_tasty_skin_script( $handle )
{
	return BP_TASTY_SKIN_ASSETS_JS_URL . '/' . $handle . '.js';
}

/**
 * Get a skin stylesheet url
 *
 * @param string $handle
 * @return string
 */
function bp_tasty_skin_style( $handle )
{
	return BP_TASTY_SKIN_ASSETS_CSS_URL . '/' . $handle . '.css';
}

/**
 * Load a custom header background, or the default if one is not set
 *
 * @param string $option_name Name of the skin option from which background was uploaded
 * @param string $default_bg Relative path to default skin header background.
 * @return string
 */
function bp_tasty_skin_header_bg( $option_name, $default_bg )
{
	// try to load the bg
	$bg = bp_tasty_skin_option_image_url( $option_name, 'full' );

	// return custom bg, or the default
	return ( $bg ) ? $bg : bp_tasty_skin_image( $default_bg );
}

/**
 * Load a custom header logo, or the default if one is not set
 *
 * @param string $option_name Name of the skin option from which logo was uploaded
 * @param string $default_logo Relative path to default skin header logo.
 * @return string
 */
function bp_tasty_skin_header_logo( $option_name, $default_logo )
{
	// try to load a logo
	$logo = bp_tasty_skin_option_image_url( $option_name, 'full' );

	// return custom logo, or the default
	return ( $logo ) ? $logo : bp_tasty_skin_image( $default_logo );
}

/**
 * Filter the template that WP is trying to load to see if the skin
 * wants to completely override it.
 *
 * @param string $template
 * @return string
 */
function bp_tasty_filter_template( $template )
{
	// see if it exists in the skin
	$skin_template = bp_tasty_locate_template( array( basename( $template ) ) );
	
	// return skin template?
	if ( $skin_template ) {
		return $skin_template;
	} else {
		return $template;
	}
}
add_filter( 'template_include', 'bp_tasty_filter_template', 10 );

/**
 * Filter located template from bp_core_load_template
 *
 * @see bp_core_load_template()
 * @param string $located_template
 * @param array $template_names
 * @return string
 */
function bp_tasty_filter_bp_template( $located_template, $template_names )
{
	// template already located, skip
	if ( empty( $located_template ) ) {
		return bp_tasty_locate_template( $template_names );
	} else {
		return $located_template;
	}
}
add_filter( 'bp_located_template', 'bp_tasty_filter_bp_template', 10, 2 );

/**
 * Check if template exists in custom style (skin) path
 *
 * @param array $template_names
 * @param boolean $load Auto load template if set to true
 * @return string
 */
function bp_tasty_locate_template( $template_names, $load = false )
{
	// did we get an array?
	if ( is_array( $template_names ) ) {

		// loop through all templates
		foreach ( $template_names as $template_name ) {

			// prepend all template names with the active skin dir
			$located_template = BP_TASTY_SKIN_DIR . '/' . $template_name;

			// does it exist?
			if ( file_exists( $located_template ) ) {
				// load it?
				if ($load) {
					load_template( $located_template );
				}
				// return the located template path
				return $located_template;
			}
		}
	}

	// didn't find a template
	return '';
}

/**
 * Load a template
 *
 * @param string $template_name
 * @return string
 */
function bp_tasty_load_template( $template_name )
{
	if ( !is_array( $template_name ) ) {
		$template_name = array( $template_name );
	}

	return bp_tasty_locate_template( $template_name, true );
}

?>
