<?php
/**
 * PIE API: option extensions, css class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load_ext( 'options/textarea' );

/**
 * CSS option
 *
 * @package PIE-extensions
 * @subpackage options
 */
class Pie_Easy_Exts_Options_Css_Custom
	extends Pie_Easy_Exts_Options_Textarea
{
	/**
	 */
	public function init_styles_dynamic()
	{
		// run parent FIRST!
		parent::init_styles_dynamic();

		// get value
		$value = trim( $this->get() );

		// did we get anything?
		if ( !empty( $value ) ) {
			// have to assume its valid CSS, add as string
			$this->style()->add_string( $value );
		}
	}
}

?>
