<?php
/**
 * Infinity Theme: dashboard support functions
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2010-2012 Marshall Sorenson and Bowe Frankema
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage dashboard
 * @since 1.1
 */

/**
 * Theme update notification/nag helper
 */
function infinity_dashboard_update_nag( $args )
{
	// get stylesheet name
	$stylesheet = get_stylesheet();

	// default settings
	$defaults = array(
		'package_file' => null,
		'package_id' => $stylesheet,
		'theme_slug' => $stylesheet,
		'theme_name' => ucfirst( $stylesheet )
	);
	
	// merge args and defaults
	$settings = wp_parse_args( $args, $defaults );

	// load notifier class
	ICE_Loader::load( 'parsers/packages' );

	// new packages instance
	$packages = new ICE_Packages( $settings['package_file'] );

	// check if update needed
	$update = $packages->theme_needs_update( $settings['theme_slug'], $settings['package_id'] );

	// spit out nag message if update needed
	if ( $update ) {
		// render markup ?>
		<div class="update-nag">
			There is a new version of the <?php echo $settings['theme_name'] ?> theme available.
			<?php if ( current_user_can( 'update_themes' ) ): ?>
				Please <a href="<?php echo $update->download ?>">download</a> it now!
			<?php else: ?>
				Please notify the site administrator!
			<?php endif; ?>
		</div><?php
	}

}

/**
 * Show useful support information. Props to Paul Gibbs for 90% of the code!
 *
 * @copyright Original code Copyright (C) Paul Gibbs
 */
function infinity_dashboard_support_info()
{
	// globals
	global $wpdb, $wp_rewrite, $wp_version;

	// show some useful infinity info
	if ( INFINITY_DEV_MODE ) {
		$is_developer_mode = __( 'Enabled', 'infinity-engine' );
	} else {
		$is_developer_mode = __( 'Disabled', 'infinity-engine' );
	}

	// get theme info
	$theme = wp_get_theme();

	// get plugin info
	$active_plugins = array();
	$all_plugins = apply_filters( 'all_plugins', get_plugins() );

	// what plugins are active?
	foreach ( $all_plugins as $filename => $plugin ) {
		if ( 'BuddyPress' != $plugin['Name'] && is_plugin_active( $filename ) ) {
			$active_plugins[] = $plugin['Name'] . ': ' . $plugin['Version'];
		}
	}

	natcasesort( $active_plugins );

	if ( !$active_plugins ) {
		$active_plugins[] = __( 'No other plugins are active', 'infinity-engine' );
	}

	// multisite info
	if ( defined( 'MULTISITE' ) && MULTISITE == true ) {
		if ( defined( 'SUBDOMAIN_INSTALL' ) && SUBDOMAIN_INSTALL == true ) {
			$is_multisite = __( 'subdomain', 'infinity-engine' );
		} else {
			$is_multisite = __( 'subdirectory', 'infinity-engine' );
		}
	} else {
		$is_multisite = __( 'no', 'infinity-engine' );
	}

   // what permalinks are being used?
	if ( empty( $wp_rewrite->permalink_structure ) ) {
		$custom_permalinks = __( 'default', 'infinity-engine' );
	} else {
		if ( strpos( $wp_rewrite->permalink_structure, 'index.php' ) ) {
			$custom_permalinks = __( 'almost', 'infinity-engine' );
		} else {
			$custom_permalinks = __( 'custom', 'infinity-engine' );
		}
	}
?>
	<h3><?php _e( 'Installation Details', 'infinity-engine' ) ?></h3>
	<p><?php _e( "If you are having issues with the theme and need support, below is some useful info about your installation.", 'infinity-engine' ) ?></p>
	<p><?php _e( "Please submit this information with your support request so its easier for us to help you!", 'infinity-engine' ) ?></p>

	<h4><?php _e( 'Versions', 'infinity-engine' ) ?></h4>
	<ul>
		<li><?php printf( __( 'Infinity Version: %s', 'infinity-engine' ), INFINITY_VERSION ) ?></li>
		<li><?php printf( __( 'Developer Mode: %s', 'infinity-engine' ), $is_developer_mode ) ?></li>
		<li><?php printf( __( 'BuddyPress: %s', 'infinity-engine' ), BP_VERSION ) ?></li>
		<li><?php printf( __( 'MySQL: %s', 'infinity-engine' ), $wpdb->db_version() ) ?></li>
		<li><?php printf( __( 'Permalinks: %s', 'infinity-engine' ), $custom_permalinks ) ?></li>
		<li><?php printf( __( 'PHP: %s', 'infinity-engine' ), phpversion() ) ?></li>
		<li><?php printf( __( 'WordPress: %s', 'infinity-engine' ), $wp_version ) ?></li>
		<li><?php printf( __( 'WordPress multisite: %s', 'infinity-engine' ), $is_multisite ) ?></li>
	</ul>

	<h4><?php _e( 'Theme', 'infinity-engine' ) ?></h4>
	<ul>
		<li><?php printf( __( 'Current theme: %s version %s', 'infinity-engine' ), $theme->Name, $theme->Version ) ?></li>
	</ul>

	<h4><?php _e( 'Active Plugins', 'infinity-engine' ) ?></h4>
	<ul>
		<?php foreach ( $active_plugins as $plugin ) : ?>
			<li><?php echo $plugin ?></li>
		<?php endforeach; ?>
	</ul><?php
}
