<?php
/**
 * Tasty Theme features classes file
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
 * Tasty Feature factory class
 */
final class Tasty_Features
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
	 * @return Tasty_Feature_Infinite_Children
	 */
	final static public function infinite_children()
	{
		return new Tasty_Feature_Infinite_Children();
	}

	/**
	 * Header Logo
	 *
	 * @return Tasty_Feature
	 */
	final static public function header_logo()
	{
		return new Tasty_Feature_Header_Logo();
	}
}

/**
 * Tasty Feature base class
 */
abstract class Tasty_Feature extends Pie_Easy_Feature
{
	/**
	 * Define our API slug
	 *
	 * @return
	 */
	final protected function get_api_slug()
	{
		return TASTY_NAME;
	}
}

/**
 * Tasty Infinite Children feature
 */
class Tasty_Feature_Infinite_Children extends Tasty_Feature
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
 * Tasty Feature base class
 */
class Tasty_Feature_Header_Logo extends Tasty_Feature
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
	 * Handle initialization of this feature if its supported
	 */
	final public function init()
	{
		if ( $this->supported() ) {
			add_action( 'wp_head', array($this, 'style') );
		}
	}

	/**
	 * Inject the style for the custom logo
	 */
	final public function style()
	{
		$image = tasty_option_image_src( 'tasty_header_logo', 'full' );
		$url = $image[0];
		$width = $image[1];
		$height = $image[2]; ?>

		<style type="text/css">
			div#header h1#site-title a {
				display: block;
				background-image: url('<?php print $url ?>');
				width: <?php print $width ?>px;
				height: <?php print $height ?>px;
				text-indent: -999px;
			}
		</style><?php
	}
}

?>
