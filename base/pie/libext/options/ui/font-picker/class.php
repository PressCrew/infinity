<?php
/**
 * PIE API: option extensions, UI font picker class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load_ext( 'options/ui/scroll-picker' );

/**
 * UI Font Picker
 *
 * This option is an extension of the scroll picker for handling font selection
 *
 * @package PIE-extensions
 * @subpackage options
 */
class Pie_Easy_Exts_Options_Ui_Font_Picker
	extends Pie_Easy_Exts_Options_Ui_Scroll_Picker
{
	/**
	 *
	public function configure( $config, $theme )
	{
		// file directory
		if ( isset( $config['file_directory'] ) ) {
			$this->directives()->set( $theme, 'file_directory', $config['file_directory'] );
		}

		// file directory
		if ( isset( $config['file_extension'] ) ) {
			$this->directives()->set( $theme, 'file_extension', $config['file_extension'] );
		}

		// run parent
		parent::configure( $config, $theme );
	}
	 */

}

?>
