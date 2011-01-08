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
 * Infinity Options
 */
class Infinity_Options
{
	/**
	 * Initialize options support
	 */
	static public function init()
	{
		Infinity_Options_Option_Renderer::init();
	}

	/**
	 * Initialize options AJAX requrest handling
	 */
	static public function init_ajax()
	{
		$uploader = new Infinity_Options_Uploader();
		$uploader->init_ajax();
	}
}

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
			if ( current_user_can('manage_options') ) {
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
				<a class="infinity-cpanel-options-save" href="#<?php $this->render_name() ?>">Save</a>
			</div>
			<div class="infinity-cpanel-options-single-flash">
				<!-- flash messages for this option will render here -->
			</div>
			<ul>
				<li><a href="#<?php $this->render_name() ?>-tabs-1">Edit Setting</a></li>
				<li><a href="#<?php $this->render_name() ?>-tabs-2">Documentation</a></li>
				<li><a href="#<?php $this->render_name() ?>-tabs-3">Sample Code</a></li>
			</ul>
			<div id="<?php $this->render_name() ?>-tabs-1">
				<p><?php $this->render_description() ?></p>
				<?php $this->render_field() ?>
			</div>
			<div id="<?php $this->render_name() ?>-tabs-2">
				<p>Docs for this option</p>
			</div>
			<div id="<?php $this->render_name() ?>-tabs-3">
				<p>Sample code for this option</p>
			</div>
		</div><?php
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
function infinity_options_render_menu_items()
{
	foreach ( Infinity_Options_Registry::instance()->get_sections() as $section ) {
		// get options
		$options = Infinity_Options_Registry::instance()->get_menu_options( $section );
		// if section has no options, skip it
		if ( empty( $options ) ) {
			continue;
		}
		// begin rendering ?>
		<div>
			<a href="#<?php print esc_attr( $section->name ) ?>"><?php print esc_html( $section->title ) ?></a>
		</div>
		<ul>
			<?php foreach( $options as $option ): ?>
			<li><a href="#<?php print esc_attr( $option->name ) ?>"><?php print esc_html( $option->title ) ?></a></li>
			<?php endforeach; ?>
		</ul><?php
	}
}

/**
 * Render options according to the option name POST var
 */
function infinity_options_render_options_screen()
{
	if ( isset( $_POST['option_name'] ) ) {
		// try to render the option
		$content = Infinity_Options_Registry::instance()->render_option( $_POST['option_name'], false );
		// respond
		if ( $content ) {
			Pie_Easy_Ajax::responseStd( true, null, $content );
		} else {
			Pie_Easy_Ajax::responseStd( false, 'Failed to render options' );
		}
	}
}
add_action( 'wp_ajax_infinity_options_screen', 'infinity_options_render_options_screen' );

?>
