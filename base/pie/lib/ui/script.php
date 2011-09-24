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
		// grab code from buffer
		$code = ob_get_clean();

		// new rule object
		$logic = new Pie_Easy_Script_Logic( $code );

		// add it to logic stack
		$this->logic->push( $logic );

		// return it for adding vars
		return $logic;
	}

	/**
	 * Generate javascript markup for this script's dynamic code
	 *
	 * @return string
	 */
	public function export()
	{
		// the markup that will be returned
		$code = null;

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
 * @property string $function
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
				return $this->function = trim($value);
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
	 */
	public function add_variable( $name, $value )
	{
		$this->variables->add( $name, $value );
	}

	/**
	 * Add a variable (shorthand)
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function av( $name, $value )
	{
		return $this->add_variable( $name, $value );
	}

	/**
	 * Generate javascript code for this logic
	 *
	 * @param array $variables
	 * @return string
	 */
	public function export( $variables = null )
	{
		// variables passed in?
		if ( is_array( $variables ) ) {
			// merge over existing vars?
			if ( is_array( $this->variables ) ) {
				$variables = array_merge( $this->variables->to_array(), $variables );
			}
		} else {
			$variables = $this->variables->to_array();
		}

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
			$code .= sprintf( 'function %s(){', $this->function ) . PHP_EOL;
		}

		// add each variable
		foreach ( $variables as $name => $value ) {
			// format the var
			switch ( true ) {
				case is_numeric( $value ):
					break;
				case is_bool( $value ):
					$value = ( $value ) ? 'true' : 'false';
					break;
				case is_null( $value ):
					$value = 'null';
					break;
				case is_string( $value ):
					$value = sprintf( "'%s'", $value );
					break;
				case is_array( $value ):
					$value = sprintf( "['%s']", implode( "','", $value ) );
					break;
			}
			// append to code
			$code .= sprintf( "\tvar %s = %s;", $name, $value ) . PHP_EOL;
		}

		// add logic
		if ( $this->logic ) {
			$code .= $this->logic . PHP_EOL;
		}

		// close function?
		if ( $this->function ) {
			$code .= '}' . PHP_EOL;
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
