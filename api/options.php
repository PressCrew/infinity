<?php
/**
 * Infinity Theme options classes file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://bp-tricks.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package api
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load( 'ajax', 'options' );

/**
 * Infinity Options Section
 */
class Infinity_Options_Section extends Pie_Easy_Options_Section
{
	// nothing custom yet
}

/**
 * Infinity Options Option
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
 * Infinity Options Registry
 */
class Infinity_Options_Registry extends Pie_Easy_Options_Registry
{
	/**
	 * The singleton instance
	 *
	 * @var Pie_Easy_Options_Registry
	 */
	private static $instance;

	/**
	 * Constructor
	 */
	private function __constructor()
	{
		// this is a singleton
	}

	/**
	 * Return the instance of this singleton
	 *
	 * @return Pie_Easy_Options_Registry
	 */
	static public function instance()
	{
		global $blog_id;
		
		if ( !self::$instance instanceof self ) {

			// init singleton
			self::$instance = new self();

			// set section and option classes
			self::$instance->set_section_class('Infinity_Options_Section');
			self::$instance->set_option_class('Infinity_Options_Option');
			
			// set renderer
			$renderer = new Infinity_Options_Option_Renderer();
			$renderer->enable_uploader( new Infinity_Options_Uploader( 'admin_head' ) );
			self::$instance->set_option_renderer( $renderer );
			
			// add form processing
			if ( current_user_can_for_blog( $blog_id, 'manage_options' ) ) {
				add_action( 'load-toplevel_page_' . INFINITY_ADMIN_PAGE, array( self::$instance, 'process_form' ) );
				add_action( 'wp_ajax_' . INFINITY_NAME . '_options_update', array( self::$instance, 'process_form_ajax' ) );
			}
		}

		return self::$instance;
	}
}

/**
 * Infinity Options Renderer
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
				<a class="infinity-cpanel-options-save" href="#">Save All</a>
				<?php if ( $this->do_save_single_button() ): ?>
					<a class="infinity-cpanel-options-save" href="#<?php $this->render_name() ?>">Save</a>
				<?php endif; ?>
			</div>
			<div class="infinity-cpanel-options-single-flash">
				<!-- flash messages for this option will render here -->
			</div>
			<ul>
				<li><a href="#<?php $this->render_name() ?>-tabs-1">Edit Setting</a></li>
				<?php if ( $this->has_documentation() ): ?>
				<li><a href="#<?php $this->render_name() ?>-tabs-2">Documentation</a></li>
				<?php endif; ?>
				<?php if ( is_admin() ): ?>
				<li><a href="#<?php $this->render_name() ?>-tabs-3">Sample Code</a></li>
				<?php endif; ?>
			</ul>
			<div id="<?php $this->render_name() ?>-tabs-1">
				<p><?php $this->render_description() ?></p>
				<?php $this->render_field() ?>
				<div class="infinity-cpanel-options-last-modified">
					Last Modified: <?php echo $this->render_date_updated() ?>
				</div>
			</div>
			<?php if ( $this->has_documentation() ): ?>
			<div id="<?php $this->render_name() ?>-tabs-2" class="infinity-docs">
				<p><?php $this->render_documentation( Pie_Easy_Scheme::instance()->theme_documentation_dirs() ) ?></p>
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
	 * Returns ture
	 * @return <type>
	 */
	private function do_save_single_button()
	{
		return ( infinity_scheme_directive( Pie_Easy_Scheme::DIRECTIVE_OPT_SAVE_SINGLE ) );
	}
}

/**
 * Infinity Options Uploader
 */
class Infinity_Options_Uploader extends Pie_Easy_Options_Uploader
{
	// nothing custom yet
}

//
// Helpers
//

/**
 * Get an option value
 *
 * @param string $option_name
 * @return mixed
 */
function infinity_option( $option_name )
{
	return Infinity_Options_Registry::instance()->get_option( $option_name )->get();
}

/**
 * Get special meta data about option value
 *
 * @param string $option_name
 * @param string $meta_type
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
 * @param string $size
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
 * @param string $size
 * @return string
 */
function infinity_option_image_url( $option_name, $size = 'thumbnail' )
{
	return Infinity_Options_Registry::instance()->get_option( $option_name )->get_image_url( $size );
}

/**
 * Render a menu composed of all the sections with their options.
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

	// get only "root" sections
	$sections = Infinity_Options_Registry::instance()->get_root_sections( $get_sections );

	// loop through fetched sections and render
	foreach ( $sections as $section ) {
		infinity_options_render_menu_section( $section );
	}
}

/**
 * Render a menu section
 */
function infinity_options_render_menu_section( Infinity_Options_Section $section )
{
	// get children of this section
	$children = Infinity_Options_Registry::instance()->get_section_children( $section );

	// get options for section
	$options = Infinity_Options_Registry::instance()->get_menu_options( $section );

	// check results
	if ( empty( $children ) && empty( $options ) ) {
		// don't render anything
		return;
	}
		
	// begin rendering ?>
	<div>
		<a href="#"><?php print esc_html( $section->title ) ?></a>
		<a href="#<?php print esc_attr( $section->name ) ?>" class="infinity-cpanel-options-menu-show infinity-cpanel-options-menu-showall">Show All</a>
	</div><?php

	if ( $children ) {
		// render all children sections ?>
		<div><div class="infinity-cpanel-options-menu infinity-cpanel-options-submenu"><?php
			foreach ( $children as $child ) {
				infinity_options_render_menu_section( $child );
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
 * @param array $options
 */
function infinity_options_render_menu_options( $options )
{
	// begin rendering ?>
	<ul><?php
	foreach( $options as $option ) { ?>
		<li><a href="#<?php print esc_attr( $option->name ) ?>" class="infinity-cpanel-options-menu-show"><?php print esc_html( $option->title ) ?></a></li><?php
	}?>
	</ul><?php
}

/**
 * Render options according to the option name POST var
 */
function infinity_options_render_options_screen()
{
	// options to render
	$options = array();

	// content to return
	$content = null;

	// try to populate options array
	if ( !empty( $_POST['option_name'] ) ) {
		// look up the single option
		$option = Infinity_Options_Registry::instance()->get_option( $_POST['option_name'] );
		// did we get a valid option?
		if ( $option instanceof Pie_Easy_Options_Option ) {
			// add it to options to array
			$options[] = $option;
		}
	} elseif ( !empty( $_POST['section_name'] ) ) {
		// look up section
		$section = Infinity_Options_Registry::instance()->get_section( $_POST['section_name'] );
		// did we get a valid section?
		if ( $section instanceof Pie_Easy_Options_Section ) {
			// get all options for this section
			$options = Infinity_Options_Registry::instance()->get_menu_options( $section );
		}
	}

	// loop through all options and render each one
	foreach ( $options as $option_to_render ) {
		// try to render the option
		$content .= Infinity_Options_Registry::instance()->render_option( $option_to_render, false );
	}

	// respond
	if ( strlen($content) ) {
		Pie_Easy_Ajax::responseStd( true, null, $content );
	} else {
		Pie_Easy_Ajax::responseStd( false, 'Failed to render options' );
	}

}
add_action( 'wp_ajax_infinity_options_screen', 'infinity_options_render_options_screen' );

?>
