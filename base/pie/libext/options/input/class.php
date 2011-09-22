<?php
/**
 * PIE API: option extensions, generic input class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load( 'components/options/component' );

/**
 * Input element base class
 *
 * @package PIE-extensions
 * @subpackage options
 * @property-read string $input_type The type attribute for the input element
 */
abstract class Pie_Easy_Exts_Options_Input
	extends Pie_Easy_Options_Option
{
	/**
	 * The type attribute for the input element
	 *
	 * @var string
	 */
	private $input_type = 'text';

	
	public function __get( $name )
	{
		switch ( $name ) {
			case 'input_type':
				return $this->input_type;
			default:
				return parent::__get( $name );
		}
	}

	public function __isset( $name )
	{
		switch ( $name ) {
			case 'input_type':
				return isset( $this->input_type );
			default:
				return parent::__isset( $name );
		}
	}

	/**
	 * Get/Set the input type
	 *
	 * @param string $type
	 * @return string
	 */
	final protected function input_type( $type = null )
	{
		if ( $type ) {
			$this->input_type = $type;
		}

		return $this->input_type;
	}
}

?>
