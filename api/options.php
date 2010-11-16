<?php
/**
 * BP Tasty Theme options classes file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://bp-tricks.com/
 * @copyright Copyright &copy; 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package api
 * @subpackage options
 * @since 1.0
 */

require_once( BP_TASTY_PIE_DIR . '/options/section.php' );
require_once( BP_TASTY_PIE_DIR . '/options/option.php' );
require_once( BP_TASTY_PIE_DIR . '/options/registry.php' );
require_once( BP_TASTY_PIE_DIR . '/options/renderer.php' );

/**
 * Tasty Options Section
 */
class BP_Tasty_Options_Section extends Pie_Easy_Options_Section
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
class BP_Tasty_Options_Option extends Pie_Easy_Options_Option
{
	/**
	 * Return the name of the implementing API
	 *
	 * @return string
	 */
	protected function get_api_slug()
	{
		return 'bp_tasty';
	}
}

/**
 * Tasty Options Registry
 */
class BP_Tasty_Options_Registry extends Pie_Easy_Options_Registry
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
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Create a new option from scratch
	 *
	 * @param string $name Option name may only contain alphanumeric characters as well as the underscore for use as a word separator.
	 * @param string $title
	 * @param string $desc
	 * @param string $field_type
	 * @param string $section
	 * @return BP_Tasty_Options_Option
	 */
	protected function create_option( $name, $title, $desc, $field_type, $section = BP_Tasty_Options_Option::DEFAULT_SECTION )
	{
		return new BP_Tasty_Options_Option( $name, $title, $desc, $field_type, $section );
	}

	/**
	 * Create a new section from scratch
	 *
	 * @param string $name Section name may only contain alphanumeric characters as well as the underscore for use as a word separator.
	 * @param string $title
	 * @return BP_Tasty_Options_Section
	 */
	protected function create_section( $name, $title )
	{
		return new BP_Tasty_Options_Section( $name, $title );
	}

	/**
	 * Create a new renderer
	 *
	 * @return BP_Tasty_Options_Renderer
	 */
	protected function create_renderer()
	{
		return new BP_Tasty_Options_Renderer();
	}

}

/**
 * Tasty Options Renderer
 */
class BP_Tasty_Options_Renderer extends Pie_Easy_Options_Renderer
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
function bp_tasty_options_registry_render_sections( $output = true )
{
	return BP_Tasty_Options_Registry::instance()->render_sections( $output );
}
?>
