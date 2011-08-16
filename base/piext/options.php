<?php
/**
 * Infinity Theme: options classes file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package api
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load( 'utils/ajax', 'utils/files', 'options' );

/**
 * Infinity Theme: options policy
 *
 * @package api
 * @subpackage options
 */
class Infinity_Options_Policy extends Pie_Easy_Options_Policy
{
	/**
	 * @return Pie_Easy_Options_Policy
	 */
	static public function instance()
	{
		self::$calling_class = __CLASS__;
		return parent::instance();
	}
	
	/**
	 * Return the name of the implementing API
	 *
	 * @return string
	 */
	final public function get_api_slug()
	{
		return 'infinity_theme';
	}

	/**
	 * @ignore
	 * @return boolean
	 */
	final public function enable_styling()
	{
		return ( is_admin() );
	}

	/**
	 * @ignore
	 * @return boolean
	 */
	final public function enable_scripting()
	{
		return ( is_admin() );
	}
	
	/**
	 * @return Infinity_Options_Registry
	 */
	final public function new_registry()
	{
		return new Infinity_Options_Registry();
	}

	/**
	 * @return Infinity_Exts_Option_Factory
	 */
	final public function new_factory()
	{
		return new Infinity_Exts_Option_Factory();
	}

	/**
	 * @return Infinity_Options_Renderer
	 */
	final public function new_renderer()
	{
		return new Infinity_Options_Renderer();
	}

	/**
	 * @return Infinity_Options_Uploader
	 */
	final public function new_uploader()
	{
		return new Infinity_Options_Uploader( 'admin_head' );
	}

}

/**
 * Infinity Theme: options registry
 *
 * @package api
 * @subpackage options
 */
class Infinity_Options_Registry extends Pie_Easy_Options_Registry
{
	/**
	 * Set up form handler
	 *
	 * @ignore
	 * @return void
	 */
	static public function init_form_processing()
	{
		if ( empty( $_POST[Infinity_Options_Renderer::FIELD_MANIFEST] ) ) {
			return;
		}

		// current theme name
		$theme = get_stylesheet();

		// add form processing
		if ( defined('DOING_AJAX') ) {
			add_action( 'wp_ajax_infinity_options_update', array( Infinity_Options_Policy::instance()->registry($theme), 'process_form_ajax' ) );
		} else {
			add_action( 'load-toplevel_page_' . INFINITY_ADMIN_PAGE, array( Infinity_Options_Policy::instance()->registry($theme), 'process_form' ) );
		}
	}
}
add_action( 'wp_loaded', array( 'Infinity_Options_Registry', 'init_form_processing' ) );

/**
 * Infinity Theme: option factory
 *
 * @package api
 * @subpackage exts
 */
class Infinity_Exts_Option_Factory extends Pie_Easy_Options_Factory
{
	/**
	 * @param string $ext
	 * @return string
	 */
	final public function load_ext( $ext )
	{
		// run parent
		return parent::load_ext( $ext, 'Infinity_Option' );
	}
}

/**
 * Infinity Theme: options renderer
 *
 * @package api
 * @subpackage options
 */
class Infinity_Options_Renderer extends Pie_Easy_Options_Renderer
{
	/**
	 * Returns true if single save button should be displayed
	 *
	 * @return boolean
	 */
	private function do_save_single_button()
	{
		return ( infinity_scheme_directive( Pie_Easy_Scheme::DIRECTIVE_OPT_SAVE_SINGLE ) );
	}

	/**
	 * Override render option method to customize output
	 */
	protected function render_output()
	{
		// load option block template
		$this->load_template( 'block' );
	}

	/**
	 * Renders option save buttons
	 */
	final public function render_buttons()
	{
		// save all
		$this->render_save_all();

		// save one?
		if ( $this->do_save_single_button() ) {
			$this->render_save_one();
		}
	}

	/**
	 * Renders option meta info
	 */
	final public function render_meta()
	{
		// load meta information template
		$this->load_template( 'meta' );
	}

	/**
	 * Render sample code for this option
	 */
	final public function render_sample_code()
	{
		// load sample code template
		$this->load_template( 'sample_code' );
	}

	/**
	 * @ignore
	 * @param string $name
	 */
	private function load_template( $name )
	{
		infinity_dashboard_load_template(
			Pie_Easy_Files::path_build( 'options', $name . '.php', true ),
			array(
				'option' => $this->get_current(),
				'renderer' => $this
			)
		);
	}
}

/**
 * Infinity Theme: options uploader
 *
 * @package api
 * @subpackage options
 */
class Infinity_Options_Uploader extends Pie_Easy_Options_Uploader
{
	// nothing custom yet
}

//
// Helpers
//

/**
 * Initialize options environment
 */
function infinity_options_init( $theme = null )
{
	// component policy
	$options_policy = Infinity_Options_Policy::instance();

	// enable component
	Pie_Easy_Scheme::instance($theme)->enable_component( $options_policy );
	
	do_action( 'infinity_options_init' );
}

/**
 * Initialize options screen requirements
 */
function infinity_options_init_screen()
{
	// init ajax OR screen reqs (not both)
	if ( defined( 'DOING_AJAX') ) {
		Infinity_Options_Policy::instance()->registry()->init_ajax();
		do_action( 'infinity_options_init_ajax' );
	} else {
		Infinity_Options_Policy::instance()->registry()->init_screen();
		do_action( 'infinity_options_init_screen' );
	}
}

/**
 * Get an option value
 *
 * @param string $option_name
 * @return mixed
 */
function infinity_option( $option_name )
{
	return infinity_option_get( $option_name );
}

/**
 * Get special meta data about option value
 *
 * @param string $option_name
 * @param string $meta_type The only valid type so far is "time_updated"
 * @return mixed
 */
function infinity_option_meta( $option_name, $meta_type )
{
	return Infinity_Options_Policy::instance()->registry()->get( $option_name )->get_meta( $meta_type );
}

/**
 * Get an option image src array
 *
 * @param string $option_name
 * @param string $size Either a string (`thumbnail`, `medium`, `large` or `full`), or a two item array representing width and height in pixels, e.g. array(32,32). The size of media icons are never affected.
 * @return array
 */
function infinity_option_image_src( $option_name, $size = 'thumbnail' )
{
	return Infinity_Options_Policy::instance()->registry()->get( $option_name )->get_image_src( $size );
}

/**
 * Get an option image url
 *
 * @param string $option_name
 * @param string $size Either a string (`thumbnail`, `medium`, `large` or `full`), or a two item array representing width and height in pixels, e.g. array(32,32). The size of media icons are never affected.
 * @return string
 */
function infinity_option_image_url( $option_name, $size = 'thumbnail' )
{
	return Infinity_Options_Policy::instance()->registry()->get( $option_name )->get_image_url( $size );
}

/**
 * Fetch and option object from the registry
 *
 * @param type $option_name
 * @return Pie_Easy_Options_Option
 */
function infinity_option_fetch( $option_name )
{
	return Infinity_Options_Policy::instance()->registry()->get( $option_name );
}

/**
 * Get the value of an option
 *
 * @param type $option_name
 * @return Pie_Easy_Options_Option
 */
function infinity_option_get( $option_name )
{
	return infinity_option_fetch($option_name)->get();
}

/**
 * Begin rendering an option
 *
 * @param type $option_name
 */
function infinity_option_render_begin( $option_name )
{
	global $infinity_246f86b591;

	if ( $infinity_246f86b591 instanceof Pie_Easy_Options_Option ) {
		throw new Exception(
			'Cannot begin rendering option "%s" because rendering of option "%s" has not ended!',
			$option_name, $infinity_246f86b591->name
		);
	}
	
	// fetch it
	$infinity_246f86b591 = infinity_option_fetch($option_name)->render_bypass();

	// start rendering
	return $infinity_246f86b591->render_begin();
}

/**
 * Render the escaped title text for the option
 */
function infinity_option_render_title()
{
	global $infinity_246f86b591;
	return $infinity_246f86b591->render_title();
}

/**
 * Render the escaped description text for the option
 */
function infinity_option_render_description()
{
	global $infinity_246f86b591;
	return $infinity_246f86b591->render_description();
}

/**
 * Render the label element for the option
 */
function infinity_option_render_label()
{
	global $infinity_246f86b591;
	return $infinity_246f86b591->render_label();
}

/**
 * Render the field element for the option
 */
function infinity_option_render_field()
{
	global $infinity_246f86b591;
	return $infinity_246f86b591->render_field();
}

/**
 * Render the meta elements for the option
 */
function infinity_option_render_meta()
{
	global $infinity_246f86b591;
	return $infinity_246f86b591->render_meta();
}

/**
 * Render one or both button elements for the option
 */
function infinity_option_render_buttons()
{
	global $infinity_246f86b591;
	return $infinity_246f86b591->render_buttons();
}

/**
 * Render the save all button element for the option
 */
function infinity_option_render_save_all()
{
	global $infinity_246f86b591;
	return $infinity_246f86b591->render_save_all();
}

/**
 * Render the save one button element for the option
 */
function infinity_option_render_save_one()
{
	global $infinity_246f86b591;
	return $infinity_246f86b591->render_save_one();
}

/**
 * End rendering the option
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
 * @param array $args
 */
function infinity_options_render_menu( $args = null )
{
	// define default args
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

	// current theme
	$theme = get_stylesheet();

	// get registries for this theme
	$sections_registry = Infinity_Sections_Policy::instance()->registry($theme);

	// get only "root" sections
	$sections = $sections_registry->get_roots( $get_sections );

	// load the menu template
	infinity_dashboard_load_template(
		Pie_Easy_Files::path_build( 'options', 'menu.php', true ),
		array( 'sections' => $sections )
	);
}

/**
 * Render a menu section
 *
 * @param Pie_Easy_Sections_Section $section
 */
function infinity_options_render_menu_section( Pie_Easy_Sections_Section $section )
{
	// get registries for this theme
	$sections_registry = Infinity_Sections_Policy::instance()->registry($theme);
	$options_registry = Infinity_Options_Policy::instance()->registry($theme);
	
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
		Pie_Easy_Files::path_build( 'options', 'menu_section.php', true ),
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
 * @param array $options
 */
function infinity_options_render_menu_options( $options )
{
	// load the menu options template
	infinity_dashboard_load_template(
		Pie_Easy_Files::path_build( 'options', 'menu_options.php', true ),
		array( 'options' => $options )
	);
}

/**
 * Render options according to the option name POST var
 *
 * @ignore
 */
function infinity_options_render_options_screen()
{
	// section
	$load_section = null;
	
	if ( !empty($_POST['load_section']) ) {
		$load_section = $_POST['load_section'];
	} else {
		Pie_Easy_Ajax::responseStd( false, 'Missing required "load_section" parameter' );
	}

	// option
	$load_option = null;

	if ( !empty($_POST['load_option']) ) {
		$load_option = $_POST['load_option'];
	}

	// options to render
	$options = array();

	// look up section
	$section = Infinity_Sections_Policy::instance()->registry()->get( $load_section );

	// did we get a valid section?
	if ( $section instanceof Pie_Easy_Sections_Section ) {
		// load specific option?
		if ( $load_option ) {
			// look up the single option
			$option = Infinity_Options_Policy::instance()->registry()->get( $load_option );
			// did we get a valid option?
			if ( $option instanceof Pie_Easy_Options_Option ) {
				// add it to options to array
				$options[] = $option;
			}
		} else {
			// get all options for the section
			$options = Infinity_Options_Policy::instance()->registry()->get_menu_options( $section );
		}
	}

	// content to return
	$content = null;

	// option content
	$option_content = null;

	// loop through all options and render each one
	foreach ( $options as $option_to_render ) {
		// enable post override
		$option_to_render->enable_post_override();
		// try to render the option
		$section->add_component( $option_to_render );
	}

	// render the section
	$content = $section->render( false );

	// respond
	if ( strlen($content) ) {
		Pie_Easy_Ajax::responseStd( true, null, $content );
	} else {
		Pie_Easy_Ajax::responseStd( false, __('Failed to render options', infinity_text_domain) );
	}

}
add_action( 'wp_ajax_infinity_options_screen', 'infinity_options_render_options_screen' );

?>
