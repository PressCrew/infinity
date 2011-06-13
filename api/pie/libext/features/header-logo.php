<?php
/**
 * PIE API: feature extensions, header logo feature class file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://marshallsorenson.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE
 * @subpackage features-ext
 * @since 1.0
 */

Pie_Easy_Loader::load( 'base/style' );

/**
 * Header Logo feature
 *
 * @package PIE
 * @subpackage features-ext
 */
class Pie_Easy_Exts_Feature_Header_Logo
	extends Pie_Easy_Features_Feature
		implements Pie_Easy_Styleable
{
	/**
	 * Inject the style for the custom logo
	 */
	final public function export_css()
	{
		// only print style if this feature is supported
		if ( $this->supported() ) {

			// logo image option name
			$logo_option = Pie_Easy_Policy::options()->registry()->get( $this->_option_upload );

			// get attachment image data
			$data = $logo_option->get_image_src( 'full' );

			// extract image data
			list( $url, $width, $height ) = $data;

			// only render if we have a url
			if ( $url ) {

				// new style object
				$style = new Pie_Easy_Style( $this->_css_selector );

				// add rules
				$style->add_rule( 'display', 'block' );
				$style->add_rule( 'text-indent', '-999px' );
				$style->add_rule( 'background-image', sprintf( "url('%s')", $url ) );

				if ( $width ) {
					$style->add_rule( 'width', $width . 'px' );
				}
				
				if ( $height ) {
					$style->add_rule( 'height', $height . 'px' );
				}

				// render
				print $style->export();
			}
		}

		return true;
	}
}

?>
