<?php
/**
 * PIE API: base javascript class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage ui
 * @since 1.0
 */

Pie_Easy_Loader::load( 'ui/asset' );

/**
 * Make scripts for components easy
 *
 * @package PIE
 * @subpackage ui
 */
class Pie_Easy_Script extends Pie_Easy_Asset
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
	 * @var Pie_Easy_Stack
	 */
	private $logic;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		// set token and bump it
		$this->token = self::$next_token++;

		// init logic stack
		$this->logic = new Pie_Easy_Stack();
	}

	/**
	 * Create and return a new logic object
	 *
	 * @param string $code valid javascript code
	 * @return Pie_Easy_Script_Logic
	 */
	public function logic( $code = null )
	{
		// new logic object
		$logic = new Pie_Easy_Script_Logic( $code );
		
		// add it to logic stack
		$this->logic->push( $logic );

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
	 * @return Pie_Easy_Script_Logic
	 */
	public function end_logic()
	{
		// add contents of output buffer to new logic object and return it
		return $this->logic( ob_get_clean() );
	}

	/**
	 * Generate javascript markup for this script's dynamic code
	 *
	 * @return string
	 */
	public function export()
	{
		// the markup that will be returned
		$code = parent::export();

		// render rules
		if ( $this->logic->count() ) {
			
			// begin script generation
			$code = sprintf( '/*+++ begin script: %s */', $this->token ) . PHP_EOL;
			
			// loop all logic objects
			foreach ( $this->logic->to_array() as $logic ) {
				// append output of logic export
				$code .= $logic->export();
			}
			
			// end script generation
			$code .= sprintf( '/*+++ end script: %s */', $this->token ) . PHP_EOL . PHP_EOL;
		}

		// all done
		return $code;
	}
}

/**
 * Make dynamic logic for scripts easy
 *
 * @property boolean $alias
 * @property boolean $ready
 * @property boolean|string $function
 * @package PIE
 * @subpackage ui
 */
class Pie_Easy_Script_Logic extends Pie_Easy_Base
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
	 * @var Pie_Easy_Map
	 */
	private $variables;

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

		// init variables
		$this->variables = new Pie_Easy_Map();
	}

	/**
	 */
	public function __get( $name )
	{
		switch ( $name ) {
			case 'alias':
			case 'ready':
			case 'function':
				return $this->$name;
			default:
				return parent::__get( $name );
		}
	}

	/**
	 */
	public function __set( $name, $value )
	{
		switch ( $name ) {
			case 'alias':
				return $this->alias = (boolean) $value;
			case 'ready':
				return $this->ready = (boolean) $value;
			case 'function':
				return $this->function = is_bool( $value ) ? $value : trim( $value );
			default:
				return parent::__set( $name, $value );
		}
	}

	/**
	 */
	public function __isset( $name )
	{
		switch ( $name ) {
			case 'alias':
			case 'ready':
			case 'function':
				return isset( $this->$name );
			default:
				return parent::__isset( $name );
		}
	}

	/**
	 */
	public function __unset( $name )
	{
		switch ( $name ) {
			case 'alias':
			case 'ready':
			case 'function':
				return $this->$name = null;
			default:
				return parent::__unset( $name );
		}
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
		if ( !is_null( $value ) || $nulls ) {
			$this->variables->add( $name, $value );
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
		return $this->add_variable( $name, $value );
	}

	/**
	 * Export all variables
	 *
	 * @param boolen $object Set to true to return as an object
	 */
	public function export_variables( $object = false )
	{
		// skip if no variables
		if ( !$this->variables->count() ) {
			return null;
		}
		
		// formatted variable statements
		$vars = array();
		
		// add each variable
		foreach ( $this->variables->to_array() as $name => $value ) {
			// format the var
			switch ( true ) {
				case is_numeric( $value ):
					$vars[$name] = $value;
					break;
				case is_bool( $value ):
					$vars[$name] = ( $value ) ? 'true' : 'false';
					break;
				case is_null( $value ):
					$vars[$name] = 'null';
					break;
				case is_string( $value ):
					$vars[$name] = sprintf( "'%s'", $value );
					break;
				case is_array( $value ):
					$vars[$name] = sprintf( "['%s']", implode( "','", $value ) );
					break;
			}
		}

		// assignment operator
		$operator = ($object) ? ':' : '=';

		// formatted var expressions
		$vars_f = array();

		// loop all vars and format them
		foreach( $vars as $var_name => $var_val ) {
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
		$code = sprintf( '/*+++ begin logic: %s */', $this->token ) . PHP_EOL;

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
		
		// end logic generation
		$code .= sprintf( '/*+++ end logic: %s */', $this->token ) . PHP_EOL;

		// all done
		return $code;
	}
}

?>
