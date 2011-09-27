<?php
/**
 * PIE API: feature extensions, header logo feature class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package PIE-extensions
 * @subpackage features
 * @since 1.0
 */

/**
 * Header Logo feature
 *
 * @package PIE-extensions
 * @subpackage features
 */
class Pie_Easy_Exts_Features_Header_Logo
	extends Pie_Easy_Features_Feature
{

	/**
	 * Return URL to uploaded logo image
	 *
	 * @return string
	 */
	public function image_url()
	{
		$src = $this->get_suboption('image')->get_image_src('full');
		return $src[0];
	}

	/**
	 */
	final public function export_css()
	{
		// options
		$opt_upload = $this->get_suboption('image');
		$opt_top = $this->get_suboption('top')->get();
		$opt_left = $this->get_suboption('left')->get();

		// get attachment image data
		$data = $opt_upload->get_image_src( 'full' );

		// extract image data
		list( $url, $width, $height ) = $data;

		// only render if we have a url
		if ( $url ) {

			// selectors to target
			$selectors =
				'div#' . $this->get_element_id() . ' a,' .
				'h1#' . $this->get_element_id() . ' a';

			// add rule
			$pos = $this->style()->new_rule( $selectors );

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
