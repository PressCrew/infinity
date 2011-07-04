<?php
/**
 * PIE API: shortcode class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage shortcodes
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/component', 'utils/files' );

/**
 * Make a shortcode easy
 *
 * @package PIE
 * @subpackage shortcodes
 */
abstract class Pie_Easy_Shortcodes_Shortcode extends Pie_Easy_Component
{
	/**
	 * Config attribute default override delimeter
	 */
	const DEFAULT_ATTR_DELIM = '=';

	/**
	 * Attributes passed to handler
	 *
	 * @var array
	 */
	private $__the_atts__;

	/**
	 * Content passed to handler
	 *
	 * @var string
	 */
	private $__the_content__;

	/**
	 * @ignore
	 */
	public function init()
	{
		parent::init();
		
		// set up handler
		add_shortcode( $this->name, array( $this, 'render_handler' ) );
	}

	/**
	 * This method handles all shortcode calls
	 *
	 * @ignore
	 * @param array $atts
	 * @param string $content
	 */
	final public function render_handler( $atts, $content = null )
	{
		// any config att overrides set?
		if ( is_array( $this->attributes ) ) {

			// atts from config
			$config_atts = array();

			// need to split up the values
			foreach ( $this->attributes as $attribute ) {
				// split at delim
				$a_parts = explode( self::DEFAULT_ATTR_DELIM, $attribute );
				// should be exactly two parts
				if ( count( $a_parts ) == 2 ) {
					// first part is key, second part is value
					$config_atts[$a_parts[0]] = $a_parts[1];
				}
			}

			// merge the config defaults over top of the core defaults
			$def_atts = array_merge( $this->default_atts(), $config_atts );
			
		} else {
			// just use the core defaults
			$def_atts = $this->default_atts();
		}

		// set the attributes
		$this->__the_atts__ = shortcode_atts( $def_atts, $atts );

		// set the content
		$this->__the_content__ = $content;

		// render it
		return $this->render( false );
	}

	/**
	 * Extract attributes, content and load the template (if it exists)
	 */
	final public function load_template()
	{
		// try to locate the template
		$__template__ = Pie_Easy_Scheme::instance()->locate_template( $this->template );

		// need default template?
		if ( empty( $__template__ ) ) {
			$__template__ = $this->default_template();
		}

		// set up env
		extract( $this->__the_atts__ );
		$content = $this->get_content();

		// load template
		include( $__template__ );
	}

	/**
	 * Default attributes defined by the shortcode itself (core defaults)
	 *
	 * These can be overridden by config settings
	 *
	 * @return array
	 */
	protected function default_atts()
	{
		return array();
	}

	/**
	 * Get the value for an attribute by name (key)
	 * 
	 * @param string $name
	 * @return mixed
	 */
	protected function get_att( $name )
	{
		if ( isset( $this->__the_atts__[$name] ) ) {
			return $this->__the_atts__[$name];
		}

		return null;
	}
	
	/**
	 * Default content defined by the shortcode itself
	 *
	 * Child classes will often want to override this to process the content and return
	 * it formatted a special way.
	 *
	 * @return array
	 */
	protected function get_content()
	{
		return $this->__the_content__;
	}
	
	/**
	 * Set the attribute defaults as defined in the config
	 *
	 * @param array $atts
	 */
	public function set_attributes( $atts )
	{
		$this->directives()->set( $this->theme, 'attributes', $atts );
	}
}

?>
