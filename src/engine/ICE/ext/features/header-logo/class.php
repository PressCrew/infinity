<?php
/**
 * ICE API: feature extensions, header logo feature class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-extensions
 * @subpackage features
 * @since 1.0
 */

/**
 * Header Logo feature
 *
 * @package ICE-extensions
 * @subpackage features
 */
class ICE_Ext_Feature_Header_Logo
	extends ICE_Feature
{

	/**
	 */
	protected function configure()
	{
		// set defaults first
		$this->title = __( 'Header Logo', 'infinity-engine' );
		$this->description = __( 'Custom header logo support', 'infinity-engine' );

		// run parent
		parent::configure();
	}

	public function init()
	{
		// run parent
		parent::init();

		// enqueue blog styles
		add_action( 'ice_init_blog', array( $this, 'blog_styles' ) );
	}

	public function blog_styles()
	{
		// enqueue dependencies
		ice_enqueue_style( 'ice-ext-blog' );

		// dynamic styles
		$style = new ICE_Style( $this );
		$style->add_callback( 'logo-gen', array( $this, 'logo_styles' ) );
		$style->enqueue();
	}

	/**
	 */
	public function renderable()
	{
		// check parent
		if ( true === parent::renderable() ) {
			// get toggle option
			$opt_toggle = (boolean) $this->get_grouped( 'option', 'toggle' )->get();
			// enabled?
			if ( true === $opt_toggle ) {
				// yep, check image url
				if ( null != $this->image_url() ) {
					// there is an image url
					return true;
				}
			}
		}

		return false;
	}
	
	/**
	 * Return URL to uploaded logo image
	 *
	 * @return string
	 */
	public function image_url()
	{
		$src = $this->get_grouped( 'option', 'image' )->get_image_src('full');

		return ( isset($src[0]) ) ? $src[0] : null;
	}

	/**
	 * Callback: generate dynamic logo css
	 */
	public function logo_styles( $style )
	{
		// is this feature renderable?
		if ( false === $this->renderable() ) {
			// nope, abort
			return false;
		}

		// options
		$opt_upload = $this->get_grouped( 'option', 'image' );
		$opt_pos = $this->get_grouped( 'option', 'pos' )->get();
		$opt_top = $this->get_grouped( 'option', 'top' )->get();
		$opt_left = $this->get_grouped( 'option', 'left' )->get();
		$opt_right = $this->get_grouped( 'option', 'right' )->get();

		// get attachment image data
		$data = $opt_upload->get_image_src( 'full' );

		// extract image data
		list( $url, $width, $height ) = $data;

		// only render if we have a url
		if ( $url ) {

			// selectors to target
			$selectors =
				'div#' . $this->element()->id() . ' a,' .
				'h1#' . $this->element()->id() . ' a';

			// add rule
			$pos = $style->rule( 'logo', $selectors );

			if ( is_numeric( $opt_top ) ) {
				$pos->ad( 'top', (int) $opt_top . 'px' );
			}

			if ( is_numeric( $width ) ) {
				$pos->ad( 'width', (int) $width . 'px' );
			}

			if ( is_numeric( $height ) ) {
				$pos->ad( 'height', $height . 'px' );
			}

			if ( $opt_pos ) {
				
				// use absolute positioning
				$pos->ad( 'position', 'absolute' );

				// this gets tricky!
				switch ( $opt_pos ) {
					// left position
					case 'l':
						if ( is_numeric( $opt_left ) && $opt_left >= 1 ) {
							$pos->ad( 'left', (int) $opt_left . 'px' );
						} else {
							$pos->ad( 'left', 'inherit' );
						}
						break;
					// center position
					case 'c':
						// put in middle
						$pos->ad( 'left', '50%' );
						// if we have a width of 2 or higher, offset the margin by half
						if ( is_numeric( $width ) && $width >= 2 ) {
							$pos->ad( 'margin-left', sprintf( '-%dpx', (int) $width / 2 ) );
						}
						break;
					// right position
					case 'r':
						if ( is_numeric( $opt_right ) ) {
							$pos->ad( 'right', (int) $opt_right . 'px' );
						} else {
							$pos->ad( 'right', 0 );
						}
						break;
				}
			}
			
		}
	}

	/**
	 */
	public function get_template_vars()
	{
		// return vars
		return array(
			'logo_url' => $this->image_url()
		);
	}
}
