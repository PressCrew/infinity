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

/**
 * Header Logo feature
 *
 * @package PIE
 * @subpackage features-ext
 */
class Pie_Easy_Exts_Feature_Header_Logo
	extends Pie_Easy_Features_Feature
{
	/**
	 * @ignore
	 */
	final public function export_css()
	{
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

			// add rule
			$pos = $this->style()->new_rule( $this->_css_selector );

			if ( $opt_top ) {
				$pos->ad( 'top', $opt_top . 'px' );
			}

			if ( $opt_left ) {
				$pos->ad( 'left', $opt_left . 'px' );
			}

			if ( $width ) {
				$pos->ad( 'width', $width . 'px' );
			}

			if ( $height ) {
				$pos->ad( 'height', $height . 'px' );
			}
			
		}

		return parent::export_css();
	}
}

?>
