<?php
/**
 * ICE API: option extensions, generic input class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-extensions
 * @subpackage options
 * @since 1.0
 */

ICE_Loader::load( 'components/options/component' );

/**
 * Input element base class
 *
 * @package ICE-extensions
 * @subpackage options
 */
abstract class ICE_Exts_Options_Input
	extends ICE_Option
{
	/**
	 * The type attribute for the input element
	 *
	 * @var string
	 */
	private $__input_type__;

	/**
	 * Get/Set the input type
	 *
	 * @param string $type
	 * @return string
	 */
	final public function input_type( $type = null )
	{
		// if we have one or more args, we are setting
		if ( func_num_args() >= 1 ) {
			// can't have been set already
			if ( is_null( $this->__input_type__ ) ) {
				$this->__input_type__ = $type;
			} else {
				throw new Exception( 'The "input_type" property cannot be changed once set' );
			}
		}

		return $this->__input_type__;
	}
}

?>
