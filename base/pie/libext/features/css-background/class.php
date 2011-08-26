<?php
/**
 * PIE API: feature extensions, css background feature class file
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
 * Header Background feature
 *
 * @package PIE
 * @subpackage features-ext
 */
class Pie_Easy_Exts_Feature_Css_Background
	extends Pie_Easy_Features_Feature
{
	/**
	 * @ignore
	 */
	final public function export_css()
	{
		// background image option name
		$opt_image = $this->get_suboption('image');
		$opt_tiling = $this->get_suboption('tiling');

		// get attachment image data
		$data = $opt_image->get_image_src('full');

		// extract image data
		list( $url, $width, $height ) = $data;

		// only render if we have a url
		if ( $url ) {
			// rule for bg styles
			$bg = $this->style()->new_rule( $this->_css_selector );
			$bg->ad( 'background-image', sprintf( "url('%s')", $url ) );
			$bg->ad( 'background-repeat', $opt_tiling->get() );
		}

		return parent::export_css();
	}
}

?>
