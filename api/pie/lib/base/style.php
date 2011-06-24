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

Pie_Easy_Loader::load( 'collections' );

/**
 * Make styles for components easy
 *
 * @package PIE
 * @subpackage base
 * @property string $selector CSS selector to which apply rules
 */
class Pie_Easy_Style
{
	/**
	 * The selector
	 *
	 * @var string
	 */
	private $selector;

	/**
	 * The rules
	 *
	 * @var Pie_Easy_Map
	 */
	private $rules;

	/**
	 * Constructor
	 *
	 * @param string $selector CSS selector expression
	 */
	public function __construct( $selector )
	{
		// set selector
		$this->selector = $selector;

		// init rules map
		$this->rules = new Pie_Easy_Map();
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
	 * Add a rule
	 * 
	 * @param string $rule
	 * @param mixed $value 
	 */
	public function add_rule( $rule, $value )
	{
		$this->rules->add( $rule, $value );
	}

	/**
	 * Get CSS markup for this style
	 *
	 * @param array $rules
	 * @return string
	 */
	public function export( $rules = null )
	{
		// rules passed in?
		if ( is_array( $rules ) ) {
			// merge over existing rules?
			if ( is_array( $this->rules ) ) {
				$rules = array_merge( $this->rules->to_array(), $rules );
			}
		} else {
			$rules = $this->rules->to_array();
		}

		// open with selector
		$markup = $this->selector . " {" . PHP_EOL;

		// add each rule
		foreach ( $rules as $rule => $value ) {
			$markup .= sprintf( "\t%s: %s;%s", $rule, $value, PHP_EOL );
		}

		// close
		$markup .= '}' . PHP_EOL . PHP_EOL;

		// all done
		return $markup;
	}
}

?>
