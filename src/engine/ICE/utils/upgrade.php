<?php
/**
 * ICE API: upgrade helpers class file.
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2014 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package ICE
 * @subpackage utils
 * @since 1.2
 */

/**
 * Make upgrades easy.
 *
 * @package ICE
 * @subpackage utils
 */
abstract class ICE_Upgrade extends ICE_Base
{
	/**
	 * Modifications instance.
	 * 
	 * @var ICE_Mod
	 */
	private $mod;

	/**
	 * The last version that was stored.
	 *
	 * @var string
	 */
	private $version;

	/**
	 * Constructor.
	 *
	 * @param string $mod_key The key to pass to the ICE_Mod constructor.
	 * @param string $default_version The version to set if no previous version was found.
	 */
	final public function __construct( $mod_key, $default_version = '1.0' )
	{
		// setup mod instance
		$this->mod = new ICE_Mod( $mod_key );
		// try to get last stored version
		$this->version = $this->mod->get( 'version' );
		// is a version set?
		if ( empty( $this->version ) ) {
			// nope, set it to 1.0
			$this->version_bump( $default_version );
		}
	}

	/**
	 * Returns true if comparison of saved version to given version using given operator is successful.
	 *
	 * @uses version_compare()
	 * @param string $version
	 * @param string $operator
	 * @return boolean
	 */
	final public function version_compare( $version, $operator )
	{
		return version_compare( $this->version, $version, $operator );
	}

	/**
	 * Returns true if saved version exactly matches given version.
	 *
	 * @param string $version
	 * @return boolean
	 */
	final public function version_equals( $version )
	{
		// do versions match exactly?
		return $this->version_compare( $version, '==' );
	}

	/**
	 * Bump the version to the given one and save it.
	 *
	 * @param string $version
	 */
	final protected function version_bump( $version )
	{
		// update version property
		$this->version = $version;
		// update stored version
		$this->mod->set( 'version', $version );
		// write changes
		$this->mod->save();
	}

	/**
	 * Perform upgrade tasks.
	 */
	abstract public function run();
}

/**
 * Upgrades required for version 1.1.
 *
 * @package ICE
 * @subpackage utils
 */
class ICE_Upgrade_1_1 extends ICE_Upgrade
{
	/**
	 */
	public function run()
	{
		// is version already 1.1?
		if ( true === $this->version_equals( '1.1' ) ) {
			// yes, all done
			return;
		}

		// version must already be 1.0
		if ( true === $this->version_equals( '1.0' ) ) {
			// no changes, just bump the version
			$this->version_bump( '1.1' );
		}

		// all done
		return;
	}
}

/**
 * Upgrades required for 1.2.
 *
 * @package ICE
 * @subpackage utils
 */
class ICE_Upgrade_1_2 extends ICE_Upgrade_1_1
{
	/**
	 */
	public function run()
	{
		// is version already 1.2?
		if ( true === $this->version_equals( '1.2' ) ) {
			// yes, all done
			return;
		} else {
			// run parent
			parent::run();
		}

		// version must already be 1.1
		if ( true === $this->version_equals( '1.1' ) ) {
			// try to import options
			if ( true === $this->import_options() ) {
				// success, bump the version
				$this->version_bump( '1.2' );
			}
		}

		// all done
		return;
	}

	/**
	 * Import old option values from the settings api into the theme modifications api.
	 *
	 * @return boolean
	 */
	private function import_options()
	{
		ice_rename_options( array(
			// core options
			'infinity-core-options.text-color' => 'core.text-color',
			'infinity-core-options.link-color' => 'core.link-color',
			'infinity-core-options.custom-favicon' => 'core.custom-favicon',
			'infinity-core-options.sidebar-position' => 'core.sidebar-position',
			'infinity-core-options.sidebar-size' => 'core.sidebar-size',
			'infinity-core-options.google-analytics' => 'core.google-analytics',
			'infinity-core-options.footer-text' => 'core.footer-text',
			// custom styles
			'infinity-custom-css.markup' => 'custom.markup',
			// body layout
			'infinity-body-layout.width' => 'body.width',
			'infinity-body-layout.background-color' => 'body.background-color',
			'infinity-body-layout.background-image' => 'body.background-image',
			'infinity-body-layout.background-repeat' => 'body.background-repeat',
			'infinity-body-layout.overlay-image' => 'body.overlay-image',
			'infinity-body-layout.overlay-opacity' => 'body.overlay-opacity',
			// header logo
			'infinity-header-logo.image' => 'header-logo.image',
			'infinity-header-logo.toggle' => 'header-logo.toggle',
			'infinity-header-logo.pos' => 'header-logo.pos',
			'infinity-header-logo.top' => 'header-logo.top',
			'infinity-header-logo.right' => 'header-logo.right',
			'infinity-header-logo.left' => 'header-logo.left',
			// header layout
			'infinity-header-layout.min-height' => 'header.min-height',
			'infinity-header-layout.margin-top' => 'header.margin-top',
			'infinity-header-layout.padding-top' => 'header.padding-top',
			'infinity-header-layout.margin-bottom' => 'header.margin-bottom',
			'infinity-header-layout.padding-bottom' => 'header.padding-bottom',
			'infinity-header-layout.background-color' => 'header.background-color',
			'infinity-header-layout.background-image' => 'header.background-image',
			'infinity-header-layout.background-repeat' => 'header.background-repeat',
			'infinity-header-layout.overlay-image' => 'header.overlay-image',
			'infinity-header-layout.overlay-opacity' => 'header.overlay-opacity',
			// main menu layout
			'infinity-main-menu-layout.color-link' => 'main-menu.color-link',
			'infinity-main-menu-layout.font-weight' => 'main-menu.font-weight',
			'infinity-main-menu-layout.padding' => 'main-menu.padding',
			'infinity-main-menu-layout.background-color' => 'main-menu.background-color',
			'infinity-main-menu-layout.overlay-image' => 'main-menu.overlay-image',
			'infinity-main-menu-layout.overlay-opacity' => 'main-menu.overlay-opacity',
			// top menu layout
			'infinity-top-menu-layout.color-link' => 'top-menu.color-link',
			'infinity-top-menu-layout.font-weight' => 'top-menu.font-weight',
			'infinity-top-menu-layout.background-color' => 'top-menu.background-color',
			'infinity-top-menu-layout.background-color-subitem' => 'top-menu.background-color-subitem',
			'infinity-top-menu-layout.overlay-image' => 'top-menu.overlay-image',
			'infinity-top-menu-layout.overlay-opacity' => 'top-menu.overlay-opacity',
			// sub menu layout
			'infinity-sub-menu-layout.color-link' => 'sub-menu.color-link',
			'infinity-sub-menu-layout.font-weight' => 'sub-menu.font-weight',
			'infinity-sub-menu-layout.background-color' => 'sub-menu.background-color',
			'infinity-sub-menu-layout.background-color-subitem' => 'sub-menu.background-color-subitem',
			'infinity-sub-menu-layout.overlay-image' => 'sub-menu.overlay-image',
			'infinity-sub-menu-layout.overlay-opacity' => 'sub-menu.overlay-opacity',
			// content layout
			'infinity-content-layout.text-color' => 'content.text-color',
			'infinity-content-layout.link-color' => 'content.link-color',
			'infinity-content-layout.background-color' => 'content.background-color',
			'infinity-content-layout.background-image' => 'content.background-image',
			'infinity-content-layout.background-repeat' => 'content.background-repeat',
			'infinity-content-layout.overlay-image' => 'content.overlay-image',
			'infinity-content-layout.overlay-opacity' => 'content.overlay-opacity',
			// sidebar layout
			'infinity-sidebar-layout.text-color' => 'sidebar.text-color',
			'infinity-sidebar-layout.link-color' => 'sidebar.link-color',
			'infinity-sidebar-layout.background-color' => 'sidebar.background-color',
			'infinity-sidebar-layout.background-image' => 'sidebar.background-image',
			'infinity-sidebar-layout.background-repeat' => 'sidebar.background-repeat',
			'infinity-sidebar-layout.overlay-image' => 'sidebar.overlay-image',
			'infinity-sidebar-layout.overlay-opacity' => 'sidebar.overlay-opacity',
			// widget layout
			'infinity-widget-layout.color-link' => 'widget.color-link',
			'infinity-widget-layout.font-weight' => 'widget.font-weight',
			'infinity-widget-layout.padding' => 'widget.padding',
			'infinity-widget-layout.background-color' => 'widget.background-color',
			'infinity-widget-layout.overlay-image' => 'widget.overlay-image',
			'infinity-widget-layout.overlay-opacity' => 'widget.overlay-opacity',
			// footer layout
			'infinity-footer-layout.text-color' => 'footer.text-color',
			'infinity-footer-layout.link-color' => 'footer.link-color',
			'infinity-footer-layout.margin-top' => 'footer.margin-top',
			'infinity-footer-layout.background-color' => 'footer.background-color',
			'infinity-footer-layout.background-image' => 'footer.background-image',
			'infinity-footer-layout.background-repeat' => 'footer.background-repeat',
			'infinity-footer-layout.overlay-image' => 'footer.overlay-image',
			'infinity-footer-layout.overlay-opacity' => 'footer.overlay-opacity',
			// post gravatar
			'infinity-post-gravatar.size' => 'post-gravatar.size',
			'infinity-post-gravatar.default-set' => 'post-gravatar.default-set',
			'infinity-post-gravatar.default-img' => 'post-gravatar.default-img',
			'infinity-post-gravatar.default-force' => 'post-gravatar.default-force',
			'infinity-post-gravatar.rating' => 'post-gravatar.rating',
			'infinity-post-gravatar.border-width' => 'post-gravatar.border-width',
			'infinity-post-gravatar.border-color' => 'post-gravatar.border-color',
			'infinity-post-gravatar.padding' => 'post-gravatar.padding',
			'infinity-post-gravatar.bg-color' => 'post-gravatar.bg-color'
		) );

	}

}
