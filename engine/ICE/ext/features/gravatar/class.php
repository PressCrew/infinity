<?php
/**
 * ICE API: feature extensions, gravatar feature class file
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
 * Gravatar feature
 *
 * @package ICE-extensions
 * @subpackage features
 */
class ICE_Ext_Feature_Gravatar
	extends ICE_Feature
{
	/**
	 * @var string
	 */
	protected $image_class;

	/**
	 */
	public function get_property( $name )
	{
		switch ( $name ) {
			case 'image_class':
				return $this->$name;
			default:
				return parent::get_property( $name );
		}
	}

	/**
	 */
	protected function configure()
	{
		// run parent first
		parent::configure();

		// import settings
		$this->import_settings( array(
			'image_class'
		));
	}

	/**
	 */
	public function init()
	{
		// run parent
		parent::init();

		// enqueue styles
		add_action( 'ice_init_blog', array( $this, 'do_enqueue_styles' ) );
	}

	/**
	 * Enqueue styles.
	 */
	public function do_enqueue_styles()
	{
		// dynamic styles
		$style = new ICE_Style( $this );
		$style->inject( 'image', 'image_css' );
		// enqueue it
		ice_enqueue_style_obj( $style );
	}

	/**
	 */
	public function image_css( $style )
	{
		// options
		$opt_border_width = $this->get_suboption('border-width')->get();
		$opt_border_color = $this->get_suboption('border-color')->get();
		$opt_padding = $this->get_suboption('padding')->get();
		$opt_bg_color = $this->get_suboption('bg-color')->get();

		// add rules
		$img = $style->rule( 'image', 'img.' . $this->image_class );

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
	}

	/**
	 * Format the gravatar URL
	 * 
	 * @return string
	 */
	public function url()
	{
		// options
		$opt_size = $this->get_suboption('size')->get();
		$opt_default_set = $this->get_suboption('default-set')->get();
		$opt_default_img = $this->get_suboption('default-img')->get_image_url();
		$opt_default_force = $this->get_suboption('default-force')->get();
		$opt_rating = $this->get_suboption('option_rating')->get();

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
