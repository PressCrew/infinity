<?php
/**
 * Infinity Theme: options classes file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity-api
 * @subpackage options
 * @since 1.0
 */

ICE_Loader::load(
	'utils/ajax',
	'components/options/component',
	'components/options/policy',
	'components/options/registry',
	'components/options/renderer'
);

//
// Helpers
//

/**
 * Render an option (field only)
 *
 * @package Infinity-api
 * @subpackage options
 * @param string $option_name
 * @param boolean $output
 * @return mixed
 */
function infinity_option( $option_name, $output = true )
{
	return infinity_option_fetch( $option_name )->render( $output );
}

/**
 * Get an option image src array
 *
 * @package Infinity-api
 * @subpackage options
 * @param string $option_name
 * @param string $size Either a string (`thumbnail`, `medium`, `large` or `full`), or a two item array representing width and height in pixels, e.g. array(32,32). The size of media icons are never affected.
 * @return array
 */
function infinity_option_image_src( $option_name, $size = 'thumbnail' )
{
	return ICE_Policy::options()->registry()->get( $option_name )->get_image_src( $size );
}

/**
 * Get an option image url
 *
 * @package Infinity-api
 * @subpackage options
 * @param string $option_name
 * @param string $size Either a string (`thumbnail`, `medium`, `large` or `full`), or a two item array representing width and height in pixels, e.g. array(32,32). The size of media icons are never affected.
 * @return string
 */
function infinity_option_image_url( $option_name, $size = 'thumbnail' )
{
	return ICE_Policy::options()->registry()->get( $option_name )->get_image_url( $size );
}

/**
 * Fetch and option object from the registry
 *
 * @package Infinity-api
 * @subpackage options
 * @param string $option_name
 * @return ICE_Option
 */
function infinity_option_fetch( $option_name )
{
	return ICE_Policy::options()->registry()->get( $option_name );
}

/**
 * Get the value of an option
 *
 * @package Infinity-api
 * @subpackage options
 * @param string $option_name
 * @return ICE_Option
 */
function infinity_option_get( $option_name )
{
	return infinity_option_fetch($option_name)->get();
}

/**
 * Begin rendering an option
 *
 * @package Infinity-api
 * @subpackage options
 * @param string $option_name
 */
function infinity_option_render_begin( $option_name )
{
	global $infinity_246f86b591;

	if ( $infinity_246f86b591 instanceof ICE_Option ) {
		throw new Exception(
			'Cannot begin rendering option "%s" because rendering of option "%s" has not ended!',
			$option_name, $infinity_246f86b591->get_name()
		);
	}
	
	// fetch it
	$infinity_246f86b591 = infinity_option_fetch($option_name)->render_bypass();

	// start rendering
	return $infinity_246f86b591->render_begin();
}

/**
 * Render the escaped title text for the option
 *
 * @package Infinity-api
 * @subpackage options
 */
function infinity_option_render_title()
{
	global $infinity_246f86b591;
	return $infinity_246f86b591->render_title();
}

/**
 * Render the escaped description text for the option
 *
 * @package Infinity-api
 * @subpackage options
 */
function infinity_option_render_description()
{
	global $infinity_246f86b591;
	return $infinity_246f86b591->render_description();
}

/**
 * Render the label element for the option
 *
 * @package Infinity-api
 * @subpackage options
 */
function infinity_option_render_label()
{
	global $infinity_246f86b591;
	return $infinity_246f86b591->render_label();
}

/**
 * Render the field element for the option
 *
 * @package Infinity-api
 * @subpackage options
 */
function infinity_option_render_field()
{
	global $infinity_246f86b591;
	return $infinity_246f86b591->load_template();
}

/**
 * Render one or both button elements for the option
 *
 * @package Infinity-api
 * @subpackage options
 */
function infinity_option_render_buttons()
{
	global $infinity_246f86b591;
	return $infinity_246f86b591->render_buttons();
}

/**
 * Render the save one button element for the option
 *
 * @package Infinity-api
 * @subpackage options
 */
function infinity_option_render_save_one()
{
	global $infinity_246f86b591;
	return $infinity_246f86b591->render_save_one();
}

/**
 * End rendering the option
 *
 * @package Infinity-api
 * @subpackage options
 */
function infinity_option_render_end()
{
	global $infinity_246f86b591;

	// end rendering
	$result = $infinity_246f86b591->render_end();

	// wipe global
	unset( $infinity_246f86b591 );

	return $result;
}

/**
 * Render a menu composed of all the sections with their options.
 *
 * @package Infinity-api
 * @subpackage options
 * @param array $args
 */
function infinity_options_render_menu( $args = null )
{
	// define default args
	$defaults = new stdClass;
	$defaults->sections = null;

	// parse the args
	$options = (object) wp_parse_args( $args, $defaults );

	// sections to filter on
	$get_sections = array();

	// determine what sections to get
	if ( !empty( $options->sections ) ) {
		// split at comma
		$split_sections = explode( ',', $options->sections );
		// get each section from registry
		foreach ( $split_sections as $split_section ) {
			$get_sections[] = trim( $split_section );
		}
	}

	// get section registry for this theme
	$sections_registry = ICE_Policy::sections()->registry();

	// get only "root" sections
	$sections = $sections_registry->get_roots( $get_sections );

	// load the menu template
	infinity_dashboard_load_template(
		'options/menu.php',
		array( 'sections' => $sections )
	);
}

/**
 * Render a menu section
 *
 * @package Infinity-api
 * @subpackage options
 * @param ICE_Section $section
 */
function infinity_options_render_menu_section( ICE_Section $section )
{
	// get registries for this theme
	$sections_registry = ICE_Policy::sections()->registry();
	$options_registry = ICE_Policy::options()->registry();
	
	// get children of this section
	$children = $sections_registry->get_children( $section );

	// get options for section
	$options = $options_registry->get_menu_options( $section );

	// check results
	if ( empty( $children ) && empty( $options ) ) {
		// don't render anything
		return;
	}

	// load the menu section template
	infinity_dashboard_load_template(
		'options/menu-section.php',
		array(
			'section' => $section,
			'children' => $children,
			'options' => $options
		)
	);
}

/**
 * Render options for a menu section
 *
 * @package Infinity-api
 * @subpackage options
 * @param array $options
 */
function infinity_options_render_menu_options( $options )
{
	// load the menu options template
	infinity_dashboard_load_template(
		'options/menu-options.php',
		array( 'options' => $options )
	);
}

/**
 * Render options according to the option name POST var
 *
 * @package Infinity-api
 * @subpackage options
 */
function infinity_options_render_options_screen()
{
	// section
	$load_section = null;
	
	if ( !empty($_POST['load_section']) ) {
		$load_section = $_POST['load_section'];
	} else {
		ICE_Ajax::responseStd( false, 'Missing required "load_section" parameter' );
	}

	// group
	$load_group = null;

	if ( !empty($_POST['load_group']) ) {
		$load_group = $_POST['load_group'];
	}

	// name
	$load_name = null;

	if ( !empty($_POST['load_name']) ) {
		$load_name = $_POST['load_name'];
	}

	// options to render
	$options = array();

	// look up section
	$section = ICE_Policy::sections()->registry()->get( $load_section );

	// did we get a valid section?
	if ( $section instanceof ICE_Section ) {
		// load specific option?
		if ( $load_name ) {
			// look up the single option
			$option = ICE_Policy::options()->registry()->get( $load_name, $load_group );
			// did we get a valid option?
			if ( $option instanceof ICE_Option ) {
				// add it to options to array
				$options[] = $option;
			}
		} else {
			// get all options for the section
			$options = ICE_Policy::options()->registry()->get_menu_options( $section );
		}
	}

	// content to return
	$content = wp_nonce_field( 'ice_options_update', '_wpnonce', true, false );

	// loop through all options and render each one
	foreach ( $options as $option_to_render ) {
		// enable post override
		$option_to_render->enable_post_override();
		// add option to section components to render
		$section->add_component( $option_to_render, true );
	}

	// render the section
	$content .= $section->render( false );

	// respond
	if ( strlen($content) ) {
		ICE_Ajax::responseStd( true, null, $content );
	} else {
		ICE_Ajax::responseStd( false, __( 'Failed to render options', 'infinity-engine') );
	}

}
add_action( 'wp_ajax_infinity_options_screen', 'infinity_options_render_options_screen' );

/**
 * Initialize options form handling.
 *
 * @package Infinity-api
 * @subpackage options
 */
function infinity_options_init_form_processing()
{
	// is admin?
	if ( false === ICE_IS_ADMIN ) {
		// nope, just return
		return;
	}

	// add form processing
	if ( defined( 'DOING_AJAX' ) ) {
		add_action( 'wp_ajax_infinity_options_update', array( ICE_Policy::options()->registry(), 'process_form_ajax' ) );
	} else {
		add_action( 'load-appearance_page_' . INFINITY_ADMIN_PAGE, array( ICE_Policy::options()->registry(), 'process_form' ) );
	}
}
add_action( 'wp_loaded', 'infinity_options_init_form_processing' );
