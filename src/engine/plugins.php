<?php
/**
 * Infinity Theme: plugins compat
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2015 Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage base
 * @since 1.2
 */

// load all plugin compat
infinity_plugin_compat( 'bbpress', 'bbpress.php' );
infinity_plugin_compat( 'buddypress', 'buddypress.php' );
infinity_plugin_compat( 'buddypress-docs', 'buddypress-docs.php' );
infinity_plugin_compat( 'buddypress-docs-wiki', 'buddypress-docs-wiki.php' );
infinity_plugin_compat( 'commons-in-a-box', 'commons-in-a-box.php' );

//
// Helpers
//

/**
 * Returns true if the given plugin is supported natively and is loaded.
 *
 * @staticvar array $plugins_loaded
 * @param string $name The plugin name.
 * @return boolean
 */
function infinity_plugin_supported( $name )
{
	// cache these checks for performance in case any or them get complicated
	static $plugins_loaded = null;
	
	// does cache need to be populated?
	if ( null === $plugins_loaded ) {
		// yep, check conditions
		$plugins_loaded = array(
			// bbPress 2+
			'bbpress' =>				function_exists( 'bbpress' ),
			// BuddyPress
			'buddypress' =>				function_exists( 'buddypress' ),
			// BuddyPress Docs
			'buddypress-docs' =>		function_exists( 'bp_docs_init' ),
			// BuddyPress Docs wiki
			'buddypress-docs-wiki' =>	function_exists( 'bpdw_init' ),
			// CAC Featured Content Widget
			'cac-featured-content' =>	function_exists( 'cac_featured_content_init' ),
			// Commons In A Box
			'commons-in-a-box' =>		function_exists( 'cbox' ),
			// Infinity Slider Extension
			'infext-slider' =>			function_exists( 'infext_slider_init' ),
			// WordPress SEO
			'wordpress-seo' =>			function_exists( 'wpseo_init' )
		);
	}
	
	// return value from cache
	return (
		true === isset( $plugins_loaded[ $name ]  ) &&
		true === $plugins_loaded[ $name ]
	);
}

/**
 * Returns true if the given plugin is supported natively and is configured to actually do something.
 *
 * @uses infinity_plugin_supported()
 * 
 * @param string $name The plugin name.
 * @return boolean
 */
function infinity_plugin_enabled( $name )
{
	// first off, is plugin even supported?
	if ( true === infinity_plugin_supported( $name ) ) {

		// check if plugin enabled
		switch ( $name ) {
			// Infinity Slider Extension
			case 'infext-slider':
				return infext_slider_is_enabled();
			// No enabled check exists (yet)
			default:
				throw new Exception(
					sprintf( 'No enabled check has been configured for the %s plugin yet.' )
				);
		}
	}

	// plugin not supported, so can't be enabled
	return false;
}

/**
 * Load given plugin compat file if plugin is loaded.
 *
 * @param string $plugin
 * @param string $filename
 */
function infinity_plugin_compat( $plugin, $filename )
{
	// is plugin loaded?
	if ( true === infinity_plugin_supported( $plugin ) ) {
		// yep, load the file
		require_once INFINITY_PLUGINS_PATH . '/' . $filename;
	}
}
