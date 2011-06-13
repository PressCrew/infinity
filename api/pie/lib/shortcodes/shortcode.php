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
 * @property-read array $attributes Attribute defaults set in config
 * @property-read string $template Relative path to shortcode template file
 */
abstract class Pie_Easy_Shortcodes_Shortcode extends Pie_Easy_Component
{
	/**
	 * Config attribute default override delimeter
	 */
	const DEFAULT_ATTR_DELIM = '=';

	/**
	 * Config attribute default override delimeter
	 */
	const DEFAULT_TEMPLATE_DIR = 'templates';

	/**
	 * Attributes passed to handler
	 *
	 * @var array
	 */
	private $the_atts;

	/**
	 * Content passed to handler
	 *
	 * @var string
	 */
	private $the_content;

	/**
	 * @ignore
	 */
	public function __construct( $theme, $name, $title, $desc = null  )
	{
		// run parent
		parent::__construct( $theme, $name, $title, $desc );

		// set up handler
		add_shortcode( $this->name, array( $this, 'render_handler' ) );
	}

	/**
	 * Return path to default template
	 *
	 * @return string
	 */
	private function default_template()
	{
		return Pie_Easy_Files::path_build(
			PIE_EASY_LIBEXT_DIR,
			$this->policy()->get_handle(),
			self::DEFAULT_TEMPLATE_DIR,
			$this->policy()->factory()->ext($this) . '.php'
		);
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
		$this->the_atts = shortcode_atts( $def_atts, $atts );

		// set the content
		$this->the_content = $content;

		// render it
		return $this->render( false );
	}

	/**
	 * Extract attributes, content and load the template (if it exists)
	 */
	final public function load_template()
	{
		// try to locate the template
		$_template = Pie_Easy_Scheme::instance()->locate_template( $this->template );

		// need default template?
		if ( empty( $_template ) ) {
			$_template = $this->default_template();
		}

		// set up env
		extract( $this->the_atts );
		$content = $this->get_content();

		// load template
		include( $_template );
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
		if ( isset( $this->the_atts[$name] ) ) {
			return $this->the_atts[$name];
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
		return $this->the_content;
	}
	
	/**
	 * Set the attribute defaults as defined in the config
	 *
	 * @param array $atts
	 */
	public function set_attributes( $atts )
	{
		$this->set_directive( 'attributes', $atts );
	}
	
	/**
	 * Set the template file path
	 *
	 * @param string $path
	 */
	public function set_template( $path )
	{
		$this->set_directive( 'template', $path );
	}
}

?>
