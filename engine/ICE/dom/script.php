<?php
/**
 * ICE API: base javascript class file
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
 * Make scripts for components easy
 *
 * @package ICE
 * @subpackage dom
 */
class ICE_Script extends ICE_Asset
{
	/**
	 * Keep track of objects for debugging
	 *
	 * @var integer
	 */
	private static $next_token = 1;

	/**
	 * The token of this object
	 *
	 * @var integer
	 */
	private $token;
	
	/**
	 * The blocks of logic
	 *
	 * @var array
	 */
	private $logic_stack = array();

	/**
	 * Constructor
	 */
	public function __construct( ICE_Component $component = null )
	{
		parent::__construct( $component );

		// set token and bump it
		$this->token = self::$next_token++;
	}

	/**
	 */
	final public function enqueue( $args = array() )
	{
		ICE_Scripts::instance()->enqueue_object( $this, $args );
	}

	/**
	 * Get/Set a new logic object
	 *
	 * @param string $handle
	 * @param string $code valid javascript code
	 * @return ICE_Script_Logic
	 */
	public function logic( $handle, $code = null )
	{
		// already exists?
		if ( true === isset( $this->logic_stack[ $handle ] ) ) {
			// yep, use that one
			$logic = $this->logic_stack[ $handle ];
		} else {
			// new logic object
			$logic = new ICE_Script_Logic( $code );
			// add it to logic stack
			$this->logic_stack[ $handle ] = $logic;
		}

		return $logic;
	}

	/**
	 * Begin new logic string
	 */
	public function begin_logic()
	{
		ob_start();
	}

	/**
	 * End logic string
	 *
	 * @param string $handle
	 * @return ICE_Script_Logic
	 */
	public function end_logic( $handle )
	{
		// add contents of output buffer to new logic object and return it
		return $this->logic( $handle, ob_get_clean() );
	}

	/**
	 * Render javascript markup for this script's dynamic code
	 */
	public function render( $wrap = false )
	{
		// render opening tag?
		if ( true === $wrap ) {
			// yep, render it
			?><script type="text/javascript"><?php
		}

		// run parent first!
		parent::render();

		// render rules
		if ( count( $this->logic_stack ) ) {
			// loop all logic objects
			foreach ( $this->logic_stack as $handle => $logic ) {
				// render output of logic export
				echo $logic->export();
			}
		}

		// render closing tag?
		if ( true === $wrap ) {
			// yep, render it
			?></script>
			<?php
		}
	}
}

/**
 * Make dynamic logic for scripts easy
 *
 * @property boolean $alias
 * @property boolean $ready
 * @property boolean|string $function
 * @package ICE
 * @subpackage dom
 */
class ICE_Script_Logic extends ICE_Base
{
	/**
	 * Keep track of objects for debugging
	 *
	 * @var integer
	 */
	private static $next_token = 1;

	/**
	 * The token of this object
	 *
	 * @var integer
	 */
	private $token;
	
	/**
	 * Set to true to wrap this logic block with the dollar sign alias
	 *
	 * @var boolean
	 */
	private $alias = false;

	/**
	 * Set to true to wrap this logic block in on document ready method
	 * 
	 * @var boolean 
	 */
	private $ready = false;

	/**
	 * Set to a string which is a valid function name to
	 * wrap this logic block *as* a function.
	 *
	 * @var string
	 */
	private $function = null;

	/**
	 * The dynamic variables
	 *
	 * @var array
	 */
	private $variables = array();

	/**
	 * The logic to print
	 * 
	 * @var string 
	 */
	private $logic;

	/**
	 * Constructor
	 *
	 * @param string $logic Intial logic
	 */
	public function __construct( $logic = null )
	{
		// set token and bump it
		$this->token = self::$next_token++;

		// assign any initial logic
		$this->logic = $logic;
	}

	/**
	 */
	public function get_property( $name )
	{
		switch ( $name ) {
			case 'alias':
			case 'ready':
			case 'function':
				return $this->$name;
			default:
				return parent::get_property( $name );
		}
	}

	/**
	 */
	protected function set_property( $name, $value )
	{
		switch ( $name ) {
			case 'alias':
			case 'ready':
				$this->$name = (boolean) $value;
				break;
			case 'function':
				$this->function = is_bool( $value ) ? $value : trim( $value );
				break;
			default:
				return parent::set_property( $name, $value );
		}

		// chain it
		return $this;
	}

	/**
	 * Add a variable
	 *
	 * @param string $name
	 * @param mixed $value
	 * @param boolean $nulls
	 */
	public function add_variable( $name, $value, $nulls = false )
	{
		if ( null !== $value || $nulls ) {
			$this->variables[ $name ] = $value;
		}
	}

	/**
	 * Add a variable (shorthand)
	 *
	 * @param string $name
	 * @param mixed $value
	 * @param boolean $nulls
	 */
	public function av( $name, $value, $nulls = false )
	{
		return $this->add_variable( $name, $value, $nulls );
	}

	/**
	 * Export all variables
	 *
	 * @param boolen $object Set to true to return as an object
	 */
	public function export_variables( $object = false )
	{
		// skip if no variables
		if ( count( $this->variables ) < 1 ) {
			return null;
		}
		
		// formatted variable statements
		$vars = array();
		
		// add each variable
		foreach ( $this->variables as $name => $value ) {
			// format the value
			$vars[$name] = $this->format_value( $value );
		}

		// assignment operator
		$operator = ($object) ? ':' : '=';

		// formatted var expressions
		$vars_f = array();

		// loop all vars and format them
		foreach( $vars as $var_name => $var_val ) {
			// object properties must be quoted
			if ( $object ) {
				$var_name = sprintf( "'%s'", $var_name );
			}
			// push onto array
			array_push( $vars_f, $var_name . $operator . $var_val );
		}

		// return complete expression
		if ( $object ) {
			return sprintf( '{%s}', implode( ',', $vars_f ) );
		} else {
			return sprintf( 'var %s;', implode( ',', $vars_f ) );
		}
	}

	/**
	 * Generate javascript code for this logic
	 *
	 * @return string
	 */
	public function export()
	{
		// begin logic generation
		$code = '';

		// wrap with alias?
		if ( $this->alias ) {
			$code .= '(function($){' . PHP_EOL;
		}

		// on doc ready?
		if ( $this->ready ) {
			$alias = ( $this->alias ) ? '$' : 'jQuery';
			$code .= $alias . '(document).ready(function() {' . PHP_EOL;
		}

		// wrap with function?
		if ( $this->function ) {
			// anonymous function?
			if ( $this->function === true ) {
				$code .= sprintf( 'jQuery(function(){', $this->function ) . PHP_EOL;
			} else {
				$code .= sprintf( 'function %s(){', $this->function ) . PHP_EOL;
			}
		}

		// add variables
		$code .= $this->export_variables();

		// add logic
		if ( $this->logic ) {
			$code .= $this->logic . PHP_EOL;
		}

		// close function?
		if ( $this->function ) {
			// anonymous?
			if ( $this->function === true ) {
				$code .= '});' . PHP_EOL;
			} else {
				$code .= '}' . PHP_EOL;
			}
		}

		// close on doc ready?
		if ( $this->ready ) {
			$code .= '});' . PHP_EOL;
		}
		
		// close alias?
		if ( $this->alias ) {
			$code .= '})(jQuery);' . PHP_EOL;
		}
		
		// all done
		return $code;
	}

	/**
	 * Format a value so that it can be rendered as valid javascript.
	 *
	 * @param mixed $value
	 * @return string
	 */
	protected function format_value( $value )
	{
		// get switchy
		switch ( true ) {

			// null
			case ( null === $value ):
				return $this->format_null();

			// boolean
			case is_bool( $value ):
				return $this->format_boolean( $value );

			// number
			case is_numeric( $value ):
				return $this->format_number( $value );

			// string
			case is_string( $value ):
				return $this->format_string( $value );

			// array
			case is_array( $value ):
				return $this->format_array( $value );

			// object
			case is_object( $value ):
				return $this->format_object( $value );
		}

		// make it null by default
		return $this->format_null();
	}

	/**
	 * Return a string representation of NULL.
	 *
	 * @return string
	 */
	protected function format_null()
	{
		return 'null';
	}

	/**
	 * Return a string representation of a boolean.
	 *
	 * @param boolean $value
	 * @return string
	 */
	protected function format_boolean( $value )
	{
		return ( true === (boolean) $value ) ? 'true' : 'false';
	}

	/**
	 * Return a string representation of a number.
	 *
	 * @param mixed $value
	 * @return string
	 */
	protected function format_number( $value )
	{
		return (string) $value;
	}

	/**
	 * Return a single quoted string with single quotes escaped.
	 *
	 * @param string $value
	 * @return string
	 */
	protected function format_string( $value )
	{
		// check if object literal
		if ( $this->is_object_literal( $value ) ) {
			// yep, don't touch it
			return $value;
		} else {
			// regular string
			return "'" . str_replace( "'", "\\'", $value ) . "'";
		}
	}

	/**
	 * Return a string representation of an array.
	 *
	 * @param array $in
	 * @return string
	 */
	protected function format_array( $in )
	{
		// array of formatted values
		$out = array();

		// loop every value and format
		foreach( $in as $value ) {
			$out[] = $this->format_value( $value );
		}

		// return formatted array syntax
		return '[' . implode( ',', $out ) . ']';
	}

	/**
	 * Return a string representation of an object.
	 *
	 * @param object $in
	 * @return string
	 */
	protected function format_object( $in )
	{
		// array of formatted values
		$out = array();

		// loop every value and format
		foreach( $in as $key => $value ) {
			$out[] = "'" . $key . "': " .  $this->format_value( $value );
		}

		// return formatted array syntax
		return '{' . implode( ',', $out ) . '}';
	}
	
	/**
	 * Returns true if string is *probably* an object literal
	 *
	 * @param string $string
	 * @return boolean
	 */
	protected function is_object_literal( $string )
	{
		// make sure its a string
		if ( is_string( $string ) ) {
			// its a string, trim it
			$trimmed = trim( $string );
			// check first and last char for matching curly braces
			if ( substr( $trimmed, 0, 1 ) == '{' && substr( $trimmed, -1, 1 ) == '}' ) {
				// string starts and ends with matching braces
				return true;
			}
		}

		// nope
		return false;
	}
}
