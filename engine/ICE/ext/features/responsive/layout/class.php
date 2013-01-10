<?php
/**
 * ICE API: feature extensions, responsive layout feature class file
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2012 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE-extensions
 * @subpackage features
 * @since 1.0
 */

ICE_Loader::load( 'components/features/component' );

/**
 * Responsive layout feature
 *
 * @package ICE-extensions
 * @subpackage features
 */
class ICE_Ext_Feature_Responsive_Layout
	extends ICE_Feature
{
	/**
	 */
	protected function init()
	{
		// run parent init method
		parent::init();

		// add actions
		add_action( 'open_head', array( $this, 'viewport_meta' ) );
		add_action( 'open_wrapper', array( $this, 'mobile_container' ) );
		add_action( 'wp_head', array( $this, 'render' ) );

		// add filter
		add_filter( 'loginout', array( $this, 'loginout_class' ) );
	}

	/**
	 * Render viewport meta tag
	 */
	public function viewport_meta()
	{
		// render meta tag ?>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"><?php
	}

	/**
	 * Render Mobile Menu Container
	 */
	public function mobile_container()
	{
	// add the Mobile Menu holder ?>
	<div class="mobile-menu-container">
		<a class="button black" href="#sidebar">Show Sidebar</a>
		<?php wp_loginout(); ?>
	</div><?php
	}

	/**
	 * Filter the Login/Logout link and add a button class
	 */
	public function loginout_class($text)
	{
		// add a button class to the logout link
		$selector = 'id="loginlogout" class="button black"';
		$text = str_replace('<a ', '<a '.$selector, $text);
		return $text;
	}
}
?>