<?php
/**
 * ICE API: base style class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @subpackage dom
 * @since 1.0
 */

ICE_Loader::load( 'dom/asset' );

/**
 * Make styles for components easy
 *
 * @package ICE
 * @subpackage dom
 */
class ICE_Style extends ICE_Asset
{
	/**
	 * The rules
	 *
	 * @var array
	 */
	private $rules = array();

	/**
	 */
	final public function enqueue( $args = array() )
	{
		ICE_Styles::instance()->enqueue_object( $this, $args );
	}

	/**
	 * Add/get a rule for a selector
	 *
	 * @param string $handle
	 * @param string $selector CSS selector to affect
	 * @return ICE_Style_Rule
	 */
	public function rule( $handle, $selector = null )
	{
		// get or create a rule
		if ( isset( $this->rules[ $handle ] ) ) {
			$rule = $this->rules[ $handle ];
		} else {
			// new rule object
			$rule = new ICE_Style_Rule( $selector );
			// add it to rule map
			$this->rules[ $handle ] = $rule;
		}

		// return it for editing
		return $rule;
	}

	/**
	 * Clear any rules which may have been set
	 */
	public function clear_rules()
	{
		$this->rules = array();
	}

	/**
	 * Render CSS markup for this style's dynamic rules
	 */
	public function render()
	{
		// run parent first!
		parent::render();

		// render rules
		foreach ( $this->rules as $handle => $rule ) {
			// render output of rule export
			echo
			'/*+++ generating style ***/', PHP_EOL,
			$rule->export(),
			'/*--- style generation complete! */', PHP_EOL, PHP_EOL;
		}
	}
}

/**
 * Make rules for styles easy
 *
 * @package ICE
 * @subpackage dom
 * @property string $selector CSS selector to which apply declarations
 */
class ICE_Style_Rule extends ICE_Base
{
	/**
	 * The selector
	 *
	 * @var string
	 */
	private $selector;

	/**
	 * The declarations.
	 *
	 * These are imported and exported "as is"
	 *
	 * @var array
	 */
	private $declarations = array();

	/**
	 * The properties.
	 *
	 * These are passed through style property validators and formatters.
	 *
	 * @var array
	 */
	private $properties = array();

	/**
	 * Constructor
	 *
	 * @param string $selector CSS selector expression
	 */
	public function __construct( $selector )
	{
		// set selector
		$this->selector = $selector;
	}

	/**
	 */
	public function get_selector()
	{
		return $this->selector;
	}

	/**
	 * Add a declaration
	 *
	 * @param string $property
	 * @param mixed $value
	 */
	public function add_declaration( $property, $value )
	{
		$this->declarations[ $property ] = $value;
	}

	/**
	 * Add a declaration (shorthand)
	 *
	 * @param string $property
	 * @param mixed $value
	 */
	public function ad( $property, $value )
	{
		return $this->add_declaration( $property, $value );
	}

	/**
	 * Add a property.
	 *
	 * @param string $property
	 * @param mixed $value This should NOT be formatted in any way.
	 */
	public function add_property( $property, $value )
	{
		$this->properties[ $property ] = $value;
	}

	/**
	 * Generate CSS markup for this rule
	 *
	 * @param array $declarations
	 * @return string
	 */
	public function export( $declarations = null )
	{
		// the markup that will be returned
		$markup = null;

		// declarations passed in?
		if ( is_array( $declarations ) ) {
			// merge over existing decs
			$declarations = array_merge( $this->declarations, $declarations );
		} else {
			// use existing decs as is
			$declarations = $this->declarations;
		}

		// open declarations with the selector
		$markup = $this->selector . " {" . PHP_EOL;

		// add each dec
		foreach ( $declarations as $property => $value ) {
			$markup .= sprintf( "\t%s: %s;", $property, $value ) . PHP_EOL;
		}

		// add each prop
		foreach ( $this->properties as $property => $value ) {
			$style_prop = ICE_Style_Property_Factory::instance()->create( $property );
			$style_prop->set_value( $value );
			$markup .= "\t" . $style_prop->format() . ';' . PHP_EOL;
		}

		// close
		$markup .= '}' . PHP_EOL;

		// all done
		return $markup;
	}
}

/**
 * A style value container and formatter
 *
 * @package ICE
 * @subpackage dom
 */
abstract class ICE_Style_Value extends ICE_Base
{
	/**
	 * The shared flyweight instances.
	 *
	 * @var array
	 */
	static private $flyweights = array();

	/**
	 * Get flyweight instance for type.
	 *
	 * @param string $type
	 * @return ICE_Style_Value
	 */
	final static public function get_flyweight( $type )
	{
		// is instance missing for type?
		if ( false === isset( self::$flyweights[ $type ] ) ) {
			// yep, format class
			$class = 'ICE_Style_Value_' . $type;
			// create static instance
			self::$flyweights[ $type ] = new $class();
		}

		// return static instance
		return self::$flyweights[ $type ];
	}

	/**
	 * Format the value as a string.
	 *
	 * @param ICE_Style_Property_Primitive $property
	 * @return string
	 */
	public function format( ICE_Style_Property_Primitive $property )
	{
		return $property->get_value();
	}

	/**
	 * Validate the value
	 *
	 * @param ICE_Style_Property_Primitive $property
	 * @param mixed $value
	 */
	abstract public function validate( ICE_Style_Property_Primitive $property, $value );
}

/**
 * A style integer value
 *
 * @package ICE
 * @subpackage dom
 */
class ICE_Style_Value_Integer extends ICE_Style_Value
{
	/**
	 */
	public function validate( ICE_Style_Property_Primitive $property, $value )
	{
		throw new Exception( 'I need some work' );
	}
}

/**
 * A style number value
 *
 * @package ICE
 * @subpackage dom
 */
class ICE_Style_Value_Number extends ICE_Style_Value
{
	/**
	 */
	public function validate( ICE_Style_Property_Primitive $property, $value )
	{
		return is_numeric( $value );
	}
}

/**
 * A style string value
 *
 * @package ICE
 * @subpackage dom
 */
class ICE_Style_Value_String extends ICE_Style_Value
{
	/**
	 */
	public function validate( ICE_Style_Property_Primitive $property, $value )
	{
		// pretty much have to allow anything here
		return true;
	}

	/**
	 */
	public function format( ICE_Style_Property_Primitive $property )
	{
		return sprintf( '"%s"', esc_attr( $property->get_value() ) );
	}
}

/**
 * A style color value
 *
 * @package ICE
 * @subpackage dom
 */
class ICE_Style_Value_Color extends ICE_Style_Value
{
	/**
	 */
	public function validate( ICE_Style_Property_Primitive $property, $value )
	{
		// color must be a hex or alpha string
		return (boolean) preg_match( '/^(#[0-9a-f]{3,6})|([a-z-]+)$/', $value );
	}
}

/**
 * A style URI value
 *
 * @package ICE
 * @subpackage dom
 */
class ICE_Style_Value_Uri extends ICE_Style_Value
{
	/**
	 */
	public function validate( ICE_Style_Property_Primitive $property, $value )
	{
		return true;
	}

	/**
	 */
	public function format( ICE_Style_Property_Primitive $property )
	{
		// simply wrap it with a URL call
		return sprintf( "url('%s')", $property->get_value() );
	}
}

/**
 * A style counter value
 *
 * @package ICE
 * @subpackage dom
 */
class ICE_Style_Value_Counter extends ICE_Style_Value
{
	/**
	 */
	public function validate( ICE_Style_Property_Primitive $property, $value )
	{
		throw new Exception( 'I need some work' );
	}
}

/**
 * A style identifier value
 *
 * @package ICE
 * @subpackage dom
 */
class ICE_Style_Value_Identifier extends ICE_Style_Value
{
	/**
	 */
	public function validate( ICE_Style_Property_Primitive $property, $value )
	{
		throw new Exception( 'I need some work' );
	}
}

/**
 * A style enumeration value
 *
 * @package ICE
 * @subpackage dom
 */
class ICE_Style_Value_Enum extends ICE_Style_Value
{
	/**
	 */
	public function validate( ICE_Style_Property_Primitive $property, $value )
	{
		// get enums from property
		$enums = $property->get_value_list();

		// check if value is a key
		return isset( $enums[ $value ] );
	}
}

/**
 * A style length value
 *
 * @package ICE
 * @subpackage dom
 */
class ICE_Style_Value_Length extends ICE_Style_Value
{
	/**
	 * Allowed units
	 *
	 * @var array
	 */
	private static $units =
		array(
			'em' => true,
			'ex' => true,
			'in' => true,
			'cm' => true,
			'mm' => true,
			'pt' => true,
			'pc' => true,
			'px' => true
		);

	/**
	 */
	public function validate( ICE_Style_Property_Primitive $property, $value )
	{
		// must be at least 3 chars
		if ( strlen( $value ) >= 3 ) {
			// get last two chars
			$unit = substr( $value, -2 );
			// check against whitelist
			if ( true === isset( self::$units[ $unit ] ) ) {
				// must be numeric
				return is_numeric( substr( $value, 0, -2 ) );
			}
		}

		// invalid
		return false;
	}
}

/**
 * A style percentage value
 *
 * @package ICE
 * @subpackage dom
 */
class ICE_Style_Value_Percentage extends ICE_Style_Value
{
	/**
	 */
	public function validate( ICE_Style_Property_Primitive $property, $value )
	{
		// must be at least 2 chars
		if ( strlen( $value ) >= 2 ) {
			// get last char
			$unit = substr( $value, -1 );
			// must be a percent sign
			if ( '%' === $unit ) {
				// must be numeric
				return is_numeric( substr( $value, 0, -1 ) );
			}
		}

		// invalid
		return false;
	}
}

/**
 * Make style properties easy
 *
 * @package ICE
 * @subpackage dom
 */
abstract class ICE_Style_Property extends ICE_Base
{
	/**
	 * The property name
	 *
	 * @var string
	 */
	private $name;

	/**
	 * Constructor
	 *
	 * @param string $name The name of the property
	 */
	public function __construct( $name )
	{
		$this->name = $name;
	}

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function get_name()
	{
		return $this->name;
	}

	/**
	 * Static factory method
	 *
	 * @param string $name Name of the property to create
	 * @return ICE_Style_Property
	 */
	abstract static public function create( $name );

	/**
	 * Get the current style value object for this property
	 *
	 * @return ICE_Style_Value
	 */
	abstract public function get_value();

	/**
	 * Set the value for this property
	 *
	 * @param mixed $value The value to set
	 * @return boolean
	 */
	abstract public function set_value( $value );

	/**
	 * Return the formatted property
	 *
	 * @param string $format An alternate sprintf() format to use instead of the default
	 * @return string
	 */
	abstract public function format( $format = null );
}

/**
 * A simple style property which has a single value
 *
 * @package ICE
 * @subpackage dom
 */
final class ICE_Style_Property_Primitive extends ICE_Style_Property
{
	/**
	 * Integer values map key
	 */
	const KEY_INTEGER = 1;

	/**
	 * Number values map key
	 */
	const KEY_NUMBER = 2;

	/**
	 * String values map key
	 */
	const KEY_STRING = 4;

	/**
	 * Color values map key
	 */
	const KEY_COLOR = 8;

	/**
	 * Length values map key
	 */
	const KEY_LENGTH = 16;

	/**
	 * Percentage values map key
	 */
	const KEY_PERCENTAGE = 32;

	/**
	 * URI values map key
	 */
	const KEY_URI = 64;

	/**
	 * Counter values map key
	 */
	const KEY_COUNTER = 128;

	/**
	 * Identifier values map key
	 */
	const KEY_IDENTIFIER = 256;

	/**
	 * Enumeration values map key
	 */
	const KEY_ENUM = 512;

	/**
	 * @var ICE_Style_Value
	 */
	private $value_helper;
	
	/**
	 * An array of possible style value objects for this property
	 *
	 * @var array
	 */
	private $value_helpers = array();

	/**
	 * Array of whitelist value => description pairs.
	 * 
	 * @var array 
	 */
	private $value_list = array();

	/**
	 * The current value (including unit).
	 *
	 * @var integer|string
	 */
	private $value;

	/**
	 */
	static public function create( $name )
	{
		return new self( $name );
	}

	/**
	 * Return the value list.
	 *
	 * @return array
	 */
	public function get_value_list()
	{
		return $this->value_list;
	}

	/**
	 * Return true if value is set.
	 *
	 * @return boolean
	 */
	public function has_value()
	{
		return ( null !== $this->value );
	}

	/**
	 */
	public function get_value()
	{
		return $this->value;
	}

	/**
	 */
	public function set_value( $value )
	{
		foreach ( $this->value_helpers as $value_helper ) {
			if ( true === $value_helper->validate( $this, $value ) ) {
				$this->value_helper = $value_helper;
				$this->value = $value;
				return true;
			}
		}

		return false;
	}

	/**
	 */
	public function format( $format = null )
	{
		if ( null === $format ) {
			$format = '%s: %s';
		}

		return sprintf( $format, $this->get_name(), $this->format_value() );
	}

	/**
	 * Return formatted value only.
	 *
	 * @return string
	 */
	public function format_value()
	{
		return $this->value_helper->format( $this );
	}

	/**
	 * Add color as a possible value type
	 */
	public function add_color()
	{
		if ( !isset( $this->value_helpers[ self::KEY_COLOR ] ) ) {
			$this->value_helpers[ self::KEY_COLOR ] = ICE_Style_Value::get_flyweight( 'Color' );
		}

		return $this;
	}

	/**
	 * Add enumeration as a possible value type
	 */
	public function add_enum( $key, $desc = null )
	{
		if ( !isset( $this->value_helpers[ self::KEY_ENUM ] ) ) {
			$this->value_helpers[ self::KEY_ENUM ] = ICE_Style_Value::get_flyweight( 'Enum' );
			$this->value_list[ 'inherit' ] = __( 'Inherit', 'infinity-engine' );
		}

		$this->value_list[ $key ] = $desc;

		return $this;
	}

	/**
	 * Add length as a possible value type
	 */
	public function add_length()
	{
		if ( !isset( $this->value_helpers[ self::KEY_LENGTH ] ) ) {
			$this->value_helpers[ self::KEY_LENGTH ] = ICE_Style_Value::get_flyweight( 'Length' );
		}

		return $this;
	}

	/**
	 * Add number as a possible value type
	 */
	public function add_number()
	{
		if ( !isset( $this->value_helpers[ self::KEY_NUMBER ] ) ) {
			$this->value_helpers[ self::KEY_NUMBER ] = ICE_Style_Value::get_flyweight( 'Number' );
		}

		return $this;
	}

	/**
	 * Add percentage as a possible value type
	 */
	public function add_percentage()
	{
		if ( !isset( $this->value_helpers[ self::KEY_PERCENTAGE ] ) ) {
			$this->value_helpers[ self::KEY_PERCENTAGE ] = ICE_Style_Value::get_flyweight( 'Percentage' );
		}

		return $this;
	}

	/**
	 * Add string as a possible value type
	 */
	public function add_string()
	{
		if ( !isset( $this->value_helpers[ self::KEY_STRING ] ) ) {
			$this->value_helpers[ self::KEY_STRING ] = ICE_Style_Value::get_flyweight( 'String' );
		}

		return $this;
	}

	/**
	 * Add URI as a possible value type
	 */
	public function add_uri()
	{
		if ( !isset( $this->value_helpers[ self::KEY_URI ] ) ) {
			$this->value_helpers[ self::KEY_URI ] = ICE_Style_Value::get_flyweight( 'Uri' );
		}

		return $this;
	}
}

/**
 * A complex style property which has a two or more values
 *
 * @package ICE
 * @subpackage dom
 */
final class ICE_Style_Property_Composite extends ICE_Style_Property
{
	/**
	 * Array of primitive properties which compose this composite's value
	 *
	 * @var array
	 */
	private $properties = array();

	/**
	 * Constructor
	 *
	 * @param string $name Name of the property to create
	 * @param array $primitives Array of primitive property objects which make up this composite.
	 */
	public function __construct( $name, $primitives )
	{
		throw new Exception( 'Composite style properties are not yet fully supported!' );

		parent::__construct( $name );

		foreach ( $primitives as $primitive ) {
			if ( $primitive instanceof ICE_Style_Property_Primitive ) {
				$this->properties[ $primitive->name ] = $primitive;
			} else {
				throw new Exception( 'Only primitive properties can be added to a composite property' );
			}
		}
	}

	/**
	 * @return ICE_Style_Property_Composite
	 */
	static public function create( $property )
	{
		return new self( $property );
	}

	/**
	 */
	public function get_value()
	{
		return $this->properties;
	}

	/**
	 */
	public function set_value( $value )
	{
		if ( !is_array( $value ) || !$value instanceof ArrayAccess ) {
			throw new Exception( 'Value must be an array or accessible as an array' );
		}

		foreach ( $this->properties as $property ) {
			if ( isset( $value[$property->name] ) ) {
				$property->set_value( $value[$property->name] );
			}
		}

		return true;
	}

	/**
	 */
	public function format( $format = null )
	{
		// array of values to format
		$values = array();

		// loop all properties
		foreach( $this->properties as $primitive ) {
			// is value set on this property?
			if ( $primitive->has_value() ) {
				// yes, push it onto the values array
				array_push( $values, $primitive->format_value() );
			}
		}

		// implode all values with a space delimeter
		return sprintf( '%s: %s', implode( ' ', $values ) );
	}
}

/**
 * A style property factory
 *
 * @package ICE
 * @subpackage dom
 */
final class ICE_Style_Property_Factory extends ICE_Base
{
	/**
	 * Singleton instance
	 *
	 * @var ICE_Style_Property
	 */
	static private $instance;

	/**
	 * Singleton constructor
	 */
	private function __construct()
	{
		// this is a singleton
	}

	/**
	 * Return the singleton instance
	 *
	 * @return ICE_Style_Property_Factory
	 */
	final static public function instance()
	{
		if ( !self::$instance instanceof self ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @return ICE_Style_Property_Primitive
	 */
	public function create( $property )
	{
		// format the method name
		$method_name = 'prop_' . str_replace( '-', '_', $property );

		// does method exist?
		if ( method_exists( $this, $method_name ) ) {
			// yes, call it to return property object
			return call_user_func( array( $this, $method_name ) );
		} else {
			// no, not good
			throw new Exception( sprintf( 'The method "%s" has not been implemented yet', $method_name ) );
		}
	}

	/**
	 * Return a new background-color property object
	 *
	 * @return ICE_Style_Property
	 */
	protected function prop_background_color()
	{
		return $this->prop_color( 'background-color' );
	}

	/**
	 * Return a new background-image property object
	 *
	 * @return ICE_Style_Property
	 */
	protected function prop_background_image()
	{
		return
			ICE_Style_Property_Primitive::create( 'background-image' )
				->add_uri()
				->add_enum( 'none' );
	}

	/**
	 * Return a new background-repeat property object
	 *
	 * @return ICE_Style_Property
	 */
	protected function prop_background_repeat()
	{
		return
			ICE_Style_Property_Primitive::create( 'background-repeat' )
				->add_enum( 'repeat', __( 'Full Tiling', 'infinity-engine' ) )
				->add_enum( 'repeat-x', __( 'Tile Horizontally Only', 'infinity-engine' ) )
				->add_enum( 'repeat-y', __( 'Tile Vertically Only', 'infinity-engine' ) )
				->add_enum( 'no-repeat', __( 'Disable Tiling', 'infinity-engine' ) );
	}

	/**
	 * Return a new border-color property object
	 *
	 * @return ICE_Style_Property
	 */
	protected function prop_border_color()
	{
		return $this->prop_color( 'border-color' );
	}

	/**
	 * Return a new border-style property object
	 *
	 * @return ICE_Style_Property
	 */
	protected function prop_border_style()
	{
		return
			ICE_Style_Property_Primitive::create( 'border-style' )
				->add_enum( 'none', __( 'No Border', 'infinity-engine' ) )
				->add_enum( 'hidden', __( 'Hidden', 'infinity-engine' ) )
				->add_enum( 'dotted', __( 'Dotted', 'infinity-engine' ) )
				->add_enum( 'dashed', __( 'Dashed', 'infinity-engine' ) )
				->add_enum( 'solid', __( 'Solid', 'infinity-engine' ) )
				->add_enum( 'double', __( 'Double', 'infinity-engine' ) )
				->add_enum( 'groove', __( 'Groove', 'infinity-engine' ) )
				->add_enum( 'ridge', __( 'Ridge', 'infinity-engine' ) )
				->add_enum( 'inset', __( 'Inset', 'infinity-engine' ) )
				->add_enum( 'outset', __( 'Outset', 'infinity-engine' ) );
	}

	/**
	 * Return a new border-width property object
	 *
	 * @return ICE_Style_Property
	 */
	protected function prop_border_width()
	{
		return
			ICE_Style_Property_Primitive::create( 'border-width' )
				->add_length()
				->add_percentage()
				->add_enum( 'thin', __( 'Thin', 'infinity-engine' ) )
				->add_enum( 'medium', __( 'Medium', 'infinity-engine' ) )
				->add_enum( 'thick', __( 'Thick', 'infinity-engine' ) );
	}

	/**
	 * Return a new color property object
	 *
	 * @return ICE_Style_Property
	 */
	protected function prop_color( $property = 'color' )
	{
		return
			ICE_Style_Property_Primitive::create( $property )
				->add_color()
				->add_enum( 'black', __( 'Black', 'infinity-engine' ) )
				->add_enum( 'silver', __( 'Silver', 'infinity-engine' ) )
				->add_enum( 'gray', __( 'Gray', 'infinity-engine' ) )
				->add_enum( 'white', __( 'White', 'infinity-engine' ) )
				->add_enum( 'maroon', __( 'Maroon', 'infinity-engine' ) )
				->add_enum( 'red', __( 'Red', 'infinity-engine' ) )
				->add_enum( 'purple', __( 'Purple', 'infinity-engine' ) )
				->add_enum( 'fuchsia', __( 'Fuchsia', 'infinity-engine' ) )
				->add_enum( 'green', __( 'Green', 'infinity-engine' ) )
				->add_enum( 'lime', __( 'Lime', 'infinity-engine' ) )
				->add_enum( 'olive', __( 'Olive', 'infinity-engine' ) )
				->add_enum( 'yellow', __( 'Yellow', 'infinity-engine' ) )
				->add_enum( 'navy', __( 'Navy', 'infinity-engine' ) )
				->add_enum( 'blue', __( 'Blue', 'infinity-engine' ) )
				->add_enum( 'teal', __( 'Teal', 'infinity-engine' ) )
				->add_enum( 'aqua', __( 'Aqua', 'infinity-engine' ) );
	}

	/**
	 * Return a new content property object
	 *
	 * @return ICE_Style_Property
	 */
	protected function prop_content()
	{
		return
			ICE_Style_Property_Primitive::create( 'content' )
				->add_string();
	}

	/**
	 * Return a new font-family property object
	 *
	 * @return ICE_Style_Property
	 */
	protected function prop_font_family()
	{
		return
			ICE_Style_Property_Primitive::create( 'font-family' )
				->add_enum( 'serif', __( 'Times (serif)', 'infinity-engine' ) )
				->add_enum( 'sans-serif', __( 'Helvetica (sans-serif)', 'infinity-engine' ) )
				->add_enum( 'monospace', __( 'Courier (monospace)', 'infinity-engine' ) )
				->add_enum( 'cursive', __( 'Zapf-Chancery (cursive)', 'infinity-engine' ) )
				->add_enum( 'fantasy', __( 'Western (fantasy)', 'infinity-engine' ) );
	}

	/**
	 * Return a new font-weight property object
	 *
	 * @return ICE_Style_Property
	 */
	protected function prop_font_weight()
	{
		return
			ICE_Style_Property_Primitive::create( 'font-weight' )
				->add_enum( 'lighter', __( 'Lighter', 'infinity-engine' ) )
				->add_enum( 'normal', __( 'Normal', 'infinity-engine' ) )
				->add_enum( 'bold', __( 'Bold', 'infinity-engine' ) )
				->add_enum( 'bolder', __( 'Bolder', 'infinity-engine' ) )
				->add_enum( '100', '100' )
				->add_enum( '200', '200' )
				->add_enum( '300', '300' )
				->add_enum( '400', '400' )
				->add_enum( '500', '500' )
				->add_enum( '600', '600' )
				->add_enum( '700', '700' )
				->add_enum( '800', '800' )
				->add_enum( '900', '900' );
	}

	/**
	 * Return a new height property object
	 *
	 * @return ICE_Style_Property
	 */
	protected function prop_height()
	{
		return
			ICE_Style_Property_Primitive::create( 'height' )
				->add_length()
				->add_percentage()
				->add_enum( 'auto' );
	}

	/**
	 * Return a new margin property object
	 *
	 * @return ICE_Style_Property
	 */
	protected function prop_margin( $property = 'margin' )
	{
		return
			ICE_Style_Property_Primitive::create( $property )
				->add_length()
				->add_percentage();
	}

	/**
	 * Return a new margin-top property object
	 *
	 * @return ICE_Style_Property
	 */
	protected function prop_margin_top()
	{
		return $this->prop_margin( 'margin-top' );
	}

	/**
	 * Return a new margin-right property object
	 *
	 * @return ICE_Style_Property
	 */
	protected function prop_margin_right()
	{
		return $this->prop_margin( 'margin-right' );
	}

	/**
	 * Return a new margin-bottom property object
	 *
	 * @return ICE_Style_Property
	 */
	protected function prop_margin_bottom()
	{
		return $this->prop_margin( 'margin-bottom' );
	}

	/**
	 * Return a new margin-left property object
	 *
	 * @return ICE_Style_Property
	 */
	protected function prop_margin_left()
	{
		return $this->prop_margin( 'margin-left' );
	}

	/**
	 * Return a new max length property object
	 *
	 * @return ICE_Style_Property
	 */
	private function prop_max_length( $property )
	{
		return
			ICE_Style_Property_Primitive::create( $property )
				->add_enum( 'none' )
				->add_length()
				->add_percentage();
	}

	/**
	 * Return a new max-height property object
	 *
	 * @return ICE_Style_Property
	 */
	protected function prop_max_height()
	{
		return $this->prop_max_length( 'max-height' );
	}

	/**
	 * Return a new max-width property object
	 *
	 * @return ICE_Style_Property
	 */
	protected function prop_max_width()
	{
		return $this->prop_max_length( 'max-width' );
	}

	/**
	 * Return a new min length property object
	 *
	 * @return ICE_Style_Property
	 */
	private function prop_min_length( $property )
	{
		return
			ICE_Style_Property_Primitive::create( $property )
				->add_length()
				->add_percentage();
	}

	/**
	 * Return a new min-height property object
	 *
	 * @return ICE_Style_Property
	 */
	protected function prop_min_height()
	{
		return $this->prop_min_length( 'min-height' );
	}

	/**
	 * Return a new min-width property object
	 *
	 * @return ICE_Style_Property
	 */
	protected function prop_min_width()
	{
		return $this->prop_min_length( 'min-width' );
	}

	/**
	 * Return a new opacity property object
	 *
	 * @return ICE_Style_Property
	 */
	protected function prop_opacity()
	{
		return
			ICE_Style_Property_Primitive::create( 'opacity' )
				->add_number();
	}

	/**
	 * Return a new padding property object
	 *
	 * @return ICE_Style_Property
	 */
	protected function prop_padding( $property = 'padding' )
	{
		return
			ICE_Style_Property_Primitive::create( $property )
				->add_length()
				->add_percentage();
	}

	/**
	 * Return a new padding-top property object
	 * @return ICE_Style_Property
	 */
	protected function prop_padding_top()
	{
		return $this->prop_padding( 'padding-top' );
	}

	/**
	 * Return a new padding-right property object
	 *
	 * @return ICE_Style_Property
	 */
	protected function prop_padding_right()
	{
		return $this->prop_padding( 'padding-right' );
	}

	/**
	 * Return a new padding-bottom property object
	 *
	 * @return ICE_Style_Property
	 */
	protected function prop_padding_bottom()
	{
		return $this->prop_padding( 'padding-bottom' );
	}

	/**
	 * Return a new padding-left property object
	 *
	 * @return ICE_Style_Property
	 */
	protected function prop_padding_left()
	{
		return $this->prop_padding( 'padding-left' );
	}

	/**
	 * Return a new width property object
	 *
	 * @return ICE_Style_Property
	 */
	protected function prop_width( $property = 'width' )
	{
		return
			ICE_Style_Property_Primitive::create( $property )
				->add_length()
				->add_percentage()
				->add_enum( 'auto' );
	}
}
