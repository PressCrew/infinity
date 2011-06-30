<?php
/**
 * PIE API: feature extensions, gravatar feature class file
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
 * Gravatar feature
 *
 * @package PIE
 * @subpackage features-ext
 */
class Pie_Easy_Exts_Feature_Gravatar
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
		$opt_border_width = $registry->get( $this->_option_border_width )->get();
		$opt_border_color = $registry->get( $this->_option_border_color )->get();
		$opt_padding = $registry->get( $this->_option_padding )->get();
		$opt_bg_color = $registry->get( $this->_option_bg_color )->get();

		// add rules
		$img = $this->style()->new_rule( 'img.' . $this->_image_class );

		if ( $opt_border_width ) {
			$img->ad( 'border-width', $opt_border_width . 'px' );
		}
		if ( $opt_border_color ) {
			$img->ad( 'border-color', $opt_border_color );
		}
		if ( $opt_padding ) {
			$img->ad( 'padding', $opt_padding . 'px' );
		}
		if ( $opt_bg_color ) {
			$img->ad( 'background-color', $opt_bg_color );
		}

		return parent::export_css();
	}

	public function url()
	{
		// registry
		$registry = Pie_Easy_Policy::options()->registry();
		
		// options
		$opt_size = $registry->get( $this->_option_size )->get();
		$opt_default_set = $registry->get( $this->_option_default_set )->get();
		$opt_default_img = $registry->get( $this->_option_default_img )->get_image_url();
		$opt_default_force = $registry->get( $this->_option_default_force )->get();
		$opt_rating = $registry->get( $this->_option_rating )->get();

		// the hash
		$hash = md5( strtolower( trim( get_the_author_meta( 'user_email' ) ) ) );

		// the options
		$params = array();

		// size
		if ( $opt_size ) {
			$params['s'] = $opt_size;
		}

		// default
		if ( $opt_default_img ) {
			$params['d'] = $opt_default_img;
		} elseif ( $opt_default_set ) {
			$params['d'] = $opt_default_set;
		}

		// force default
		if ( $opt_default_force ) {
			$params['f'] = 'y';
		}

		// rating
		if ( $opt_rating ) {
			$params['r'] = $opt_rating;
		}

		return sprintf( 'http://www.gravatar.com/avatar/%s.jpg?%s', $hash, http_build_query( $params ) );
	}
}

?>
