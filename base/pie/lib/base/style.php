<?php
/**
 * PIE API: base style class file
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
 * Make styles for components easy
 *
 * @package PIE
 * @subpackage base
 */
class Pie_Easy_Style extends Pie_Easy_Asset
{
	/**
	 * The rules
	 *
	 * @var Pie_Easy_Stack
	 */
	private $rules;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		// init rules map
		$this->rules = new Pie_Easy_Stack();
	}

	/**
	 * Add a rule
	 * 
	 * @param string $rule
	 * @param mixed $value 
	 */
	public function new_rule( $selector )
	{
		// new rule object
		$rule = new Pie_Easy_Style_Rule( $selector );

		// add it to rulemap
		$this->rules->push( $rule );

		// return it for editing
		return $rule;
	}

	/**
	 * Generate CSS @import markup for this style's files
	 *
	 * @return string
	 */
	public function import()
	{
		// the markup that will be returned
		$markup = null;

		// render import statements first
		if ( $this->has_files() ) {
			// one per line
			foreach ( $this->get_files() as $file ) {
				// make sure it actually exists
				$filepath = Pie_Easy_Scheme::instance()->locate_file( $file );
				// find it?
				if ( $filepath ) {
					$markup .= sprintf( "@import '%s';", Pie_Easy_Files::theme_file_to_url( $filepath ) ) . PHP_EOL;
				}
			}
		}

		// all done
		return $markup;
	}

	/**
	 * Generate CSS markup for this style's dynamic rules
	 *
	 * @return string
	 */
	public function export()
	{
		// the markup that will be returned
		$markup = null;

		// render rules
		if ( $this->rules->count() ) {
			// get markup for each rule
			foreach ( $this->rules->to_array() as $rule ) {
				// append output of rule export
				$markup .= $rule->export() . PHP_EOL;
			}
		}

		// all done
		return $markup;
	}
}

/**
 * Make rules for styles easy
 *
 * @package PIE
 * @subpackage base
 * @property string $selector CSS selector to which apply declarations
 */
class Pie_Easy_Style_Rule
{
	/**
	 * The selector
	 *
	 * @var string
	 */
	private $selector;

	/**
	 * The declarations
	 *
	 * @var Pie_Easy_Map
	 */
	private $declarations;

	/**
	 * Constructor
	 *
	 * @param string $selector CSS selector expression
	 */
	public function __construct( $selector )
	{
		// set selector
		$this->selector = $selector;

		// init declarations
		$this->declarations = new Pie_Easy_Map();
	}

	/**
	 * @ignore
	 * @param string $name
	 * @return mixed
	 */
	public function __get( $name )
	{
		switch ( $name ) {
			case 'selector':
				return $this->selector;
			default:
				throw new Exception( 'Invalid property' );
		}
	}

	/**
	 * Add a declaration
	 *
	 * @param string $property
	 * @param mixed $value
	 */
	public function add_declaration( $property, $value )
	{
		$this->declarations->add( $property, $value );
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
			// merge over existing decs?
			if ( is_array( $this->declarations ) ) {
				$declarations = array_merge( $this->declarations->to_array(), $declarations );
			}
		} else {
			$declarations = $this->declarations->to_array();
		}

		// open declarations with the selector
		$markup = $this->selector . " {" . PHP_EOL;

		// add each dec
		foreach ( $declarations as $property => $value ) {
			$markup .= sprintf( "\t%s: %s;", $property, $value ) . PHP_EOL;
		}

		// close
		$markup .= '}' . PHP_EOL;

		// all done
		return $markup;
	}
}

?>
