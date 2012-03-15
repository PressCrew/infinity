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
	 */
	protected function init()
	{
		// run parent
		parent::init();

		// init directives
		$this->title = __( 'Header Logo', pie_easy_text_domain );
		$this->description = __( 'Custom header logo support', pie_easy_text_domain );
	}

	/**
	 */
	public function init_styles()
	{
		parent::init_styles();
		
		// add static logo styles
		$this->style()->cache( 'logo', 'logo.css' );

		// add dynamic logo styles callback
		$this->style()->cache( 'logo-gen', 'logo_styles' );
	}

	/**
	 * Return URL to uploaded logo image
	 *
	 * @return string
	 */
	public function image_url()
	{
		$src = $this->get_suboption('image')->get_image_src('full');

		return ( isset($src[0]) ) ? $src[0] : null;
	}

	/**
	 * Callback: generate dynamic logo css
	 */
	public function logo_styles( $style )
	{
		// options
		$opt_upload = $this->get_suboption('image');
		$opt_pos = $this->get_suboption('pos')->get();
		$opt_top = $this->get_suboption('top')->get();
		$opt_left = $this->get_suboption('left')->get();
		$opt_right = $this->get_suboption('right')->get();

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
			$pos = $style->rule( $selectors );

			if ( $opt_top ) {
				$pos->ad( 'top', $opt_top . 'px' );
			}

			if ( $width ) {
				$pos->ad( 'width', $width . 'px' );
			}

			if ( $height ) {
				$pos->ad( 'height', $height . 'px' );
			}

			if ( $opt_pos ) {
				
				// use absolute positioning
				$pos->ad( 'position', 'absolute' );

				// this gets tricky!
				switch ( $opt_pos ) {
					// left position
					case 'l':
						if ( $opt_left ) {
							$pos->ad( 'left', $opt_left . 'px' );
						} else {
							$pos->ad( 'left', '0px' );
						}
						break;
					// center position
					case 'c':
						// put in middle
						$pos->ad( 'left', '50%' );
						// if we have a width, offset the margin by half
						if ( $width ) {
							$pos->ad( 'margin-left', sprintf( '-%dpx', $width / 2 ) );
						}
						break;
					// right position
					case 'r':
						if ( $opt_right ) {
							$pos->ad( 'right', $opt_right . 'px' );
						} else {
							$pos->ad( 'right', '0px' );
						}
						break;
				}
			}
			
		}
	}
}

?>
