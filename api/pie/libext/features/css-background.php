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

Pie_Easy_Loader::load( 'base/style' );

/**
 * Header Background feature
 *
 * @package PIE
 * @subpackage features-ext
 */
class Pie_Easy_Exts_Feature_Css_Background
	extends Pie_Easy_Features_Feature
		implements Pie_Easy_Styleable
{
	/**
	 * Export styles for the custom background
	 *
	 * @return string
	 */
	final public function export_css()
	{
		// background image option name
		$bg_option = Pie_Easy_Policy::options()->registry()->get( $this->_option_upload );
		$bg_repeat_option = Pie_Easy_Policy::options()->registry()->get( $this->_option_repeat );

		// get attachment image data
		$data = $bg_option->get_image_src( 'full' );

		// extract image data
		list( $url, $width, $height ) = $data;

		// only render if we have a url
		if ( $url ) {

			// new style object
			$style = new Pie_Easy_Style( $this->_css_selector );

			// add rules
			$style->add_rule( 'background-image', sprintf( "url('%s')", $url ) );
			$style->add_rule( 'background-repeat', $bg_repeat_option->get() );

			// render
			return $style->export();
		}
	}
}

?>
