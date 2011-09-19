<?php
/**
 * PIE API: base style class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage ui
 * @since 1.0
 */

Pie_Easy_Loader::load( 'ui/asset' );

/**
 * Make styles for components easy
 *
 * @package PIE
 * @subpackage ui
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
	 * Parent dir of last filename being fixed for css url() values
	 *
	 * @var string
	 */
	private $last_dirname;

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
				$markup .= '/*+++ generating style ***/' . PHP_EOL;
				$markup .= $rule->export();
				$markup .= '/*--- style generation complete! */' . PHP_EOL . PHP_EOL;
			}
		}

		// all done
		return $markup;
	}

	protected function get_file_contents( $filename )
	{
		// run parent to get content
		$content = parent::get_file_contents( $filename );

		// get content?
		if ( $content ) {
			// save last filename
			$this->last_dirname = dirname( $filename );
			// replace all CSS url() values
			return preg_replace_callback( '/url\s*\([\'\"\s]*([^\'\"\s]*)[\'\"\s]*\)/', array($this, 'fix_url_path'), $content );
		}
	}

	/**
	 * @ignore
	 * @param array $matches
	 * @return string
	 */
	protected function fix_url_path( $matches )
	{
		// path is index 2
		$path = $matches[1];

		// fix if applicable
		switch ( true ) {
			// absolute path to doc root, leave alone
			case ( $path{0} == '/' ):
				break;
			// absolute URL, leave alone
			case ( preg_match( '/^https?:\/\//', $path ) ):
				break;
			// anything else needs to be resolved
			default:
				$path = Pie_Easy_Files::path_build( $this->last_dirname, $path );
				$path = Pie_Easy_Files::theme_file_to_url( $path );
		}

		// return fixed url value
		return sprintf( "url('%s')", $path );
	}
}

/**
 * Make rules for styles easy
 *
 * @package PIE
 * @subpackage ui
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
