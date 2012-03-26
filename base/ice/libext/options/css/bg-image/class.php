<?php
/**
 * ICE API: option extensions, css background image class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-extensions
 * @subpackage options
 * @since 1.0
 */

ICE_Loader::load_ext( 'options/upload' );

/**
 * CSS background image option
 *
 * @package ICE-extensions
 * @subpackage options
 */
class ICE_Exts_Options_Css_Bg_Image
	extends ICE_Exts_Options_Upload
{
	/**
	 */
	protected function init()
	{
		// run parent
		parent::init();

		// init directives
		$this->title = __( 'Background Image', infinity_text_domain );
		$this->description = __( 'Upload an image to use as the background', infinity_text_domain );
		$this->documentation = 'options/uploader';
		$this->style_property = 'background-image';
	}
}

?>
