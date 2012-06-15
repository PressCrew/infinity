<?php
/**
 * ICE API: feature extensions, BuddyPress custom mysteryman feature class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2011 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-extensions
 * @subpackage features
 * @since 1.0
 */

ICE_Loader::load( 'components/features/component' );

/**
 * BuddyPress custom mysteryman feature
 *
 * @package ICE-extensions
 * @subpackage features
 */
class ICE_Ext_Feature_Bp_Custom_Mysteryman
	extends ICE_Feature
{
	/**
	 */
	protected $suboptions = true;

	/**
	 * Cached overriding URL to avoid multiple lookups
	 *
	 * @var null|false|string Set to null if no lookup yet, false if URL lookup failed, string if lookup was a success
	 */
	private $custom_url;

	/**
	 */
	public function check_reqs()
	{
		// is buddypress active?
		if ( true === parent::check_reqs() ) {
			return class_exists( 'BP_Component' );
		}

		return false;
	}
	
	/**
	 */
	protected function init()
	{
		// run parent init method
		parent::init();

		// init directives
		$this->title = __( 'Custom Mystery Man', infinity_text_domain );
		$this->description = __( 'Set a custom BuddyPress mystery man image', infinity_text_domain );

		// add mysteryman src filter
		add_filter( 'bp_core_mysteryman_src', array($this,'custom_mysteryman'), 2, 7 );
	}

	/**
	 * Return URL to the custom mysterman image
	 *
	 * @param string $src The original source URL
	 * @param string $grav_size The original source gravatar size
	 */
	public function custom_mysteryman( $src, $grav_size )
	{
		// handle cached results of previous lookup
		if ( is_string( $this->custom_url ) ) {
			// its a string, use that!
			return $this->custom_url;
		// handle failed lookup
		} elseif ( $this->custom_url === false ) {
			// previous lookup faile, return original src
			return $src;
		}
		
		// grab image option from registry
		$opt_image = $this->get_suboption( 'image' );

		// get an option?
		if ( $opt_image ) {
			// yes, get the URL
			$url = $opt_image->get_image_url();
			// get a URL?
			if ( $url ) {
				// yes, cache it
				$this->custom_url = $url;
				// return it
				return $this->custom_url;
			}
		}

		// lookup failed, cache as false
		$this->custom_url = false;

		// return original src
		return $src;
	}
}

?>
