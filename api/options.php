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

Pie_Easy_Loader::load( 'ajax', 'options' );

/**
 * Infinity Theme: options section
 *
 * @package api
 * @subpackage options
 */
class Infinity_Options_Section extends Pie_Easy_Options_Section
{
	// nothing custom yet
}

/**
 * Infinity Theme: options option
 *
 * @package api
 * @subpackage options
 */
class Infinity_Options_Option extends Pie_Easy_Options_Option
{
	/**
	 * Return the name of the implementing API
	 *
	 * @return string
	 */
	protected function get_api_slug()
	{
		return 'infinity_theme';
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
	 * The singleton instance
	 *
	 * Map of Pie_Easy_Options_Registry instances
	 *
	 * @var Pie_Easy_Map
	 */
	private static $instances;

	/**
	 * This is a singleton
	 */
	private function __constructor() {}

	/**
	 * Return the registry for a theme ancestory
	 *
	 * @param $theme Theme for which to manage options
	 * @return Pie_Easy_Options_Registry
	 */
	static public function instance( $theme = null )
	{
		if ( empty( $theme ) ) {
			$theme = get_stylesheet();
		}

		if ( !self::$instances instanceof Pie_Easy_Map ) {
			self::$instances = new Pie_Easy_Map();
		}

		if ( !self::$instances->contains( $theme ) ) {

			// init theme registry
			$registry = new self();

			// set section and option classes
			$registry->set_section_class('Infinity_Options_Section');
			$registry->set_option_class('Infinity_Options_Option');

			// set renderer
			$renderer = new Infinity_Options_Option_Renderer();
			$renderer->enable_uploader( new Infinity_Options_Uploader( 'admin_head' ) );
			$registry->set_option_renderer( $renderer );

			// push this registry onto the map
			self::$instances->add( $theme, $registry );
		}

		return self::$instances->item_at( $theme );
	}

	/**
	 * Set up form handler
	 *
	 * @ignore
	 * @return void
	 */
	static public function init_form_processing()
	{
		if ( empty( $_POST['_manifest_'] ) ) {
			return;
		}

		// switch blog?
		if ( !empty($_POST[Pie_Easy_Options_Registry::PARAM_BLOG_ID]) ) {
			switch_to_blog( $_POST[Pie_Easy_Options_Registry::PARAM_BLOG_ID] );
		}

		// current theme name
		$theme = get_stylesheet();

		// initialize scheme and options
		infinity_scheme_init( $theme );
		infinity_options_init( $theme );

		// add form processing
		if ( defined('DOING_AJAX') ) {
			add_action( 'wp_ajax_infinity_options_update', array( Infinity_Options_Registry::instance($theme), 'process_form_ajax' ) );
		} else {
			add_action( 'load-toplevel_page_' . INFINITY_ADMIN_PAGE, array( Infinity_Options_Registry::instance($theme), 'process_form' ) );
		}

		restore_current_blog();
	}

	final protected function localize_script()
	{
		parent::localize_script();

		wp_localize_script(
			INFINITY_NAME . '-cpanel',
			'InfinityOptionsL10n',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'blog_id' => $this->screen_blog_id,
				'blog_theme' => $this->screen_blog_theme,
			)
		);
	}
}
add_action( 'wp_loaded', array( 'Infinity_Options_Registry', 'init_form_processing' ) );

/**
 * Infinity Theme: options renderer
 *
 * @package api
 * @subpackage options
 */
class Infinity_Options_Option_Renderer extends Pie_Easy_Options_Option_Renderer
{
	/**
	 * Override render option method to customize output
	 */
	protected function render_option()
	{
		// start rendering ?>
		<div class="<?php $this->render_classes( 'infinity-cpanel-options-single' ) ?>">
			<div class="infinity-cpanel-options-single-header">
				<?php $this->render_label() ?>
				<a class="infinity-cpanel-options-save" href="#"><?php _e('Save All', infinity_text_domain) ?></a>
				<?php if ( $this->do_save_single_button() ): ?>
					<a class="infinity-cpanel-options-save" href="#<?php $this->render_name() ?>">Save</a>
				<?php endif; ?>
			</div>
			<div class="infinity-cpanel-options-single-flash">
				<!-- flash messages for this option will render here -->
			</div>
			<ul>
				<li><a href="#<?php $this->render_name() ?>-tabs-1"><?php _e('Edit Setting', infinity_text_domain) ?></a></li>
				<?php if ( $this->has_documentation() ): ?>
				<li><a href="#<?php $this->render_name() ?>-tabs-2"><?php _e('Documentation', infinity_text_domain) ?></a></li>
				<?php endif; ?>
				<?php if ( is_admin() ): ?>
				<li><a href="#<?php $this->render_name() ?>-tabs-3"><?php _e('Sample Code', infinity_text_domain) ?></a></li>
				<?php endif; ?>
			</ul>
			<div id="<?php $this->render_name() ?>-tabs-1">
				<p><?php $this->render_description() ?></p>
				<?php $this->render_field() ?>
				<div class="infinity-cpanel-options-last-modified">
					<?php _e('Last Modified:', infinity_text_domain) ?> <?php echo $this->render_date_updated() ?>
				</div>
			</div>
			<?php if ( $this->has_documentation() ): ?>
			<div id="<?php $this->render_name() ?>-tabs-2">
				<div class="infinity-docs"><?php $this->render_documentation( Pie_Easy_Scheme::instance()->theme_documentation_dirs() ) ?></div>
			</div>
			<?php endif; ?>
			<?php if ( is_admin() ): ?>
			<div id="<?php $this->render_name() ?>-tabs-3">
				<p><?php $this->render_sample_code() ?></p>
			</div>
			<?php endif; ?>
		</div><?php
	}

	/**
	 * Returns true if single save button should be displayed
	 *
	 * @return boolean
	 */
	private function do_save_single_button()
	{
		return ( infinity_scheme_directive( Pie_Easy_Scheme::DIRECTIVE_OPT_SAVE_SINGLE ) );
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
	// enable option in scheme
	Pie_Easy_Scheme::instance($theme)->enable_options( Infinity_Options_Registry::instance($theme) );

	// init screen reqs
	Infinity_Options_Registry::instance($theme)->init_screen();
	Infinity_Options_Registry::instance($theme)->init_ajax();

	do_action( 'infinity_options_init' );
}

/**
 * Get an option value
 *
 * @param string $option_name
 * @return mixed
 */
function infinity_option( $option_name )
{
	return Infinity_Options_Registry::instance()->get_option($option_name)->get();
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
	return Infinity_Options_Registry::instance()->get_option( $option_name )->get_meta( $meta_type );
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
	return Infinity_Options_Registry::instance()->get_option( $option_name )->get_image_src( $size );
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
	return Infinity_Options_Registry::instance()->get_option( $option_name )->get_image_url( $size );
}

/**
 * Render a menu composed of all the sections with their options.
 *
 * @ignore
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

	// get registry for this theme
	$registry = Infinity_Options_Registry::instance($theme);

	// get only "root" sections
	$sections = $registry->get_root_sections( $get_sections );

	// begin rendering ?>
	<div id="menu___root" class="infinity-cpanel-options-menu"><?php

	// loop through fetched sections and render
	foreach ( $sections as $section ) {
		infinity_options_render_menu_section( $registry, $section );
	}?>

	</div><?php
}

/**
 * Render a menu section
 *
 * @ignore
 */
function infinity_options_render_menu_section( Infinity_Options_Registry $registry, Infinity_Options_Section $section )
{
	// get children of this section
	$children = $registry->get_section_children( $section );

	// get options for section
	$options = $registry->get_menu_options( $section );

	// check results
	if ( empty( $children ) && empty( $options ) ) {
		// don't render anything
		return;
	}

	// begin rendering ?>
	<div id="menu___<?php print esc_attr( $section->name ) ?>">
		<a><?php print esc_html( $section->title ) ?></a>
		<a id="section___<?php print esc_attr( $section->name ) ?>" class="infinity-cpanel-options-menu-show infinity-cpanel-options-menu-showall" href="#"><?php _e('Show All', infinity_text_domain) ?></a>
	</div><?php

	if ( $children ) {
		// render all children sections ?>
		<div><div id="submenu___<?php print esc_attr( $section->name ) ?>" class="infinity-cpanel-options-menu infinity-cpanel-options-submenu"><?php
			foreach ( $children as $child ) {
				infinity_options_render_menu_section( $registry, $child );
			}?>
		</div></div><?php
	} else {
		// render this section's options
		infinity_options_render_menu_options( $options );
	}
}

/**
 * Render options for a menu section
 *
 * @ignore
 * @param array $options
 */
function infinity_options_render_menu_options( $options )
{
	// begin rendering ?>
	<ul><?php
	foreach( $options as $option ) { ?>
		<li><a id="option___<?php print esc_attr( $option->name ) ?>" class="infinity-cpanel-options-menu-show" href="#"><?php print esc_html( $option->title ) ?></a></li><?php
	}?>
	</ul><?php
}

/**
 * Render options according to the option name POST var
 *
 * @ignore
 */
function infinity_options_render_options_screen()
{
	// load type
	if ( !empty($_POST['load_type']) ) {
		$load_type = $_POST['load_type'];
	} else {
		Pie_Easy_Ajax::responseStd( false, 'Missing required "load_type" parameter' );
	}

	// load name
	if ( !empty($_POST['load_name']) ) {
		$load_name = $_POST['load_name'];
	} else {
		Pie_Easy_Ajax::responseStd( false, 'Missing requried "load_name" parameter' );
	}

	// switch blog?
	if ( !empty($_POST[Pie_Easy_Options_Registry::PARAM_BLOG_ID]) ) {
		switch_to_blog( $_POST[Pie_Easy_Options_Registry::PARAM_BLOG_ID] );
	}

	// current theme name
	$theme = get_stylesheet();

	// initialize scheme and options
	infinity_scheme_init( $theme );
	infinity_options_init( $theme );

	// options to render
	$options = array();

	// populate options array
	switch( $load_type ) {
		// load all options in a section
		case 'section':
			// look up section
			$section = Infinity_Options_Registry::instance($theme)->get_section( $load_name );
			// did we get a valid section?
			if ( $section instanceof Pie_Easy_Options_Section ) {
				// get all options for this section
				$options = Infinity_Options_Registry::instance($theme)->get_menu_options( $section );
			}
			break;
		// load a single option
		case 'option':
			// look up the single option
			$option = Infinity_Options_Registry::instance($theme)->get_option( $load_name );
			// did we get a valid option?
			if ( $option instanceof Pie_Easy_Options_Option ) {
				// add it to options to array
				$options[] = $option;
			}
			break;
		// unknown load type
		default:
			Pie_Easy_Ajax::responseStd( false, sprintf( 'The load type "%s" is invalid', $load_type ) );
	}

	// content to return
	$content = null;

	// loop through all options and render each one
	foreach ( $options as $option_to_render ) {
		// try to render the option
		$content .= Infinity_Options_Registry::instance($theme)->render_option( $option_to_render, false );
	}

	// restore blog
	restore_current_blog();

	// respond
	if ( strlen($content) ) {
		Pie_Easy_Ajax::responseStd( true, null, $content );
	} else {
		Pie_Easy_Ajax::responseStd( false, __('Failed to render options', infinity_text_domain) );
	}

}
add_action( 'wp_ajax_infinity_options_screen', 'infinity_options_render_options_screen' );

?>
