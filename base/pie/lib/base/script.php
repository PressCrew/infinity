<?php
/**
 * PIE API: base javascript class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage base
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/asset' );

/**
 * Make scripts for components easy
 *
 * @package PIE
 * @subpackage base
 */
class Pie_Easy_Script extends Pie_Easy_Asset
{
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
			// get markup for each rule
			foreach ( $this->logic->to_array() as $logic ) {
				// append output of rule export
				$code .= '/*+++ generating script */' . PHP_EOL;
				$code .= $logic->export();
				$code .= '/*--- script generation complete! */' . PHP_EOL . PHP_EOL;
			}
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
 * @subpackage base
 */
class Pie_Easy_Script_Logic
{
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
	 */
	public function __construct( $logic = null )
	{
		$this->logic = $logic;

		// init variables
		$this->variables = new Pie_Easy_Map();
	}

	/**
	 * @ignore
	 * @param string $name
	 * @return mixed
	 */
	public function __get( $name )
	{
		switch ( $name ) {
			case 'alias':
				return $this->alias;
			case 'ready':
				return $this->ready;
			case 'function':
				return $this->function;
			default:
				throw new Exception( 'Invalid property' );
		}
	}

	/**
	 * @ignore
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set( $name, $value )
	{
		switch ( $name ) {
			case 'alias':
				$this->alias = (boolean) $value;
				break;
			case 'ready':
				$this->ready = (boolean) $value;
				break;
			case 'function':
				$this->function = trim($value);
				break;
			default:
				throw new Exception( 'Invalid property' );
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
		// the markup that will be returned
		$code = null;

		// variables passed in?
		if ( is_array( $variables ) ) {
			// merge over existing vars?
			if ( is_array( $this->variables ) ) {
				$variables = array_merge( $this->variables->to_array(), $variables );
			}
		} else {
			$variables = $this->variables->to_array();
		}

		// the code to be returned
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

		// all done
		return $code;
	}
}

?>
