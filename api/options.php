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

Pie_Easy_Loader::load( 'options' );

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
		Infinity_Options_Renderer::init();
	}

	/**
	 * Initialize options AJAX requrest handling
	 */
	static public function init_ajax()
	{
		Pie_Easy_Loader::load('ajax');
		$uploader = new Infinity_Options_Uploader();
		$uploader->init_ajax();
	}
}

/**
 * Infinity Options Section
 */
class Infinity_Options_Section extends Pie_Easy_Options_Section
{
	/**
	 * Constructor
	 *
	 * @param string $name
	 * @param string $title
	 */
	public function __construct( $name, $title )
	{
		// run the parent
		parent::__construct( $name, $title );

		// set default classes
		$this->set_class( 'rm_section' );
		$this->set_class_title( 'rm_title' );
		$this->set_class_content( 'rm_options' );
	}
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
			// add form processing
			if ( current_user_can('manage_options') ) {
				add_action( 'buddypress_page_infinity-control-panel', array( self::$instance, 'process_form' ) );
			}
		}

		return self::$instance;
	}

	/**
	 * Initialize the registry with an options file
	 *
	 * @param string $ini
	 * @param string $section_class
	 * @param string $option_class
	 * @return boolean
	 */
	static public function init( $ini, $section_class, $option_class )
	{
		// already initialized?
		if ( self::$instance instanceof self ) {
			return;
		}

		// initialize it!
		return self::instance()->load_config_file( $ini, $section_class, $option_class );
	}

	/**
	 * Create a new renderer
	 *
	 * @return Infinity_Options_Renderer
	 */
	protected function create_renderer()
	{
		$renderer = new Infinity_Options_Renderer();
		$renderer->enable_uploader( new Infinity_Options_Uploader( 'admin_head' ) );
		return $renderer;
	}

}

/**
 * Infinity Options Renderer
 */
class Infinity_Options_Renderer extends Pie_Easy_Options_Renderer
{
	// nothing custom yet
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
 * Render all sections in the registery
 *
 * @param boolean $output
 * @return string|void
 */
function infinity_options_registry_render_sections( $output = true )
{
	return Infinity_Options_Registry::instance()->render_sections( $output );
}

/**
 * Get an option value
 *
 * @param string $option_name
 * @return mixed
 */
function infinity_option( $option_name )
{
	return Infinity_Options_Registry::instance()->option( $option_name )->get();
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
	return Infinity_Options_Registry::instance()->option( $option_name )->get_image_src( $size );
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
	return Infinity_Options_Registry::instance()->option( $option_name )->get_image_url( $size );
}

?>
