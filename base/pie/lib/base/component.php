<?php
/**
 * PIE API: base component class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage base
 * @since 1.0
 */

Pie_Easy_Loader::load(
	'base/componentable',
	'base/style',
	'base/styleable',
	'init/directive',
	'utils/files'
);

/**
 * Make content components easy
 *
 * @package PIE
 * @subpackage base
 * @property-read string $theme The theme that created this concrete component
 * @property-read string $name The concrete component name
 * @property-read string $parent The parent component (slug)
 * @property-read string $title The concrete component title
 * @property-read string $description The concrete component description
 * @property-read string $class The CSS class to apply to the component's container
 * @property-read boolean|string $documentation true/false to enable/disable, string for manual page name
 * @property-read array $capabilities Required capabilities, can only be appended
 * @property-read string $required_feature Feature required for this component to run/display
 * @property-read string $required_option Options only required if this component is run/displayed
 * @property-read boolean $ignore Whether or not this component should be ignored
 * @property-read string $stylesheet Relative path to component stylesheet file
 * @property-read string $template Relative path to component template file
 */
abstract class Pie_Easy_Component
	extends Pie_Easy_Componentable
		implements Pie_Easy_Styleable
{
	/**
	 * Name of the default section
	 */
	const DEFAULT_SECTION = 'default';

	/**
	 * Name of the default templates subdir
	 */
	const DEFAULT_TEMPLATE_DIR = 'templates';
	
	/**
	 * Prefix for custom directives (pass thru vars)
	 */
	const PREFIX_PASS_THRU = '_';

	/**
	 * The theme that created this concrete component
	 *
	 * @var string
	 */
	private $__theme__;

	/**
	 * Name of the concrete component
	 *
	 * @var string
	 */
	private $__name__;

	/**
	 * The parent component
	 * 
	 * @var string
	 */
	private $__parent__;
	
	/**
	 * Component directives registry
	 *
	 * @var Pie_Easy_Init_Directive_Registry
	 */
	private $__directives__;

	/**
	 * Component's styling if applicable
	 *
	 * @var Pie_Easy_Style
	 */
	private $__style__;

	/**
	 * @param string $theme The theme that created this option
	 * @param string $name Option name may only contain alphanumeric characters as well as the underscore for use as a word separator.
	 * @param string $title
	 */
	public function __construct( $theme, $name, $title )
	{
		// set basic properties
		$this->__theme__ = $theme;
		$this->set_name($name);

		// init directives registry
		$this->__directives__ = new Pie_Easy_Init_Directive_Registry();

		// set directives
		$this->directives()->set( $this->__theme__, 'title', $title );

		// run init template method
		$this->init();
	}

	/**
	 * WARNING! This magic method creates a black hole for properties.
	 *
	 * Accessing a property which does not exist will always return null!
	 *
	 * @ignore
	 * @param string $name
	 * @return mixed
	 */
	public function __get( $name )
	{
		switch ( $name ) {
			case 'theme':
				return $this->__theme__;
			case 'name':
				return $this->__name__;
			case 'parent':
				return $this->__parent__;
			default:
				if ( $this->directives()->has($name) ) {
					return $this->directives()->get($name)->value;
				} else {
					return null;
				}
		}
	}

	/**
	 * Return directives registry
	 *
	 * @return Pie_Easy_Init_Directive_Registry
	 */
	final protected function directives()
	{
		return $this->__directives__;
	}

	/**
	 * Return one directive
	 *
	 * @param string $name
	 * @return mixed
	 */
	final protected function directive( $name )
	{
		return $this->directives()->get($name);
	}
	
	/**
	 * Return style object
	 *
	 * @return Pie_Easy_Style
	 */
	public function style()
	{
		if ( !$this->__style__ instanceof Pie_Easy_Style ) {
			$this->__style__ = new Pie_Easy_Style();
		}

		return $this->__style__;
	}

	/**
	 * Execute style import
	 *
	 * @return string
	 */
	public function import_css()
	{
		return $this->style()->import();
	}

	/**
	 * Execute style exporter
	 *
	 * @return string
	 */
	public function export_css()
	{
		return $this->style()->export();
	}
	
	/**
	 * Set a custom directive (pass thru var)
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return boolean
	 */
	final public function set_directive_var( $name, $value )
	{
		// first character match prefix?
		if ( $name{0} == self::PREFIX_PASS_THRU ) {
			return $this->directives()->set( $this->__theme__, $name, $value, true );
		}

		return false;
	}

	/**
	 * Set additional capabilities which are required for this option to show
	 *
	 * @todo needs a lot of testing
	 * @param string $string A comma separated list of capabilities
	 */
	final public function add_capabilities( $string )
	{
		// split at comma
		$caps = explode( ',', $string );

		// trim and set each
		foreach ( $caps as $cap ) {
			$cap_trimmed = trim( $cap );
			$capabilities[$cap_trimmed] = $cap_trimmed;
		}

		if ( $this->directives()->has('capabilities') ) {
			$theme_map = $this->directives()->get_map( 'capabilities' );
			$theme_caps = $theme_map->item_at($this->__theme__)->value;
			$theme_caps->merge_with( $capabilities );
		} else {
			$this->directives()->set( $this->__theme__, 'capabilities', $capabilities );
		}
	}

	/**
	 * Check that current user has all required capabilities to view/edit this option
	 *
	 * @return boolean
	 */
	final public function check_caps()
	{
		if ( !empty( $this->capabilities ) ) {
			foreach ( $this->capabilities as $cap ) {
				if ( !current_user_can( $cap ) ) {
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * This template method is called at the end of the constructor
	 */
	protected function init()
	{
		// override this method to initialize special PHP handling for a component
	}

	/**
	 * This template method is called if the current request is AJAX
	 */
	public function init_ajax()
	{
		// override this method to initialize special AJAX handling for an option
	}

	/**
	 * This template method is called on the option renderer init_screen method
	 */
	public function init_screen()
	{
		// override this method to initialize special screen handling for an option
	}

	/**
	 * This template method is called "just in time" to enqueue styles
	 */
	public function init_styles()
	{
		// override this method to initialize special style handling for an option
	}

	/**
	 * This template method is called "just in time" to enqueue scripts
	 */
	public function init_scripts()
	{
		// override this method to initialize special script handling for an option
	}

	/**
	 * Check that component is supported, varies by component
	 *
	 * @return boolean
	 */
	public function supported()
	{
		return $this->check_caps();
	}

	/**
	 * Set the name
	 *
	 * @param string $name
	 */
	private function set_name( $name )
	{
		// name must adhere to a strict format
		if ( preg_match( '/^[a-z0-9]+((_|-)[a-z0-9]+)*$/', $name ) ) {
			$this->__name__ = $name;
			return true;
		} else {
			throw new Exception( sprintf( 'Option name "%s" does not match the allowed pattern', $name ) );
		}
	}

	/**
	 * Set the parent component
	 *
	 * @param string $parent_name
	 */
	public function set_parent( $parent_name )
	{
		if ( $this->__name__ != $parent_name ) {
			$this->__parent__ = trim( $parent_name );
		} else {
			throw new Exception( sprintf( 'The component "%s" cannot be a parent of itself', $this->__name__ ) );
		}
	}

	/**
	 * Returns true if component is parent of given component
	 *
	 * @param Pie_Easy_Component $component
	 * @return boolean
	 */
	public function is_parent_of( Pie_Easy_Component $component )
	{
		return $this->__name__ == $component->parent;
	}

	/**
	 * Set the long description
	 *
	 * @param string $desc
	 */
	final public function set_description( $desc )
	{
		$this->directives()->set( $this->__theme__, 'description', $desc );
	}

	/**
	 * Set the CSS class attribute to apply to this component's container element
	 *
	 * @param string $class
	 */
	final public function set_class( $class )
	{
		$this->directives()->set( $this->__theme__, 'class', $class );
	}

	/**
	 * Set the documentation file for this option
	 *
	 * @param string $rel_path Path to documentation file relative to the theme config docs
	 */
	final public function set_documentation( $rel_path )
	{
		$this->directives()->set( $this->__theme__, 'documentation', trim( $rel_path, '\\/' ) );
	}

	/**
	 * Set a feature that must be supported/enabled for this option to display
	 *
	 * @param string $feature_name
	 */
	final public function set_required_feature( $feature_name )
	{
		$this->directives()->set( $this->__theme__, 'required_feature', $feature_name, true );
	}

	/**
	 * Set an option that is required for this component to display
	 *
	 * @param string $option_name
	 */
	final public function set_required_option( $option_name )
	{
		$this->directives()->set( $this->__theme__, 'required_option', $option_name, true );
	}

	/**
	 * Set the stylesheet file path
	 *
	 * @param string $path
	 */
	public function set_stylesheet( $path )
	{
		$this->style()->add_file( $path );
	}

	/**
	 * Set the template file path
	 *
	 * @param string $path
	 */
	public function set_template( $path )
	{
		$this->directives()->set( $this->__theme__, 'template', $path );
	}
	
	/**
	 * Set ignore toggle
	 *
	 * @param boolean $toggle
	 */
	final public function set_ignore( $toggle )
	{
		$this->directives()->set( $this->__theme__, 'ignore', (boolean) $toggle );
	}

	/**
	 * Return array of variables to extract() for use by the template
	 *
	 * @return array
	 */
	public function get_template_vars()
	{
		// empty array by default
		return array();
	}
	
	/**
	 * Return absolute path to default template
	 *
	 * @return string
	 */
	final protected function get_template_path()
	{
		// template path to return
		$template = null;

		// try to locate the template
		if ( $this->template ) {
			$template = Pie_Easy_Scheme::instance()->locate_template( $this->template );
		}

		if ( $template ) {
			return $template;
		} else {
			return $this->policy()->factory()->ext_tpl( $this );
		}
	}

	/**
	 * Load the template (if it exists)
	 */
	final public function load_template()
	{
		// get template vars
		$__tpl_vars__ = $this->get_template_vars();

		// extract?
		if ( is_array( $__tpl_vars__ ) && !empty( $__tpl_vars__ ) ) {
			extract( $__tpl_vars__ );
		}

		// load template
		include( $this->get_template_path() );
	}
	
	/**
	 * Render this component
	 *
	 * @param boolean $output Whether to output or return result
	 * @return string|void
	 */
	public function render( $output = true )
	{
		if ( $this->ignore ) {
			throw new Exception( 'Cannot render a component that has been ignored' );
		} else {
			if ( $output === true ) {
				$this->policy()->renderer()->render( $this, true );
				return true;
			} else {
				return $this->policy()->renderer()->render( $this, $output );
			}
		}
	}

	/**
	 * Render this component in bypass mode
	 *
	 * @return Pie_Easy_Renderer
	 */
	public function render_bypass()
	{
		if ( $this->ignore ) {
			throw new Exception( 'Cannot render a component that has been ignored' );
		} else {
			return $this->policy()->renderer()->render_bypass( $this );
		}
	}
}

?>
