<?php
/**
 * Infinity Theme features classes file
 *
 * @author Marshall Sorenson <marshall.sorenson@gmail.com>
 * @link http://bp-tricks.com/
 * @copyright Copyright (C) 2010 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package api
 * @subpackage options
 * @since 1.0
 */

Pie_Easy_Loader::load( 'features' );

/**
 * Infinity Feature factory class
 */
final class Infinity_Features
{
	/**
	 * Initialize all features that require it
	 */
	final static public function init()
	{
		self::header_logo()->init();
	}

	/**
	 * Infinite Children
	 *
	 * @return Infinity_Feature_Infinite_Children
	 */
	final static public function infinite_children()
	{
		return new Infinity_Feature_Infinite_Children();
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
 */
abstract class Infinity_Feature extends Pie_Easy_Feature
{
	/**
	 * Define our API slug
	 *
	 * @return
	 */
	final protected function get_api_slug()
	{
		return INFINITY_NAME;
	}
}

/**
 * Infinity Infinite Children feature
 */
class Infinity_Feature_Infinite_Children extends Infinity_Feature
{
	/**
	 * Constructor
	 */
	final public function __construct() {
		parent::__construct(
			'infinite-children',
			'Infinite Children',
			'Create an unlimited hierarchy of child themes.'
		);
	}
}

/**
 * Infinity "Header Logo" Feature
 */
class Infinity_Feature_Header_Logo extends Infinity_Feature
{
	/**
	 * Constructor
	 */
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
?>
			<style type="text/css">
				<?php print $selector ?> {
					display: block;
					background-image: url('<?php print $url ?>');
					width: <?php print $width ?>px;
					height: <?php print $height ?>px;
					text-indent: -999px;
				}
			</style><?php
		}

		return true;
	}
}

/**
 * Infinity "Header Background" Feature
 */
class Infinity_Feature_Header_Background extends Infinity_Feature
{
	/**
	 * Constructor
	 */
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
?>
			<style type="text/css">
				<?php print $selector ?> {
					background-image: url('<?php print $url ?>');
					background-repeat: <?php print infinity_option( 'infinity_header_background_tiling' ) ?>;
				}
			</style><?php
		}

		return true;
	}
}

/**
 * Infinity "Site Background" Feature
 */
class Infinity_Feature_Site_Background extends Infinity_Feature
{
	/**
	 * Constructor
	 */
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
	final public function style( $selector = null )
	{
		// only print style if this feature is supported
		if ( $this->supported() ) {

			// fall back to body tag
			if ( empty( $selector ) ) {
				$selector = 'body';
			}

			// load attachment image url
			$url = infinity_option_image_url( 'infinity_site_background', 'full' );
?>
			<style type="text/css">
				<?php print $selector ?> {
					background-image: url('<?php print $url ?>');
					background-repeat: <?php print infinity_option( 'infinity_site_background_tiling' ) ?>;
				}
			</style><?php
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
 * @param string $selector
 * @return boolean
 */
function infinity_feature_site_background_style( $selector = null )
{
	return Infinity_Features::site_background()->style( $selector );
}

?>
