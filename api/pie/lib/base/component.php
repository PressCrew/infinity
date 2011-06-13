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

Pie_Easy_Loader::load( 'base/componentable', 'init/directive' );

/**
 * Make content components easy
 *
 * @package PIE
 * @subpackage base
 * @property-read string $theme The theme that created this concrete component
 * @property-read string $parent The parent component (slug)
 * @property-read string $name The concrete component name
 * @property-read string $title The concrete component title
 * @property-read string $description The concrete component description
 * @property-read string $class The CSS class to apply to the component's container
 * @property-read boolean|string $documentation true/false to enable/disable, string for manual page name
 * @property-read array $capabilities Required capabilities, can only be appended
 * @property-read string $required_feature Feature required for this component to run/display
 * @property-read string $required_option Options only required if this component is run/displayed
 * @property-read boolean $ignore Whether or not this component should be ignored
 */
abstract class Pie_Easy_Component extends Pie_Easy_Componentable
{
	/**
	 * Prefix for custom directives (pass thru vars)
	 */
	const PREFIX_PASS_THRU = '_';

	/**
	 * The theme that created this concrete component
	 *
	 * @var string
	 */
	private $theme;

	/**
	 * Name of the concrete component
	 *
	 * @var string
	 */
	private $name;

	/**
	 * The parent component
	 * 
	 * @var string
	 */
	private $parent;
	
	/**
	 * @var Pie_Easy_Map Option directives
	 */
	private $directives;

	/**
	 * @param string $theme The theme that created this option
	 * @param string $name Option name may only contain alphanumeric characters as well as the underscore for use as a word separator.
	 * @param string $title
	 * @param string $desc
	 */
	public function __construct( $theme, $name, $title, $desc = null  )
	{
		// set basic properties
		$this->theme = $theme;
		$this->set_name($name);

		// init directives map
		$this->directives = new Pie_Easy_Map();

		// set directives
		$this->set_directive( 'title', $title, true );
		$this->set_directive( 'description', $desc, true );
	}

	/**
	 * @ignore
	 * @param string $name
	 * @return mixed
	 */
	public function __get( $name )
	{
		if ( $this->directives->contains($name) ) {
			return $this->directives->item_at($name)->value;
		} else {
			return $this->$name;
		}
	}
	
	/**
	 * Set a directive
	 *
	 * @param string $name
	 * @param mixed $value
	 * @param boolean $read_only
	 * @return true
	 */
	final protected function set_directive( $name, $value, $read_only = null )
	{
		if ( $this->directives->contains($name) ) {
			$this->directives->item_at($name)->set_value($value, true);
		} else {
			$directive = new Pie_Easy_Init_Directive( $name, $value, $read_only );
			$this->directives->add( $name, $directive );
		}

		return true;
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
			return $this->set_directive( $name, $value, true );
		}

		return false;
	}

	/**
	 * Set additional capabilities which are required for this option to show
	 *
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

		if ( $this->capabilities ) {
			$capabilities = array_merge( $this->capabilities, $capabilities );
		}

		$this->set_directive( 'capabilities', $capabilities );
	}

	/**
	 * Check that current user has all required capabilities to view/edit this option
	 *
	 * @return boolean
	 */
	final public function check_caps()
	{
		foreach ( $this->capabilities as $cap ) {
			if ( !current_user_can( $cap ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * This template method is called at the end of the constructor
	 */
	protected function init()
	{
		// override this method to initialize special PHP handling for an option
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
	 * Check that theme has required feature support enabled if applicable
	 *
	 * @return boolean
	 */
	final public function supported()
	{
		if ( $this->required_feature ) {
			return current_theme_supports( $this->required_feature );
		}

		return true;
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
			$this->name = $name;
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
		if ( $this->name != $parent_name ) {
			$this->parent = trim( $parent_name );
		} else {
			throw new Exception( sprintf( 'The component "%s" cannot be a parent of itself', $this->name ) );
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
		return $this->name == $component->parent;
	}

	/**
	 * Set the CSS class attribute to apply to this component's container element
	 *
	 * @param string $class
	 */
	final public function set_class( $class )
	{
		$this->set_directive( 'class', $class );
	}

	/**
	 * Set the documentation file for this option
	 *
	 * @param string $rel_path Path to documentation file relative to the theme config docs
	 */
	final public function set_documentation( $rel_path )
	{
		$this->set_directive( 'documentation', trim( $rel_path, '\\/' ) );
	}

	/**
	 * Set a feature that must be supported/enabled for this option to display
	 *
	 * @param string $feature_name
	 */
	final public function set_required_feature( $feature_name )
	{
		$this->set_directive( 'required_feature', $feature_name, true );
	}

	/**
	 * Set an option that is required for this component to display
	 *
	 * @param string $option_name
	 */
	final public function set_required_option( $option_name )
	{
		$this->set_directive( 'required_option', $option_name, true );
	}

	/**
	 * Set ignore toggle
	 *
	 * @param boolean $toggle
	 */
	final public function set_ignore( $toggle )
	{
		$this->set_directive( 'ignore', (boolean) $toggle, true );
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
			return $this->policy()->renderer()->render( $this, $output );
		}
	}
}

?>
