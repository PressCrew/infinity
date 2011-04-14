<?php
/**
 * Infinity Theme features classes file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://bp-tricks.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package api
 * @subpackage features
 * @since 1.0
 */

Pie_Easy_Loader::load( 'enqueue', 'features' );

/**
 * Infinity Feature factory class
 *
 * @package api
 * @subpackage features
 */
final class Infinity_Features
{
	/**
	 * Initialize features that require it
	 */
	final static function init()
	{
		add_action( 'pie_easy_enqueue_styles', array( self::custom_css(), 'init_styles' ), 999 );
	}

	/**
	 * Custom CSS
	 *
	 * @return Infinity_Feature_Custom_Css
	 */
	final static public function custom_css()
	{
		return new Infinity_Feature_Custom_Css();
	}

	/**
	 * Header Logo
	 *
	 * @return Infinity_Feature_Header_Logo
	 */
	final static public function header_logo()
	{
		return new Infinity_Feature_Header_Logo();
	}

	/**
	 * Header Background
	 *
	 * @return Infinity_Feature_Header_Background
	 */
	final static public function header_background()
	{
		return new Infinity_Feature_Header_Background();
	}

	/**
	 * Site Background
	 *
	 * @return Infinity_Feature_Site_Background
	 */
	final static public function site_background()
	{
		return new Infinity_Feature_Site_Background();
	}
}

/**
 * Infinity Feature base class
 *
 * @package api
 * @subpackage features
 */
abstract class Infinity_Feature extends Pie_Easy_Feature
{
	/**
	 * Define our API slug
	 *
	 * @return string
	 */
	final protected function get_api_slug()
	{
		return INFINITY_NAME;
	}
}

/**
 * Infinity Custom CSS feature
 *
 * @package api
 * @subpackage features
 */
class Infinity_Feature_Custom_Css extends Infinity_Feature
{
	final public function __construct() {
		parent::__construct(
			'custom-css',
			'Custom CSS',
			'Allow custom CSS to be provided with theme options.'
		);
	}

	/**
	 * Enqueue the css export script
	 */
	final public function init_styles()
	{
		if ( $this->supported() ) {
			wp_enqueue_style( 'infinity-custom', INFINITY_EXPORT_URL . '/css.php', null, infinity_option_meta( 'infinity_custom_css', 'time_updated' ) );
		}
	}
}

/**
 * Infinity "Header Logo" Feature
 *
 * @package api
 * @subpackage features
 */
class Infinity_Feature_Header_Logo extends Infinity_Feature
{
	final public function __construct()
	{
		// run parent constructor
		parent::__construct(
			'header-logo',
			'Header Logo',
			'Custom header logo support.'
		);
	}

	/**
	 * Inject the style for the custom logo
	 */
	final public function style( $selector = null )
	{
		// only print style if this feature is supported
		if ( $this->supported() ) {

			// fall back to Twenty Ten style of layout
			if ( empty( $selector ) ) {
				$selector = 'div#header h1#site-title a';
			}

			// load attachment image data
			list( $url, $width, $height ) = infinity_option_image_src( 'infinity_header_logo', 'full' );

			// only render if we have a url
			if ( $url ) {
				// render the styles ?>
				<style type="text/css">
					<?php print $selector ?> {
						display: block;
						text-indent: -999px;
						background-image: url('<?php print $url ?>');
						<?php if ( $width ): ?>width: <?php print $width ?>px;<?php endif; ?>
						<?php if ( $height ): ?>height: <?php print $height ?>px;<?php endif; ?>
					}
				</style><?php
			}
		}

		return true;
	}
}

/**
 * Infinity "Header Background" Feature
 *
 * @package api
 * @subpackage features
 */
class Infinity_Feature_Header_Background extends Infinity_Feature
{
	final public function __construct()
	{
		// run parent constructor
		parent::__construct(
			'header-background',
			'Header Background',
			'Custom header background support.'
		);
	}

	/**
	 * Inject the style for the custom header background
	 */
	final public function style( $selector = null )
	{
		// only print style if this feature is supported
		if ( $this->supported() ) {

			// fall back to Twenty Ten style of layout
			if ( empty( $selector ) ) {
				$selector = 'div#header';
			}

			// load attachment image url
			$url = infinity_option_image_url( 'infinity_header_background', 'full' );

			// only render if we have a url
			if ( $url ) {
				// start rendering ?>
				<style type="text/css">
					<?php print $selector ?> {
						background-image: url('<?php print $url ?>');
						background-repeat: <?php print infinity_option( 'infinity_header_background_tiling' ) ?>;
					}
				</style><?php
			}
		}

		return true;
	}
}

/**
 * Infinity "Site Background" Feature
 *
 * @package api
 * @subpackage features
 */
class Infinity_Feature_Site_Background extends Infinity_Feature
{
	final public function __construct()
	{
		// run parent constructor
		parent::__construct(
			'site-background',
			'Site Background',
			'Custom site background support.'
		);
	}

	/**
	 * Inject the style for the custom site background
	 */
	final public function style( $selector = 'body' )
	{
		// only print style if this feature is supported
		if ( $this->supported() ) {

			// load attachment image url
			$url = infinity_option_image_url( 'infinity_site_background', 'full' );

			// only render if we have a url
			if ( $url ) {
				// start rendering ?>
				<style type="text/css">
					<?php print $selector ?> {
						background-image: url('<?php print $url ?>');
						background-repeat: <?php print infinity_option( 'infinity_site_background_tiling' ) ?>;
					}
				</style><?php
			}
		}

		return true;
	}
}

//
// Helpers
//

/**
 * Print header logo style
 *
 * This should be placed inside the <head> element in your theme template
 *
 * @see Infinity_Feature_Header_Logo::style()
 * @param string $selector
 * @return boolean
 */
function infinity_feature_header_logo_style( $selector = null )
{
	return Infinity_Features::header_logo()->style( $selector );
}

/**
 * Print header background style
 *
 * This should be placed inside the <head> element in your theme template
 *
 * @see Infinity_Feature_Header_Background::style()
 * @param string $selector
 * @return boolean
 */
function infinity_feature_header_background_style( $selector = null )
{
	return Infinity_Features::header_background()->style( $selector );
}

/**
 * Print site background style
 *
 * This should be placed inside the <head> element in your theme template
 *
 * @see Infinity_Feature_Site_Background::style()
 * @param string $selector
 * @return boolean
 */
function infinity_feature_site_background_style( $selector = null )
{
	return Infinity_Features::site_background()->style( $selector );
}

?>
