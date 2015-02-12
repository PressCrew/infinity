<?php
/**
 * Infinity Theme: cbox
 *
 * @author Marshall Sorenson <marshall@presscrew.com>
 * @link http://infinity.presscrew.com/
 * @copyright Copyright (C) 2012-2015 CUNY Academic Commons, Bowe Frankema, Marshall Sorenson
 * @license http://www.gnu.org/licenses/gpl.html GPLv2 or later
 * @package Infinity
 * @subpackage cbox
 * @since 1.2
 */

/**
 * Don't allow WordPress to move widgets over to this theme, as it messes with
 * our own widget setup routine
 */
remove_action( 'after_switch_theme', '_wp_sidebars_changed' );
add_action( 'after_switch_theme', 'retrieve_widgets' );

/**
 * Options page title and menu title
 */
function cbox_theme_menu_title()
{
	return __( 'CBOX Theme Options', 'cbox' );
}
add_filter( 'infinity_dashboard_menu_setup_page_title', 'cbox_theme_menu_title' );
add_filter( 'infinity_dashboard_menu_setup_menu_title', 'cbox_theme_menu_title' );

/**
 * Create an excerpt
 *
 * Uses bp_create_excerpt() when available. Otherwise falls back on a very
 * rough approximation, ignoring the fancy params passed.
 *
 * @since 1.0.5
 */
function cbox_create_excerpt( $text, $length = 425, $options = array() ) {
	if ( function_exists( 'bp_create_excerpt' ) ) {
		return bp_create_excerpt( $text, $length, $options );
	} else {
		return substr( $text, 0, $length ) . ' [&hellip;]';
	}
}

/**
 * Is there more than one profile group tab?
 *
 * @since 1.2
 * @return bool
 */
function cbox_profile_has_multiple_tabs()
{
	global $profile_template;
	return $profile_template->group_count > 1;
}

/**
 * Automagically create a front page if one has not been set already
 */
function cbox_theme_auto_create_home_page()
{
	$is_root_blog = function_exists( 'bp_is_root_blog' ) ? bp_is_root_blog() : is_main_site();

	// if we're not on the root blog, do not auto create the homepage
	if ( ! $is_root_blog ) {
		return;
	}

	// get frontpage ID
	$front_page = get_option( 'page_on_front' );

	// no frontpage?
	if ( ! $front_page ) {

		// set our flag to create a page to true by default
		$create_page = true;

		// grab current auto-created home page id
		$home_page_id = get_option( '_cbox_theme_auto_create_home_page' );

		// we have a page ID, but does it still exist?
		if ( is_numeric( $home_page_id ) ) {
			// page exists, so set $create_page flag to false
			if ( get_post( $home_page_id ) ) {
				$create_page = false;
			}

		}

		// we need to create a new page
		if ( $create_page ) {
			// create the new page
			$home_page_id = wp_insert_post( array(
				'post_type'   => 'page',
				'post_title'  => 'Home Page',
				'post_status' => 'publish',
			) );

			// set the new page as the frontpage and use our homepage template
			update_post_meta( $home_page_id, '_wp_page_template', 'homepage-template.php' );
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $home_page_id );
			update_option( '_cbox_theme_auto_create_home_page', $home_page_id );
		}

	// check if front page still exists
	} else {
		// do this check only on 404 pages b/c if the front page doesn't exist,
		// the front page will 404, so we can run our check then to prevent
		// unnecessary DB queries on other pages
		if ( is_404() && get_post( $front_page ) === NULL ) {
			// front page no longer exists so purge the following options
			delete_option( 'page_on_front' );
			delete_option( '_cbox_theme_auto_create_home_page' );

			// redirect back to homepage
			wp_redirect( get_home_url() ); die();
		}
	}
}
add_action( 'wp', 'cbox_theme_auto_create_home_page' );


// TODO temporarily bailing on remaining items, will tackle as additional commits
return;

/**
 * Custom jQuery Buttons
 */
function cbox_theme_custom_buttons()
{
	// get button color option
	$cbox_button_color = infinity_option_get( 'cbox_button_color' );

	// render script tag ?>
	<script>
	// Adds color button classes to buttons depening on preset style/option
	jQuery(document).ready(function() {
			//buttons
			jQuery('.bp-primary-action,div.group-button').addClass('button white');
			jQuery('.generic-button .acomment-reply,div.not_friends').addClass('button white');
			jQuery('.bp-secondary-action, .view-post,.comment-reply-link').addClass('button white');
			jQuery('.standard-form .button,.not_friends,.group-button,.dir-form .button,.not-following,#item-buttons .group-button,#bp-create-doc-button').addClass('<?php echo $cbox_button_color ?>');
			jQuery('input[type="submit"],.submit,#item-buttons .generic-button,#aw-whats-new-submit,.activity-comments submit').addClass('button <?php echo $cbox_button_color ?>');
			jQuery('div.pending,.dir-list .group-button,.dir-list .friendship-button').removeClass('<?php echo $cbox_button_color ?>');
			jQuery('#previous-next,#upload, div.submit,div,reply,#groups_search_submit').removeClass('<?php echo $cbox_button_color ?> button');
			jQuery('div.pending,.dir-list .group-button,.dir-list .friendship-button').addClass('white');
			jQuery('#upload').addClass('button green');
	});
	</script><?php
}
add_action( 'close_body', 'cbox_theme_custom_buttons' );

//
// Slider
//

/**
 * Load metaboxes class callback: https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
 */
function cbox_theme_init_cmb()
{
	if ( !class_exists( 'cmb_Meta_Box' ) ) {
		require_once( INFINITY_INC_PATH . '/metaboxes/init.php' );
	}
}

/**
 * Slider setup
 */
function cbox_theme_slider_setup()
{
	// load slider setup
	require_once( INFINITY_INC_PATH . '/feature-slider/setup.php' );

	// load meta box lib (a bit later)
	add_action( 'init', 'cbox_theme_init_cmb', 9999 );
}
add_action( 'after_setup_theme', 'cbox_theme_slider_setup' );
