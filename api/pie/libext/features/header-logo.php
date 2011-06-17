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

			// registry
			$registry = Pie_Easy_Policy::options()->registry();

			// options
			$opt_upload = $registry->get( $this->_option_upload );
			$opt_top = $registry->get( $this->_option_top )->get();
			$opt_left = $registry->get( $this->_option_left )->get();

			// get attachment image data
			$data = $opt_upload->get_image_src( 'full' );

			// extract image data
			list( $url, $width, $height ) = $data;

			// only render if we have a url
			if ( $url ) {

				// new style object
				$style = new Pie_Easy_Style( $this->_css_selector );

				// add rules
				$style->add_rule( 'position', 'absolute' );

				if ( $opt_top ) {
					$style->add_rule( 'top', $opt_top . 'px' );
				}

				if ( $opt_left ) {
					$style->add_rule( 'left', $opt_left . 'px' );
				}
				
				// render
				print $style->export();
			}
		}

		return true;
	}
}

?>
