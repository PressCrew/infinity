<?php
/**
 * BP Tasty Theme options classes file
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
 * Tasty Options
 */
class Tasty_Options
{
	/**
	 * Initialize options support
	 */
	static public function init()
	{
		Tasty_Options_Renderer::init();
	}

	/**
	 * Initialize options AJAX requrest handling
	 */
	static public function init_ajax()
	{
		Pie_Easy_Loader::load('ajax');
		$uploader = new Tasty_Options_Uploader();
		$uploader->init_ajax();
	}
}

/**
 * Tasty Options Section
 */
class Tasty_Options_Section extends Pie_Easy_Options_Section
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
 * Tasty Options Option
 */
class Tasty_Options_Option extends Pie_Easy_Options_Option
{
	/**
	 * Return the name of the implementing API
	 *
	 * @return string
	 */
	protected function get_api_slug()
	{
		return 'tasty_theme';
	}
}

/**
 * Tasty Options Option
 */
class Tasty_Options_Skin_Option extends Tasty_Options_Option
{
	/**
	 * Use a custom name prefix to keep option scopes from being tainted
	 * 
	 * @return string
	 */
	protected function name_prefix()
	{
		return tasty_skins_options_name_prefix();
	}
}

/**
 * Tasty Options Registry
 */
class Tasty_Options_Registry extends Pie_Easy_Options_Registry
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
				add_action( 'buddypress_page_tasty-control-panel', array( self::$instance, 'process_form' ) );
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
	 * @return Tasty_Options_Renderer
	 */
	protected function create_renderer()
	{
		$renderer = new Tasty_Options_Renderer();
		$renderer->enable_uploader( new Tasty_Options_Uploader( 'admin_head' ) );
		return $renderer;
	}

}

/**
 * Tasty Options Renderer
 */
class Tasty_Options_Renderer extends Pie_Easy_Options_Renderer
{
	// nothing custom yet
}

/**
 * Tasty Options Uploader
 */
class Tasty_Options_Uploader extends Pie_Easy_Options_Uploader
{
	// nothing custom yet
}

//
// Helpers
//

/**
 * Initialize the registry
 *
 * @return boolean
 */
function tasty_options_registry_init()
{
	return Tasty_Options_Registry::init(
		TASTY_CONF_DIR . '/options.ini',
		'Tasty_Options_Section',
		'Tasty_Options_Option' );
}

/**
 * Render all sections in the registery
 *
 * @param boolean $output
 * @return string|void
 */
function tasty_options_registry_render_sections( $output = true )
{
	return Tasty_Options_Registry::instance()->render_sections( $output );
}

/**
 * Get an option value
 *
 * @param string $option_name
 * @return mixed
 */
function tasty_option( $option_name )
{
	return Tasty_Options_Registry::instance()->option( $option_name )->get();
}

/**
 * Get an option image url
 * 
 * @param string $option_name
 * @param string $size
 * @return mixed
 */
function tasty_option_image_url( $option_name, $size = 'thumbnail' )
{
	return Tasty_Options_Registry::instance()->option( $option_name )->get_image_url( $size );
}

?>
